<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\Empresa;
use App\Models\Oferta;
use App\Models\User;

class AdminController extends Controller
{
    /* ════════════════════════════════════════
       VISTAS
    ════════════════════════════════════════ */
    public function home()
    {
        return redirect()->route('admin.estudiantes');
    }


    public function listarEstudiantes()
    {
        $seccion = 'estudiantes';

        $totalAlumnos      = Estudiante::count();
        $alumnosActivos    = Estudiante::where('estado', 'activo')->count();
        $alumnosSuspendidos= Estudiante::where('estado', 'suspendido')->count();
        $alumnosPendientes = Estudiante::where('estado', 'pendiente')->count();

        $estudiantes = Estudiante::with(['user', 'carrera'])
            ->withCount('postulaciones')
            ->orderBy('fecha_creacion', 'desc')
            ->paginate(20);

        return view('admin.admin', compact(
            'seccion',
            'totalAlumnos', 'alumnosActivos', 'alumnosSuspendidos', 'alumnosPendientes',
            'estudiantes'
        ));
    }

    public function listarEmpresas()
    {
        $seccion = 'empresas';

        $totalEmpresas      = Empresa::count();
        $empresasAprobadas  = Empresa::where('estado', 'aprobada')->count();
        $empresasPendientes = Empresa::where('estado', 'pendiente')->count();
        $empresasRechazadas = Empresa::where('estado', 'rechazada')->count();

        $empresas = Empresa::with('user')
            ->withCount(['ofertas as ofertas_activas_count' => fn($q) => $q->where('estado', 'activa')])
            ->orderBy('fecha_creacion', 'desc')
            ->paginate(20);

        return view('admin.admin', compact(
            'seccion',
            'totalEmpresas', 'empresasAprobadas', 'empresasPendientes', 'empresasRechazadas',
            'empresas'
        ));
    }

    public function listarOfertas()
    {
        $seccion = 'ofertas';

        $totalOfertas      = Oferta::count();
        $ofertasPublicadas = Oferta::where('estado', 'activa')->count();
        $ofertasPendientes = Oferta::where('estado', 'pendiente')->count();
        $ofertasPausadas   = Oferta::where('estado', 'pausada')->count();

      
        $ofertas = Oferta::with('empresa')
        ->withCount('postulaciones')
        ->orderBy('fecha_publicacion', 'desc')
        ->paginate(20);

        return view('admin.admin', compact(
            'seccion',
            'totalOfertas', 'ofertasPublicadas', 'ofertasPendientes', 'ofertasPausadas',
            'ofertas'
        ));
    }

    /* ════════════════════════════════════════
       CAMBIOS DE ESTADO
    ════════════════════════════════════════ */

    public function cambiarEstadoEstudiante(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:activo,suspendido,pendiente'
        ]);

        Estudiante::findOrFail($id)->update(['estado' => $request->estado]);

        return redirect()->back()->with('success', 'Estado del estudiante actualizado.');
    }

public function cambiarEstadoEmpresa(Request $request, $id)
{
    $request->validate([
        'estado' => 'required|in:aprobada,suspendida,pendiente,rechazada'
    ]);

    $empresa = Empresa::findOrFail($id);
    $estadoAnterior = $empresa->estado;
    $empresa->update(['estado' => $request->estado]);

    // 🔥 SINCRONIZACIÓN AUTOMÁTICA DE OFERTAS
    if (in_array($request->estado, ['suspendida', 'rechazada'])) {

        $empresa->ofertas()->update([
            'estado' => 'pausada'
        ]);
    }

    if ($request->estado === 'aprobada') {

        $empresa->ofertas()->update(['estado' => 'activa']);
    }

    // ✉️ ENVIAR CORREO DE NOTIFICACIÓN SI EL ESTADO CAMBIA
    if ($estadoAnterior !== $request->estado && in_array($request->estado, ['aprobada', 'rechazada', 'suspendida'])) {
        if ($empresa->user && $empresa->user->email) {
            // Parche SSL temporal para entorno local
            config([
                'mail.mailers.smtp.stream' => [
                    'ssl' => [
                        'allow_self_signed' => true,
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ],
            ]);
            \Illuminate\Support\Facades\Mail::to($empresa->user->email)->send(new \App\Mail\EstadoEmpresaEmail($empresa, $request->estado));
        }
    }

    return redirect()->back()
        ->with('success', "Empresa actualizada a {$request->estado}.");
}

    public function cambiarEstadoOferta(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:activa,pausada,cerrada'
        ]);

        Oferta::findOrFail($id)->update(['estado' => $request->estado]);

        return redirect()->back()->with('success', "Oferta actualizada a {$request->estado}.");
    }

    /* ════════════════════════════════════════
       ELIMINACIONES
    ════════════════════════════════════════ */

    public function eliminarEstudiante($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        $idUsuario  = $estudiante->id_usuario;

        // Borrar relaciones antes de eliminar el estudiante
        $estudiante->habilidades()->detach();         // estudiante_habilidad
        $estudiante->postulaciones()->delete();        // postulaciones

        $estudiante->delete();

        if ($idUsuario) {
            User::destroy($idUsuario);
        }

        return redirect()->back()->with('success', 'Estudiante y usuario eliminados.');
    }

    public function eliminarEmpresa($id)
    {
        $empresa   = Empresa::findOrFail($id);
        $idUsuario = $empresa->id_usuario;

        Oferta::where('id_empresa', $empresa->id_empresa)->delete();
        $empresa->delete();

        if ($idUsuario) {
            User::destroy($idUsuario);
        }

        return redirect()->back()->with('success', 'Empresa, ofertas y usuario eliminados.');
    }

    public function eliminarOferta($id)
    {
        Oferta::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Oferta eliminada.');
    }
}
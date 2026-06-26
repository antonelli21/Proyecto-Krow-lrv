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
            'estado' => 'required|in:Activa,Pausada,Cerrada',
            'motivo' => 'nullable|string|max:500',
        ]);

        $oferta = Oferta::findOrFail($id);

        if ($request->estado === 'Pausada') {
            $oferta->update([
                'estado'             => 'Pausada',
                'pausada_por_admin'  => true,
                'motivo_pausa_admin' => $request->motivo ?? null,
            ]);
        } else {
            $oferta->update([
                'estado'             => $request->estado,
                'pausada_por_admin'  => false,
                'motivo_pausa_admin' => null,
            ]);
        }

        return redirect()->back()->with('success', "Oferta actualizada a {$request->estado}.");
    }

    /* ════════════════════════════════════════
       ELIMINACIONES
    ════════════════════════════════════════ */


    public function eliminarOferta($id)
    {
        Oferta::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Oferta eliminada.');
    }


public function bulkEstadoEstudiantes(Request $request)
{
    $request->validate([
        'ids'    => 'required|array',
        'ids.*'  => 'integer|exists:estudiante,id_estudiante',
        'estado' => 'required|in:activo,suspendido,pendiente',
    ]);

    Estudiante::whereIn('id_estudiante', $request->ids)
        ->update(['estado' => $request->estado]);

    return redirect()->back()->with('success', count($request->ids) . ' estudiantes actualizados a ' . $request->estado . '.');
}


public function bulkEstadoEmpresas(Request $request)
{
    $request->validate([
        'ids'    => 'required|array',
        'ids.*'  => 'integer|exists:empresa,id_empresa',
        'estado' => 'required|in:aprobada,suspendida,pendiente,rechazada',
    ]);

    foreach ($request->ids as $id) {
        $empresa = Empresa::find($id);
        if (!$empresa) continue;

        $empresa->update(['estado' => $request->estado]);

        if (in_array($request->estado, ['suspendida', 'rechazada'])) {
            $empresa->ofertas()->update(['estado' => 'pausada']);
        } elseif ($request->estado === 'aprobada') {
            $empresa->ofertas()->update(['estado' => 'activa']);
        }
    }

    return redirect()->back()->with('success', count($request->ids) . ' empresas actualizadas.');
}

public function bulkEstadoOfertas(Request $request)
{
    $request->validate([
        'ids'    => 'required|array',
        'ids.*'  => 'integer|exists:oferta,id_oferta',
        'estado' => 'required|in:Activa,Pausada,Cerrada',
    ]);

    Oferta::whereIn('id_oferta', $request->ids)
        ->update(['estado' => $request->estado]);

    return redirect()->back()->with('success', count($request->ids) . ' ofertas actualizadas.');
}

public function bulkDestroyOfertas(Request $request)
{
    $request->validate([
        'ids'   => 'required|array',
        'ids.*' => 'integer|exists:oferta,id_oferta',
    ]);

    Oferta::whereIn('id_oferta', $request->ids)->delete();

    return redirect()->back()->with('success', count($request->ids) . ' ofertas eliminadas.');
}

public function eliminarEstudiante($id)
{
    $estudiante = Estudiante::findOrFail($id);
    $idUsuario  = $estudiante->id_usuario;

    $estudiante->habilidades()->detach();
    $estudiante->postulaciones()->delete();
    $estudiante->delete();

    if ($idUsuario) {
    $this->eliminarUserConDependencias($idUsuario);
}

    return redirect()->back()->with('success', 'Estudiante y usuario eliminados.');
}

public function bulkDestroyEstudiantes(Request $request)
{
    $request->validate([
        'ids'   => 'required|array',
        'ids.*' => 'integer|exists:estudiante,id_estudiante',
    ]);

    foreach ($request->ids as $id) {
        $estudiante = Estudiante::find($id);
        if (!$estudiante) continue;

        $idUsuario = $estudiante->id_usuario;
        $estudiante->habilidades()->detach();
        $estudiante->postulaciones()->delete();
        $estudiante->delete();

        if ($idUsuario) {
    $this->eliminarUserConDependencias($idUsuario);
}
    }

    return redirect()->back()->with('success', count($request->ids) . ' estudiantes eliminados.');
}

public function eliminarEmpresa($id)
{
    $empresa   = Empresa::findOrFail($id);
    $idUsuario = $empresa->id_usuario;

    Oferta::where('id_empresa', $empresa->id_empresa)->delete();
    $empresa->delete();

    if ($idUsuario) {
    $this->eliminarUserConDependencias($idUsuario);
}

    return redirect()->back()->with('success', 'Empresa, ofertas y usuario eliminados.');
}

public function bulkDestroyEmpresas(Request $request)
{
    $request->validate([
        'ids'   => 'required|array',
        'ids.*' => 'integer|exists:empresa,id_empresa',
    ]);

    foreach ($request->ids as $id) {
        $empresa = Empresa::find($id);
        if (!$empresa) continue;

        $idUsuario = $empresa->id_usuario;
        Oferta::where('id_empresa', $empresa->id_empresa)->delete();
        $empresa->delete();

        if ($idUsuario) {
    $this->eliminarUserConDependencias($idUsuario);
}
    }

    return redirect()->back()->with('success', count($request->ids) . ' empresas eliminadas.');
}

/**
 * Borra todas las tablas hijas de un user antes de eliminar el registro users.
 * Orden respeta las FKs: chats y mensajes primero, luego tickets, notificaciones, etc.
 */
private function eliminarUserConDependencias(int $idUsuario): void
{
    // Chats donde el user es participante (id_usuario_1 o id_usuario_2)
    $chatIds = \DB::table('chat')
        ->where('id_usuario_1', $idUsuario)
        ->orWhere('id_usuario_2', $idUsuario)
        ->pluck('id_chat');

    if ($chatIds->isNotEmpty()) {
        \DB::table('mensaje')->whereIn('id_chat', $chatIds)->delete();
        \DB::table('chat')->whereIn('id_chat', $chatIds)->delete();
    }

    // Tickets de soporte
    \DB::table('ticket_soporte')->where('id_usuario', $idUsuario)->delete();

    // Finalmente el user
    User::where('id', $idUsuario)->delete();
}
public function listarReportes()
{
    $seccion = 'reportes';

    $totalReportes   = \DB::table('ticket_soporte')->count();
    $reportesAbiertos = \DB::table('ticket_soporte')->where('estado', 'Abierto')->count();
    $reportesEnProceso = \DB::table('ticket_soporte')->where('estado', 'En Proceso')->count();
    $reportesResueltos = \DB::table('ticket_soporte')->where('estado', 'Resuelto')->count();

    $reportes = \DB::table('ticket_soporte')
        ->leftJoin('users', 'ticket_soporte.id_usuario', '=', 'users.id')
        ->select(
            'ticket_soporte.*',
            'users.email as user_email',
            'users.name as user_name'
        )
        ->orderBy('ticket_soporte.fecha_creacion', 'desc')
        ->paginate(20);

    return view('admin.admin', compact(
        'seccion',
        'totalReportes', 'reportesAbiertos', 'reportesEnProceso', 'reportesResueltos',
        'reportes'
    ));
}

public function cambiarEstadoReporte(Request $request, $id)
{
    $request->validate(['estado' => 'required|in:Abierto,En Proceso,Resuelto']);
    \DB::table('ticket_soporte')->where('id_ticket', $id)->update(['estado' => $request->estado]);
    return redirect()->back()->with('success', 'Ticket actualizado.');
}

}
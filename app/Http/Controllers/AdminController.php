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
        $empresasSuspendidas = Empresa::where('estado', 'suspendida')->count();

        $empresas = Empresa::with('user')
            ->withCount(['ofertas as ofertas_activas_count' => fn($q) => $q->where('estado', 'activa')])
            ->orderBy('fecha_creacion', 'desc')
            ->paginate(20);

        return view('admin.admin', compact(
            'seccion',
            'totalEmpresas', 'empresasAprobadas', 'empresasPendientes', 'empresasRechazadas', 'empresasSuspendidas',
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

        if ($estadoAnterior !== $request->estado && in_array($request->estado, ['aprobada', 'suspendida', 'rechazada'])) {
            $notifs = [
                'aprobada'   => [
                    'titulo'  => 'Tu cuenta fue aprobada',
                    'mensaje' => 'El administrador aprobó tu cuenta. Ya podés publicar ofertas laborales.',
                    'tipo'    => 'success',
                    'url'     => route('empresa.home'),
                ],
                'suspendida' => [
                    'titulo'  => 'Tu cuenta fue suspendida',
                    'mensaje' => 'El administrador suspendió tu cuenta. Contactate con soporte para más información.',
                    'tipo'    => 'danger',
                    'url'     => route('configuracion'),
                ],
                'rechazada'  => [
                    'titulo'  => 'Tu cuenta fue rechazada',
                    'mensaje' => 'El administrador rechazó el registro de tu empresa. Contactate con soporte.',
                    'tipo'    => 'danger',
                    'url'     => route('configuracion'),
                ],
            ];

            if (isset($notifs[$request->estado])) {
                $n = $notifs[$request->estado];
                \DB::table('notificaciones')->insert([
                    'id_usuario'  => $empresa->id_usuario,
                    'titulo'      => $n['titulo'],
                    'mensaje'     => $n['mensaje'],
                    'url'         => $n['url'],
                    'tipo'        => $n['tipo'],
                    'leida'       => false,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        // 🔥 SINCRONIZACIÓN AUTOMÁTICA DE OFERTAS
        if (in_array($request->estado, ['suspendida', 'rechazada'])) {
            $empresa->ofertas()->update(['estado' => 'pausada']);
        }

        if ($request->estado === 'aprobada') {
            $empresa->ofertas()->update(['estado' => 'activa']);
        }

        // ✉️ ENVIAR CORREO DE NOTIFICACIÓN SI EL ESTADO CAMBIA
        if ($estadoAnterior !== $request->estado && in_array($request->estado, ['aprobada', 'rechazada', 'suspendida'])) {
            if ($empresa->user && $empresa->user->email) {
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

        return redirect()->back()->with('success', "Empresa actualizada a {$request->estado}.");
    }

    public function cambiarEstadoOferta(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:Activa,Pausada,Cerrada',
            'motivo' => 'nullable|string|max:5000',
        ]);

        $oferta = Oferta::with('empresa')->findOrFail($id);

        if ($request->estado === 'Pausada' && $oferta->estado === 'Pausada') {
            return redirect()->back()->with('error', 'Esta oferta ya está pausada. No se puede volver a pausar.');
        }

        if ($request->estado === 'Pausada') {
            $oferta->update([
                'estado'             => 'Pausada',
                'pausada_por_admin'  => true,
                'motivo_pausa_admin' => $request->motivo ?? null,
            ]);

            \DB::table('notificaciones')->insert([
                'id_usuario'  => $oferta->empresa->id_usuario,
                'titulo'      => 'Tu publicación fue pausada',
                'mensaje'     => "La oferta \"{$oferta->titulo}\" fue pausada por el administrador."
                                . ($request->motivo ? " Motivo: {$request->motivo}." : ''),
                'url'         => route('empresa.home'),
                'tipo'        => 'warning',
                'leida'       => false,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            if ($request->filled('motivo') && $oferta->empresa->id_usuario) {
                $adminId       = auth()->id();
                $empresaUserId = $oferta->empresa->id_usuario;

                $chat = \App\Models\Chat::betweenUsers($adminId, $empresaUserId)->first();

                if (!$chat) {
                    $chat = \App\Models\Chat::create([
                        'id_usuario_1' => $adminId,
                        'id_usuario_2' => $empresaUserId,
                    ]);
                }

                \App\Models\Mensaje::create([
                    'id_chat'      => $chat->id_chat,
                    'id_remitente' => $adminId,
                    'contenido'    => "Tu oferta \"{$oferta->titulo}\" fue pausada.\r\n\r Motivo: {$request->motivo}",
                    'leido'        => false,
                ]);
            }
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
        $oferta = Oferta::findOrFail($id);

        // Hacer soft delete de todas las postulaciones de esta oferta
        $oferta->postulaciones()->delete();

        // Luego hacer soft delete de la oferta
        $oferta->delete();

        return redirect()->back()->with('success', 'Oferta y sus postulaciones movidas a papelera.');
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

       
        foreach ($request->ids as $id) {
            $oferta = Oferta::find($id);
            if ($oferta) {
                $oferta->postulaciones()->delete();
                $oferta->delete();
            }
        }

        return redirect()->back()->with('success', count($request->ids) . ' ofertas y sus postulaciones movidas a papelera.');
    }

    public function eliminarEstudiante($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        $idUsuario  = $estudiante->id_usuario;

        $estudiante->habilidades()->detach();
        $estudiante->postulaciones()->withTrashed()->forceDelete();
        $estudiante->delete();

        if ($idUsuario) {
            $this->eliminarUserConDependencias($idUsuario);
        }

        return redirect()->back()->with('success', 'Estudiante eliminado.');
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
            $estudiante->postulaciones()->withTrashed()->forceDelete();
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

        $ofertaIds = Oferta::withTrashed()
            ->where('id_empresa', $empresa->id_empresa)
            ->pluck('id_oferta');

        if ($ofertaIds->isNotEmpty()) {
            // Eliminar postulaciones (incluidas las eliminadas)
            \App\Models\Postulacion::withTrashed()->whereIn('id_oferta', $ofertaIds)->forceDelete();

            // Eliminar tablas pivote
            \DB::table('oferta_habilidad')->whereIn('id_oferta', $ofertaIds)->delete();
            \DB::table('oferta_carrera')->whereIn('id_oferta', $ofertaIds)->delete();

            // Eliminar ofertas (incluidas las eliminadas)
            Oferta::withTrashed()->whereIn('id_oferta', $ofertaIds)->forceDelete();
        }

        $empresa->delete();

        if ($idUsuario) {
            $this->eliminarUserConDependencias($idUsuario);
        }

        return redirect()->back()->with('success', 'Empresa, ofertas y postulantes eliminados.');
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

            $ofertaIds = Oferta::withTrashed()
                ->where('id_empresa', $empresa->id_empresa)
                ->pluck('id_oferta');

            if ($ofertaIds->isNotEmpty()) {
                \App\Models\Postulacion::withTrashed()->whereIn('id_oferta', $ofertaIds)->forceDelete();
                \DB::table('oferta_habilidad')->whereIn('id_oferta', $ofertaIds)->delete();
                \DB::table('oferta_carrera')->whereIn('id_oferta', $ofertaIds)->delete();
                Oferta::withTrashed()->whereIn('id_oferta', $ofertaIds)->forceDelete();
            }

            $empresa->delete();

            if ($idUsuario) {
                $this->eliminarUserConDependencias($idUsuario);
            }
        }

        return redirect()->back()->with('success', count($request->ids) . ' empresas eliminadas.');
    }


    private function eliminarUserConDependencias(int $idUsuario): void
    {
        $chatIds = \DB::table('chat')
            ->where('id_usuario_1', $idUsuario)
            ->orWhere('id_usuario_2', $idUsuario)
            ->pluck('id_chat');

        if ($chatIds->isNotEmpty()) {
            \DB::table('mensaje')->whereIn('id_chat', $chatIds)->delete();
            \DB::table('chat')->whereIn('id_chat', $chatIds)->delete();
        }

        \DB::table('ticket_soporte')->where('id_usuario', $idUsuario)->delete();
        User::where('id', $idUsuario)->delete();
    }

    /* ════════════════════════════════════════
       REPORTES
    ════════════════════════════════════════ */

    public function listarReportes()
    {
        $seccion = 'reportes';

        $totalReportes    = \DB::table('ticket_soporte')->count();
        $reportesAbiertos = \DB::table('ticket_soporte')->where('estado', 'Abierto')->count();
        $reportesEnProceso = \DB::table('ticket_soporte')->where('estado', 'En Proceso')->count();
        $reportesResueltos = \DB::table('ticket_soporte')->where('estado', 'Resuelto')->count();

        $reportes = \DB::table('ticket_soporte')
            ->leftJoin('users', 'ticket_soporte.id_usuario', '=', 'users.id')
            ->select('ticket_soporte.*', 'users.email as user_email', 'users.name as user_name')
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

    public function eliminarReporte($id)
    {
        \DB::table('ticket_soporte')->where('id_ticket', $id)->delete();
        return redirect()->back()->with('success', 'Ticket eliminado.');
    }

    /* ════════════════════════════════════════
       PAPELERA
    ════════════════════════════════════════ */

    public function papelera()
    {
        $seccion = 'papelera';

        $postulacionesEliminadas = \App\Models\Postulacion::onlyTrashed()
            ->with(['estudiante', 'oferta.empresa'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(20, ['*'], 'page_post');

        $ofertasEliminadas = \App\Models\Oferta::onlyTrashed()
            ->with('empresa')
            ->orderBy('deleted_at', 'desc')
            ->paginate(20, ['*'], 'page_ofe');

        return view('admin.admin', compact(
            'seccion',
            'postulacionesEliminadas',
            'ofertasEliminadas'
        ));
    }

    public function restaurarPostulacion($id)
    {
        \App\Models\Postulacion::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->back()->with('success', 'Postulación restaurada.');
    }

    public function restaurarOferta($id)
    {
        $oferta = \App\Models\Oferta::onlyTrashed()->findOrFail($id);
        $oferta->restore();
        $oferta->postulaciones()->onlyTrashed()->restore();

        return redirect()->back()->with('success', 'Oferta restaurada.');
    }

    public function eliminarPostulacionDefinitivo($id)
    {
        \App\Models\Postulacion::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->back()->with('success', 'Postulación eliminada definitivamente.');
    }


    public function eliminarOfertaDefinitivo($id)
    {
        $oferta = \App\Models\Oferta::onlyTrashed()->findOrFail($id);
        $ofertaId = $oferta->id_oferta;

        // Eliminar postulaciones (incluidas las eliminadas)
        \App\Models\Postulacion::withTrashed()->where('id_oferta', $ofertaId)->forceDelete();

        // Eliminar tablas pivote
        \DB::table('oferta_habilidad')->where('id_oferta', $ofertaId)->delete();
        \DB::table('oferta_carrera')->where('id_oferta', $ofertaId)->delete();

        $oferta->forceDelete();

        return redirect()->back()->with('success', 'Oferta eliminada definitivamente.');
    }
}
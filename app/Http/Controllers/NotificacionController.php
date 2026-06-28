<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Services\NotificacionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class NotificacionController extends Controller
{
    public function __construct(
        private readonly NotificacionService $notificacionService
    ) {}

    // ─── Vistas ───────────────────────────────────────────────

    /**
     * Vista del historial completo paginado.
     * GET /notificaciones
     */
    public function historial()
    {
        $notificaciones = $this->notificacionService->obtenerHistorial(Auth::id());
        $rol = Auth::user()->rol;

        return match($rol) {
            'estudiante' => view('estudiante.notificaciones.index', compact('notificaciones')),
            'empresa'    => view('empresa.notificaciones.index', compact('notificaciones')),
            'admin'      => view('admin.notificaciones.index', compact('notificaciones')),
            default      => abort(403),
        };
    }
    // ─── API Endpoints (usados por el dropdown) ───────────────

    /**
     * Resumen para el dropdown: cantidad no leídas + últimas 10.
     * GET /notificaciones/api/resumen
     */
    public function resumen(): JsonResponse
    {
        return response()->json(
            $this->notificacionService->resumen(Auth::id())
        );
    }

    /**
     * Solo el contador de no leídas (usado por el polling del badge).
     * GET /notificaciones/api/contador
     */
    public function contarNoLeidas(): JsonResponse
    {
        return response()->json([
            'cantidad' => $this->notificacionService->contarNoLeidas(Auth::id()),
        ]);
    }

    /**
     * Marca una notificación como leída.
     * Verifica que pertenezca al usuario autenticado.
     * POST /notificaciones/api/marcar-leida
     */
    public function marcarLeida(Request $request): JsonResponse
    {
        $request->validate([
            'id' => [
                'required',
                'integer',
                Rule::exists('notificaciones', 'id')->where('id_usuario', Auth::id()),
            ],
        ]);

        $this->notificacionService->marcarComoLeida($request->id, Auth::id());

        return response()->json(['success' => true]);
    }

    /**
     * Marca todas las notificaciones del usuario como leídas.
     * POST /notificaciones/api/marcar-todas
     */
    public function marcarTodasComoLeidas(): JsonResponse
    {
        $cantidad = $this->notificacionService->marcarTodasComoLeidas(Auth::id());

        return response()->json([
            'success'  => true,
            'marcadas' => $cantidad,
        ]);
    }


        public function eliminar($id)
    {
        $this->notificacionService->eliminar($id, Auth::id());

        return response()->json(['success' => true]);
    }

    public function eliminarTodas()
    {
        $this->notificacionService->eliminarTodas(Auth::id());

        return response()->json(['success' => true]);
    }
}
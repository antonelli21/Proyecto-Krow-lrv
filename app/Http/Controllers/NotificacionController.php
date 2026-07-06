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

    public function resumen(): JsonResponse
    {
        return response()->json(
            $this->notificacionService->resumen(Auth::id())
        );
    }


    public function contarNoLeidas(): JsonResponse
    {
        return response()->json([
            'cantidad' => $this->notificacionService->contarNoLeidas(Auth::id()),
        ]);
    }


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
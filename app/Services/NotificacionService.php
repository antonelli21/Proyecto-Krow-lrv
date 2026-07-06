<?php

namespace App\Services;

use App\Models\Notificacion;
use App\Models\User;
use Carbon\Carbon;

class NotificacionService
{

    public function crear(
        int $usuarioId,
        string $titulo,
        string $mensaje,
        ?string $url = null,
        string $tipo = Notificacion::TIPO_INFO
    ): Notificacion {
        return Notificacion::create([
            'id_usuario' => $usuarioId,
            'titulo'     => $titulo,
            'mensaje'    => $mensaje,
            'url'        => $url,
            'tipo'       => in_array($tipo, Notificacion::TIPOS_VALIDOS) ? $tipo : Notificacion::TIPO_INFO,
            'leida'      => false,
        ]);
    }

 
    public function crearSiNoExisteHoy(
        int $usuarioId,
        string $titulo,
        string $mensaje,
        ?string $url = null,
        string $tipo = Notificacion::TIPO_INFO
    ): ?Notificacion {
        $existe = Notificacion::delUsuario($usuarioId)
            ->where('titulo', $titulo)
            ->where('created_at', '>=', Carbon::now()->subHours(20))
            ->exists();

        return $existe ? null : $this->crear($usuarioId, $titulo, $mensaje, $url, $tipo);
    }


    public function notificarEstudiante(
        int $id,
        string $titulo,
        string $mensaje,
        ?string $url = null,
        string $tipo = Notificacion::TIPO_INFO
    ): Notificacion {
        return $this->crear($id, $titulo, $mensaje, $url, $tipo);
    }


    public function notificarEmpresa(
        int $id,
        string $titulo,
        string $mensaje,
        ?string $url = null,
        string $tipo = Notificacion::TIPO_INFO
    ): Notificacion {
        return $this->crear($id, $titulo, $mensaje, $url, $tipo);
    }


    public function notificarAdmin(
        string $titulo,
        string $mensaje,
        ?string $url = null,
        string $tipo = Notificacion::TIPO_INFO
    ): void {
        User::where('rol', 'admin')
            ->each(fn($admin) => $this->crear($admin->id, $titulo, $mensaje, $url, $tipo));
    }


    public function obtenerRecientes(int $usuarioId, int $limite = 10): array
    {
        return Notificacion::delUsuario($usuarioId)
            ->latest()
            ->take($limite)
            ->get()
            ->map(fn($n) => $this->formatear($n))
            ->all();
    }


    public function contarNoLeidas(int $usuarioId): int
    {
        return Notificacion::delUsuario($usuarioId)
            ->noLeidas()
            ->count();
    }


    public function resumen(int $usuarioId): array
    {
        return [
            'cantidad' => $this->contarNoLeidas($usuarioId),
            'recientes' => $this->obtenerRecientes($usuarioId),
        ];
    }

    public function obtenerHistorial(int $usuarioId)
    {
        return Notificacion::delUsuario($usuarioId)
            ->latest()
            ->paginate(20);
    }


    public function marcarComoLeida(int $notificacionId, int $usuarioId): bool
    {
        return Notificacion::delUsuario($usuarioId)
            ->where('id', $notificacionId)
            ->update(['leida' => true]) > 0;
    }


    public function marcarTodasComoLeidas(int $usuarioId): int
    {
        return Notificacion::delUsuario($usuarioId)
            ->noLeidas()
            ->update(['leida' => true]);
    }

    
    private function formatear(Notificacion $n): array
    {
        return [
            'id'      => $n->id,
            'titulo'  => $n->titulo,
            'mensaje' => $n->mensaje,
            'url'     => $n->url,
            'tipo'    => $n->tipo,
            'icono'   => $n->icono,  // Accessor del modelo
            'leida'   => $n->leida,
            'fecha'   => $n->created_at->diffForHumans(),
        ];
    }


        public function eliminar(int $notificacionId, int $usuarioId): bool
        {
            return Notificacion::delUsuario($usuarioId)
                ->where('id', $notificacionId)
                ->delete() > 0;
        }

        public function eliminarTodas(int $usuarioId): int
        {
            return Notificacion::delUsuario($usuarioId)
                ->delete();
        }
}
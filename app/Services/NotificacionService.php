<?php

namespace App\Services;

use App\Models\Notificacion;
use App\Models\User;
use Carbon\Carbon;

class NotificacionService
{
    // ─── Creación base ────────────────────────────────────────

    /**
     * Crea una nueva notificación.
     */
    public function crear(
        int $usuarioId,
        string $titulo,
        string $mensaje,
        ?string $url = null,
        string $tipo = Notificacion::TIPO_INFO
    ): Notificacion {
        return Notificacion::create([
            'usuario_id' => $usuarioId,
            'titulo'     => $titulo,
            'mensaje'    => $mensaje,
            'url'        => $url,
            'tipo'       => in_array($tipo, Notificacion::TIPOS_VALIDOS) ? $tipo : Notificacion::TIPO_INFO,
            'leida'      => false,
        ]);
    }

    /**
     * Crea la notificación solo si no existe una igual en las últimas 20 horas.
     * Útil para recordatorios y alertas que no deben repetirse.
     */
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

    // ─── Helpers por rol ──────────────────────────────────────

    /**
     * Notifica a un estudiante.
     *
     * Ejemplos de uso:
     *   $this->notificarEstudiante($id, 'Nueva oferta disponible', '...', '/ofertas/5');
     *   $this->notificarEstudiante($id, 'Avanzaste en la preselección', '...', '/postulaciones/3', Notificacion::TIPO_SUCCESS);
     */
    public function notificarEstudiante(
        int $id,
        string $titulo,
        string $mensaje,
        ?string $url = null,
        string $tipo = Notificacion::TIPO_INFO
    ): Notificacion {
        return $this->crear($id, $titulo, $mensaje, $url, $tipo);
    }

    /**
     * Notifica a una empresa.
     *
     * Ejemplos de uso:
     *   $this->notificarEmpresa($id, 'Nuevo postulante', '...', '/postulantes/12');
     *   $this->notificarEmpresa($id, 'Cuenta suspendida', '...', '/cuenta', Notificacion::TIPO_DANGER);
     */
    public function notificarEmpresa(
        int $id,
        string $titulo,
        string $mensaje,
        ?string $url = null,
        string $tipo = Notificacion::TIPO_INFO
    ): Notificacion {
        return $this->crear($id, $titulo, $mensaje, $url, $tipo);
    }

    /**
     * Notifica a TODOS los administradores.
     * Si no hay admins registrados, no hace nada.
     *
     * Ejemplos de uso:
     *   $this->notificarAdmin('Nueva empresa registrada', '...', '/admin/empresas/8');
     *   $this->notificarAdmin('Reporte de queja', '...', '/admin/reportes/2', Notificacion::TIPO_DANGER);
     */
    public function notificarAdmin(
        string $titulo,
        string $mensaje,
        ?string $url = null,
        string $tipo = Notificacion::TIPO_INFO
    ): void {
        User::where('rol', 'admin')
            ->each(fn($admin) => $this->crear($admin->id, $titulo, $mensaje, $url, $tipo));
    }

    // ─── Consultas ────────────────────────────────────────────

    /**
     * Últimas notificaciones para el dropdown (formateadas para JSON).
     */
    public function obtenerRecientes(int $usuarioId, int $limite = 10): array
    {
        return Notificacion::delUsuario($usuarioId)
            ->latest()
            ->take($limite)
            ->get()
            ->map(fn($n) => $this->formatear($n))
            ->all();
    }

    /**
     * Cantidad de notificaciones no leídas.
     */
    public function contarNoLeidas(int $usuarioId): int
    {
        return Notificacion::delUsuario($usuarioId)
            ->noLeidas()
            ->count();
    }

    /**
     * Resumen para el dropdown: cantidad no leídas + recientes.
     * Usado por NotificacionController::resumen().
     */
    public function resumen(int $usuarioId): array
    {
        return [
            'cantidad' => $this->contarNoLeidas($usuarioId),
            'recientes' => $this->obtenerRecientes($usuarioId),
        ];
    }

    /**
     * Historial completo paginado (para la vista /notificaciones).
     */
    public function obtenerHistorial(int $usuarioId)
    {
        return Notificacion::delUsuario($usuarioId)
            ->latest()
            ->paginate(20);
    }

    // ─── Acciones ─────────────────────────────────────────────

    /**
     * Marca una notificación como leída.
     * Verifica que pertenezca al usuario para evitar manipulación.
     */
    public function marcarComoLeida(int $notificacionId, int $usuarioId): bool
    {
        return Notificacion::delUsuario($usuarioId)
            ->where('id', $notificacionId)
            ->update(['leida' => true]) > 0;
    }

    /**
     * Marca todas las notificaciones del usuario como leídas.
     * Devuelve la cantidad de filas afectadas.
     */
    public function marcarTodasComoLeidas(int $usuarioId): int
    {
        return Notificacion::delUsuario($usuarioId)
            ->noLeidas()
            ->update(['leida' => true]);
    }

    // ─── Formato ──────────────────────────────────────────────

    /**
     * Serializa una notificación para el frontend.
     */
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
}
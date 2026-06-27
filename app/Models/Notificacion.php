<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
 
class Notificacion extends Model
{
    use HasFactory;
 
    protected $table = 'notificaciones';
 
    /**
     * Tipos válidos de notificación.
     * Usados para icono y color en el frontend.
     */
    const TIPO_INFO    = 'info';
    const TIPO_SUCCESS = 'success';
    const TIPO_WARNING = 'warning';
    const TIPO_DANGER  = 'danger';
    const TIPO_MESSAGE = 'message';
 
    const TIPOS_VALIDOS = [
        self::TIPO_INFO,
        self::TIPO_SUCCESS,
        self::TIPO_WARNING,
        self::TIPO_DANGER,
        self::TIPO_MESSAGE,
    ];
 
    protected $fillable = [
        'id_usuario',
        'titulo',
        'mensaje',
        'url',
        'tipo',
        'leida',
    ];
 
    protected $casts = [
        'leida'      => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
 
    // ─── Relaciones ───────────────────────────────────────────
 
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
 
    // ─── Scopes ───────────────────────────────────────────────
 
    /**
     * Solo notificaciones no leídas.
     */
    public function scopeNoLeidas(Builder $query): Builder
    {
        return $query->where('leida', false);
    }
 
    /**
     * Solo notificaciones de un usuario específico.
     */
    public function scopeDelUsuario(Builder $query, int $usuarioId): Builder
    {
        return $query->where('id_usuario', $usuarioId);
    }
 
    /**
     * Solo notificaciones de un tipo específico.
     */
    public function scopeDelTipo(Builder $query, string $tipo): Builder
    {
        return $query->where('tipo', $tipo);
    }
 
    // ─── Accesors ─────────────────────────────────────────────
 
    /**
     * Devuelve el ícono SVG path correspondiente al tipo.
     * Útil para renderizar en Blade sin lógica en la vista.
     */
    public function getIconoAttribute(): string
    {
        return match($this->tipo) {
            self::TIPO_SUCCESS => '✓',
            self::TIPO_WARNING => '⚠',
            self::TIPO_DANGER  => '✕',
            self::TIPO_MESSAGE => '✉',
            default            => 'ℹ',
        };
    }
}
 
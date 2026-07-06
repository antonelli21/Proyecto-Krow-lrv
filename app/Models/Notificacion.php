<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
 
class Notificacion extends Model
{
    use HasFactory;
 
    protected $table = 'notificaciones';
 
 
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
 

 
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
 

 

    public function scopeNoLeidas(Builder $query): Builder
    {
        return $query->where('leida', false);
    }
 

    public function scopeDelUsuario(Builder $query, int $usuarioId): Builder
    {
        return $query->where('id_usuario', $usuarioId);
    }
 

    public function scopeDelTipo(Builder $query, string $tipo): Builder
    {
        return $query->where('tipo', $tipo);
    }
 

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
 
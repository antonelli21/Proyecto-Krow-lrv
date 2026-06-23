<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chat extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'chat';
    protected $primaryKey = 'id_chat';

    protected $fillable = [
        'id_usuario_1',
        'id_usuario_2',
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
    ];

    // --- SCOPE PARA BUSCAR CHATS ---
    // Esto es lo que permite que tu ChatController sea tan limpio
    public function scopeBetweenUsers($query, $user1, $user2)
    {
        return $query->where(function ($q) use ($user1, $user2) {
            $q->where('id_usuario_1', $user1)->where('id_usuario_2', $user2);
        })->orWhere(function ($q) use ($user1, $user2) {
            $q->where('id_usuario_1', $user2)->where('id_usuario_2', $user1);
        });
    }

    // --- RELACIONES ---
    public function usuario1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_1');
    }

    public function usuario2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_2');
    }

    public function mensajes(): HasMany
    {
        return $this->hasMany(Mensaje::class, 'id_chat', 'id_chat');
    }
        public function ultimoMensaje()
        {
            // Obtiene el último mensaje de este chat ordenado por fecha
            return $this->hasOne(Mensaje::class, 'id_chat')->latestOfMany('fecha_envio');
        }
}
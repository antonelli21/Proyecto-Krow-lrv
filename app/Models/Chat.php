<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Mensaje;
use App\Models\User;

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

    public function usuario1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_1', 'id');
    }

    public function usuario2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_2', 'id');
    }

    public function mensajes(): HasMany
    {
        return $this->hasMany(Mensaje::class, 'id_chat', 'id_chat');
    }
}

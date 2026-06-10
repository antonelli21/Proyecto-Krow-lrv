<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mensaje extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'mensaje';
    protected $primaryKey = 'id_mensaje';

    protected $fillable = [
        'id_chat',
        'id_remitente',
        'contenido',
        'leido',
    ];

    protected $casts = [
        'fecha_envio' => 'datetime',
        'leido' => 'boolean',
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'id_chat', 'id_chat');
    }

    public function remitente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_remitente', 'id');
    }
}

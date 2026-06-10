<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketSoporte extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'ticket_soporte';
    protected $primaryKey = 'id_ticket';

    protected $fillable = [
        'id_usuario',
        'asunto',
        'descripcion',
        'estado',
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }
}

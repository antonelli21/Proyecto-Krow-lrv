<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Postulacion extends Model
{
    use HasFactory;
     use SoftDeletes;

    public $timestamps = false;
    protected $table = 'postulacion';
    protected $primaryKey = 'id_postulacion';

    protected $fillable = [
        'id_estudiante',
        'id_oferta',
        'estado',
        'fecha_postulacion',

    ];

    protected $casts = [
        'fecha_postulacion' => 'datetime',
    ];

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante', 'id_estudiante');
    }

    public function oferta(): BelongsTo
    {
        return $this->belongsTo(Oferta::class, 'id_oferta', 'id_oferta');
    }
}

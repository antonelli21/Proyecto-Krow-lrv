<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Postulacion;

class Estudiante extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'estudiante';
    protected $primaryKey = 'id_estudiante';

    protected $fillable = [
        'id_usuario',
        'nombre',
        'apellido',
        'dni',
        'legajo',
        'fecha_nacimiento',
        'telefono',
        'id_carrera',
        'descripcion',
        'modalidad_deseada',
        'disponibilidad_horaria',
        'foto_perfil',
        'cv',
        'portfolio',
        'linkedin',
        'github',
        'id_localidad',
        'id_provincia',
        'estado',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_creacion' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class, 'id_carrera', 'id_carrera');
    }

    public function localidad(): BelongsTo
    {
        return $this->belongsTo(Localidad::class, 'id_localidad', 'id_localidad');
    }

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class, 'id_provincia', 'id_provincia');
    }

    public function habilidades(): BelongsToMany
    {
        return $this->belongsToMany(Habilidad::class, 'estudiante_habilidad', 'id_estudiante', 'id_habilidad');
    }

    public function postulaciones(): HasMany
    {
        return $this->hasMany(Postulacion::class, 'id_estudiante', 'id_estudiante');
    }
}

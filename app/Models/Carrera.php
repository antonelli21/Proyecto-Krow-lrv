<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrera extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'carrera';
    protected $primaryKey = 'id_carrera';

    protected $fillable = [
        'nombre',
    ];

    public function estudiantes(): HasMany
    {
        return $this->hasMany(Estudiante::class, 'id_carrera', 'id_carrera');
    }

    public function ofertas(): BelongsToMany
    {
        return $this->belongsToMany(Oferta::class, 'oferta_carrera', 'id_carrera', 'id_oferta');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Estudiante;
use App\Models\Oferta;

class Habilidad extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'habilidad';
    protected $primaryKey = 'id_habilidad';

    protected $fillable = [
        'nombre',
    ];

    public function estudiantes(): BelongsToMany
    {
        return $this->belongsToMany(Estudiante::class, 'estudiante_habilidad', 'id_habilidad', 'id_estudiante');
    }

    public function ofertas(): BelongsToMany
    {
        return $this->belongsToMany(Oferta::class, 'oferta_habilidad', 'id_habilidad', 'id_oferta');
    }
}

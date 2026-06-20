<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Localidad extends Model
{
    use HasFactory;

    // Configuración de la tabla
    protected $table = 'localidad';
    protected $primaryKey = 'id_localidad';
    public $timestamps = false;

    protected $fillable = [
        'id_provincia',
        'nombre',
    ];

    // Relaciones
    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class, 'id_provincia', 'id_provincia');
    }

    public function estudiantes(): HasMany
    {
        return $this->hasMany(Estudiante::class, 'id_localidad', 'id_localidad');
    }

    public function empresas(): HasMany
    {
        return $this->hasMany(Empresa::class, 'id_localidad', 'id_localidad');
    }

    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class, 'id_localidad', 'id_localidad');
    }
}
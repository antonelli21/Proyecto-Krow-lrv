<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Estudiante;
use App\Models\Empresa;
use App\Models\Oferta;

class Localidad extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'localidad';
    protected $primaryKey = 'id_localidad';

    protected $fillable = [
        'id_provincia',
        'nombre',
    ];

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

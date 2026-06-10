<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Localidad;
use App\Models\Estudiante;
use App\Models\Empresa;
use App\Models\Oferta;

class Provincia extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'provincia';
    protected $primaryKey = 'id_provincia';

    protected $fillable = [
        'nombre',
    ];

    public function localidades(): HasMany
    {
        return $this->hasMany(Localidad::class, 'id_provincia', 'id_provincia');
    }

    public function estudiantes(): HasMany
    {
        return $this->hasMany(Estudiante::class, 'id_provincia', 'id_provincia');
    }

    public function empresas(): HasMany
    {
        return $this->hasMany(Empresa::class, 'id_provincia', 'id_provincia');
    }

    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class, 'id_provincia', 'id_provincia');
    }
}

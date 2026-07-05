<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes; 
use App\Models\Empresa;
use App\Models\Localidad;
use App\Models\Provincia;
use App\Models\Carrera;
use App\Models\Habilidad;
use App\Models\Postulacion;

class Oferta extends Model
{
    use HasFactory;
     use SoftDeletes;

    public $timestamps = false;
    protected $table = 'oferta';
    protected $primaryKey = 'id_oferta';

    protected $fillable = [
        'id_empresa',
        'titulo',
        'descripcion',
        'requisitos',
        'area',
        'experiencia_requerida',
        'tipo_oferta',
        'modalidad',
        'salario_min',
        'salario_max',
        'id_localidad',
        'id_provincia',
        'fecha_cierre',
        'estado',
        'pausada_por_admin',
        'motivo_pausa_admin',
    ];

    protected $casts = [
        'fecha_publicacion' => 'datetime',
        'fecha_cierre' => 'date',
        'pausada_por_admin' => 'boolean',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id_empresa');
    }

    public function localidad(): BelongsTo
    {
        return $this->belongsTo(Localidad::class, 'id_localidad', 'id_localidad');
    }

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class, 'id_provincia', 'id_provincia');
    }
    public function carreras(): BelongsToMany
{
    return $this->belongsToMany(Carrera::class, 'oferta_carrera', 'id_oferta', 'id_carrera');
}
    public function habilidades(): BelongsToMany
    {
        return $this->belongsToMany(Habilidad::class, 'oferta_habilidad', 'id_oferta', 'id_habilidad');
    }

    public function postulaciones(): HasMany
    {
        return $this->hasMany(Postulacion::class, 'id_oferta', 'id_oferta');
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Empresa extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'empresa';
    protected $primaryKey = 'id_empresa';

    protected $fillable = [
        'id_usuario',
        'nombre_empresa',
        'razon_social',
        'cuit',
        'rubro',
        'direccion',
        'telefono',
        'email_contacto',
        'sitio_web',
        'descripcion',
        'logo',
        'representante',
        'email_representante',
        'tamano_empresa',
        'linkedin',
        'instagram',
        'facebook',
        'id_localidad',
        'id_provincia',
        'estado',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function localidad(): BelongsTo
    {
        return $this->belongsTo(Localidad::class, 'id_localidad', 'id_localidad');
    }

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class, 'id_provincia', 'id_provincia');
    }

    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class, 'id_empresa', 'id_empresa');
    }
}

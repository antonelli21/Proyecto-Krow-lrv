<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Estudiante;
use App\Models\Empresa;
use App\Models\TicketSoporte;
use App\Models\Mensaje;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * Se agregaron email_verification_code y email_verification_expires
     * para el sistema de verificación de email por código.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        // Campos para verificación de email por código de 6 dígitos
        'email_verification_code',
        'email_verification_expires',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * Se ocultó email_verification_code para que no se exponga en APIs/JSON.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        // Ocultar el código de verificación en las respuestas JSON
        'email_verification_code',
    ];

    /**
     * Get the attributes that should be cast.
     * Se agregó el cast de email_verification_expires a datetime.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'fecha_creacion' => 'datetime',
            'password' => 'hashed',
            // Cast para que la fecha de expiración del código se maneje como Carbon
            'email_verification_expires' => 'datetime',
        ];
    }

    public function estudiante(): HasOne
    {
        return $this->hasOne(Estudiante::class, 'id_usuario', 'id');
    }

    public function empresa(): HasOne
    {
        return $this->hasOne(Empresa::class, 'id_usuario', 'id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(TicketSoporte::class, 'id_usuario', 'id');
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Mensaje::class, 'id_remitente', 'id');
    }
}

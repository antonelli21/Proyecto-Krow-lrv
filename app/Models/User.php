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
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'fecha_creacion' => 'datetime',
            'password' => 'hashed',
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

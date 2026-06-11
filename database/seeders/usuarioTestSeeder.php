<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Estudiante;
use App\Models\Empresa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsuarioTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ═════════════════════════════════════════════
        // 0. CARRERA DE PRUEBA
        // ═════════════════════════════════════════════
        DB::table('carrera')->insertOrIgnore([
            'id_carrera' => 1,
            'nombre'     => 'Tecnicatura en Programación',
        ]);

        // ═════════════════════════════════════════════
        // 1. ROL: ESTUDIANTE 1 (Juan Pérez)
        // ═════════════════════════════════════════════
        $userEstudiante = User::updateOrCreate(
            ['email' => 'estudiante@krow.com'],
            [
                'name'     => 'Juan Pérez',
                'password' => Hash::make('password123'),
                'rol'      => 'estudiante',
                'email_verified_at' => Carbon::now(),
                'email_verification_code' => null,
                'email_verification_expires' => null,
            ]
        );

        DB::table('estudiante')->insertOrIgnore([
            'id_estudiante'          => 1,
            'id_usuario'             => $userEstudiante->id,
            'nombre'                 => 'Juan',
            'apellido'               => 'Pérez',
            'dni'                    => 41234567,
            'legajo'                 => '12345',
            'fecha_nacimiento'       => '2001-05-15',
            'telefono'               => '1122334455',
            'id_carrera'             => 1,
            'descripcion'            => 'Estudiante de Sistemas buscando su primera experiencia IT.',
            'modalidad_deseada'      => 'Remoto',
            'disponibilidad_horaria' => 'Mañana y Tarde',
            'foto_perfil'            => null,
            'cv'                     => null,
            'portfolio'              => 'https://juanperez.dev',
            'linkedin'               => 'https://linkedin.com/in/juanperez',
            'github'                 => 'https://github.com/juanperez',
            'id_localidad'           => null,
            'id_provincia'           => null,
            'fecha_creacion'         => now(),
        ]);

        // ═════════════════════════════════════════════
        // 1b. ROL: ESTUDIANTE 2 (José Mendoza) 🆕
        // ═════════════════════════════════════════════
        $userEstudiante2 = User::updateOrCreate(
            ['email' => 'jose@krow.com'], // Email único para loguearse
            [
                'name'     => 'José Mendoza',
                'password' => Hash::make('123'), // Contraseña configurada en '123'
                'rol'      => 'estudiante',
                'email_verified_at' => Carbon::now(),
                'email_verification_code' => null,
                'email_verification_expires' => null,
            ]
        );

        DB::table('estudiante')->insertOrIgnore([
            'id_estudiante'          => 2, // ID diferente para no chocar con Juan
            'id_usuario'             => $userEstudiante2->id,
            'nombre'                 => 'José',
            'apellido'               => 'Mendoza',
            'dni'                    => 42345678,
            'legajo'                 => '54321',
            'fecha_nacimiento'       => '2002-09-20',
            'telefono'               => '1199887766',
            'id_carrera'             => 1,
            'descripcion'            => 'Desarrollador Junior entusiasmado por los desafíos backend.',
            'modalidad_deseada'      => 'Presencial',
            'disponibilidad_horaria' => 'Tarde',
            'foto_perfil'            => null,
            'cv'                     => null,
            'portfolio'              => 'https://josemendoza.dev',
            'linkedin'               => 'https://linkedin.com/in/josemendoza',
            'github'                 => 'https://github.com/josemendoza',
            'id_localidad'           => null,
            'id_provincia'           => null,
            'fecha_creacion'         => now(),
        ]);

        // ═════════════════════════════════════════════
        // 2. ROL: EMPRESA
        // ═════════════════════════════════════════════
        $userEmpresa = User::updateOrCreate(
            ['email' => 'empresa@krow.com'],
            [
                'name'     => 'Tech Solutions S.A.',
                'password' => Hash::make('password123'),
                'rol'      => 'empresa',
                'email_verified_at' => Carbon::now(),
                'email_verification_code' => null,
                'email_verification_expires' => null,
            ]
        );

        DB::table('empresa')->insertOrIgnore([
            'id_empresa'          => 1,
            'id_usuario'          => $userEmpresa->id,
            'nombre_empresa'      => 'Tech Solutions',
            'razon_social'        => 'Tech Solutions S.A.',
            'cuit'                => 30123456789,
            'rubro'               => 'Tecnología y Software',
            'direccion'           => 'Av. Corrientes 1234',
            'telefono'            => '1144445555',
            'email_contacto'      => 'contacto@techsolutions.com',
            'sitio_web'           => 'https://techsolutions.com',
            'descripcion'         => 'Empresa de desarrollo de sistemas cloud y outsourcing.',
            'logo'                => null,
            'representante'       => 'Carlos Gómez',
            'email_representante' => 'cgomez@techsolutions.com',
            'linkedin'            => 'https://linkedin.com/company/techsolutions',
            'instagram'           => null,
            'facebook'            => null,
            'fecha_creacion'      => now(),
        ]);

        // ═════════════════════════════════════════════
        // 3. ROL: ADMIN
        // ═════════════════════════════════════════════
        User::updateOrCreate(
            ['email' => 'admin@krow.com'],
            [
                'name'     => 'Administrador General',
                'password' => Hash::make('password123'),
                'rol'      => 'admin',
                'email_verified_at' => Carbon::now(),
                'email_verification_code' => null,
                'email_verification_expires' => null,
            ]
        );
    }
}

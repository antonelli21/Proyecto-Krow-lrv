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
        // ═══════════════════════════════════════════════════════
        // PROVINCIAS
        // ═══════════════════════════════════════════════════════
        $provincias = [
            [1,  'Buenos Aires'],
            [2,  'Buenos Aires-GBA'],
            [3,  'Capital Federal'],
            [4,  'Catamarca'],
            [5,  'Chaco'],
            [6,  'Chubut'],
            [7,  'Córdoba'],
            [8,  'Corrientes'],
            [9,  'Entre Ríos'],
            [10, 'Formosa'],
            [11, 'Jujuy'],
            [12, 'La Pampa'],
            [13, 'La Rioja'],
            [14, 'Mendoza'],
            [15, 'Misiones'],
            [16, 'Neuquén'],
            [17, 'Río Negro'],
            [18, 'Salta'],
            [19, 'San Juan'],
            [20, 'San Luis'],
            [21, 'Santa Cruz'],
            [22, 'Santa Fe'],
            [23, 'Santiago del Estero'],
            [24, 'Tierra del Fuego'],
            [25, 'Tucumán'],
        ];

        foreach ($provincias as [$id, $nombre]) {
            DB::table('provincia')->insertOrIgnore(['id_provincia' => $id, 'nombre' => $nombre]);
        }

        // ═══════════════════════════════════════════════════════
        // LOCALIDADES (muestra representativa por provincia)
        // El archivo SQL completo tiene las 2382 localidades;
        // acá van las principales para que los selects funcionen.
        // ═══════════════════════════════════════════════════════
        $localidades = [
            // Buenos Aires (1)
            [10, 1, 'Bahía Blanca'],
            [73, 1, 'La Plata'],
            [84, 1, 'Luján'],
            [90, 1, 'Mar del Plata'],
            [107, 1, 'Pilar'],
            [129, 1, 'San Nicolás'],
            [134, 1, 'Tandil'],
            [142, 1, 'Zárate'],
            // Buenos Aires GBA (2)
            [150, 2, 'Avellaneda'],
            [156, 2, 'Berazategui'],
            [181, 2, 'Florencio Varela'],
            [192, 2, 'Hurlingham'],
            [195, 2, 'Ituzaingó'],
            [202, 2, 'Lanús'],
            [205, 2, 'Lomas de Zamora'],
            [214, 2, 'Merlo'],
            [218, 2, 'Moreno'],
            [219, 2, 'Morón'],
            [229, 2, 'Quilmes'],
            [237, 2, 'San Fernando'],
            [239, 2, 'San Isidro'],
            [241, 2, 'San Justo'],
            [242, 2, 'San Martín'],
            [243, 2, 'San Miguel'],
            [249, 2, 'Tigre'],
            [255, 2, 'Vicente López'],
            // Capital Federal (3)
            [283, 3, 'Almagro'],
            [286, 3, 'Belgrano'],
            [289, 3, 'Caballito'],
            [294, 3, 'Flores'],
            [299, 3, 'Monserrat'],
            [303, 3, 'Palermo'],
            [309, 3, 'Recoleta'],
            [310, 3, 'Retiro'],
            [313, 3, 'San Nicolás'],
            [314, 3, 'San Telmo'],
            [317, 3, 'Villa Crespo'],
            [329, 3, 'Villa Urquiza'],
            // Córdoba (7)
            [402, 7, 'Alta Gracia'],
            [406, 7, 'Capital'],
            [408, 7, 'Córdoba Capital'],
            [409, 7, 'Cosquín'],
            [414, 7, 'La Calera'],
            [416, 7, 'La Falda'],
            [422, 7, 'Río Cuarto'],
            [424, 7, 'San Francisco'],
            [426, 7, 'Villa Carlos Paz'],
            [429, 7, 'Villa María'],
            // Santa Fe (22)
            [555, 22, 'Cañada de Gómez'],
            [561, 22, 'Esperanza'],
            [563, 22, 'Funes'],
            [568, 22, 'Rafaela'],
            [569, 22, 'Reconquista'],
            [570, 22, 'Rosario'],
            [573, 22, 'San Lorenzo'],
            [574, 22, 'Santa Fe Capital'],
            [576, 22, 'Sunchales'],
            [577, 22, 'Venado Tuerto'],
            // Mendoza (14)
            [477, 14, 'Godoy Cruz'],
            [478, 14, 'Guaymallén'],
            [480, 14, 'Las Heras'],
            [481, 14, 'Luján de Cuyo'],
            [484, 14, 'Mendoza Capital'],
            [485, 14, 'San Rafael'],
            // Tucumán (25)
            [593, 25, 'Aguilares'],
            [595, 25, 'Banda del Río Sali'],
            [603, 25, 'San Miguel de Tucumán'],
            [605, 25, 'Tafí Viejo'],
            [606, 25, 'Yerba Buena'],
            // Salta (18)
            [522, 18, 'Cafayate'],
            [526, 18, 'Gral. Güemes'],
            [531, 18, 'Salta Capital'],
            [532, 18, 'Tartagal'],
            // Neuquén (16)
            [499, 16, 'Chos Malal'],
            [500, 16, 'Cutral Có'],
            [502, 16, 'Neuquén Capital'],
            [504, 16, 'Plottier'],
            [505, 16, 'San Martín de Los Andes'],
            // Río Negro (17)
            [509, 17, 'Bariloche'],
            [513, 17, 'Cipolletti'],
            [515, 17, 'Gral. Roca'],
            [520, 17, 'Viedma'],
            // Misiones (15)
            [493, 15, 'Posadas'],
            [494, 15, 'Puerto Iguazú'],
            [492, 15, 'Oberá'],
            // Entre Ríos (9)
            [441, 9, 'Concepción del Uruguay'],
            [442, 9, 'Concordia'],
            [446, 9, 'Gualeguaychú'],
            [449, 9, 'Paraná'],
            // San Juan (19)
            [533, 19, 'Caucete'],
            [538, 19, 'San Juan Capital'],
            // San Luis (20)
            [543, 20, 'Merlo'],
            [544, 20, 'San Luis Capital'],
            [545, 20, 'Villa Mercedes'],
            // Santa Cruz (21)
            [547, 21, 'El Calafate'],
            [553, 21, 'Río Gallegos'],
            // Tierra del Fuego (24)
            [590, 24, 'Río Grande'],
            [592, 24, 'Ushuaia'],
            // Santiago del Estero (23)
            [583, 23, 'La Banda'],
            [588, 23, 'Santiago del Estero Capital'],
            // La Pampa (12)
            [472, 12, 'Gral. Pico'],
            [473, 12, 'Santa Rosa'],
            // Chaco (5)
            [387, 5, 'Resistencia'],
            [388, 5, 'Sáenz Peña'],
            // Corrientes (8)
            [431, 8, 'Corrientes Capital'],
            [433, 8, 'Goya'],
            // Jujuy (11)
            [468, 11, 'San Salvador de Jujuy'],
            [469, 11, 'Tilcara'],
            // Chubut (6)
            [390, 6, 'Comodoro Rivadavia'],
            [397, 6, 'Puerto Madryn'],
            [400, 6, 'Trelew'],
            // Formosa (10)
            [455, 10, 'Formosa Capital'],
            // Catamarca (4)
            [336, 4, 'Capital'],
            // La Rioja (13)
            [476, 13, 'La Rioja Capital'],
        ];

        foreach ($localidades as [$id, $prov, $nombre]) {
            DB::table('localidad')->insertOrIgnore([
                'id_localidad' => $id,
                'id_provincia' => $prov,
                'nombre'       => $nombre,
            ]);
        }

        // ═══════════════════════════════════════════════════════
        // CARRERAS
        // ═══════════════════════════════════════════════════════
        $carreras = [
            [1,  'Ingeniería Aeronáutica/Aeroespacial'],
            [2,  'Ingeniería Electrónica'],
            [3,  'Ingeniería Ferroviaria'],
            [4,  'Ingeniería Industrial'],
            [5,  'Ingeniería Mecánica'],
            [6,  'Bioingeniería'],
            [7,  'Tecnicatura en Programación'],
            [8,  'Tecnicatura en Operación de Aeronaves'],
            [9,  'Tecnicatura en Material Rodante Ferroviario'],
            [10, 'Tecnicatura en Desarrollo y Producción de Videojuegos'],
            [11, 'Tecnicatura en Higiene y Seguridad en el Trabajo'],
            [12, 'Tecnicatura en Comercio Electrónico y Marketing Digital'],
            [13, 'Tecnicatura en Logística'],
        ];

        foreach ($carreras as [$id, $nombre]) {
            DB::table('carrera')->insertOrIgnore(['id_carrera' => $id, 'nombre' => $nombre]);
        }

        // ═══════════════════════════════════════════════════════
        // HABILIDADES
        // ═══════════════════════════════════════════════════════
        $habilidades = [
            [1,  'PHP'],
            [2,  'Laravel'],
            [3,  'JavaScript'],
            [4,  'TypeScript'],
            [5,  'React'],
            [6,  'Vue.js'],
            [7,  'Node.js'],
            [8,  'Python'],
            [9,  'Java'],
            [10, 'SQL'],
            [11, 'MySQL'],
            [12, 'PostgreSQL'],
            [13, 'Git'],
            [14, 'Docker'],
            [15, 'Linux'],
            [16, 'REST APIs'],
            [17, 'HTML/CSS'],
            [18, 'Tailwind CSS'],
            [19, 'Testing'],
            [20, 'Scrum / Agile'],
            [21, 'Unity'],
            [22, 'C#'],
            [23, 'Blender'],
            [24, 'Adobe Illustrator'],
            [25, 'Marketing Digital'],
            [26, 'SEO'],
            [27, 'Google Ads'],
            [28, 'Logística y Supply Chain'],
            [29, 'SAP'],
            [30, 'AutoCAD'],
        ];

        foreach ($habilidades as [$id, $nombre]) {
            DB::table('habilidad')->insertOrIgnore(['id_habilidad' => $id, 'nombre' => $nombre]);
        }

        // ═══════════════════════════════════════════════════════
        // USUARIOS + PERFILES
        // ═══════════════════════════════════════════════════════

        // --- Estudiante 1: Juan Pérez ---
        $userEstudiante = User::updateOrCreate(
            ['email' => 'estudiante@krow.com'],
            [
                'name'                       => 'Juan Pérez',
                'password'                   => Hash::make('password123'),
                'rol'                        => 'estudiante',
                'email_verified_at'          => Carbon::now(),
                'email_verification_code'    => null,
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
            'id_carrera'             => 7,   // Tecnicatura en Programación
            'descripcion'            => 'Estudiante de Sistemas buscando su primera experiencia IT.',
            'modalidad_deseada'      => 'Remoto',
            'disponibilidad_horaria' => 'Mañana y Tarde',
            'foto_perfil'            => null,
            'cv'                     => null,
            'portfolio'              => 'https://juanperez.dev',
            'linkedin'               => 'https://linkedin.com/in/juanperez',
            'github'                 => 'https://github.com/juanperez',
            'id_localidad'           => 241,  // San Justo, GBA
            'id_provincia'           => 2,
            'fecha_creacion'         => now(),
        ]);
        DB::table('estudiante_habilidad')->insertOrIgnore([
            ['id_estudiante' => 1, 'id_habilidad' => 3],   // JavaScript
            ['id_estudiante' => 1, 'id_habilidad' => 5],   // React
            ['id_estudiante' => 1, 'id_habilidad' => 17],  // HTML/CSS
            ['id_estudiante' => 1, 'id_habilidad' => 18],  // Tailwind CSS
            ['id_estudiante' => 1, 'id_habilidad' => 13],  // Git
            ['id_estudiante' => 1, 'id_habilidad' => 10],  // SQL
        ]);

        // --- Estudiante 2: José Mendoza ---
        $userEstudiante2 = User::updateOrCreate(
            ['email' => 'jose@krow.com'],
            [
                'name'                       => 'José Mendoza',
                'password'                   => Hash::make('123'),
                'rol'                        => 'estudiante',
                'email_verified_at'          => Carbon::now(),
                'email_verification_code'    => null,
                'email_verification_expires' => null,
            ]
        );
        DB::table('estudiante')->insertOrIgnore([
            'id_estudiante'          => 2,
            'id_usuario'             => $userEstudiante2->id,
            'nombre'                 => 'José',
            'apellido'               => 'Mendoza',
            'dni'                    => 42345678,
            'legajo'                 => '54321',
            'fecha_nacimiento'       => '2002-09-20',
            'telefono'               => '1199887766',
            'id_carrera'             => 7,
            'descripcion'            => 'Desarrollador Junior entusiasmado por los desafíos backend.',
            'modalidad_deseada'      => 'Presencial',
            'disponibilidad_horaria' => 'Tarde',
            'foto_perfil'            => null,
            'cv'                     => null,
            'portfolio'              => 'https://josemendoza.dev',
            'linkedin'               => 'https://linkedin.com/in/josemendoza',
            'github'                 => 'https://github.com/josemendoza',
            'id_localidad'           => 303,  // Palermo, CABA
            'id_provincia'           => 3,
            'fecha_creacion'         => now(),
        ]);
        DB::table('estudiante_habilidad')->insertOrIgnore([
            ['id_estudiante' => 2, 'id_habilidad' => 1],   // PHP
            ['id_estudiante' => 2, 'id_habilidad' => 2],   // Laravel
            ['id_estudiante' => 2, 'id_habilidad' => 11],  // MySQL
            ['id_estudiante' => 2, 'id_habilidad' => 13],  // Git
            ['id_estudiante' => 2, 'id_habilidad' => 16],  // REST APIs
            ['id_estudiante' => 2, 'id_habilidad' => 15],  // Linux
        ]);

        // --- Estudiante 3: María García ---
        $userEstudiante3 = User::updateOrCreate(
            ['email' => 'maria@krow.com'],
            [
                'name'                       => 'María García',
                'password'                   => Hash::make('password123'),
                'rol'                        => 'estudiante',
                'email_verified_at'          => Carbon::now(),
                'email_verification_code'    => null,
                'email_verification_expires' => null,
            ]
        );
        DB::table('estudiante')->insertOrIgnore([
            'id_estudiante'          => 3,
            'id_usuario'             => $userEstudiante3->id,
            'nombre'                 => 'María',
            'apellido'               => 'García',
            'dni'                    => 43123456,
            'legajo'                 => '67890',
            'fecha_nacimiento'       => '2000-03-10',
            'telefono'               => '1177776666',
            'id_carrera'             => 7,
            'descripcion'            => 'Apasionada por el diseño de interfaces y la experiencia de usuario.',
            'modalidad_deseada'      => 'Hibrido',
            'disponibilidad_horaria' => 'Mañana',
            'foto_perfil'            => null,
            'cv'                     => null,
            'portfolio'              => 'https://mariagarcia.design',
            'linkedin'               => 'https://linkedin.com/in/mariagarcia',
            'github'                 => 'https://github.com/mariagarcia',
            'id_localidad'           => 309,  // Recoleta, CABA
            'id_provincia'           => 3,
            'fecha_creacion'         => now(),
        ]);
        DB::table('estudiante_habilidad')->insertOrIgnore([
            ['id_estudiante' => 3, 'id_habilidad' => 3],   // JavaScript
            ['id_estudiante' => 3, 'id_habilidad' => 5],   // React
            ['id_estudiante' => 3, 'id_habilidad' => 6],   // Vue.js
            ['id_estudiante' => 3, 'id_habilidad' => 17],  // HTML/CSS
            ['id_estudiante' => 3, 'id_habilidad' => 18],  // Tailwind CSS
            ['id_estudiante' => 3, 'id_habilidad' => 13],  // Git
        ]);

        // --- Estudiante 4: Lucas Romero ---
        $userEstudiante4 = User::updateOrCreate(
            ['email' => 'lucas@krow.com'],
            [
                'name'                       => 'Lucas Romero',
                'password'                   => Hash::make('password123'),
                'rol'                        => 'estudiante',
                'email_verified_at'          => Carbon::now(),
                'email_verification_code'    => null,
                'email_verification_expires' => null,
            ]
        );
        DB::table('estudiante')->insertOrIgnore([
            'id_estudiante'          => 4,
            'id_usuario'             => $userEstudiante4->id,
            'nombre'                 => 'Lucas',
            'apellido'               => 'Romero',
            'dni'                    => 44567890,
            'legajo'                 => '11223',
            'fecha_nacimiento'       => '2003-07-22',
            'telefono'               => '1188889999',
            'id_carrera'             => 7,
            'descripcion'            => 'Interesado en DevOps, automatización e infraestructura cloud.',
            'modalidad_deseada'      => 'Remoto',
            'disponibilidad_horaria' => 'Tarde y Noche',
            'foto_perfil'            => null,
            'cv'                     => null,
            'portfolio'              => null,
            'linkedin'               => 'https://linkedin.com/in/lucasromero',
            'github'                 => 'https://github.com/lucasromero',
            'id_localidad'           => 570,  // Rosario, Santa Fe
            'id_provincia'           => 22,
            'fecha_creacion'         => now(),
        ]);
        DB::table('estudiante_habilidad')->insertOrIgnore([
            ['id_estudiante' => 4, 'id_habilidad' => 8],   // Python
            ['id_estudiante' => 4, 'id_habilidad' => 14],  // Docker
            ['id_estudiante' => 4, 'id_habilidad' => 15],  // Linux
            ['id_estudiante' => 4, 'id_habilidad' => 13],  // Git
            ['id_estudiante' => 4, 'id_habilidad' => 10],  // SQL
            ['id_estudiante' => 4, 'id_habilidad' => 16],  // REST APIs
        ]);

        // --- Estudiante 5: Valentina Torres (Videojuegos) ---
        $userEstudiante5 = User::updateOrCreate(
            ['email' => 'vale@krow.com'],
            [
                'name'                       => 'Valentina Torres',
                'password'                   => Hash::make('password123'),
                'rol'                        => 'estudiante',
                'email_verified_at'          => Carbon::now(),
                'email_verification_code'    => null,
                'email_verification_expires' => null,
            ]
        );
        DB::table('estudiante')->insertOrIgnore([
            'id_estudiante'          => 5,
            'id_usuario'             => $userEstudiante5->id,
            'nombre'                 => 'Valentina',
            'apellido'               => 'Torres',
            'dni'                    => 45678901,
            'legajo'                 => '33445',
            'fecha_nacimiento'       => '2002-11-05',
            'telefono'               => '1100112233',
            'id_carrera'             => 10,  // Tecnicatura en Videojuegos
            'descripcion'            => 'Estudiante de desarrollo de videojuegos. Trabajo con Unity, C# y modelado 3D básico en Blender.',
            'modalidad_deseada'      => 'Presencial',
            'disponibilidad_horaria' => 'Tarde',
            'foto_perfil'            => null,
            'cv'                     => null,
            'portfolio'              => 'https://valedev.itch.io',
            'linkedin'               => 'https://linkedin.com/in/valetorres',
            'github'                 => 'https://github.com/valetorres',
            'id_localidad'           => 303,  // Palermo, CABA
            'id_provincia'           => 3,
            'fecha_creacion'         => now(),
        ]);
        DB::table('estudiante_habilidad')->insertOrIgnore([
            ['id_estudiante' => 5, 'id_habilidad' => 21],  // Unity
            ['id_estudiante' => 5, 'id_habilidad' => 22],  // C#
            ['id_estudiante' => 5, 'id_habilidad' => 23],  // Blender
            ['id_estudiante' => 5, 'id_habilidad' => 13],  // Git
            ['id_estudiante' => 5, 'id_habilidad' => 19],  // Testing
        ]);

        // --- Estudiante 6: Matías Fernández (Marketing Digital) ---
        $userEstudiante6 = User::updateOrCreate(
            ['email' => 'matias@krow.com'],
            [
                'name'                       => 'Matías Fernández',
                'password'                   => Hash::make('password123'),
                'rol'                        => 'estudiante',
                'email_verified_at'          => Carbon::now(),
                'email_verification_code'    => null,
                'email_verification_expires' => null,
            ]
        );
        DB::table('estudiante')->insertOrIgnore([
            'id_estudiante'          => 6,
            'id_usuario'             => $userEstudiante6->id,
            'nombre'                 => 'Matías',
            'apellido'               => 'Fernández',
            'dni'                    => 46789012,
            'legajo'                 => '55667',
            'fecha_nacimiento'       => '2001-08-18',
            'telefono'               => '1144556677',
            'id_carrera'             => 12,  // Comercio Electrónico y Marketing Digital
            'descripcion'            => 'Estudiante de Marketing Digital. Manejo de campañas en Google Ads y redes sociales.',
            'modalidad_deseada'      => 'Remoto',
            'disponibilidad_horaria' => 'Mañana y Tarde',
            'foto_perfil'            => null,
            'cv'                     => null,
            'portfolio'              => 'https://matiasfernandez.ar',
            'linkedin'               => 'https://linkedin.com/in/matiasfern',
            'github'                 => null,
            'id_localidad'           => 408,  // Córdoba Capital
            'id_provincia'           => 7,
            'fecha_creacion'         => now(),
        ]);
        DB::table('estudiante_habilidad')->insertOrIgnore([
            ['id_estudiante' => 6, 'id_habilidad' => 25],  // Marketing Digital
            ['id_estudiante' => 6, 'id_habilidad' => 26],  // SEO
            ['id_estudiante' => 6, 'id_habilidad' => 27],  // Google Ads
            ['id_estudiante' => 6, 'id_habilidad' => 10],  // SQL
            ['id_estudiante' => 6, 'id_habilidad' => 20],  // Scrum / Agile
        ]);

        // ═══════════════════════════════════════════════════════
        // EMPRESA 1: Tech Solutions
        // ═══════════════════════════════════════════════════════
        $userEmpresa = User::updateOrCreate(
            ['email' => 'empresa@krow.com'],
            [
                'name'                       => 'Tech Solutions S.A.',
                'password'                   => Hash::make('password123'),
                'rol'                        => 'empresa',
                'email_verified_at'          => Carbon::now(),
                'email_verification_code'    => null,
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
            'tamano_empresa'      => null,
            'linkedin'            => 'https://linkedin.com/company/techsolutions',
            'instagram'           => null,
            'facebook'            => null,
            'id_localidad'        => 313,  // San Nicolás, CABA
            'id_provincia'        => 3,
            'fecha_creacion'      => now(),
        ]);

        // ─── Empresa 2: DevHouse Argentina ───
        $userEmpresa2 = User::updateOrCreate(
            ['email' => 'devhouse@krow.com'],
            [
                'name'                       => 'DevHouse Argentina',
                'password'                   => Hash::make('password123'),
                'rol'                        => 'empresa',
                'email_verified_at'          => Carbon::now(),
                'email_verification_code'    => null,
                'email_verification_expires' => null,
            ]
        );
        DB::table('empresa')->insertOrIgnore([
            'id_empresa'          => 2,
            'id_usuario'          => $userEmpresa2->id,
            'nombre_empresa'      => 'DevHouse Argentina',
            'razon_social'        => 'DevHouse Argentina S.R.L.',
            'cuit'                => 30987654321,
            'rubro'               => 'Desarrollo Web y Mobile',
            'direccion'           => 'Av. Santa Fe 2100',
            'telefono'            => '1133332222',
            'email_contacto'      => 'hola@devhouse.com.ar',
            'sitio_web'           => 'https://devhouse.com.ar',
            'descripcion'         => 'Agencia de desarrollo ágil para startups y pymes.',
            'logo'                => null,
            'representante'       => null,
            'email_representante' => null,
            'tamano_empresa'      => 'Pequena',
            'linkedin'            => null,
            'instagram'           => null,
            'facebook'            => null,
            'id_localidad'        => 303,  // Palermo, CABA
            'id_provincia'        => 3,
            'fecha_creacion'      => now(),
        ]);

        // ─── Empresa 3: Softland ───
        $userEmpresa3 = User::updateOrCreate(
            ['email' => 'softland@krow.com'],
            [
                'name'                       => 'Softland S.A.',
                'password'                   => Hash::make('password123'),
                'rol'                        => 'empresa',
                'email_verified_at'          => Carbon::now(),
                'email_verification_code'    => null,
                'email_verification_expires' => null,
            ]
        );
        DB::table('empresa')->insertOrIgnore([
            'id_empresa'          => 3,
            'id_usuario'          => $userEmpresa3->id,
            'nombre_empresa'      => 'Softland',
            'razon_social'        => 'Softland S.A.',
            'cuit'                => 30111222333,
            'rubro'               => 'Software de Gestión Empresarial',
            'direccion'           => 'Av. Del Libertador 890',
            'telefono'            => '1155554444',
            'email_contacto'      => 'rrhh@softland.com.ar',
            'sitio_web'           => 'https://softland.com.ar',
            'descripcion'         => 'ERP y soluciones de gestión para empresas medianas y grandes.',
            'logo'                => null,
            'representante'       => null,
            'email_representante' => null,
            'tamano_empresa'      => 'Mediana',
            'linkedin'            => null,
            'instagram'           => null,
            'facebook'            => null,
            'id_localidad'        => 408,  // Córdoba Capital
            'id_provincia'        => 7,
            'fecha_creacion'      => now(),
        ]);

        // ─── Empresa 4: GameForge Studios ───
        $userEmpresa4 = User::updateOrCreate(
            ['email' => 'gameforge@krow.com'],
            [
                'name'                       => 'GameForge Studios',
                'password'                   => Hash::make('password123'),
                'rol'                        => 'empresa',
                'email_verified_at'          => Carbon::now(),
                'email_verification_code'    => null,
                'email_verification_expires' => null,
            ]
        );
        DB::table('empresa')->insertOrIgnore([
            'id_empresa'          => 4,
            'id_usuario'          => $userEmpresa4->id,
            'nombre_empresa'      => 'GameForge Studios',
            'razon_social'        => 'GameForge Studios S.R.L.',
            'cuit'                => 30222333444,
            'rubro'               => 'Desarrollo de Videojuegos',
            'direccion'           => 'Av. Corrientes 4500',
            'telefono'            => '1166667777',
            'email_contacto'      => 'info@gameforge.com.ar',
            'sitio_web'           => 'https://gameforge.com.ar',
            'descripcion'         => 'Estudio indie de videojuegos para PC y mobile.',
            'logo'                => null,
            'representante'       => null,
            'email_representante' => null,
            'tamano_empresa'      => 'Pequena',
            'linkedin'            => null,
            'instagram'           => null,
            'facebook'            => null,
            'id_localidad'        => 303,  // Palermo, CABA
            'id_provincia'        => 3,
            'fecha_creacion'      => now(),
        ]);

        // ═══════════════════════════════════════════════════════
        // ADMIN
        // ═══════════════════════════════════════════════════════
        User::updateOrCreate(
            ['email' => 'admin@krow.com'],
            [
                'name'                       => 'Administrador General',
                'password'                   => Hash::make('password123'),
                'rol'                        => 'admin',
                'email_verified_at'          => Carbon::now(),
                'email_verification_code'    => null,
                'email_verification_expires' => null,
            ]
        );

        // ═══════════════════════════════════════════════════════
        // OFERTAS
        // ═══════════════════════════════════════════════════════
        $ofertas = [
            [
                'id_oferta' => 1,
                'id_empresa' => 1,
                'titulo' => 'Desarrollador Laravel Jr.',
                'descripcion' => 'Buscamos un desarrollador junior para el equipo de backend. Trabajarás en APIs RESTful con Laravel, colaborando con el equipo de producto en proyectos reales de clientes.',
                'requisitos' => 'Conocimientos básicos de PHP y Laravel. Manejo de Git. Estudiante avanzado o egresado de Tecnicatura en Programación.',
                'area' => 'Backend',
                'experiencia_requerida' => 'Junior',
                'tipo_oferta' => 'Pasantia',
                'modalidad' => 'Remoto',
                'salario_min' => 80000,
                'salario_max' => 120000,
                'id_localidad' => null,
                'id_provincia' => null,
                'fecha_cierre' => '2026-09-30',
                'estado' => 'Activa',
            ],
            [
                'id_oferta' => 2,
                'id_empresa' => 1,
                'titulo' => 'Pasante Front-End React',
                'descripcion' => 'Incorporamos pasantes para el área de frontend. Trabajarás con React y Tailwind CSS en proyectos reales. Contarás con mentoreo constante del equipo senior.',
                'requisitos' => 'Conocimientos de JavaScript, React y HTML/CSS. Ganas de aprender y trabajar en equipo.',
                'area' => 'Frontend',
                'experiencia_requerida' => 'Sin Experiencia',
                'tipo_oferta' => 'Pasantia',
                'modalidad' => 'Hibrido',
                'salario_min' => 70000,
                'salario_max' => 100000,
                'id_localidad' => 313,
                'id_provincia' => 3,
                'fecha_cierre' => '2026-08-15',
                'estado' => 'Activa',
            ],
            [
                'id_oferta' => 3,
                'id_empresa' => 2,
                'titulo' => 'Desarrollador Full Stack Node.js + React',
                'descripcion' => 'Buscamos full stack para plataforma SaaS en crecimiento. Stack: React, Node.js + Express, PostgreSQL. Trabajo ágil con Scrum.',
                'requisitos' => 'Experiencia con Node.js y React. Manejo de bases de datos relacionales. Inglés técnico lectura.',
                'area' => 'Full Stack',
                'experiencia_requerida' => 'Junior',
                'tipo_oferta' => 'Part-Time',
                'modalidad' => 'Remoto',
                'salario_min' => 150000,
                'salario_max' => 200000,
                'id_localidad' => null,
                'id_provincia' => null,
                'fecha_cierre' => '2026-07-31',
                'estado' => 'Activa',
            ],
            [
                'id_oferta' => 4,
                'id_empresa' => 2,
                'titulo' => 'UX/UI Designer con conocimientos frontend',
                'descripcion' => 'Perfil mixto diseño + implementación frontend. Trabajarás en Figma y luego implementarás con React + Tailwind CSS.',
                'requisitos' => 'Conocimientos de Figma, HTML/CSS, JavaScript básico. Portafolio requerido.',
                'area' => 'Diseño',
                'experiencia_requerida' => 'Sin Experiencia',
                'tipo_oferta' => 'Pasantia',
                'modalidad' => 'Presencial',
                'salario_min' => 60000,
                'salario_max' => 90000,
                'id_localidad' => 303,
                'id_provincia' => 3,
                'fecha_cierre' => '2026-08-01',
                'estado' => 'Activa',
            ],
            [
                'id_oferta' => 5,
                'id_empresa' => 3,
                'titulo' => 'Desarrollador Python / Django',
                'descripcion' => 'Empresa de software de gestión busca desarrollador Python para mantenimiento y nuevas funcionalidades en sistema ERP.',
                'requisitos' => 'Conocimientos sólidos de Python. Familiaridad con SQL. Comunicación clara.',
                'area' => 'Backend',
                'experiencia_requerida' => 'Junior',
                'tipo_oferta' => 'Full-Time',
                'modalidad' => 'Hibrido',
                'salario_min' => 180000,
                'salario_max' => 250000,
                'id_localidad' => 408,
                'id_provincia' => 7,
                'fecha_cierre' => '2026-10-01',
                'estado' => 'Activa',
            ],
            [
                'id_oferta' => 6,
                'id_empresa' => 3,
                'titulo' => 'Analista QA y Testing',
                'descripcion' => 'Incorporamos analista QA para pruebas funcionales y automatizadas sobre nuestros productos ERP. Aprenderás Selenium, Cypress y CI/CD.',
                'requisitos' => 'Conocimientos básicos de programación. Atención al detalle.',
                'area' => 'QA',
                'experiencia_requerida' => 'Sin Experiencia',
                'tipo_oferta' => 'Practica Profesional',
                'modalidad' => 'Presencial',
                'salario_min' => 90000,
                'salario_max' => 130000,
                'id_localidad' => 408,
                'id_provincia' => 7,
                'fecha_cierre' => '2026-09-15',
                'estado' => 'Activa',
            ],
            [
                'id_oferta' => 7,
                'id_empresa' => 1,
                'titulo' => 'DevOps / Infrastructure Intern',
                'descripcion' => 'Proyecto de modernización de infraestructura. Trabajarás con Docker, CI/CD y despliegues en la nube.',
                'requisitos' => 'Conocimientos de Linux, Git y redes básicas. Deseable Docker.',
                'area' => 'DevOps',
                'experiencia_requerida' => 'Sin Experiencia',
                'tipo_oferta' => 'Pasantia',
                'modalidad' => 'Remoto',
                'salario_min' => 85000,
                'salario_max' => 110000,
                'id_localidad' => null,
                'id_provincia' => null,
                'fecha_cierre' => '2026-08-31',
                'estado' => 'Activa',
            ],
            [
                'id_oferta' => 8,
                'id_empresa' => 4,
                'titulo' => 'Programador de Videojuegos Jr. (Unity / C#)',
                'descripcion' => 'Estudio indie busca pasante para desarrollo de mecánicas de juego en Unity. Participarás en el ciclo completo de desarrollo.',
                'requisitos' => 'Conocimientos de Unity y C#. Git. Portafolio o proyecto propio deseable.',
                'area' => 'Desarrollo de Juegos',
                'experiencia_requerida' => 'Sin Experiencia',
                'tipo_oferta' => 'Pasantia',
                'modalidad' => 'Presencial',
                'salario_min' => 75000,
                'salario_max' => 100000,
                'id_localidad' => 303,
                'id_provincia' => 3,
                'fecha_cierre' => '2026-09-01',
                'estado' => 'Activa',
            ],
            [
                'id_oferta' => 9,
                'id_empresa' => 4,
                'titulo' => 'Artista 3D / Game Artist Jr.',
                'descripcion' => 'Buscamos artista con conocimientos en Blender para crear assets 3D para videojuegos.',
                'requisitos' => 'Conocimientos de Blender o similar. Portafolio con modelos 3D requerido.',
                'area' => 'Arte / Diseño',
                'experiencia_requerida' => 'Sin Experiencia',
                'tipo_oferta' => 'Pasantia',
                'modalidad' => 'Hibrido',
                'salario_min' => 65000,
                'salario_max' => 95000,
                'id_localidad' => 303,
                'id_provincia' => 3,
                'fecha_cierre' => '2026-08-20',
                'estado' => 'Activa',
            ],
            [
                'id_oferta' => 10,
                'id_empresa' => 2,
                'titulo' => 'Asistente de Marketing Digital',
                'descripcion' => 'Incorporamos pasante de marketing digital para gestionar campañas en redes sociales, Google Ads y estrategias de contenido.',
                'requisitos' => 'Conocimientos de redes sociales, herramientas de marketing digital y Google Ads. Redacción clara.',
                'area' => 'Marketing',
                'experiencia_requerida' => 'Sin Experiencia',
                'tipo_oferta' => 'Practica Profesional',
                'modalidad' => 'Remoto',
                'salario_min' => 70000,
                'salario_max' => 95000,
                'id_localidad' => null,
                'id_provincia' => null,
                'fecha_cierre' => '2026-09-30',
                'estado' => 'Activa',
            ],
        ];

        foreach ($ofertas as $oferta) {
            DB::table('oferta')->insertOrIgnore(array_merge($oferta, [
                'fecha_publicacion' => now(),
            ]));
        }

        // ─── Ofertas → Carreras ───
        $ofertaCarreras = [
            [1, 7],
            [2, 7],
            [3, 7],
            [4, 7],
            [5, 7],
            [6, 7],
            [7, 7],
            [8, 7],
            [8, 10],
            [9, 10],
            [10, 12],
        ];
        foreach ($ofertaCarreras as [$o, $c]) {
            DB::table('oferta_carrera')->insertOrIgnore(['id_oferta' => $o, 'id_carrera' => $c]);
        }

        // ─── Ofertas → Habilidades ───
        $ofertaHabilidades = [
            // Oferta 1: Laravel Jr.
            [1, 1],
            [1, 2],
            [1, 11],
            [1, 13],
            [1, 16],
            // Oferta 2: Pasante React
            [2, 3],
            [2, 5],
            [2, 17],
            [2, 18],
            [2, 13],
            // Oferta 3: Full Stack
            [3, 3],
            [3, 4],
            [3, 5],
            [3, 7],
            [3, 12],
            [3, 13],
            [3, 20],
            // Oferta 4: UX/UI
            [4, 3],
            [4, 5],
            [4, 17],
            [4, 18],
            // Oferta 5: Python
            [5, 8],
            [5, 10],
            [5, 14],
            [5, 13],
            // Oferta 6: QA
            [6, 19],
            [6, 13],
            // Oferta 7: DevOps
            [7, 14],
            [7, 15],
            [7, 13],
            // Oferta 8: Unity
            [8, 21],
            [8, 22],
            [8, 13],
            [8, 19],
            // Oferta 9: 3D
            [9, 23],
            [9, 24],
            // Oferta 10: Marketing
            [10, 25],
            [10, 26],
            [10, 27],
            [10, 20],
        ];
        foreach ($ofertaHabilidades as [$o, $h]) {
            DB::table('oferta_habilidad')->insertOrIgnore(['id_oferta' => $o, 'id_habilidad' => $h]);
        }

        // ═══════════════════════════════════════════════════════
        // POSTULACIONES
        // ═══════════════════════════════════════════════════════
        $postulaciones = [
            [1, 1, 2, 'Preseleccionado', 'Perfil muy interesante para la pasantía React. Llamar esta semana.'],
            [2, 1, 3, 'En Revision',     null],
            [3, 2, 1, 'En Contacto',     'Buen conocimiento de Laravel. Entrevista coordinada para el viernes.'],
            [4, 2, 5, 'Postulado',       null],
            [5, 3, 2, 'Postulado',       null],
            [6, 3, 4, 'En Revision',     'Portfolio de diseño revisado. Muy buena presentación visual.'],
            [7, 4, 7, 'Preseleccionado', 'Conocimiento de Docker destacado para el nivel. A confirmar disponibilidad.'],
            [8, 4, 5, 'Rechazado',       'Falta de experiencia con Python según lo requerido.'],
            [9, 5, 8, 'En Contacto',     'Proyecto propio en Unity muy interesante. Reunión agendada.'],
            [10, 5, 9, 'Postulado',      null],
            [11, 6, 10, 'En Revision',   'Buena presencia en redes. Revisar experiencia con Google Ads.'],
            [12, 6, 3,  'Rechazado',     'Perfil no coincide con el stack técnico requerido.'],
        ];
        foreach ($postulaciones as [$id, $est, $of, $estado, $obs]) {
            DB::table('postulacion')->insertOrIgnore([
                'id_postulacion'   => $id,
                'id_estudiante'    => $est,
                'id_oferta'        => $of,
                'estado'           => $estado,
                'observaciones'    => $obs,
                'fecha_postulacion' => now(),
            ]);
        }

        // ═══════════════════════════════════════════════════════
        // TICKETS DE SOPORTE
        // ═══════════════════════════════════════════════════════
        $tickets = [
            [1, $userEstudiante->id,  'No puedo subir mi CV',              'Intenté subir mi CV en PDF pero la página no responde después de seleccionar el archivo.',      'Abierto'],
            [2, $userEmpresa->id,     'Error al publicar oferta',           'Al guardar la oferta me aparece un error 500. Ya intenté dos veces con distintos datos.',       'En Proceso'],
            [3, $userEstudiante2->id, 'Cómo actualizo mi foto de perfil',   'No encuentro la opción para cambiar la foto de perfil desde el panel de estudiante.',          'Resuelto'],
            [4, $userEstudiante3->id, 'Postulación duplicada',              'Me aparece que ya me postulé a una oferta, pero no recuerdo haberlo hecho. ¿Puedo anularla?', 'Abierto'],
            [5, $userEstudiante4->id, 'No recibo emails de notificación',   'Me registré hace 3 días y nunca recibí confirmación de postulación por email.',                'En Proceso'],
        ];
        foreach ($tickets as [$id, $uid, $asunto, $desc, $estado]) {
            DB::table('ticket_soporte')->insertOrIgnore([
                'id_ticket'      => $id,
                'id_usuario'     => $uid,
                'asunto'         => $asunto,
                'descripcion'    => $desc,
                'estado'         => $estado,
                'fecha_creacion' => now(),
            ]);
        }
    }
}
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
          // ── Copiar imágenes seed a storage ──────────────────────
            $carpetas = ['banners', 'cvs', 'logos', 'perfiles'];

            foreach ($carpetas as $carpeta) {
                $origen  = public_path("img/seed/{$carpeta}");
                $destino = storage_path("app/public/{$carpeta}");

                if (!is_dir($origen)) continue;

                @mkdir($destino, 0755, true);

                foreach (glob("{$origen}/*") as $archivo) {
                    $nombreArchivo  = basename($archivo);
                    $destinoArchivo = "{$destino}/{$nombreArchivo}";
                    if (!file_exists($destinoArchivo)) {
                        copy($archivo, $destinoArchivo);
                    }
                }
            }
   

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
        // LOCALIDADES
        // ═══════════════════════════════════════════════════════
        $localidades = [
            // Buenos Aires (1)
            [10,  1,  'Bahía Blanca'],
            [73,  1,  'La Plata'],
            [84,  1,  'Luján'],
            [90,  1,  'Mar del Plata'],
            [107, 1,  'Pilar'],
            [129, 1,  'San Nicolás'],
            [134, 1,  'Tandil'],
            [142, 1,  'Zárate'],
            // Buenos Aires GBA (2)
            [150, 2,  'Avellaneda'],
            [156, 2,  'Berazategui'],
            [181, 2,  'Florencio Varela'],
            [192, 2,  'Hurlingham'],
            [195, 2,  'Ituzaingó'],
            [202, 2,  'Lanús'],
            [205, 2,  'Lomas de Zamora'],
            [214, 2,  'Merlo'],
            [218, 2,  'Moreno'],
            [219, 2,  'Morón'],
            [229, 2,  'Quilmes'],
            [237, 2,  'San Fernando'],
            [239, 2,  'San Isidro'],
            [241, 2,  'San Justo'],
            [242, 2,  'San Martín'],
            [243, 2,  'San Miguel'],
            [249, 2,  'Tigre'],
            [255, 2,  'Vicente López'],
            [260, 2,  'Villa Sarmiento'],   // ← agregada
            [261, 2,  'Ramos Mejía'],        // ← agregada
            [262, 2,  'Haedo'],              // ← agregada
            [263, 2,  'Castelar'],           // ← agregada
            // Capital Federal (3)
            [283, 3,  'Almagro'],
            [286, 3,  'Belgrano'],
            [289, 3,  'Caballito'],
            [294, 3,  'Flores'],
            [299, 3,  'Monserrat'],
            [303, 3,  'Palermo'],
            [309, 3,  'Recoleta'],
            [310, 3,  'Retiro'],
            [313, 3,  'San Nicolás'],
            [314, 3,  'San Telmo'],
            [317, 3,  'Villa Crespo'],
            [329, 3,  'Villa Urquiza'],
            [330, 3,  'Puerto Madero'],      // ← agregada
            // Córdoba (7)
            [402, 7,  'Alta Gracia'],
            [406, 7,  'Capital'],
            [408, 7,  'Córdoba Capital'],
            [409, 7,  'Cosquín'],
            [414, 7,  'La Calera'],
            [416, 7,  'La Falda'],
            [422, 7,  'Río Cuarto'],
            [424, 7,  'San Francisco'],
            [426, 7,  'Villa Carlos Paz'],
            [429, 7,  'Villa María'],
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
            [492, 15, 'Oberá'],
            [493, 15, 'Posadas'],
            [494, 15, 'Puerto Iguazú'],
            // Entre Ríos (9)
            [441, 9,  'Concepción del Uruguay'],
            [442, 9,  'Concordia'],
            [446, 9,  'Gualeguaychú'],
            [449, 9,  'Paraná'],
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
            [387, 5,  'Resistencia'],
            [388, 5,  'Sáenz Peña'],
            // Corrientes (8)
            [431, 8,  'Corrientes Capital'],
            [433, 8,  'Goya'],
            // Jujuy (11)
            [468, 11, 'San Salvador de Jujuy'],
            [469, 11, 'Tilcara'],
            // Chubut (6)
            [390, 6,  'Comodoro Rivadavia'],
            [397, 6,  'Puerto Madryn'],
            [400, 6,  'Trelew'],
            // Formosa (10)
            [455, 10, 'Formosa Capital'],
            // Catamarca (4)
            [336, 4,  'Capital'],
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
            [31, 'Next.js'],           // ← nuevas
            [32, 'AWS'],
            [33, 'Figma'],
            [34, 'Angular'],
            [35, 'Excel Avanzado'],
        ];

        foreach ($habilidades as [$id, $nombre]) {
            DB::table('habilidad')->insertOrIgnore(['id_habilidad' => $id, 'nombre' => $nombre]);
        }

        // ═══════════════════════════════════════════════════════
        // USUARIOS + PERFILES — ESTUDIANTES ORIGINALES
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
            'id_carrera'             => 7,
            'descripcion'            => 'Estudiante de Sistemas buscando su primera experiencia IT.',
            'modalidad_deseada'      => 'Remoto',
            'disponibilidad_horaria' => 'Mañana y Tarde',
            'foto_perfil'            => 'perfiles/A6HaYmuR50PzCoTzZmeGCM2ywMG7PUR6cYEecFqI.jpg',
            'cv'                     => 'cvs/sMJgoV5Yi17aH6vt7JgbkmDgn5QVM5WMJM1ytZH8.pdf',
            'portfolio'              => 'https://juanperez.dev',
            'linkedin'               => 'https://linkedin.com/in/juanperez',
            'github'                 => 'https://github.com/juanperez',
            'id_localidad'           => 241,
            'id_provincia'           => 2,
            'fecha_creacion'         => now(),
            'estado'                 => 'activo',
        ]);
        DB::table('estudiante_habilidad')->insertOrIgnore([
            ['id_estudiante' => 1, 'id_habilidad' => 3],
            ['id_estudiante' => 1, 'id_habilidad' => 5],
            ['id_estudiante' => 1, 'id_habilidad' => 17],
            ['id_estudiante' => 1, 'id_habilidad' => 18],
            ['id_estudiante' => 1, 'id_habilidad' => 13],
            ['id_estudiante' => 1, 'id_habilidad' => 10],
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
            'foto_perfil'            => 'perfiles/LwNnrzFtgKpfsd7HmX25v2yu4eLks0S0JuIGgdFY.jpg',
            'cv'                     => 'cvs/E3bmSed83ZhufWg8Z9TDiPvNbldYphLFQfp41vMi.pdf',
            'portfolio'              => 'https://josemendoza.dev',
            'linkedin'               => 'https://linkedin.com/in/josemendoza',
            'github'                 => 'https://github.com/josemendoza',
            'id_localidad'           => 303,
            'id_provincia'           => 3,
            'fecha_creacion'         => now(),
            'estado'                 => 'activo',
        ]);
        DB::table('estudiante_habilidad')->insertOrIgnore([
            ['id_estudiante' => 2, 'id_habilidad' => 1],
            ['id_estudiante' => 2, 'id_habilidad' => 2],
            ['id_estudiante' => 2, 'id_habilidad' => 11],
            ['id_estudiante' => 2, 'id_habilidad' => 13],
            ['id_estudiante' => 2, 'id_habilidad' => 16],
            ['id_estudiante' => 2, 'id_habilidad' => 15],
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
            'foto_perfil'            => 'perfiles/Zpna5GumSRWamup99z0IsCtoUUIoXOzv51QJbiMJ.jpg',
            'cv'                     => 'cvs/ttboHPqdWqUTA6CRVplR8KHsnW4UbctZdaAtjkqS.pdf',
            'portfolio'              => 'https://mariagarcia.design',
            'linkedin'               => 'https://linkedin.com/in/mariagarcia',
            'github'                 => 'https://github.com/mariagarcia',
            'id_localidad'           => 309,
            'id_provincia'           => 3,
            'fecha_creacion'         => now(),
            'estado'                 => 'activo',
        ]);
        DB::table('estudiante_habilidad')->insertOrIgnore([
            ['id_estudiante' => 3, 'id_habilidad' => 3],
            ['id_estudiante' => 3, 'id_habilidad' => 5],
            ['id_estudiante' => 3, 'id_habilidad' => 6],
            ['id_estudiante' => 3, 'id_habilidad' => 17],
            ['id_estudiante' => 3, 'id_habilidad' => 18],
            ['id_estudiante' => 3, 'id_habilidad' => 13],
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
            'foto_perfil'            => 'perfiles/RigT9bjUbJHlM1d9GiF5Tkl6hBjhYyDaLEnciVtp.jpg',
            'cv'                     => 'cvs/QKOsi7cgs5H71WGuaDJPb4urEClEpG9d5IpW1JPs.pdf',
            'portfolio'              => null,
            'linkedin'               => 'https://linkedin.com/in/lucasromero',
            'github'                 => 'https://github.com/lucasromero',
            'id_localidad'           => 570,
            'id_provincia'           => 22,
            'fecha_creacion'         => now(),
            'estado'                 => 'activo',
        ]);
        DB::table('estudiante_habilidad')->insertOrIgnore([
            ['id_estudiante' => 4, 'id_habilidad' => 8],
            ['id_estudiante' => 4, 'id_habilidad' => 14],
            ['id_estudiante' => 4, 'id_habilidad' => 15],
            ['id_estudiante' => 4, 'id_habilidad' => 13],
            ['id_estudiante' => 4, 'id_habilidad' => 10],
            ['id_estudiante' => 4, 'id_habilidad' => 16],
        ]);

        // --- Estudiante 5: Valentina Torres ---
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
            'id_carrera'             => 10,
            'descripcion'            => 'Estudiante de desarrollo de videojuegos. Trabajo con Unity, C# y modelado 3D básico en Blender.',
            'modalidad_deseada'      => 'Presencial',
            'disponibilidad_horaria' => 'Tarde',
            'foto_perfil'            => 'perfiles/Om1TjxSXvKEIJfXYSftNfUUBCnjiE9tez4lGCmdF.jpg',
            'cv'                     => null,
            'portfolio'              => 'https://valedev.itch.io',
            'linkedin'               => 'https://linkedin.com/in/valetorres',
            'github'                 => 'https://github.com/valetorres',
            'id_localidad'           => 303,
            'id_provincia'           => 3,
            'fecha_creacion'         => now(),
            'estado'                 => 'activo',
        ]);
        DB::table('estudiante_habilidad')->insertOrIgnore([
            ['id_estudiante' => 5, 'id_habilidad' => 21],
            ['id_estudiante' => 5, 'id_habilidad' => 22],
            ['id_estudiante' => 5, 'id_habilidad' => 23],
            ['id_estudiante' => 5, 'id_habilidad' => 13],
            ['id_estudiante' => 5, 'id_habilidad' => 19],
        ]);

        // --- Estudiante 6: Matías Fernández ---
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
            'id_carrera'             => 12,
            'descripcion'            => 'Estudiante de Marketing Digital. Manejo de campañas en Google Ads y redes sociales.',
            'modalidad_deseada'      => 'Remoto',
            'disponibilidad_horaria' => 'Mañana y Tarde',
            'foto_perfil'            => 'perfiles/ZyCfDj9qjj37UhNILDkcEENpWKaHTfPR4qnhr1PN.jpg',
            'cv'                     => 'cvs/aOpCFMLpmc9lxn6GHiYMnhW9Qdo5RBhmNMyTwKXw.pdf',
            'portfolio'              => 'https://matiasfernandez.ar',
            'linkedin'               => 'https://linkedin.com/in/matiasfern',
            'github'                 => null,
            'id_localidad'           => 408,
            'id_provincia'           => 7,
            'fecha_creacion'         => now(),
            'estado'                 => 'activo',
        ]);
        DB::table('estudiante_habilidad')->insertOrIgnore([
            ['id_estudiante' => 6, 'id_habilidad' => 25],
            ['id_estudiante' => 6, 'id_habilidad' => 26],
            ['id_estudiante' => 6, 'id_habilidad' => 27],
            ['id_estudiante' => 6, 'id_habilidad' => 10],
            ['id_estudiante' => 6, 'id_habilidad' => 20],
        ]);

        // ═══════════════════════════════════════════════════════
        // ESTUDIANTES NUEVOS
        // ═══════════════════════════════════════════════════════

        // --- Estudiante 7: Sofía Beltrán (Ingeniería Industrial) ---
        $userEst7 = User::updateOrCreate(
            ['email' => 'sofia@krow.com'],
            [
                'name'                       => 'Sofía Beltrán',
                'password'                   => Hash::make('password123'),
                'rol'                        => 'estudiante',
                'email_verified_at'          => Carbon::now(),
                'email_verification_code'    => null,
                'email_verification_expires' => null,
            ]
        );
        DB::table('estudiante')->insertOrIgnore([
            'id_estudiante'          => 7,
            'id_usuario'             => $userEst7->id,
            'nombre'                 => 'Sofía',
            'apellido'               => 'Beltrán',
            'dni'                    => 47890123,
            'legajo'                 => '77889',
            'fecha_nacimiento'       => '2002-04-12',
            'telefono'               => '1166778899',
            'id_carrera'             => 4,  // Ingeniería Industrial
            'descripcion'            => 'Estudiante avanzada de Ingeniería Industrial con interés en mejora de procesos y logística.',
            'modalidad_deseada'      => 'Presencial',
            'disponibilidad_horaria' => 'Tarde',
            'foto_perfil'            => 'perfiles/q2m4DpYwCRJjVDLOezaNqJQkJno7pvAKlZqrzDWP.jpg',
            'cv'                     => 'cvs/20ZTlHms6UqnsQTrtexWAzs5y93BkAt6lwFEFVqe.pdf',
            'portfolio'              => null,
            'linkedin'               => 'https://linkedin.com/in/sofiabeltran',
            'github'                 => null,
            'id_localidad'           => 260,  // Villa Sarmiento, GBA
            'id_provincia'           => 2,
            'fecha_creacion'         => now(),
            'estado'                 => 'activo',
        ]);
        DB::table('estudiante_habilidad')->insertOrIgnore([
            ['id_estudiante' => 7, 'id_habilidad' => 28],  // Logística y Supply Chain
            ['id_estudiante' => 7, 'id_habilidad' => 29],  // SAP
            ['id_estudiante' => 7, 'id_habilidad' => 30],  // AutoCAD
            ['id_estudiante' => 7, 'id_habilidad' => 20],  // Scrum / Agile
            ['id_estudiante' => 7, 'id_habilidad' => 35],  // Excel Avanzado
        ]);

        // --- Estudiante 8: Nicolás Aguilar (Tecnicatura en Programación) ---
        $userEst8 = User::updateOrCreate(
            ['email' => 'nico@krow.com'],
            [
                'name'                       => 'Nicolás Aguilar',
                'password'                   => Hash::make('password123'),
                'rol'                        => 'estudiante',
                'email_verified_at'          => Carbon::now(),
                'email_verification_code'    => null,
                'email_verification_expires' => null,
            ]
        );
        DB::table('estudiante')->insertOrIgnore([
            'id_estudiante'          => 8,
            'id_usuario'             => $userEst8->id,
            'nombre'                 => 'Nicolás',
            'apellido'               => 'Aguilar',
            'dni'                    => 48901234,
            'legajo'                 => '99001',
            'fecha_nacimiento'       => '2003-01-30',
            'telefono'               => '1155443322',
            'id_carrera'             => 7,
            'descripcion'            => 'Fullstack en formación. Trabajo con Next.js en el frontend y Node.js en el backend.',
            'modalidad_deseada'      => 'Remoto',
            'disponibilidad_horaria' => 'Noche',
            'foto_perfil'            => 'perfiles/fYnVRae5uD1mr4Fj9bSUcCuHxZ0tsCg0W9vCZFA9.jpg',
            'cv'                     => 'cvs/BuwaUVXkOOoQapSsazODRtPv9myNLif3090FRUAa.pdf',
            'portfolio'              => 'https://nicoaguilar.dev',
            'linkedin'               => 'https://linkedin.com/in/nicoaguilar',
            'github'                 => 'https://github.com/nicoaguilar',
            'id_localidad'           => 286,  // Belgrano, CABA
            'id_provincia'           => 3,
            'fecha_creacion'         => now(),
            'estado'                 => 'activo',
        ]);
        DB::table('estudiante_habilidad')->insertOrIgnore([
            ['id_estudiante' => 8, 'id_habilidad' => 7],   // Node.js
            ['id_estudiante' => 8, 'id_habilidad' => 31],  // Next.js
            ['id_estudiante' => 8, 'id_habilidad' => 4],   // TypeScript
            ['id_estudiante' => 8, 'id_habilidad' => 12],  // PostgreSQL
            ['id_estudiante' => 8, 'id_habilidad' => 13],  // Git
            ['id_estudiante' => 8, 'id_habilidad' => 32],  // AWS
        ]);

        // --- Estudiante 9: Camila Ríos (Tecnicatura en Logística) ---
        $userEst9 = User::updateOrCreate(
            ['email' => 'camila@krow.com'],
            [
                'name'                       => 'Camila Ríos',
                'password'                   => Hash::make('password123'),
                'rol'                        => 'estudiante',
                'email_verified_at'          => Carbon::now(),
                'email_verification_code'    => null,
                'email_verification_expires' => null,
            ]
        );
        DB::table('estudiante')->insertOrIgnore([
            'id_estudiante'          => 9,
            'id_usuario'             => $userEst9->id,
            'nombre'                 => 'Camila',
            'apellido'               => 'Ríos',
            'dni'                    => 49012345,
            'legajo'                 => '22334',
            'fecha_nacimiento'       => '2001-12-03',
            'telefono'               => '1133221100',
            'id_carrera'             => 13,  // Tecnicatura en Logística
            'descripcion'            => 'Estudiante de Logística con experiencia en ayudantía de almacén e interés en supply chain.',
            'modalidad_deseada'      => 'Hibrido',
            'disponibilidad_horaria' => 'Mañana',
            'foto_perfil'            => 'perfiles/6VYTW8vvi8x4gxXp5TFsobKSJ353sDcQhbNq5f8x.jpg',
            'cv'                     => 'cvs/BuRltFMI9ktFYHcMF4NSWBIRgYVh3GU2scAFtRre.pdf',
            'portfolio'              => null,
            'linkedin'               => 'https://linkedin.com/in/camilarios',
            'github'                 => null,
            'id_localidad'           => 205,  // Lomas de Zamora, GBA
            'id_provincia'           => 2,
            'fecha_creacion'         => now(),
            'estado'                 => 'activo',
        ]);
        DB::table('estudiante_habilidad')->insertOrIgnore([
            ['id_estudiante' => 9, 'id_habilidad' => 28],  // Logística y Supply Chain
            ['id_estudiante' => 9, 'id_habilidad' => 35],  // Excel Avanzado
            ['id_estudiante' => 9, 'id_habilidad' => 29],  // SAP
            ['id_estudiante' => 9, 'id_habilidad' => 20],  // Scrum / Agile
        ]);

        // --- Estudiante 10: Agustín Molina (Tecnicatura en Programación, suspendido) ---
        $userEst10 = User::updateOrCreate(
            ['email' => 'agustin@krow.com'],
            [
                'name'                       => 'Agustín Molina',
                'password'                   => Hash::make('password123'),
                'rol'                        => 'estudiante',
                'email_verified_at'          => Carbon::now(),
                'email_verification_code'    => null,
                'email_verification_expires' => null,
            ]
        );
        DB::table('estudiante')->insertOrIgnore([
            'id_estudiante'          => 10,
            'id_usuario'             => $userEst10->id,
            'nombre'                 => 'Agustín',
            'apellido'               => 'Molina',
            'dni'                    => 40111222,
            'legajo'                 => '44556',
            'fecha_nacimiento'       => '2000-06-25',
            'telefono'               => '1122113344',
            'id_carrera'             => 7,
            'descripcion'            => 'Desarrollador backend Java. Cuenta con experiencia en proyectos académicos con Spring Boot.',
            'modalidad_deseada'      => 'Presencial',
            'disponibilidad_horaria' => 'Tarde',
            'foto_perfil'            => 'perfiles/Ztx0P0b0zXVLaIyRFx1TW0jdrE9UWlSBoRAyNOvn.jpg',
            'cv'                     => 'cvs/PggfCAIXEUsVF6UkZU6DpaCwSm2dHNsrg1CF6r5L.pdf',
            'portfolio'              => null,
            'linkedin'               => null,
            'github'                 => 'https://github.com/agustinmolina',
            'id_localidad'           => 570,  // Rosario, Santa Fe
            'id_provincia'           => 22,
            'fecha_creacion'         => now(),
            'estado'                 => 'suspendido',  // Cuenta suspendida para probar el estado
        ]);
        DB::table('estudiante_habilidad')->insertOrIgnore([
            ['id_estudiante' => 10, 'id_habilidad' => 9],   // Java
            ['id_estudiante' => 10, 'id_habilidad' => 10],  // SQL
            ['id_estudiante' => 10, 'id_habilidad' => 11],  // MySQL
            ['id_estudiante' => 10, 'id_habilidad' => 13],  // Git
        ]);

        // --- Estudiante 11: Florencia Navarro (Diseño / Marketing) ---
        $userEst11 = User::updateOrCreate(
            ['email' => 'flor@krow.com'],
            [
                'name'                       => 'Florencia Navarro',
                'password'                   => Hash::make('password123'),
                'rol'                        => 'estudiante',
                'email_verified_at'          => Carbon::now(),
                'email_verification_code'    => null,
                'email_verification_expires' => null,
            ]
        );
        DB::table('estudiante')->insertOrIgnore([
            'id_estudiante'          => 11,
            'id_usuario'             => $userEst11->id,
            'nombre'                 => 'Florencia',
            'apellido'               => 'Navarro',
            'dni'                    => 41999888,
            'legajo'                 => '66778',
            'fecha_nacimiento'       => '2001-02-14',
            'telefono'               => '1199001122',
            'id_carrera'             => 12,  // Marketing Digital
            'descripcion'            => 'Apasionada por el diseño gráfico y la comunicación visual en redes sociales.',
            'modalidad_deseada'      => 'Remoto',
            'disponibilidad_horaria' => 'Mañana y Tarde',
            'foto_perfil'            => 'perfiles/XwsQazRdTM4EeVjkDud5fqj56drP5svfsqdUntAb.jpg',
            'cv'                     => 'cvs/MVGtOvwMjCChZJfax7QfOGh594834JE0555jfqaU.pdf',
            'portfolio'              => 'https://flornavarro.behance.net',
            'linkedin'               => 'https://linkedin.com/in/flornavarro',
            'github'                 => null,
            'id_localidad'           => 484,  // Mendoza Capital
            'id_provincia'           => 14,
            'fecha_creacion'         => now(),
            'estado'                 => 'activo',
        ]);
        DB::table('estudiante_habilidad')->insertOrIgnore([
            ['id_estudiante' => 11, 'id_habilidad' => 25],  // Marketing Digital
            ['id_estudiante' => 11, 'id_habilidad' => 26],  // SEO
            ['id_estudiante' => 11, 'id_habilidad' => 33],  // Figma
            ['id_estudiante' => 11, 'id_habilidad' => 24],  // Adobe Illustrator
            ['id_estudiante' => 11, 'id_habilidad' => 17],  // HTML/CSS
        ]);

        // ═══════════════════════════════════════════════════════
        // EMPRESAS ORIGINALES
        // ═══════════════════════════════════════════════════════

        // ─── Empresa 1: Tech Solutions ───
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
            'sitio_web'           => 'https://www.mcdonalds.com.ar',
            'descripcion'         => 'Empresa de desarrollo de sistemas cloud y outsourcing.',
            'logo'                => 'logos/7dF0Gq1RIKSk4jWM85thQs5HY5KpcC5M59Ptrb3v.jpg',
            'banner'              => 'banners/fx5IFsYutoLOWndDcbYHMIJa1XolnBffvo3ozjTN.png',
            'representante'       => 'Carlos Gómez',
            'email_representante' => 'cgomez@techsolutions.com',
            'tamano_empresa'      => 'Mediana',
            'linkedin'            => 'https://linkedin.com/company/techsolutions',
            'instagram'           => 'https://www.instagram.com/?hl=es',
            'facebook'            => 'https://www.facebook.com/?locale=es_LA',
            'id_localidad'        => 313,
            'id_provincia'        => 3,
            'fecha_creacion'      => now(),
            'estado'              => 'aprobada',
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
            'logo'                => 'logos/TxB4bjmp9dGmL78Sl1Zmu7tGOUc8grgEx1IIEgUx.png',
            'banner'              => 'banners/Wdyy1BsOJZ3jDZzpAJyuDyYzyVGbx91NT21Lvjah.png',
            'representante'       => null,
            'email_representante' => null,
            'tamano_empresa'      => 'Pequena',
            'linkedin'            => null,
            'instagram'           => null,
            'facebook'            => null,
            'id_localidad'        => 303,
            'id_provincia'        => 3,
            'fecha_creacion'      => now(),
            'estado'              => 'aprobada',
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
            'logo'                => 'logos/CFynI3tmSNJ2NyFYgJv3xsqu4CHhqFFr046cZiwl.png',
            'banner'              => 'banners/k6OT9W8ND4UdRzpIofMhUaVfK5HsnxjjnqnxEV0H.png',
            'representante'       => null,
            'email_representante' => null,
            'tamano_empresa'      => 'Mediana',
            'linkedin'            => null,
            'instagram'           => null,
            'facebook'            => null,
            'id_localidad'        => 408,
            'id_provincia'        => 7,
            'fecha_creacion'      => now(),
            'estado'              => 'aprobada',
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
            'logo'                => 'logos/NGuBfgDd5TlexXEXnXMv6VEqRK0EB6AaeSswH2zt.png',
            'banner'              => 'banners/kkbhz09w81cGtR9WCf7iz43NPpVmHVnIyHQHZMWE.png',
            'representante'       => null,
            'email_representante' => null,
            'tamano_empresa'      => 'Pequena',
            'linkedin'            => null,
            'instagram'           => null,
            'facebook'            => null,
            'id_localidad'        => 303,
            'id_provincia'        => 3,
            'fecha_creacion'      => now(),
            'estado'              => 'aprobada',
        ]);

        // ═══════════════════════════════════════════════════════
        // EMPRESAS NUEVAS
        // ═══════════════════════════════════════════════════════

        // ─── Empresa 5: LogiRed S.A. (Logística) ───
        $userEmpresa5 = User::updateOrCreate(
            ['email' => 'logired@krow.com'],
            [
                'name'                       => 'LogiRed S.A.',
                'password'                   => Hash::make('password123'),
                'rol'                        => 'empresa',
                'email_verified_at'          => Carbon::now(),
                'email_verification_code'    => null,
                'email_verification_expires' => null,
            ]
        );
        DB::table('empresa')->insertOrIgnore([
            'id_empresa'          => 5,
            'id_usuario'          => $userEmpresa5->id,
            'nombre_empresa'      => 'LogiRed',
            'razon_social'        => 'LogiRed S.A.',
            'cuit'                => 30333444555,
            'rubro'               => 'Logística y Distribución',
            'direccion'           => 'Ruta 8 km 45',
            'telefono'            => '1177889900',
            'email_contacto'      => 'rrhh@logired.com.ar',
            'sitio_web'           => 'https://logired.com.ar',
            'descripcion'         => 'Empresa líder en logística de última milla y distribución nacional.',
            'logo'                => 'logos/p7TOvE5V7p7CCuBznBfVyElZ7DTp5VmVg6M8DOaI.webp',
            'banner'              => 'banners/pdKwlzskFLth1hcb3JJqeo6s5BVQlTJbKSJE20Bs.png',
            'representante'       => 'Andrea Suárez',
            'email_representante' => 'asuarez@logired.com.ar',
            'tamano_empresa'      => 'Grande',
            'linkedin'            => 'https://linkedin.com/company/logired',
            'instagram'           => null,
            'facebook'            => null,
            'id_localidad'        => 261,  // Ramos Mejía, GBA
            'id_provincia'        => 2,
            'fecha_creacion'      => now(),
            'estado'              => 'aprobada',
        ]);

        // ─── Empresa 6: CloudNine (Startup SaaS, pendiente de aprobación) ───
        $userEmpresa6 = User::updateOrCreate(
            ['email' => 'cloudnine@krow.com'],
            [
                'name'                       => 'CloudNine Tech',
                'password'                   => Hash::make('password123'),
                'rol'                        => 'empresa',
                'email_verified_at'          => Carbon::now(),
                'email_verification_code'    => null,
                'email_verification_expires' => null,
            ]
        );
        DB::table('empresa')->insertOrIgnore([
            'id_empresa'          => 6,
            'id_usuario'          => $userEmpresa6->id,
            'nombre_empresa'      => 'CloudNine Tech',
            'razon_social'        => 'CloudNine Tech S.R.L.',
            'cuit'                => 30444555666,
            'rubro'               => 'SaaS / Cloud Computing',
            'direccion'           => 'Av. del Trabajo 780',
            'telefono'            => '1188990011',
            'email_contacto'      => 'hola@cloudnine.com.ar',
            'sitio_web'           => 'https://cloudnine.com.ar',
            'descripcion'         => 'Startup que desarrolla herramientas SaaS para equipos de desarrollo de software.',
            'logo'                => 'logos/1KDx27CXzXkpvBBkpPy9ka488jkYbjVE7Ifaikxa.png',
            'banner'              => 'banners/oF6TavZlXZntf8oiZz7NJEteww5bk8T0RMGs6Yhk.png',
            'representante'       => null,
            'email_representante' => null,
            'tamano_empresa'      => 'Microempresa',
            'linkedin'            => 'https://linkedin.com/company/cloudnine-tech',
            'instagram'           => null,
            'facebook'            => null,
            'id_localidad'        => 570,  // Rosario, Santa Fe
            'id_provincia'        => 22,
            'fecha_creacion'      => now(),
            'estado'              => 'pendiente',  // Empresa pendiente de aprobación
        ]);

        // ─── Empresa 7: MercadoCrecer (E-commerce/Marketing) ───
        $userEmpresa7 = User::updateOrCreate(
            ['email' => 'mercadocrecer@krow.com'],
            [
                'name'                       => 'MercadoCrecer S.A.',
                'password'                   => Hash::make('password123'),
                'rol'                        => 'empresa',
                'email_verified_at'          => Carbon::now(),
                'email_verification_code'    => null,
                'email_verification_expires' => null,
            ]
        );
        DB::table('empresa')->insertOrIgnore([
            'id_empresa'          => 7,
            'id_usuario'          => $userEmpresa7->id,
            'nombre_empresa'      => 'MercadoCrecer',
            'razon_social'        => 'MercadoCrecer S.A.',
            'cuit'                => 30555666777,
            'rubro'               => 'E-commerce y Marketing',
            'direccion'           => 'Thames 1820',
            'telefono'            => '1199110022',
            'email_contacto'      => 'talentos@mercadocrecer.com',
            'sitio_web'           => 'https://mercadocrecer.com',
            'descripcion'         => 'Plataforma de e-commerce para pymes argentinas. Crecimiento acelerado.',
            'logo'                => 'logos/abG7f4QVmZOLnN7hYBBazgRuiSYq2xSPktcQry5N.png',
            'banner'              => 'banners/zQNT3wXI1gBicp5IeCpdylOF40zRumNa5ENRx2iR.png',
            'representante'       => 'Laura Herrera',
            'email_representante' => 'lherrera@mercadocrecer.com',
            'tamano_empresa'      => 'Pequena',
            'linkedin'            => null,
            'instagram'           => 'https://instagram.com/mercadocrecer',
            'facebook'            => null,
            'id_localidad'        => 317,  // Villa Crespo, CABA
            'id_provincia'        => 3,
            'fecha_creacion'      => now(),
            'estado'              => 'aprobada',
        ]);

        // ─── Empresa 8: IndustrIA (rechazada, para testear admin) ───
        $userEmpresa8 = User::updateOrCreate(
            ['email' => 'industria@krow.com'],
            [
                'name'                       => 'IndustrIA S.R.L.',
                'password'                   => Hash::make('password123'),
                'rol'                        => 'empresa',
                'email_verified_at'          => Carbon::now(),
                'email_verification_code'    => null,
                'email_verification_expires' => null,
            ]
        );
        DB::table('empresa')->insertOrIgnore([
            'id_empresa'          => 8,
            'id_usuario'          => $userEmpresa8->id,
            'nombre_empresa'      => 'IndustrIA',
            'razon_social'        => 'IndustrIA S.R.L.',
            'cuit'                => 30666777888,
            'rubro'               => 'Industria Manufacturera',
            'direccion'           => 'Parque Industrial Morón, lote 12',
            'telefono'            => '1100223344',
            'email_contacto'      => 'rrhh@industria.com.ar',
            'sitio_web'           => null,
            'descripcion'         => 'Empresa manufacturera buscando pasantes de ingeniería.',
            'logo'                => 'logos/4czFLUAa8Qt0prcHAcxDF7tKT1yJxX2lXAgY97jD.png',
            'banner'              => 'banners/masvbMYh3toZ3N5prYetR9N7DBofFijg1hhuzHfK.png',
            'representante'       => null,
            'email_representante' => null,
            'tamano_empresa'      => 'Grande',
            'linkedin'            => null,
            'instagram'           => null,
            'facebook'            => null,
            'id_localidad'        => 219,  // Morón, GBA
            'id_provincia'        => 2,
            'fecha_creacion'      => now(),
            'estado'              => 'rechazada',  // Rechazada para testear admin
        ]);

        // ═══════════════════════════════════════════════════════
        // ADMIN
        // ═══════════════════════════════════════════════════════
        User::updateOrCreate(
            ['email' => 'admin@krow.com'],
            [
                'name'                       => 'Administrador General',
                'password'                   => Hash::make('admin123admin456admin789'),
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
            // ── Originales ──────────────────────────────────────
            [
                'id_oferta'             => 1,
                'id_empresa'            => 1,
                'titulo'                => 'Desarrollador Laravel Jr.',
                'descripcion'           => 'Buscamos un desarrollador junior para el equipo de backend. Trabajarás en APIs RESTful con Laravel, colaborando con el equipo de producto en proyectos reales de clientes.',
                'requisitos'            => 'Conocimientos básicos de PHP y Laravel. Manejo de Git. Estudiante avanzado o egresado de Tecnicatura en Programación.',
                'area'                  => 'Backend',
                'experiencia_requerida' => 'Junior',
                'tipo_oferta'           => 'Pasantia',
                'modalidad'             => 'Remoto',
                'salario_min'           => 80000,
                'salario_max'           => 120000,
                'id_localidad'          => null,
                'id_provincia'          => null,
                'fecha_cierre'          => '2026-09-30',
                'estado'                => 'Activa',
            ],
            [
                'id_oferta'             => 2,
                'id_empresa'            => 1,
                'titulo'                => 'Pasante Front-End React',
                'descripcion'           => 'Incorporamos pasantes para el área de frontend. Trabajarás con React y Tailwind CSS en proyectos reales. Contarás con mentoreo constante del equipo senior.',
                'requisitos'            => 'Conocimientos de JavaScript, React y HTML/CSS. Ganas de aprender y trabajar en equipo.',
                'area'                  => 'Frontend',
                'experiencia_requerida' => 'Sin Experiencia',
                'tipo_oferta'           => 'Pasantia',
                'modalidad'             => 'Hibrido',
                'salario_min'           => 70000,
                'salario_max'           => 100000,
                'id_localidad'          => 313,
                'id_provincia'          => 3,
                'fecha_cierre'          => '2026-08-15',
                'estado'                => 'Activa',
            ],
            [
                'id_oferta'             => 3,
                'id_empresa'            => 2,
                'titulo'                => 'Desarrollador Full Stack Node.js + React',
                'descripcion'           => 'Buscamos full stack para plataforma SaaS en crecimiento. Stack: React, Node.js + Express, PostgreSQL. Trabajo ágil con Scrum.',
                'requisitos'            => 'Experiencia con Node.js y React. Manejo de bases de datos relacionales. Inglés técnico lectura.',
                'area'                  => 'Full Stack',
                'experiencia_requerida' => 'Junior',
                'tipo_oferta'           => 'Part-Time',
                'modalidad'             => 'Remoto',
                'salario_min'           => 150000,
                'salario_max'           => 200000,
                'id_localidad'          => null,
                'id_provincia'          => null,
                'fecha_cierre'          => '2026-07-31',
                'estado'                => 'Activa',
            ],
            [
                'id_oferta'             => 4,
                'id_empresa'            => 2,
                'titulo'                => 'UX/UI Designer con conocimientos frontend',
                'descripcion'           => 'Perfil mixto diseño + implementación frontend. Trabajarás en Figma y luego implementarás con React + Tailwind CSS.',
                'requisitos'            => 'Conocimientos de Figma, HTML/CSS, JavaScript básico. Portafolio requerido.',
                'area'                  => 'Diseño',
                'experiencia_requerida' => 'Sin Experiencia',
                'tipo_oferta'           => 'Pasantia',
                'modalidad'             => 'Presencial',
                'salario_min'           => 60000,
                'salario_max'           => 90000,
                'id_localidad'          => 303,
                'id_provincia'          => 3,
                'fecha_cierre'          => '2026-08-01',
                'estado'                => 'Activa',
            ],
            [
                'id_oferta'             => 5,
                'id_empresa'            => 3,
                'titulo'                => 'Desarrollador Python / Django',
                'descripcion'           => 'Empresa de software de gestión busca desarrollador Python para mantenimiento y nuevas funcionalidades en sistema ERP.',
                'requisitos'            => 'Conocimientos sólidos de Python. Familiaridad con SQL. Comunicación clara.',
                'area'                  => 'Backend',
                'experiencia_requerida' => 'Junior',
                'tipo_oferta'           => 'Full-Time',
                'modalidad'             => 'Hibrido',
                'salario_min'           => 180000,
                'salario_max'           => 250000,
                'id_localidad'          => 408,
                'id_provincia'          => 7,
                'fecha_cierre'          => '2026-10-01',
                'estado'                => 'Activa',
            ],
            [
                'id_oferta'             => 6,
                'id_empresa'            => 3,
                'titulo'                => 'Analista QA y Testing',
                'descripcion'           => 'Incorporamos analista QA para pruebas funcionales y automatizadas sobre nuestros productos ERP. Aprenderás Selenium, Cypress y CI/CD.',
                'requisitos'            => 'Conocimientos básicos de programación. Atención al detalle.',
                'area'                  => 'QA',
                'experiencia_requerida' => 'Sin Experiencia',
                'tipo_oferta'           => 'Practica Profesional',
                'modalidad'             => 'Presencial',
                'salario_min'           => 90000,
                'salario_max'           => 130000,
                'id_localidad'          => 408,
                'id_provincia'          => 7,
                'fecha_cierre'          => '2026-09-15',
                'estado'                => 'Activa',
            ],
            [
                'id_oferta'             => 7,
                'id_empresa'            => 1,
                'titulo'                => 'DevOps / Infrastructure Intern',
                'descripcion'           => 'Proyecto de modernización de infraestructura. Trabajarás con Docker, CI/CD y despliegues en la nube.',
                'requisitos'            => 'Conocimientos de Linux, Git y redes básicas. Deseable Docker.',
                'area'                  => 'DevOps',
                'experiencia_requerida' => 'Sin Experiencia',
                'tipo_oferta'           => 'Pasantia',
                'modalidad'             => 'Remoto',
                'salario_min'           => 85000,
                'salario_max'           => 110000,
                'id_localidad'          => null,
                'id_provincia'          => null,
                'fecha_cierre'          => '2026-08-31',
                'estado'                => 'Activa',
            ],
            [
                'id_oferta'             => 8,
                'id_empresa'            => 4,
                'titulo'                => 'Programador de Videojuegos Jr. (Unity / C#)',
                'descripcion'           => 'Estudio indie busca pasante para desarrollo de mecánicas de juego en Unity. Participarás en el ciclo completo de desarrollo.',
                'requisitos'            => 'Conocimientos de Unity y C#. Git. Portafolio o proyecto propio deseable.',
                'area'                  => 'Desarrollo de Juegos',
                'experiencia_requerida' => 'Sin Experiencia',
                'tipo_oferta'           => 'Pasantia',
                'modalidad'             => 'Presencial',
                'salario_min'           => 75000,
                'salario_max'           => 100000,
                'id_localidad'          => 303,
                'id_provincia'          => 3,
                'fecha_cierre'          => '2026-09-01',
                'estado'                => 'Activa',
            ],
            [
                'id_oferta'             => 9,
                'id_empresa'            => 4,
                'titulo'                => 'Artista 3D / Game Artist Jr.',
                'descripcion'           => 'Buscamos artista con conocimientos en Blender para crear assets 3D para videojuegos.',
                'requisitos'            => 'Conocimientos de Blender o similar. Portafolio con modelos 3D requerido.',
                'area'                  => 'Arte / Diseño',
                'experiencia_requerida' => 'Sin Experiencia',
                'tipo_oferta'           => 'Pasantia',
                'modalidad'             => 'Hibrido',
                'salario_min'           => 65000,
                'salario_max'           => 95000,
                'id_localidad'          => 303,
                'id_provincia'          => 3,
                'fecha_cierre'          => '2026-08-20',
                'estado'                => 'Activa',
            ],
            [
                'id_oferta'             => 10,
                'id_empresa'            => 2,
                'titulo'                => 'Asistente de Marketing Digital',
                'descripcion'           => 'Incorporamos pasante de marketing digital para gestionar campañas en redes sociales, Google Ads y estrategias de contenido.',
                'requisitos'            => 'Conocimientos de redes sociales, herramientas de marketing digital y Google Ads. Redacción clara.',
                'area'                  => 'Marketing',
                'experiencia_requerida' => 'Sin Experiencia',
                'tipo_oferta'           => 'Practica Profesional',
                'modalidad'             => 'Remoto',
                'salario_min'           => 70000,
                'salario_max'           => 95000,
                'id_localidad'          => null,
                'id_provincia'          => null,
                'fecha_cierre'          => '2026-09-30',
                'estado'                => 'Activa',
            ],
            // ── Nuevas ──────────────────────────────────────────
            [
                'id_oferta'             => 11,
                'id_empresa'            => 5,
                'titulo'                => 'Asistente de Logística y Operaciones',
                'descripcion'           => 'Buscamos estudiante avanzado de Logística para apoyar en la coordinación de rutas, seguimiento de envíos y gestión de almacenes.',
                'requisitos'            => 'Estudiante de Tecnicatura en Logística o Ingeniería Industrial. Manejo de Excel. Disponibilidad presencial.',
                'area'                  => 'Logística',
                'experiencia_requerida' => 'Sin Experiencia',
                'tipo_oferta'           => 'Practica Profesional',
                'modalidad'             => 'Presencial',
                'salario_min'           => 85000,
                'salario_max'           => 110000,
                'id_localidad'          => 261,
                'id_provincia'          => 2,
                'fecha_cierre'          => '2026-10-15',
                'estado'                => 'Activa',
            ],
            [
                'id_oferta'             => 12,
                'id_empresa'            => 5,
                'titulo'                => 'Analista de Procesos SAP Jr.',
                'descripcion'           => 'Incorporamos pasante para el área de procesos. Trabajarás junto a consultores SAP en implementaciones y soporte a usuarios internos.',
                'requisitos'            => 'Estudiante de Ingeniería Industrial o Administración. Conocimientos básicos de SAP y Excel avanzado.',
                'area'                  => 'Operaciones',
                'experiencia_requerida' => 'Sin Experiencia',
                'tipo_oferta'           => 'Pasantia',
                'modalidad'             => 'Hibrido',
                'salario_min'           => 90000,
                'salario_max'           => 130000,
                'id_localidad'          => 261,
                'id_provincia'          => 2,
                'fecha_cierre'          => '2026-09-01',
                'estado'                => 'Activa',
            ],
            [
                'id_oferta'             => 13,
                'id_empresa'            => 6,
                'titulo'                => 'Desarrollador Next.js (Pasantía Remota)',
                'descripcion'           => 'Startup SaaS busca pasante Next.js para trabajar en el dashboard de la plataforma. Stack: Next.js, TypeScript, PostgreSQL, AWS.',
                'requisitos'            => 'Conocimientos de React/Next.js y TypeScript. Ganas de aprender en entorno startup. Inglés básico.',
                'area'                  => 'Frontend',
                'experiencia_requerida' => 'Sin Experiencia',
                'tipo_oferta'           => 'Pasantia',
                'modalidad'             => 'Remoto',
                'salario_min'           => 100000,
                'salario_max'           => 150000,
                'id_localidad'          => null,
                'id_provincia'          => null,
                'fecha_cierre'          => '2026-08-01',
                'estado'                => 'Activa',
            ],
            [
                'id_oferta'             => 14,
                'id_empresa'            => 7,
                'titulo'                => 'Community Manager Jr.',
                'descripcion'           => 'MercadoCrecer busca community manager para gestionar redes sociales, crear contenido y analizar métricas de campañas digitales.',
                'requisitos'            => 'Estudios en Marketing Digital o afines. Manejo de Instagram, TikTok y Meta Ads. Creatividad y redacción.',
                'area'                  => 'Marketing',
                'experiencia_requerida' => 'Sin Experiencia',
                'tipo_oferta'           => 'Part-Time',
                'modalidad'             => 'Remoto',
                'salario_min'           => 80000,
                'salario_max'           => 110000,
                'id_localidad'          => null,
                'id_provincia'          => null,
                'fecha_cierre'          => '2026-10-31',
                'estado'                => 'Activa',
            ],
            [
                'id_oferta'             => 15,
                'id_empresa'            => 7,
                'titulo'                => 'Diseñador Gráfico / UX para E-commerce',
                'descripcion'           => 'Buscamos diseñador para crear piezas gráficas, landing pages y mejorar la experiencia de compra en nuestra plataforma.',
                'requisitos'            => 'Manejo de Figma y Adobe Illustrator. Portafolio con trabajos de diseño digital. Sensibilidad visual.',
                'area'                  => 'Diseño',
                'experiencia_requerida' => 'Sin Experiencia',
                'tipo_oferta'           => 'Practica Profesional',
                'modalidad'             => 'Hibrido',
                'salario_min'           => 75000,
                'salario_max'           => 100000,
                'id_localidad'          => 317,
                'id_provincia'          => 3,
                'fecha_cierre'          => '2026-09-30',
                'estado'                => 'Activa',
            ],
            [
                'id_oferta'             => 16,
                'id_empresa'            => 1,
                'titulo'                => 'Backend Node.js — Práctica Profesional',
                'descripcion'           => 'Práctica profesional en el equipo de integración de APIs. Trabajarás en microservicios con Node.js, Docker y AWS.',
                'requisitos'            => 'Node.js, REST APIs, Git. Deseable TypeScript y Docker.',
                'area'                  => 'Backend',
                'experiencia_requerida' => 'Junior',
                'tipo_oferta'           => 'Practica Profesional',
                'modalidad'             => 'Remoto',
                'salario_min'           => 130000,
                'salario_max'           => 170000,
                'id_localidad'          => null,
                'id_provincia'          => null,
                'fecha_cierre'          => '2026-11-30',
                'estado'                => 'Activa',
            ],
            [
                // Oferta cerrada para testear filtros de estado
                'id_oferta'             => 17,
                'id_empresa'            => 3,
                'titulo'                => 'Desarrollador Angular — Pasantía (CERRADA)',
                'descripcion'           => 'Esta posición ya fue cubierta. Se mantiene para histórico.',
                'requisitos'            => 'Angular, TypeScript, REST APIs.',
                'area'                  => 'Frontend',
                'experiencia_requerida' => 'Junior',
                'tipo_oferta'           => 'Pasantia',
                'modalidad'             => 'Presencial',
                'salario_min'           => 90000,
                'salario_max'           => 120000,
                'id_localidad'          => 408,
                'id_provincia'          => 7,
                'fecha_cierre'          => '2026-05-01',
                'estado'                => 'Cerrada',
            ],
        ];

        foreach ($ofertas as $oferta) {
            DB::table('oferta')->insertOrIgnore(array_merge($oferta, [
                'fecha_publicacion' => now(),
            ]));
        }

        // ─── Ofertas → Carreras ───
        $ofertaCarreras = [
            // Originales (CORREGIDO: oferta 8 solo carrera 10, no 8)
            [1,  7],
            [2,  7],
            [3,  7],
            [4,  7],
            [5,  7],
            [6,  7],
            [7,  7],
            [8,  10],   // ← FIX: era [8,7] y [8,10]; carrera 8 = Aeronaves, no corresponde
            [9,  10],
            [10, 12],
            // Nuevas
            [11, 13],   // Logística → Tec. Logística
            [11, 4],    // Logística → Ingeniería Industrial
            [12, 4],    // SAP → Ingeniería Industrial
            [12, 13],   // SAP → Tec. Logística
            [13, 7],    // Next.js → Tec. Programación
            [14, 12],   // Community Manager → Marketing Digital
            [15, 12],   // Diseño UX → Marketing Digital
            [16, 7],    // Node.js Backend → Tec. Programación
            [17, 7],    // Angular (cerrada) → Tec. Programación
        ];
        foreach ($ofertaCarreras as [$o, $c]) {
            DB::table('oferta_carrera')->insertOrIgnore(['id_oferta' => $o, 'id_carrera' => $c]);
        }

        // ─── Ofertas → Habilidades ───
        $ofertaHabilidades = [
            // Originales
            [1,  1],
            [1,  2],
            [1,  11],
            [1,  13],
            [1,  16],
            [2,  3],
            [2,  5],
            [2,  17],
            [2,  18],
            [2,  13],
            [3,  3],
            [3,  4],
            [3,  5],
            [3,  7],
            [3,  12],
            [3,  13],
            [3,  20],
            [4,  3],
            [4,  5],
            [4,  17],
            [4,  18],
            [5,  8],
            [5,  10],
            [5,  14],
            [5,  13],
            [6,  19],
            [6,  13],
            [7,  14],
            [7,  15],
            [7,  13],
            [8,  21],
            [8,  22],
            [8,  13],
            [8,  19],
            [9,  23],
            [9,  24],
            [10, 25],
            [10, 26],
            [10, 27],
            [10, 20],
            // Nuevas
            [11, 28],
            [11, 35],
            [11, 20],           // Logística
            [12, 29],
            [12, 35],
            [12, 20],           // SAP
            [13, 31],
            [13, 4],
            [13, 12],
            [13, 32], // Next.js
            [14, 25],
            [14, 26],
            [14, 27],           // Community Manager
            [15, 33],
            [15, 24],
            [15, 17],           // Diseño UX
            [16, 7],
            [16, 4],
            [16, 14],
            [16, 32],
            [16, 16], // Node.js Backend
            [17, 34],
            [17, 4],
            [17, 13],           // Angular (cerrada)
        ];
        foreach ($ofertaHabilidades as [$o, $h]) {
            DB::table('oferta_habilidad')->insertOrIgnore(['id_oferta' => $o, 'id_habilidad' => $h]);
        }

        // ═══════════════════════════════════════════════════════
        // POSTULACIONES
        // ═══════════════════════════════════════════════════════
        $postulaciones = [
            // Originales
            [1,  1, 2,  'Preseleccionado', 'Perfil muy interesante para la pasantía React. Llamar esta semana.'],
            [2,  1, 3,  'Postulado',     null],
            [3,  2, 1,  'En Contacto',     'Buen conocimiento de Laravel. Entrevista coordinada para el viernes.'],
            [4,  2, 5,  'Postulado',       null],
            [5,  3, 2,  'Postulado',       null],
            [6,  3, 4,  'Postuladon',     'Portfolio de diseño revisado. Muy buena presentación visual.'],
            [7,  4, 7,  'Preseleccionado', 'Conocimiento de Docker destacado para el nivel. A confirmar disponibilidad.'],
            [8,  4, 5,  'Rechazado',       'Falta de experiencia con Python según lo requerido.'],
            [9,  5, 8,  'En Contacto',     'Proyecto propio en Unity muy interesante. Reunión agendada.'],
            [10, 5, 9,  'Postulado',       null],
            [11, 6, 10, 'Postulado',     'Buena presencia en redes. Revisar experiencia con Google Ads.'],
            [12, 6, 3,  'Rechazado',       'Perfil no coincide con el stack técnico requerido.'],
            // Nuevas
            [13, 7,  11, 'Postulado',       null],
            [14, 7,  12, 'Postulado',     'Tiene SAP básico visto en la tecnicatura. Citar para entrevista técnica.'],
            [15, 9,  11, 'Preseleccionado', 'Perfil ideal para el puesto. Coordinar inicio de práctica.'],
            [16, 8,  13, 'En Contacto',     'Excelente conocimiento de Next.js y TypeScript. Oferta extendida.'],
            [17, 8,  16, 'Postulado',       null],
            [18, 11, 14, 'Postulado',       null],
            [19, 11, 15, 'Postulado',     'Portfolio con trabajos en Figma. Muy buena presentación visual.'],
            [20, 6,  14, 'Rechazado',       'Perfil más orientado a ads, falta experiencia en content creation.'],
            [21, 3,  15, 'Preseleccionado', 'Domina Figma y tiene sensibilidad de diseño. Avanzar con entrevista.'],
            [22, 1,  16, 'Postulado',     null],
            [23, 4,  16, 'En Contacto',     'Buen manejo de Docker y AWS. Coordinar incorporación.'],
            [24, 2,  1,  'Postulado',       null],   // José también se postula a Laravel Jr. (segundo intento)
        ];

        // Nota: la postulación 24 duplicaría (estudiante 2, oferta 1) que ya existe en id 3.
        // Usamos id_estudiante=2, id_oferta=1 con UNIQUE KEY → la 24 se ignora automáticamente con insertOrIgnore.
        // La dejamos así para demostrar que el constraint funciona.

        foreach ($postulaciones as [$id, $est, $of, $estado]) {
            DB::table('postulacion')->insertOrIgnore([
                'id_postulacion'    => $id,
                'id_estudiante'     => $est,
                'id_oferta'         => $of,
                'estado'            => $estado,
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
            [6, $userEst7->id,        'Error al cargar CV en formato Word',  'Intento subir mi CV en .docx y el sistema lo rechaza. Solo acepta PDF?',                      'Abierto'],
            [7, $userEmpresa5->id,    'No puedo ver las postulaciones',      'Desde el panel de empresa no veo la lista de postulantes a nuestra oferta publicada.',         'Abierto'],
            [8, $userEst9->id,        'Quiero cancelar una postulación',     'Me postulé por error a una oferta de programación. ¿Es posible retirar la postulación?',      'Resuelto'],
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

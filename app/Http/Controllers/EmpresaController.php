<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use App\Models\Empresa;
use Illuminate\Http\Request;
use App\Models\Oferta;
use App\Models\Postulacion;
use App\Models\Provincia;
use App\Models\Localidad;
use App\Models\Carrera;

class EmpresaController extends Controller
{
    public function index()
    {
        return response()->json(Empresa::with(['user', 'localidad', 'provincia', 'ofertas'])->get());
    }

    public function show(Empresa $empresa)
    {
        return response()->json($empresa->load(['user', 'localidad', 'provincia', 'ofertas']));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_usuario'          => 'required|exists:users,id|unique:empresa,id_usuario',
            'nombre_empresa'      => 'required|string|max:100',
            'razon_social'        => 'required|string|max:150',
            'cuit'                => 'required|numeric|digits_between:10,11|unique:empresa,cuit',
            'rubro'               => 'required|string|max:100',
            'direccion'           => 'nullable|string|max:200',
            'telefono'            => 'required|string|max:20',
            'email_contacto'      => 'required|email|max:100',
            'sitio_web'           => 'nullable|string|max:255',
            'descripcion'         => 'nullable|string',
            'logo'                => 'nullable|string|max:255',
            'representante'       => 'required|string|max:100',
            'email_representante' => 'required|email|max:100',
            'tamano_empresa'      => 'nullable|in:Microempresa,Pequena,Mediana,Grande',
            'linkedin'            => 'nullable|string|max:255',
            'instagram'           => 'nullable|string|max:255',
            'facebook'            => 'nullable|string|max:255',
            'id_localidad'        => 'nullable|exists:localidad,id_localidad',
            'id_provincia'        => 'nullable|exists:provincia,id_provincia',
        ]);

        $empresa = Empresa::create($data);

        return response()->json($empresa, 201);
    }

    public function updatePerfil(Request $request)
    {
        $usuario = auth()->user();
        $empresa = $usuario->empresa;

        // Mapeo de campos para compatibilidad
        if ($request->has('nombre') && !$request->has('nombre_empresa')) {
            $request->merge(['nombre_empresa' => $request->input('nombre')]);
        }
        if ($request->has('email') && !$request->has('email_contacto')) {
            $request->merge(['email_contacto' => $request->input('email')]);
        }

        $data = $request->validate([
            'logo'                => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'banner' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
                Rule::dimensions()->minWidth(1200)->minHeight(400),
            ],
            'nombre_empresa'      => 'required|string|max:100',
            'razon_social'        => 'required|string|max:150',
            'cuit'                => 'required|numeric|digits_between:10,11|unique:empresa,cuit,' . $empresa->id_empresa . ',id_empresa',
            'rubro'               => 'required|string|max:100',
            'direccion'           => 'nullable|string|max:200',
            'telefono'            => 'required|string|max:20',
            'email_contacto'      => 'required|email|max:100',
            'sitio_web'           => 'nullable|string|max:255',
            'descripcion'         => 'nullable|string',
            'representante'       => 'required|string|max:100',
            'email_representante' => 'required|email|max:100',
            'tamano_empresa'      => 'nullable|in:Microempresa,Pequena,Mediana,Grande',
            'linkedin'            => 'nullable|string|max:255',
            'instagram'           => 'nullable|string|max:255',
            'facebook'            => 'nullable|string|max:255',
            'id_localidad'        => 'nullable|exists:localidad,id_localidad',
            'id_provincia'        => 'nullable|exists:provincia,id_provincia',
        ], [
            'banner.dimensions' => 'El banner debe tener al menos 1200x400 píxeles para no verse pixelado.',
            'banner.max'        => 'El banner no puede pesar más de 5MB.',
            'logo.max'          => 'El logo no puede pesar más de 3MB.',
        ]);

        try {
            // Guardar logo
            if ($request->hasFile('logo')) {
                // Eliminar logo anterior si existe
                if ($empresa->logo && Storage::disk('public')->exists($empresa->logo)) {
                    Storage::disk('public')->delete($empresa->logo);
                }
                $path = $request->file('logo')->store('logos', 'public');
                $empresa->logo = $path;
            }

            // Guardar banner - La clave está aquí
            if ($request->hasFile('banner')) {
                // Eliminar banner anterior si existe
                if ($empresa->banner && Storage::disk('public')->exists($empresa->banner)) {
                    Storage::disk('public')->delete($empresa->banner);
                }
                $path = $request->file('banner')->store('banners', 'public');
                $empresa->banner = $path; // Asegurar que se guarda
            }

            // Actualizar datos del usuario
            $usuario->name  = $data['nombre_empresa'];
            $usuario->email = $data['email_contacto'];
            $usuario->save();

            // Actualizar datos de la empresa
            $empresa->fill([
                'nombre_empresa'      => $data['nombre_empresa'],
                'razon_social'        => $data['razon_social'],
                'cuit'                => $data['cuit'],
                'rubro'               => $data['rubro'],
                'direccion'           => $data['direccion'] ?? null,
                'telefono'            => $data['telefono'],
                'email_contacto'      => $data['email_contacto'],
                'sitio_web'           => $data['sitio_web'] ?? null,
                'descripcion'         => $data['descripcion'] ?? null,
                'representante'       => $data['representante'],
                'email_representante' => $data['email_representante'],
                'tamano_empresa'      => $data['tamano_empresa'] ?? null,
                'linkedin'            => $data['linkedin'] ?? null,
                'instagram'           => $data['instagram'] ?? null,
                'facebook'            => $data['facebook'] ?? null,
                'id_localidad'        => $data['id_localidad'] ?? null,
                'id_provincia'        => $data['id_provincia'] ?? null,
            ]);

            // Asegurar que el banner se guarde en el modelo
            // (ya se asignó arriba, pero por si acaso)
            if (isset($path) && $request->hasFile('banner')) {
                $empresa->banner = $path;
            }

            $empresa->save();

            // Debug: verificar si se guardó
            \Log::info('Banner guardado:', ['banner' => $empresa->banner]);

            return redirect()->route('empresa.perfil')->with('perfil_ok', 'Perfil actualizado correctamente');

        } catch (\Exception $e) {
            \Log::error('Error al actualizar perfil:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error al actualizar el perfil: ' . $e->getMessage()]);
        }
    }

    public function verPerfil()
    {
        $usuario = auth()->user();
        $empresa = $usuario->empresa()->with(['localidad', 'provincia'])->first();
        $ofertas = Oferta::where('id_empresa', $empresa->id_empresa)->get();

        return view('empresa.perfil-empresa', compact('empresa', 'ofertas'));
    }

    public function editarPerfil()
    {
        $usuario = auth()->user();
        $empresa = $usuario->empresa;

        $provincias  = Provincia::orderBy('nombre')->get();
        $localidades = $empresa && $empresa->id_provincia
            ? Localidad::where('id_provincia', $empresa->id_provincia)->orderBy('nombre')->get()
            : collect();

        return view('empresa.perfil-empresa-editar', compact('usuario', 'empresa', 'provincias', 'localidades'));
    }

    public function destroy(Empresa $empresa)
    {
        $empresa->ofertas->each(function (Oferta $oferta) {
            $oferta->carreras()->detach();
            $oferta->habilidades()->detach();
            $oferta->postulaciones()->delete();
            $oferta->delete();
        });

        $empresa->delete();

        return response()->noContent();
    }

    public function home()
    {
        $empresa = auth()->user()->empresa;
        $empresaId = $empresa->id_empresa;
        $userId = auth()->id();

        $ofertas = Oferta::where('id_empresa', $empresaId)->withCount('postulaciones')->get();

        $totalPostulantes = $ofertas->sum('postulaciones_count');
        $totalVistas = $ofertas->sum('vistas') ?? 0;

        // Mensajes no leídos de la empresa
        $mensajesSinLeer = \App\Models\Mensaje::whereHas('chat', function($q) use ($userId) {
                $q->where('id_usuario_1', $userId)->orWhere('id_usuario_2', $userId);
            })
            ->where('id_remitente', '!=', $userId)
            ->where('leido', false)
            ->count();

        return view('empresa.home-empresa', compact('ofertas', 'totalPostulantes', 'totalVistas', 'mensajesSinLeer'));
    }

    public function verPostulantes($id)
    {
        $oferta = Oferta::where('id_oferta', $id)
            ->where('id_empresa', auth()->user()->empresa->id_empresa)
            ->firstOrFail();

        $postulantes = Postulacion::where('id_oferta', $id)
            ->with(['estudiante.carrera', 'estudiante.user'])
            ->get()
            ->map(function ($postulacion) {
                $estudiante = $postulacion->estudiante;

                return (object)[
                    'id'                => $postulacion->id_postulacion,
                    'id_usuario'        => $estudiante->id_usuario,       
                    'id_estudiante'     => $estudiante->id_estudiante,
                    'nombre'            => trim($estudiante->nombre . ' ' . $estudiante->apellido),
                    'carrera'           => $estudiante->carrera->nombre ?? 'No especificada',
                    'email'             => $estudiante->user->email ?? 'No disponible',
                    'telefono'          => $estudiante->telefono ?? 'No disponible',
                    'fecha_postulacion' => $postulacion->fecha_postulacion,
                    'estado'            => $postulacion->estado,
                    'linkedin'          => $estudiante->linkedin ?? null,
                    'github'            => $estudiante->github ?? null,
                    'cv'                => $estudiante->cv ?? null,
                ];
            });

        return view('empresa.postulantes-empresa', compact('oferta', 'postulantes'));
    }

        public function storeOferta(Request $request)
        {
            $data = $request->validate([
                'titulo'                => 'required|string|max:100',
                'tipo_trabajo'          => 'required|string',
                'modalidad'             => 'required|string',
                'rango_salarial'        => 'required|string|max:100',
                'experiencia_requerida' => 'required|string',
                'descripcion'           => 'required|string',
                'requisitos'            => 'required|string',
                'id_localidad'          => 'required|exists:localidad,id_localidad',
                'id_provincia'          => 'required|exists:provincia,id_provincia',
                'area'                  => 'required|string|max:50',
                'id_carrera'            => 'required|exists:carrera,id_carrera',
            ], [
                'titulo.required' => 'El título del puesto es obligatorio.',
                'tipo_trabajo.required' => 'Seleccioná el tipo de trabajo.',
                'modalidad.required' => 'Seleccioná la modalidad.',
                'rango_salarial.required' => 'Ingresá el rango salarial.',
                'experiencia_requerida.required' => 'Seleccioná la experiencia requerida.',
                'descripcion.required' => 'La descripción del puesto es obligatoria.',
                'requisitos.required' => 'Los requisitos son obligatorios.',
                'id_localidad.required' => 'Seleccioná una localidad.',
                'id_provincia.required' => 'Seleccioná una provincia.',
                'area.required' => 'Seleccioná una categoría.',
                'id_carrera.required' => 'Seleccioná una carrera destinada.',
            ]);

            $oferta = new \App\Models\Oferta();
            $oferta->titulo       = $data['titulo'];
            $oferta->descripcion  = $data['descripcion'];
            $oferta->requisitos   = $data['requisitos'] ?? null;
            $oferta->id_localidad = $data['id_localidad'] ?? null;
            $oferta->id_provincia = $data['id_provincia'] ?? null;
            $oferta->area         = $data['area'];
            $oferta->id_carrera   = $data['id_carrera'];

            if (!empty($data['rango_salarial'])) {
                preg_match_all('/\d+/', str_replace('.', '', $data['rango_salarial']), $matches);
                $numeros = $matches[0];
                $oferta->salario_min = $numeros[0] ?? null;
                $oferta->salario_max = $numeros[1] ?? null;
            } else {
                $oferta->salario_min = null;
                $oferta->salario_max = null;
            }

            $oferta->modalidad = ucfirst(strtolower($data['modalidad']));

            $oferta->tipo_oferta = $data['tipo_trabajo'] === 'Practica Profesional'
                ? 'Practica Profesional'
                : $data['tipo_trabajo'];

            $oferta->experiencia_requerida = $data['experiencia_requerida'] === 'sin-experiencia'
                ? 'Sin Experiencia'
                : ucwords(str_replace('-', ' ', $data['experiencia_requerida']));

            $oferta->id_empresa        = auth()->user()->empresa->id_empresa;
            $oferta->fecha_publicacion = now();
            $oferta->estado            = 'Activa';
            $oferta->save();

            // Guardar habilidades
            if ($request->has('tecnologias') && is_array($request->tecnologias) && count($request->tecnologias) > 0) {
                $ids = [];
                foreach ($request->tecnologias as $nombre) {
                    $nombre = trim($nombre);
                    if (!$nombre) continue;
                    $habilidad = \App\Models\Habilidad::whereRaw('LOWER(nombre) = ?', [strtolower($nombre)])->first();
                    if (!$habilidad) {
                        $habilidad = \App\Models\Habilidad::create(['nombre' => ucfirst(strtolower($nombre))]);
                    }
                    $ids[] = $habilidad->id_habilidad;
                }
                $oferta->habilidades()->sync($ids);
            }

            // ── Notificar a todos los estudiantes ─────────────────────
            $ahora      = now();
            $estudiantes = \App\Models\User::where('rol', 'estudiante')->pluck('id');

            if ($estudiantes->isNotEmpty()) {
                \DB::table('notificaciones')->insert(
                    $estudiantes->map(fn($id) => [
                        'id_usuario'  => $id,
                        'titulo'      => 'Nueva oferta disponible',
                        'mensaje'     => "Se publicó la oferta '{$oferta->titulo}'.",
                        'url' => route('ofertas.detalle', $oferta->id_oferta),
                        'tipo'        => 'info',
                        'leida'       => false,
                        'created_at'  => $ahora,
                        'updated_at'  => $ahora,
                    ])->toArray()
                );
            }

            return redirect()->route('empresa.home')->with('success', 'La oferta se cargó correctamente.');
        }





    public function actualizarEstadoPostulante(Request $request, $postulacionId)
    {
        $postulacion = Postulacion::with('oferta', 'estudiante')->findOrFail($postulacionId);
        if ($postulacion->oferta->id_empresa !== auth()->user()->empresa->id_empresa) {
            abort(403, 'No autorizado');
        }

        $estadosValidos = ['Postulado', 'Preseleccionado', 'En Contacto', 'Rechazado'];
        $estado         = $request->input('estado');

        if (!in_array($estado, $estadosValidos)) {
            return response()->json(['error' => 'Estado inválido'], 422);
        }

        $postulacion->estado = $estado;
            $postulacion->save();

            $notificaciones = [
        'Preseleccionado' => [
            'titulo'  => 'Avanzaste en la preselección',
            'mensaje' => "La empresa revisó tu postulación a '{$postulacion->oferta->titulo}' y avanzaste a la siguiente etapa.",
            'tipo'    => 'success',
        ],
        'En Contacto' => [
            'titulo'  => 'Una empresa quiere contactarte',
            'mensaje' => "La empresa está interesada en tu postulación a '{$postulacion->oferta->titulo}'.",
            'tipo'    => 'success',
        ],
        'Rechazado' => [
            'titulo'  => 'Tu postulación no avanzó',
            'mensaje' => "La empresa finalizó el proceso de selección para '{$postulacion->oferta->titulo}'.",
            'tipo'    => 'warning',
        ],
        'Postulado' => [
            'titulo'  => 'Postulación recibida',
            'mensaje' => "Tu postulación a '{$postulacion->oferta->titulo}' fue registrada correctamente.",
            'tipo'    => 'info',
        ],
    ];

            if (isset($notificaciones[$estado])) {
                $n = $notificaciones[$estado];
                \DB::table('notificaciones')->insert([
                    'id_usuario'  => $postulacion->estudiante->id_usuario,
                    'titulo'      => $n['titulo'],
                    'mensaje'     => $n['mensaje'],
                    'url'         => route('estudiante.home'),
                    'tipo'        => $n['tipo'],
                    'leida'       => false,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }

            return response()->json(['success' => true, 'estado' => $postulacion->estado]);
    }

    public function verPerfilEstudiante($id)
    {
        $estudiante = \App\Models\Estudiante::with([
            'user',
            'carrera',
            'localidad',
            'provincia',
            'habilidades',
            'postulaciones.oferta.empresa',
        ])->findOrFail($id);

        return view('empresa.perfil-estudiante', compact('estudiante'));
    }

    public function verPerfilPublico(Empresa $empresa)
    {
        $empresa->load(['localidad', 'provincia']);
        $ofertas = \App\Models\Oferta::where('id_empresa', $empresa->id_empresa)
                    ->where('estado', 'Activa')
                    ->get();

        return view('empresa.perfil-publico', compact('empresa'));
    }

    public function crearOferta()
    {
        $provincias = Provincia::orderBy('nombre', 'asc')->get();
        $carreras   = Carrera::all();

        return view('empresa.crear-oferta', compact('provincias', 'carreras'));
    }

    public function cambiarEstadoOferta(Request $request, $id)
    {
        $oferta = Oferta::where('id_oferta', $id)
            ->where('id_empresa', auth()->user()->empresa->id_empresa)
            ->firstOrFail();

        $estadosValidos = ['Activa', 'Pausada', 'Cerrada'];
        $nuevoEstado    = $request->input('estado');

        if (!in_array($nuevoEstado, $estadosValidos)) {
            return response()->json(['error' => 'Estado inválido'], 422);
        }

        $oferta->estado = $nuevoEstado;
        $oferta->save();

        return response()->json(['success' => true, 'estado' => $oferta->estado]);
    }

    public function eliminarOferta($id)
    {
        $oferta = Oferta::where('id_oferta', $id)
            ->where('id_empresa', auth()->user()->empresa->id_empresa)
            ->firstOrFail();

        $oferta->postulaciones()->delete();
        $oferta->habilidades()->detach();
        \DB::table('oferta_carrera')->where('id_oferta', $id)->delete();
        $oferta->delete();

        return response()->json(['success' => true]);
    }
}
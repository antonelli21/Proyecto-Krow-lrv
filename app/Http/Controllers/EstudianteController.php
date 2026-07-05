<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage; 
use App\Models\Estudiante;
use Illuminate\Http\Request;
use App\Models\Oferta;
use App\Models\Mensaje;
class EstudianteController extends Controller

{
    public function index()
    {
        return response()->json(Estudiante::with(['user', 'carrera', 'localidad', 'provincia', 'habilidades'])->get());
    }
    public function show(Estudiante $estudiante)
    {
        $estudiante->load([
            'user',
            'carrera',
            'localidad',
            'provincia',
            'habilidades',
            'postulaciones.oferta.empresa',
            'postulaciones.oferta.habilidades'
        ]);

        return response()->json(
             $estudiante->load(['postulaciones.oferta.empresa'])
        );
    }

    public function homeEstudiante()
{
    $userId = auth()->id();
    $usuario = auth()->user();
    $estudiante = $usuario->estudiante->load('habilidades');

    $mensajesSinLeer = \App\Models\Mensaje::whereHas('chat', function($q) use ($userId) {
            $q->where('id_usuario_1', $userId)
            ->orWhere('id_usuario_2', $userId);
        })
        ->where('id_remitente', '!=', $userId)
        ->where('leido', false)
        ->count();

    // Completitud del perfil
    $campos = [
        'foto_perfil'        => 10,
        'cv'                 => 20,
        'telefono'           => 10,
        'descripcion'        => 15,
        'linkedin'           => 10,
        'modalidad_deseada'  => 10,
        'portfolio'          => 10,
    ];
    $completitud = 0;
    foreach ($campos as $campo => $peso) {
        if (!empty($estudiante->$campo)) $completitud += $peso;
    }
    if ($estudiante->habilidades->count() > 0) $completitud += 15;

    $panelData = [];
    $panelData['completitud'] = $completitud;
    $panelData['sin_cv'] = empty($estudiante->cv);

    $panelData['postulaciones'] = \App\Models\Postulacion::where('id_estudiante', $estudiante->id_estudiante)->count();

    $panelData['en_revision'] = \App\Models\Postulacion::where('id_estudiante', $estudiante->id_estudiante)
        ->whereIn('estado', ['Preseleccionado', 'En Contacto'])
        ->count();

    $panelData['contactado'] = \App\Models\Postulacion::where('id_estudiante', $estudiante->id_estudiante)
        ->where('estado', 'En Contacto')
        ->count();

    $panelData['ultimas_ofertas'] = Oferta::with('empresa')
        ->where('estado', 'activa')
        ->orderBy('fecha_publicacion', 'desc')
        ->take(3)
        ->get();

    return view('estudiante.home-estudiante', compact('mensajesSinLeer', 'panelData'));
}

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_usuario' => 'required|exists:users,id|unique:estudiante,id_usuario',
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'dni' => 'required|integer|unique:estudiante,dni',
            'legajo' => 'required|string|max:20|unique:estudiante,legajo',
            'fecha_nacimiento' => 'nullable|date',
            'telefono' => 'nullable|string|max:15',
            'id_carrera' => 'required|exists:carrera,id_carrera',
            'descripcion' => 'nullable|string',
            'modalidad_deseada' => 'nullable|in:Full-Time,Part-Time,Hibrido,Remoto',
            'disponibilidad_horaria' => 'nullable|string|max:100',
            'foto_perfil' => 'nullable|string|max:255',
            'cv' => 'nullable|string|max:255',
            'portfolio' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'github' => 'nullable|string|max:255',
            'id_localidad' => 'nullable|exists:localidad,id_localidad',
            'id_provincia' => 'nullable|exists:provincia,id_provincia',
        ]);

        $estudiante = Estudiante::create($data);

        return response()->json($estudiante, 201);
    }

    public function update(Request $request, Estudiante $estudiante)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|required|string|max:50',
            'apellido' => 'sometimes|required|string|max:50',
            'dni' => 'sometimes|required|integer|unique:estudiante,dni,' . $estudiante->id_estudiante . ',id_estudiante',
            'legajo' => 'sometimes|required|string|max:20|unique:estudiante,legajo,' . $estudiante->id_estudiante . ',id_estudiante',
            'fecha_nacimiento' => 'nullable|date',
            'telefono' => 'nullable|string|max:15',
            'id_carrera' => 'sometimes|required|exists:carrera,id_carrera',
            'descripcion' => 'nullable|string',
            'modalidad_deseada' => 'nullable|in:Full-Time,Part-Time,Hibrido,Remoto',
            'disponibilidad_horaria' => 'nullable|string|max:100',
            'foto_perfil' => 'nullable|string|max:255',
            'cv' => 'nullable|string|max:255',
            'portfolio' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'github' => 'nullable|string|max:255',
            'id_localidad' => 'nullable|exists:localidad,id_localidad',
            'id_provincia' => 'nullable|exists:provincia,id_provincia',
        ]);

        $estudiante->update($data);

        return response()->json($estudiante);
    }

  public function updatePerfil(Request $request)
    {
        $usuario = auth()->user();
        $estudiante = $usuario->estudiante;

        $data = $request->validate([
            'foto_perfil' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'email' => 'required|email|max:255|unique:users,email,' . $usuario->id,
            'dni' => 'required|integer|unique:estudiante,dni,' . $estudiante->id_estudiante . ',id_estudiante',
            'legajo' => 'required|string|max:20|unique:estudiante,legajo,' . $estudiante->id_estudiante . ',id_estudiante',
            'fecha_nacimiento' => 'nullable|date|before:today -16 years|after:today -100 years',
            'telefono' => 'nullable|string|max:15',
            'id_carrera' => 'required|exists:carrera,id_carrera',
            'descripcion' => 'nullable|string',
            'modalidad_deseada' => 'nullable|in:Full-Time,Part-Time,Hibrido,Remoto',
            'disponibilidad_horaria' => 'nullable|string|max:100',
            'portfolio' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'github' => 'nullable|url|max:255',
            'cv' => 'nullable|file|mimes:pdf|max:5120',
            // Los tags de habilidades llegan como texto libre (mismo patrón
            // que "tecnologias[]" en Crear Oferta), no como IDs — por eso
            // acá solo validamos que sean strings, y más abajo hacemos
            // firstOrCreate por nombre antes de sincronizar el pivot.
            'habilidades' => 'nullable|array',
            'habilidades.*' => 'string|max:100',
        ]);

        try {
            // 1) Foto de perfil
            if ($request->hasFile('foto_perfil')) {
                $path = $request->file('foto_perfil')->store('perfiles', 'public');
                $estudiante->foto_perfil = $path;
            }

            // 2) Datos del usuario (tabla users)
            $usuario->name = $data['nombre'] . ' ' . $data['apellido'];
            $usuario->email = $data['email'];
            $usuario->save();

            // 3) CV: si subieron uno nuevo, lo guardamos
            if ($request->hasFile('cv')) {
                // Eliminar CV anterior si existe
                if ($estudiante->cv && Storage::disk('public')->exists($estudiante->cv)) {
                    Storage::disk('public')->delete($estudiante->cv);
                }
                $path = $request->file('cv')->store('cvs', 'public');
                $estudiante->cv = $path;
            }

            // 4) Datos del estudiante
            $estudiante->fill([
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'dni' => $data['dni'],
                'legajo' => $data['legajo'],
                'fecha_nacimiento' => $data['fecha_nacimiento'] ?? null,
                'telefono' => $data['telefono'] ?? null,
                'id_carrera' => $data['id_carrera'],
                'descripcion' => $data['descripcion'] ?? null,
                'modalidad_deseada' => $data['modalidad_deseada'] ?? null,
                'disponibilidad_horaria' => $data['disponibilidad_horaria'] ?? null,
                'portfolio' => $data['portfolio'] ?? null,
                'linkedin' => $data['linkedin'] ?? null,
                'github' => $data['github'] ?? null,
            ]);

            $estudiante->save();

            // 5) Habilidades — mismo patrón que "Crear Oferta": el tag input
            // manda nombres de tecnología en texto libre, no IDs. Buscamos
            // (o creamos) el registro en `habilidad` por nombre, sin
            // distinguir mayúsculas/minúsculas, y sincronizamos el pivot
            // estudiante_habilidad con los IDs resultantes.
            $idsHabilidades = [];
            foreach ($data['habilidades'] ?? [] as $nombreHabilidad) {
                $nombreHabilidad = trim($nombreHabilidad);
                if ($nombreHabilidad === '') continue;

                $habilidad = \App\Models\Habilidad::whereRaw('LOWER(nombre) = ?', [strtolower($nombreHabilidad)])->first();
                if (!$habilidad) {
                    $habilidad = \App\Models\Habilidad::create(['nombre' => ucfirst(strtolower($nombreHabilidad))]);
                }
                $idsHabilidades[] = $habilidad->id_habilidad;
            }
            $estudiante->habilidades()->sync($idsHabilidades);

            return redirect()->route('estudiante.perfil')
                ->with('perfil_ok', '✅ Perfil actualizado correctamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => '❌ Error al actualizar el perfil: ' . $e->getMessage()]);
        }

    }

    public function editarPerfil()
    {
        $estudiante  = auth()->user()->estudiante;
        $carreras    = \App\Models\Carrera::orderBy('nombre')->get();
        $habilidades = \App\Models\Habilidad::orderBy('nombre')->get();

        return view('estudiante.perfil-estudiante-editar', compact('estudiante', 'carreras', 'habilidades'));
    }


    public function verPerfil()
    {
        $usuario = auth()->user();
        $estudiante = $usuario->estudiante()->with('habilidades', 'carrera')->first();

        return view('estudiante.perfil-estudiante', compact('usuario', 'estudiante'));
    }

    public function destroy(Estudiante $estudiante)
    {
        $estudiante->habilidades()->detach();
        $estudiante->postulaciones()->delete();
        $estudiante->delete();

        return response()->noContent();
    }

    public function obtenerOfertas()
    {
        $ofertas = Oferta::where('estado', 'activa')
            ->whereHas('empresa', function($query) {
                $query->where('estado', 'aprobada');
            })
            ->latest()
            ->get();

        return response()->json($ofertas);
    }

    public function cancelarPostulacion(Request $request, $id)
{
    $estudiante = auth()->user()->estudiante;

    $postulacion = \App\Models\Postulacion::where('id_postulacion', $id)
        ->where('id_estudiante', $estudiante->id_estudiante)
        ->firstOrFail();

    $postulacion->delete();

    return response()->json(['success' => true]);
}
}
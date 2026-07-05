<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\NotificacionService;
use App\Models\Notificacion;

class OfertaController extends Controller
{

    public function detalle($id_oferta)
    {
        $oferta = Oferta::with(['empresa', 'localidad.provincia', 'habilidades', 'carreras'])->findOrFail($id_oferta);

        $oferta->ya_postulado = auth()->check()
            ? $oferta->postulaciones()
                ->whereHas('estudiante', fn($q) => $q->where('id_usuario', auth()->id()))
                ->exists()
            : false;

        return view('empresa.oferta-detalle', compact('oferta'));
    }

public function preview($id_oferta)
{
    $oferta = Oferta::with([
        'empresa',
        'habilidades',
        'carreras',
        'localidad',
        'provincia'
    ])->findOrFail($id_oferta);
    $oferta->ya_postulado = auth()->check()
            ? $oferta->postulaciones()
                ->whereHas('estudiante', fn($q) => $q->where('id_usuario', auth()->id()))
                ->exists()
            : false;

    return view('empresa.oferta-detalle-preview', compact('oferta'));
}

    // aquí guardás la postulación en la BD cuando tengas el modelo
        public function postular($id_oferta)
        {
            $oferta     = Oferta::with('empresa')->findOrFail($id_oferta);
            $estudiante = auth()->user()->estudiante;

            if (!$estudiante) {
                return back()->with('error', 'No tenés un perfil de estudiante.');
            }

            if (empty($estudiante->cv)) {
                return back()->with('error', 'Necesitás cargar tu CV antes de postularte. <a style="color:var(--accent);" href="' . route('estudiante.perfil.editar') . '">Ir a mi perfil</a>');
            }

            $postulacion = \App\Models\Postulacion::withTrashed()
    ->where('id_oferta', $oferta->id_oferta)
    ->where('id_estudiante', $estudiante->id_estudiante)
    ->first();

if ($postulacion) {

    if ($postulacion->trashed()) {
        $postulacion->restore();

        $postulacion->update([
            'estado' => 'Postulado',
            'fecha_postulacion' => now(),
        ]);
    } else {
        return back()->with('error', 'Ya te postulaste a esta oferta.');
    }

} else {

    $oferta->postulaciones()->create([
        'id_estudiante'     => $estudiante->id_estudiante,
        'fecha_postulacion' => now(),
        'estado'            => 'Postulado',
    ]);

}

            // ── Notificar a la empresa ─────────────────────────────
            \DB::table('notificaciones')->insert([
                'id_usuario'  => $oferta->empresa->id_usuario,
                'titulo'      => 'Nuevo postulante',
                'mensaje'     => "{$estudiante->nombre} {$estudiante->apellido} se postuló a \"{$oferta->titulo}\".",
                'url'         => route('empresa.ofertas.postulantes', $oferta->id_oferta),
                'tipo'        => 'info',
                'leida'       => false,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            return back()->with('success', '¡Te postulaste exitosamente!');
        }
            

    public function index()
    {
        return response()->json(Oferta::with(['empresa', 'localidad', 'provincia', 'carreras', 'habilidades'])->get());
    }

    public function show(Oferta $oferta)
    {
        return response()->json($oferta->load(['empresa', 'localidad', 'provincia', 'carreras', 'habilidades']));
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'id_empresa' => 'required|exists:empresa,id_empresa',
        'titulo' => 'required|string|max:100',
        'descripcion' => 'required|string',
        'requisitos' => 'nullable|string',
        'area' => 'nullable|string|max:50',
        'experiencia_requerida' => 'required|in:Sin Experiencia,Junior,Semi Senior,Senior',
        'tipo_oferta' => 'required|in:Pasantia,Practica Profesional,Part-Time,Full-Time',
        'modalidad' => 'required|in:Presencial,Remoto,Hibrido',
        'salario_min' => 'nullable|integer|min:0',
        'salario_max' => 'nullable|integer|min:0',
        'id_localidad' => 'nullable|exists:localidad,id_localidad',
        'id_provincia' => 'nullable|exists:provincia,id_provincia',
        'fecha_cierre' => 'nullable|date',
        'estado' => 'nullable|in:Activa,Pausada,Cerrada',
        'id_carrera' => 'nullable', // puede venir como array o escalar, ajustá según tu form
    ]);

    $idCarreras = (array) ($data['id_carrera'] ?? []);
    unset($data['id_carrera']);

    $oferta = Oferta::create($data);

    if (!empty($idCarreras)) {
        $oferta->carreras()->attach($idCarreras);
    }

    return response()->json($oferta, 201);
}


    public function update(Request $request, Oferta $oferta)
    {
        if ($oferta->pausada_por_admin && $request->estado === 'Activa') {
        return response()->json([
            'success' => false,
            'mensaje' => 'Esta oferta fue pausada por un administrador. Enviá un ticket para solicitar su reactivación.',
        ], 403);
        }

        $data = $request->validate([
            'titulo' => 'sometimes|required|string|max:100',
            'descripcion' => 'sometimes|required|string',
            'requisitos' => 'nullable|string',
            'area' => 'nullable|string|max:50',
            'experiencia_requerida' => 'sometimes|required|in:Sin Experiencia,Junior,Semi Senior,Senior',
            'tipo_oferta' => 'sometimes|required|in:Pasantia,Practica Profesional,Part-Time,Full-Time',
            'modalidad' => 'sometimes|required|in:Presencial,Remoto,Hibrido',
            'salario_min' => 'nullable|integer|min:0',
            'salario_max' => 'nullable|integer|min:0',
            'id_localidad' => 'nullable|exists:localidad,id_localidad',
            'id_provincia' => 'nullable|exists:provincia,id_provincia',
            'fecha_cierre' => 'nullable|date',
            'estado' => 'nullable|in:Activa,Pausada,Cerrada',
        ]);

        $oferta->update($data);
        return response()->json($oferta);
    }

    public function destroy(Oferta $oferta)
{
    $empresa = $oferta->id_empresa;

    $oferta->carreras()->detach();
    $oferta->habilidades()->detach();
    $oferta->postulaciones()->delete();
    $oferta->delete();

    $activas = Oferta::where('id_empresa', $empresa)
        ->where('estado', 'Activa')
        ->count();

    $pausadas = Oferta::where('id_empresa', $empresa)
        ->where('estado', 'Pausada')
        ->count();

    $totalPostulantes = \App\Models\Postulacion::whereHas('oferta', function ($q) use ($empresa) {
        $q->where('id_empresa', $empresa);
    })->count();

    return response()->json([
        'success' => true,
        'activas' => $activas,
        'pausadas' => $pausadas,
        'totalPostulantes' => $totalPostulantes,
    ]);
}

    public function listar()
{
    $ofertas = Oferta::with(['empresa', 'localidad', 'provincia'])
        ->where('estado', 'activa')
        ->orderBy('fecha_publicacion', 'desc')
        ->paginate(6);
    
    return view('estudiante.oferta-detalle', compact('oferta')); // ← $oferta no existe, y la vista no pega con el propósito
}
}















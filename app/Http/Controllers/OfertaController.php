<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use Illuminate\Http\Request;

class OfertaController extends Controller
{
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
        ]);

        $oferta = Oferta::create($data);

        return response()->json($oferta, 201);
    }

    public function update(Request $request, Oferta $oferta)
    {
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
        $oferta->carreras()->detach();
        $oferta->habilidades()->detach();
        $oferta->postulaciones()->delete();
        $oferta->delete();

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;

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

        return view('estudiante.perfil', compact('estudiante'));
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

    public function destroy(Estudiante $estudiante)
    {
        $estudiante->habilidades()->detach();
        $estudiante->postulaciones()->delete();
        $estudiante->delete();

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use Illuminate\Http\Request;

class OfertaController extends Controller
{
    public function detalle($id)
    {
        $oferta = is_numeric($id)
            ? Oferta::with(['empresa', 'localidad', 'provincia', 'carreras', 'habilidades'])->findOrFail($id)
            : $this->ofertaDemo($id);

        return view('empresa.crear-oferta', [
            'oferta' => $oferta,
            'readonly' => true,
        ]);
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

    private function ofertaDemo(string $id): array
    {
        $ofertas = [
            'mock-1' => [
                'titulo' => 'Fullstack Developer Node / React',
                'empresa' => ['nombre_empresa' => 'MegaCorp'],
                'modalidad' => 'Remoto',
                'tipo_oferta' => 'Full-Time',
                'experiencia_requerida' => 'Junior',
                'salario_min' => 450000,
                'salario_max' => 650000,
                'descripcion' => 'Buscamos un desarrollador proactivo para sumarse al equipo de core-banking, participando en el desarrollo de nuevas funcionalidades, mantenimiento de APIs y mejora continua del producto.',
                'requisitos' => "Experiencia con Node.js y React\nConocimientos de bases de datos SQL\nManejo de Git\nBuenas practicas de testing",
                'habilidades' => [
                    ['nombre' => 'Node.js'],
                    ['nombre' => 'React'],
                    ['nombre' => 'SQL'],
                    ['nombre' => 'Git'],
                ],
                'estado' => 'Activa',
            ],
            'mock-2' => [
                'titulo' => 'Analista QA Semi-Senior',
                'empresa' => ['nombre_empresa' => 'DevSoft'],
                'modalidad' => 'Hibrido',
                'tipo_oferta' => 'Part-Time',
                'experiencia_requerida' => 'Semi Senior',
                'salario_min' => 300000,
                'salario_max' => 420000,
                'descripcion' => 'Incorporamos QA con experiencia en testing funcional y automatizado para acompanar releases de productos web.',
                'requisitos' => "Testing funcional\nDiseno de casos de prueba\nAutomatizacion basica\nComunicacion con equipos de desarrollo",
                'habilidades' => [
                    ['nombre' => 'QA'],
                    ['nombre' => 'Selenium'],
                    ['nombre' => 'Jira'],
                ],
                'estado' => 'Activa',
            ],
        ];

        abort_unless(isset($ofertas[$id]), 404);

        return $ofertas[$id];
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use App\Models\Oferta;
use App\Models\Postulacion;

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
            'id_usuario' => 'required|exists:users,id|unique:empresa,id_usuario',
            'nombre_empresa' => 'required|string|max:100',
            'razon_social' => 'required|string|max:150',
            'cuit' => 'required|numeric|digits_between:10,11|unique:empresa,cuit',
            'rubro' => 'required|string|max:100',
            'direccion' => 'nullable|string|max:200',
            'telefono' => 'required|string|max:20',
            'email_contacto' => 'required|email|max:100',
            'sitio_web' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'logo' => 'nullable|string|max:255',
            'representante' => 'required|string|max:100',
            'email_representante' => 'required|email|max:100',
            'tamano_empresa' => 'nullable|in:Microempresa,Pequena,Mediana,Grande',
            'linkedin' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'id_localidad' => 'nullable|exists:localidad,id_localidad',
            'id_provincia' => 'nullable|exists:provincia,id_provincia',
        ]);

        $empresa = Empresa::create($data);

        return response()->json($empresa, 201);
    }

    public function update(Request $request, Empresa $empresa)
    {
        $data = $request->validate([
            'nombre_empresa' => 'sometimes|required|string|max:100',
            'razon_social' => 'sometimes|required|string|max:150',
            'cuit' => 'sometimes|required|numeric|digits_between:10,11|unique:empresa,cuit,' . $empresa->id_empresa . ',id_empresa',
            'rubro' => 'sometimes|required|string|max:100',
            'direccion' => 'nullable|string|max:200',
            'telefono' => 'sometimes|required|string|max:20',
            'email_contacto' => 'sometimes|required|email|max:100',
            'sitio_web' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'logo' => 'nullable|string|max:255',
            'representante' => 'sometimes|required|string|max:100',
            'email_representante' => 'sometimes|required|email|max:100',
            'tamano_empresa' => 'nullable|in:Microempresa,Pequena,Mediana,Grande',
            'linkedin' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'id_localidad' => 'nullable|exists:localidad,id_localidad',
            'id_provincia' => 'nullable|exists:provincia,id_provincia',
        ]);

        $empresa->update($data);

        return response()->json($empresa);
    }

    public function destroy(Empresa $empresa)
    {
        $empresa->delete();

        return response()->noContent();
    }

    public function home()
    {
        $empresaId = auth()->user()->empresa->id_empresa;
        $ofertas = Oferta::where('id_empresa', $empresaId)->withCount('postulaciones')->get();
        
        return view('empresa.home-empresa', compact('ofertas'));
    }


/* Mostrar los postulantes de una oferta específica*/
    public function verPostulantes($id)
    {
        // Buscar la oferta y verificar que pertenece a la empresa logueada
        $oferta = Oferta::where('id_oferta', $id)
                        ->where('id_empresa', auth()->user()->empresa->id_empresa) // Asumiendo que User tiene relación con Empresa
                        ->firstOrFail();
        
        // Obtener los postulantes con sus datos relacionados
        $postulantes = Postulacion::where('id_oferta', $id)
                                  ->with('estudiante') // Cargar la relación con estudiante
                                  ->get()
                                  ->map(function($postulacion) {
                                      // Transformar los datos al formato que usa la vista
                                      $estudiante = $postulacion->estudiante;

                                    $estadoMap = [
                                      'Postulado' => 'postulado',
                                      'En Revision' => 'en_revision',
                                      'Preseleccionado' => 'preseleccionado',
                                      'En Contacto' => 'en_contacto',
                                      'Rechazado' => 'rechazado'
                                    ];
                                     
                                    return (object)[
                                        'id' => $postulacion->id_postulacion,
                                        'nombre' => $estudiante->name ?? $estudiante->nombre ?? 'Nombre no disponible',
                                        'carrera' => $estudiante->carrera->nombre ?? $estudiante->carrera ?? 'No especificada',
                                        'email' => $estudiante->email ?? $postulacion->email,
                                        'telefono' => $estudiante->telefono ?? 'No disponible',
                                        'fecha_postulacion' => $postulacion->created_at,
                                        'estado_original' => $postulacion->estado,
                                        'estado' => $postulacion->estado ?? 'pendiente',
                                        'cv_url' => $estudiante->cv_url ?? null,
                                        'linkedin_url' => $estudiante->linkedin_url ?? null,
                                        'github_url' => $estudiante->github_url ?? null
                                    ];
                                    });
        
        return view('empresa.postulantes-empresa', compact('oferta', 'postulantes'));
    }
    
    /**
     * Actualizar el estado de un postulante (aceptar/rechazar)
     */
    public function actualizarEstadoPostulante(Request $request, $postulacionId)
    {
        $postulacion = Postulacion::findOrFail($postulacionId);
        
        // Verificar que la oferta pertenece a la empresa logueada
        if ($postulacion->oferta->empresa_id !== auth()->user()->empresa->id) {
            abort(403, 'No autorizado');
        }
        
        $postulacion->estado = $request->estado;
        $postulacion->save();
        
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->back()->with('success', 'Estado actualizado correctamente');
    }

    
}

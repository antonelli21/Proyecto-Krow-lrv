<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

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
}

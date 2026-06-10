<?php

namespace App\Http\Controllers;

use App\Models\Mensaje;
use Illuminate\Http\Request;

class MensajeController extends Controller
{
    public function index()
    {
        return response()->json(Mensaje::with(['chat', 'remitente'])->get());
    }

    public function show(Mensaje $mensaje)
    {
        return response()->json($mensaje->load(['chat', 'remitente']));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_chat' => 'required|exists:chat,id_chat',
            'id_remitente' => 'required|exists:users,id',
            'contenido' => 'required|string',
            'leido' => 'nullable|boolean',
        ]);

        $mensaje = Mensaje::create($data);

        return response()->json($mensaje, 201);
    }

    public function update(Request $request, Mensaje $mensaje)
    {
        $data = $request->validate([
            'contenido' => 'sometimes|required|string',
            'leido' => 'nullable|boolean',
        ]);

        $mensaje->update($data);

        return response()->json($mensaje);
    }

    public function destroy(Mensaje $mensaje)
    {
        $mensaje->delete();

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return response()->json(Chat::with(['usuario1', 'usuario2', 'mensajes'])->get());
    }

    public function show(Chat $chat)
    {
        return response()->json($chat->load(['usuario1', 'usuario2', 'mensajes']));
    }

public function store(Request $request)
{
    // 1. Validamos que nos manden IDs de usuarios reales
    $data = $request->validate([
        'id_usuario_1' => 'required|exists:users,id',
        'id_usuario_2' => 'required|exists:users,id',
    ]);

    // 2. CONTROL DE DUPLICADOS: Buscamos si ya existe el chat entre A y B o entre B y A
    $chatExistente = Chat::where(function($query) use ($data) {
        $query->where('id_usuario_1', $data['id_usuario_1'])
              ->where('id_usuario_2', $data['data_usuario_2']);
    })->orWhere(function($query) use ($data) {
        $query->where('id_usuario_1', $data['id_usuario_2'])
              ->where('id_usuario_2', $data['id_usuario_1']);
    })->first();

    // 3. Si ya existía, no creamos nada, le devolvemos el chat viejo directo
    if ($chatExistente) {
        return response()->json($chatExistente, 200);
    }

    // 4. Si no existía, recién ahí creamos la fila en la base de datos
    $chat = Chat::create($data);

    return response()->json($chat, 201); // 201 significa "Creado con éxito"
}
    public function destroy(Chat $chat)
    {
        $chat->delete();

        return response()->noContent();
    }
}

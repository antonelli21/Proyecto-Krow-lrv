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
        $data = $request->validate([
            'id_usuario_1' => 'required|exists:users,id',
            'id_usuario_2' => 'required|exists:users,id',
        ]);

        $chat = Chat::create($data);

        return response()->json($chat, 201);
    }

    public function destroy(Chat $chat)
    {
        $chat->delete();

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $chats = Chat::with([
                'usuario1:id,name,rol',
                'usuario1.estudiante:id_usuario,foto_perfil',
                'usuario1.empresa:id_usuario,logo',
                'usuario2:id,name,rol',
                'usuario2.estudiante:id_usuario,foto_perfil',
                'usuario2.empresa:id_usuario,logo',
                'ultimoMensaje'
            ])
            ->where('id_usuario_1', $userId)
            ->orWhere('id_usuario_2', $userId)
            ->get();

        // Agregar avatar_ruta a cada usuario
        $chats->each(function($chat) {
            if ($chat->usuario1) $chat->usuario1->append('avatar_ruta');
            if ($chat->usuario2) $chat->usuario2->append('avatar_ruta');
        });

        return response()->json($chats);
    }

    public function show(Chat $chat)
    {
        $userId = auth()->id();
        if ($chat->id_usuario_1 !== $userId && $chat->id_usuario_2 !== $userId) {
            abort(403);
        }
        return response()->json($chat->load(['usuario1:id,name', 'usuario2:id,name', 'mensajes']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_usuario_destinatario' => 'required|exists:users,id',
        ]);

        $usuarioLogueado = auth()->id();
        $usuarioDestino  = (int) $request->id_usuario_destinatario;

        if ($usuarioLogueado === $usuarioDestino) {
            return redirect()->back()->with('error', 'No podés iniciar un chat con vos mismo.');
        }

        // ✅ Usando el scope del modelo
        $chat = Chat::betweenUsers($usuarioLogueado, $usuarioDestino)->first()
            ?? Chat::create([
                'id_usuario_1' => $usuarioLogueado,
                'id_usuario_2' => $usuarioDestino,
            ]);

        return redirect()->route('empresa.mensajes.show', $chat->id_chat);
    }

    public function destroy(Chat $chat)
    {
        $userId = auth()->id();
        if ($chat->id_usuario_1 !== $userId && $chat->id_usuario_2 !== $userId) {
            abort(403);
        }
        $chat->delete();
        return redirect()->route('empresa.mensajes')->with('success', 'Chat eliminado.');
    }

    public function buscarOCrear(Request $request)
    {
        $miId   = auth()->id();
        $otroId = $request->query('usuario_id');

        // ✅ Validar primero, buscar después
        if (!$otroId) {
            return response()->json(['error' => 'Falta el ID del usuario objetivo.'], 400);
        }

        $otro = User::findOrFail($otroId);

        if ((int) $otroId === $miId) {
            return response()->json(['error' => 'No podés chatear con vos mismo.'], 422);
        }

        // ✅ Scope reutilizado, sin duplicar lógica
        $chat = Chat::betweenUsers($miId, $otroId)->first()
            ?? Chat::create([
                'id_usuario_1' => $miId,
                'id_usuario_2' => $otroId,
            ]);

        $chat->load([
            'usuario1:id,name,rol',
            'usuario1.estudiante:id_usuario,foto_perfil',
            'usuario1.empresa:id_usuario,logo',
            'usuario2:id,name,rol',
            'usuario2.estudiante:id_usuario,foto_perfil',
            'usuario2.empresa:id_usuario,logo',
            'mensajes'
        ]);

        if ($chat->usuario1) $chat->usuario1->append('avatar_ruta');
        if ($chat->usuario2) $chat->usuario2->append('avatar_ruta');

        return response()->json($chat); // ✅ Devuelve el chat, no un error
    }
}
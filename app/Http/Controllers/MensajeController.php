<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Mensaje;
use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MensajeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_chat'   => 'required|exists:chat,id_chat',
            'contenido' => 'required_without:archivo|nullable|string',
            'archivo'   => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $userId = auth()->id();

        $chat = Chat::where('id_chat', $request->id_chat)
            ->where(function ($q) use ($userId) {
                $q->where('id_usuario_1', $userId)
                  ->orWhere('id_usuario_2', $userId);
            })->firstOrFail();

        $mensajeData = [
            'id_chat'      => $chat->id_chat,
            'id_remitente' => $userId,
            'contenido'    => $request->contenido ?? 'Archivo adjunto',
            'leido'        => false,
        ];

        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $mensajeData['ruta_archivo']   = $file->store('mensajes/adjuntos', 'public');
            $mensajeData['nombre_archivo'] = pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_FILENAME
            );
        }

        $mensaje = Mensaje::create($mensajeData);

        // ── Notificar al destinatario ──────────────────────────
        $idDestinatario = $chat->id_usuario_1 === $userId
            ? $chat->id_usuario_2
            : $chat->id_usuario_1;

        $destinatario = User::find($idDestinatario);
        $remitente    = auth()->user();

        $urlMensajes = match($destinatario->rol) {
            'estudiante' => route('estudiante.mensajes'),
            'empresa'    => route('empresa.mensajes'),
            'admin'      => route('admin.mensajes'),
            default      => '/mensajes',
        };

        DB::table('notificaciones')->insert([
            'id_usuario'  => $idDestinatario,
            'titulo'      => "Nuevo mensaje de {$remitente->name}",
            'mensaje'     => $request->hasFile('archivo')
                                ? "{$remitente->name} te envió un archivo adjunto."
                                : Str::limit($request->contenido, 80),
            'url'         => $urlMensajes,
            'tipo'        => Notificacion::TIPO_MESSAGE,
            'leida'       => false,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return response()->json(['success' => true, 'mensaje' => $mensaje], 201);
    }

    public function getMensajesByChat($id_chat)
    {
        $userId = auth()->id();

        $chat = Chat::where('id_chat', $id_chat)
            ->where(function ($q) use ($userId) {
                $q->where('id_usuario_1', $userId)
                  ->orWhere('id_usuario_2', $userId);
            })->firstOrFail();

        $chat->mensajes()
            ->where('id_remitente', '!=', $userId)
            ->where('leido', false)
            ->update(['leido' => true]);

        return response()->json([
            'mensajes' => $chat->mensajes()
                ->with('remitente:id,name')
                ->orderBy('fecha_envio', 'asc')
                ->get(['id_mensaje', 'id_remitente', 'contenido',
                       'leido', 'fecha_envio', 'ruta_archivo', 'nombre_archivo']),
        ]);
    }
}
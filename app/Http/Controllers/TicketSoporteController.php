<?php

namespace App\Http\Controllers;

use App\Models\TicketSoporte;
use Illuminate\Http\Request;

class TicketSoporteController extends Controller
{
    public function index()
    {
        return response()->json(TicketSoporte::with('user')->get());
    }

    public function show(TicketSoporte $ticketSoporte)
    {
        return response()->json($ticketSoporte->load('user'));
    }

        public function store(Request $request)
        {
            $data = $request->validate([
                'id_usuario'  => 'required|exists:users,id',
                'asunto'      => 'required|string|max:100',
                'descripcion' => 'required|string',
                'estado'      => 'nullable|in:Abierto,En Proceso,Resuelto',
            ]);

            $ticket    = TicketSoporte::create($data);
            $remitente = \App\Models\User::find($data['id_usuario']);

            \App\Models\User::where('rol', 'admin')->each(function ($admin) use ($ticket, $remitente) {
                \DB::table('notificaciones')->insert([
                    'id_usuario'  => $admin->id,
                    'titulo'      => 'Nuevo reporte de soporte',
                    'mensaje'     => "{$remitente->name} abrió un ticket: \"{$ticket->asunto}\".",
                    'url'         => route('admin.reportes'),
                    'tipo'        => 'danger',
                    'leida'       => false,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            });



      return response()->json($ticket, 201);
    }

    public function update(Request $request, TicketSoporte $ticketSoporte)
    {
        $data = $request->validate([
            'asunto' => 'sometimes|required|string|max:100',
            'descripcion' => 'sometimes|required|string',
            'estado' => 'nullable|in:Abierto,En Proceso,Resuelto',
        ]);

        $ticketSoporte->update($data);

        return response()->json($ticketSoporte);
    }

    public function destroy(TicketSoporte $ticketSoporte)
    {
        $ticketSoporte->delete();

        return response()->noContent();
    }
}

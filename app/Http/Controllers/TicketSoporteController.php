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
            'id_usuario' => 'required|exists:users,id',
            'asunto' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'estado' => 'nullable|in:Abierto,En Proceso,Resuelto',
        ]);

        $ticket = TicketSoporte::create($data);

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

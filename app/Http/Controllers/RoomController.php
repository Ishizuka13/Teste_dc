<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class RoomController extends Controller
{

    public function index()
    {
        try {
            $rooms = Room::all();
            return response()->json($rooms);
        } catch (\Exception $e) {
            Log::error("Erro ao buscar lista de quartos: " . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar lista de quartos.'], 500);
        }
    }

    // public function create(Request $request)
    // {
    //     try {
    //         $data = $request->only(['room_number', 'room_type', 'price']);
    //         $room = Room::where('room_number', '=', $data['room_number'])->first();

    //         if ($room) {
    //             return response()->json(['error' => 'Número de quarto já existente.'], 409);
    //         }

    //         $room = Room::create([
    //             'room_number' => $data['room_number'],
    //             'room_type' => $data['room_type'],
    //             'status' => "Disponível",
    //             'price' => $data['price']
    //         ]);

    //         return response()->json($room, 201);
    //     } catch (\Exception $e) {
    //         Log::error("Erro ao criar quarto: " . $e->getMessage());
    //         return response()->json(['error' => 'Erro ao criar quarto.'], 500);
    //     }
    // }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->only(['room_number', 'room_type', 'price', 'status']);
            $room = Room::findOrFail($id);

            if ($room) {
                $room->update($data);
                return response()->json($room);
            }

            return response()->json(['message' => 'Informações do quarto alteradas com sucesso.']);
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar o quarto $id: " . $e->getMessage());
            return response()->json(['error' => 'Erro ao atualizar o quarto.'], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class HotelController extends Controller
{

    public function reports(Request $request)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $roomType = $request->input('room_type');

            if ($startDate && !Carbon::canBeCreatedFromFormat($startDate, 'Y-m-d')) {
                return response()->json(['error' => 'Formato de data inválido para "start_date".'], 400);
            }
            if ($endDate && !Carbon::canBeCreatedFromFormat($endDate, 'Y-m-d')) {
                return response()->json(['error' => 'Formato de data inválido para "end_date".'], 400);
            }

            $startDate = Carbon::parse($startDate ?? Carbon::now()->startOfMonth());
            $endDate = Carbon::parse($endDate ?? Carbon::now()->addMonth()->endOfMonth());

            $reservations = Reservation::where(function ($query) use ($startDate, $endDate, $roomType) {
                $query->whereBetween('check_in', [$startDate, $endDate])
                    ->orWhereBetween('check_out', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('check_in', '<=', $startDate)
                            ->where('check_out', '>=', $endDate);
                    });
            })->when($roomType, function ($query) use ($roomType) {
                $query->whereHas('room', function ($q) use ($roomType) {
                    $q->where('room_type', 'LIKE', '%' . $roomType . '%');
                });
            })->with('room', 'user')
                ->get();

            $clientList = [];
            $price = 0;

            foreach ($reservations as $reservation) {
                $clientName = $reservation->user->name;
                if (!in_array($clientName, $clientList)) {
                    $clientList[] = $clientName;
                }

                $price += $reservation->room->price;
            }

            return response()->json([
                'reservations' => count($reservations),
                'amount' => $price,
                'clientList' => $clientList
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao gerar o relatório de reservas: ' . $e->getMessage());

            return response()->json(['error' => 'Erro ao gerar o relatório. Por favor, tente novamente.'], 500);
        }
    }
}

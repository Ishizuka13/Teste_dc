<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\Mails\AuthMailController;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use App\Services\WeatherService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{


    public function index()
    {
        try {
            $reservations = Reservation::all();
            return response()->json($reservations);
        } catch (\Exception $e) {
            Log::error("Erro ao buscar reservas: " . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar reservas.'], 500);
        }
    }

    public function get_reservations_by_users($user_id)
    {
        try {
            $reservation = Reservation::where('user_id', '=', $user_id)->get();
            return response()->json($reservation);
        } catch (\Exception $e) {
            Log::error("Erro ao buscar reservas do usuário $user_id: " . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar reservas do usuário.'], 500);
        }
    }

    public function get_reservations_by_date(Request $request)
    {
        try {
            $startDate = Carbon::parse($request->input('start_date', Carbon::now()->startOfMonth()));
            $endDate = Carbon::parse($request->input('end_date', Carbon::now()->addMonth()->endOfMonth()));
            $period = CarbonPeriod::create($startDate, $endDate);

            $availabilityByDate = [];

            foreach ($period as $date) {
                $availableRooms = [];
                $rooms = Room::all();
                foreach ($rooms as $room) {
                    $isReserved = Reservation::where('room_id', $room->id)
                        ->whereDate('check_in', '<=', $date)
                        ->whereDate('check_out', '>=', $date)
                        ->exists();
                    if (!$isReserved) {
                        $availableRooms[] = $room->room_number;
                    }
                }
                $availabilityByDate[$date->format('Y-m-d')] = $availableRooms;
            }

            return response()->json($availabilityByDate);
        } catch (\Exception $e) {
            Log::error("Erro ao buscar disponibilidade de quartos por data: " . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar disponibilidade de quartos.'], 500);
        }
    }


    public function create(Request $request, WeatherService $weatherService)
    {
        try {
            $data = $request->only(['room_number', 'user_email', 'hotel_guest', 'check_in', 'check_out']);

            $room = Room::where('room_number', '=', $data['room_number'])->first();
            $user = User::where('email', '=', $data['user_email'])->first();
            if (!$room || !$user) {
                return response()->json(['error' => 'Quarto ou usuário não encontrado.'], 404);
            }

            $conflictingReservations = $this->daysReserved($data, $room);
            if ($conflictingReservations) {
                return response()->json(['error' => 'Quarto já reservado neste período.'], 409);
            }

            $weatherForecast = $weatherService->getWeatherForecast('Vitoria', $data['check_in']);

            $reservation = Reservation::create([
                'room_id' => $room->id,
                'user_id' => $user->id,
                'check_in' => Carbon::parse($data['check_in'])->format('Y-m-d H:i:s'),
                'check_out' => Carbon::parse($data['check_out'])->format('Y-m-d H:i:s'),
                'hotel_guest' => $data['hotel_guest'],
            ]);

            $reservationDetails = [
                'room_number' => $data['room_number'],
                'check_in' => $reservation->check_in,
                'check_out' => $reservation->check_out,
            ];

            $authMailController = new AuthMailController();
            $authMailController->sendReserveMail($data['user_email'], $reservationDetails);

            return response()->json([
                'reservation' => $reservation,
                'weatherForecast' => $weatherForecast['main'] ?? []
            ], 201);
        } catch (\Exception $e) {
            Log::error("Erro ao criar reserva: " . $e->getMessage());
            return response()->json(['error' => 'Erro ao criar reserva.'], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $data = $request->only(['room_id', 'user_id', 'check_in', 'check_out']);
            $reservation = Reservation::findOrFail($id);
            $reservation->update($data);
            return response()->json($reservation);
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar reserva $id: " . $e->getMessage());
            return response()->json(['error' => 'Erro ao atualizar reserva.'], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $reservation = Reservation::find($id);
            if (!$reservation) {
                return response()->json(['error' => 'Reserva não encontrada.'], 404);
            }

            $hoursUntilCheckIn = Carbon::now()->diffInHours(Carbon::parse($reservation->check_in), false);
            if ($hoursUntilCheckIn < 48) {
                return response()->json(['error' => 'Menos de 48h até o check-in. Não foi possível cancelar.'], 403);
            }

            $reservation->delete();
            return response()->json(['message' => 'Reserva cancelada.']);
        } catch (\Exception $e) {
            Log::error("Erro ao cancelar reserva $id: " . $e->getMessage());
            return response()->json(['error' => 'Erro ao cancelar reserva.'], 500);
        }
    }

    public function admin_destroy($id)
    {
        try {
            Reservation::where('id', '=', $id)->delete();
            return response()->json(['message' => 'Reserva cancelada.']);
        } catch (\Exception $e) {
            Log::error("Erro ao cancelar reserva pelo admin $id: " . $e->getMessage());
            return response()->json(['error' => 'Erro ao cancelar reserva pelo admin.'], 500);
        }
    }

    protected function daysReserved($data, $room)
    {
        try {
            $isReserved = Reservation::where('room_id', $room->id)
                ->where(function ($query) use ($data) {
                    $query->whereBetween('check_in', [Carbon::parse($data['check_in']), Carbon::parse($data['check_out'])])
                        ->orWhereBetween('check_out', [Carbon::parse($data['check_in']), Carbon::parse($data['check_out'])])
                        ->orWhere(function ($query) use ($data) {
                            $query->where('check_in', '<=', Carbon::parse($data['check_in']))
                                ->where('check_out', '>=', Carbon::parse($data['check_out']));
                        });
                })
                ->exists();

            return $isReserved;
        } catch (\Exception $e) {
            Log::error("Erro ao verificar disponibilidade do quarto: " . $e->getMessage());
            return true;
        }
    }
}

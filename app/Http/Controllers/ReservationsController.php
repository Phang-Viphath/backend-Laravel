<?php

namespace App\Http\Controllers;

use App\Models\Guests;
use App\Models\Historys;
use App\Models\Reservations;
use App\Models\Room;
use App\Http\Controllers\TelegramController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReservationsController extends Controller
{
    private TelegramController $telegramController;

    public function __construct()
    {
        $this->telegramController = new TelegramController();
    }

    private function autoCheckOutReservations(): void
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $isPastNoon = $now->hour >= 12;

        $pastReservations = Reservations::where('status', 'Checked In')
            ->where(function ($query) use ($today, $isPastNoon) {
                $query->whereDate('check_out', '<', $today);
                if ($isPastNoon) {
                    $query->orWhereDate('check_out', '=', $today);
                }
            })
            ->get();
            
        foreach ($pastReservations as $reservation) {
            $reservation->update(['status' => 'Checked Out']);
            if ($reservation->room_id) {
                Room::where('id', $reservation->room_id)->update(['status' => 'available']);
            }
            
            $latestReservation = Reservations::where('guest_id', $reservation->guest_id)
                ->where('status', '!=', 'Cancelled')
                ->latest()
                ->first();

            Historys::where('guest_id', $reservation->guest_id)->delete();

            if ($latestReservation) {
                $historyStatus = match ($latestReservation->status) {
                    'Checked In' => 'current',
                    'Checked Out' => 'past',
                    'Cancelled' => 'past',
                    default => 'upcoming',
                };

                $totalStays = Reservations::where('guest_id', $reservation->guest_id)
                    ->where('status', '!=', 'Cancelled')
                    ->count();

                Historys::create([
                    'guest_id' => $reservation->guest_id,
                    'room_id' => $latestReservation->room_id,
                    'reservation_id' => $latestReservation->id,
                    'total_stays' => $totalStays,
                    'status' => $historyStatus,
                ]);
            }
        }
    }

    private function autoCheckInReservations(): void
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $isPastNoon = $now->hour >= 12;

        $dueReservations = Reservations::where('status', 'Confirmed')
            ->where(function ($query) use ($today, $isPastNoon) {
                $query->whereDate('check_in', '<', $today);
                if ($isPastNoon) {
                    $query->orWhereDate('check_in', '=', $today);
                }
            })
            ->get();
            
        foreach ($dueReservations as $reservation) {
            $reservation->update(['status' => 'Checked In']);
            if ($reservation->room_id) {
                Room::where('id', $reservation->room_id)->update(['status' => 'occupied']);
            }
            
            $latestReservation = Reservations::where('guest_id', $reservation->guest_id)
                ->where('status', '!=', 'Cancelled')
                ->latest()
                ->first();

            Historys::where('guest_id', $reservation->guest_id)->delete();

            if ($latestReservation) {
                $historyStatus = match ($latestReservation->status) {
                    'Checked In' => 'current',
                    'Checked Out' => 'past',
                    'Cancelled' => 'past',
                    default => 'upcoming',
                };

                $totalStays = Reservations::where('guest_id', $reservation->guest_id)
                    ->where('status', '!=', 'Cancelled')
                    ->count();

                Historys::create([
                    'guest_id' => $reservation->guest_id,
                    'room_id' => $latestReservation->room_id,
                    'reservation_id' => $latestReservation->id,
                    'total_stays' => $totalStays,
                    'status' => $historyStatus,
                ]);
            }
        }
    }

    // Get all reservations
    public function index(): JsonResponse
    {
        $this->autoCheckOutReservations();
        $this->autoCheckInReservations();

        return response()->json(
            Reservations::with(['guest', 'room'])
                ->latest()
                ->get()
        );
    }

    public function publicHistory(Request $request): JsonResponse
    {
        $this->autoCheckOutReservations();
        $this->autoCheckInReservations();
        $validated = $request->validate([
            'guest_id' => ['required', 'integer', 'exists:guests,id'],
        ]);

        $reservations = Reservations::with('room')
            ->where('guest_id', $validated['guest_id'])
            ->latest()
            ->get();

        return response()->json($reservations);
    }

    // Store a new reservation
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'guest_id'  => ['required', 'integer', 'exists:guests,id'],
            'room_id'   => ['required', 'integer', 'exists:rooms,id'],
            'check_in'  => ['required', 'date', 'date_format:Y-m-d'],
            'check_out' => ['required', 'date', 'date_format:Y-m-d', 'after:check_in'],
            'status'    => ['sometimes', Rule::in(['Pending', 'Confirmed', 'Checked In', 'Checked Out', 'Cancelled'])],
        ]);

        try {
            return DB::transaction(function () use ($validated) {
                $room = Room::findOrFail($validated['room_id']);

                $overlapping = Reservations::where('room_id', $room->id)
                    ->where('status', '!=', 'Cancelled')
                    ->whereDate('check_in', '<', $validated['check_out'])
                    ->whereDate('check_out', '>', $validated['check_in'])
                    ->exists();

                if ($overlapping) {
                    return response()->json([
                        'message' => 'This room is not available for the selected dates.'
                    ], 422);
                }

                $checkIn  = Carbon::parse($validated['check_in']);
                $checkOut = Carbon::parse($validated['check_out']);
                $nights   = max(1, $checkIn->diffInDays($checkOut));
                $total    = (float) $room->price * $nights;

                $statusToUpdate = $validated['status'] ?? 'Pending';
                if ($statusToUpdate === 'Confirmed' && $checkIn->isToday() && \Carbon\Carbon::now()->hour >= 12) {
                    $statusToUpdate = 'Checked In';
                }

                $reservation = Reservations::create([
                    'guest_id'  => $validated['guest_id'],
                    'room_id'   => $room->id,
                    'check_in'  => $validated['check_in'],
                    'check_out' => $validated['check_out'],
                    'status'    => $statusToUpdate,
                    'total'     => $total,
                ]);

                $reservation->load(['guest', 'room']);

                // Send Telegram notification
                $this->telegramController->sendReservationNotification($reservation->toArray());

                if ($reservation->status === 'Checked In') {
                    Room::where('id', $room->id)->update(['status' => 'occupied']);
                } elseif (in_array($reservation->status, ['Checked Out', 'Cancelled'])) {
                    Room::where('id', $room->id)->update(['status' => 'available']);
                }

                $historyStatus = match ($reservation->status) {
                    'Checked In' => 'current',
                    'Checked Out' => 'past',
                    'Cancelled' => 'past',
                    default => 'upcoming',
                };

                $totalStays = Reservations::where('guest_id', $reservation->guest_id)
                    ->where('status', '!=', 'Cancelled')
                    ->count();

                Historys::where('guest_id', $reservation->guest_id)->delete();

                Historys::create([
                    'guest_id' => $reservation->guest_id,
                    'room_id' => $reservation->room_id,
                    'reservation_id' => $reservation->id,
                    'total_stays' => $totalStays,
                    'status' => $historyStatus,
                ]);

                return response()->json($reservation, 201);
            });
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Resource not found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create reservation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Show a specific reservation
    public function show($id): JsonResponse
    {
        try {
            $reservation = Reservations::with(['guest', 'room'])->findOrFail($id);
            return response()->json($reservation);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $reservation = Reservations::with('room')->findOrFail($id);
                $oldStatus = $reservation->status;
                $oldGuestId = $reservation->guest_id;
                $oldRoomId = $reservation->room_id;

                $validated = $request->validate([
                    'guest_id'  => ['sometimes', 'integer', 'exists:guests,id'],
                    'room_id'   => ['sometimes', 'integer', 'exists:rooms,id'],
                    'check_in'  => ['sometimes', 'date', 'date_format:Y-m-d'],
                    'check_out' => ['sometimes', 'date', 'date_format:Y-m-d', 'after:check_in'],
                    'status'    => ['sometimes', Rule::in([
                        'Pending', 'Confirmed', 'Checked In', 'Checked Out', 'Cancelled'
                    ])],
                ]);

                $newRoomId = $validated['room_id'] ?? $reservation->room_id;
                $newCheckIn  = $validated['check_in']  ?? $reservation->check_in;
                $newCheckOut = $validated['check_out'] ?? $reservation->check_out;

                if (isset($validated['check_in']) || isset($validated['check_out']) || isset($validated['room_id'])) {
                    $overlapping = Reservations::where('room_id', $newRoomId)
                        ->where('id', '!=', $reservation->id)
                        ->where('status', '!=', 'Cancelled')
                        ->whereDate('check_in', '<', $newCheckOut)
                        ->whereDate('check_out', '>', $newCheckIn)
                        ->exists();

                    if ($overlapping) {
                        return response()->json(['message' => 'Room not available for new dates'], 422);
                    }

                    $roomForPrice = Room::findOrFail($newRoomId);
                    $nights = max(1, Carbon::parse($newCheckIn)->diffInDays(Carbon::parse($newCheckOut)));
                    $validated['total'] = (float) $roomForPrice->price * $nights;
                }

                if (isset($validated['status']) && $validated['status'] === 'Confirmed') {
                    if (\Carbon\Carbon::parse($newCheckIn)->isToday() && \Carbon\Carbon::now()->hour >= 12) {
                        $validated['status'] = 'Checked In';
                    }
                }

                $reservation->update($validated);
                $reservation->refresh()->load(['guest', 'room']);

                $newStatus = $reservation->status;
                $newRoomIdFinal = $reservation->room_id;

                // Send Telegram notification if status changed
                if (isset($validated['status']) && $oldStatus !== $newStatus) {
                    $this->telegramController->sendStatusUpdateNotification(
                        $reservation->toArray(),
                        $oldStatus
                    );
                }

                if ($oldRoomId && $oldRoomId !== $newRoomIdFinal) {
                    Room::where('id', $oldRoomId)->update(['status' => 'available']);
                }

                if ($newRoomIdFinal) {
                    if ($newStatus === 'Checked In') {
                        Room::where('id', $newRoomIdFinal)->update(['status' => 'occupied']);
                    } elseif (in_array($newStatus, ['Checked Out', 'Cancelled'])) {
                        Room::where('id', $newRoomIdFinal)->update(['status' => 'available']);
                    }
                }

                $syncHistoryForGuest = function (int $guestId): void {
                    $latestReservation = Reservations::where('guest_id', $guestId)
                        ->where('status', '!=', 'Cancelled')
                        ->latest()
                        ->first();

                    Historys::where('guest_id', $guestId)->delete();

                    if (!$latestReservation) {
                        return;
                    }

                    $historyStatus = match ($latestReservation->status) {
                        'Checked In' => 'current',
                        'Checked Out' => 'past',
                        'Cancelled' => 'past',
                        default => 'upcoming',
                    };

                    $totalStays = Reservations::where('guest_id', $guestId)
                        ->where('status', '!=', 'Cancelled')
                        ->count();

                    Historys::create([
                        'guest_id' => $guestId,
                        'room_id' => $latestReservation->room_id,
                        'reservation_id' => $latestReservation->id,
                        'total_stays' => $totalStays,
                        'status' => $historyStatus,
                    ]);
                };

                if (($validated['status'] ?? null) === 'Cancelled') {
                    if ($reservation->room_id) {
                        Room::where('id', $reservation->room_id)->update(['status' => 'available']);
                    }
                    Historys::where('reservation_id', $reservation->id)->delete();
                    
                    // Send cancellation notification
                    $this->telegramController->sendCancellationNotification($reservation->toArray());
                }

                $syncHistoryForGuest($oldGuestId);
                if ($reservation->guest_id !== $oldGuestId) {
                    $syncHistoryForGuest($reservation->guest_id);
                }

                return response()->json($reservation);
            });
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Reservation not found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update reservation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    // Cancel a reservation
    public function destroy($id): JsonResponse
    {
        try {
            return DB::transaction(function () use ($id) {
                $reservation = Reservations::with('room')->findOrFail($id);
                
                // Store reservation data for notification before updating
                $reservationData = $reservation->load(['guest', 'room'])->toArray();
                
                $reservation->update(['status' => 'Cancelled']);

                // Send cancellation notification
                $this->telegramController->sendCancellationNotification($reservationData);

                if ($reservation->room) {
                    $reservation->room->update(['status' => 'available']);
                }

                Historys::where('reservation_id', $reservation->id)->delete();

                $latestReservation = Reservations::where('guest_id', $reservation->guest_id)
                    ->where('status', '!=', 'Cancelled')
                    ->latest()
                    ->first();

                if ($latestReservation) {
                    $historyStatus = match ($latestReservation->status) {
                        'Checked In' => 'current',
                        'Checked Out' => 'past',
                        'Cancelled' => 'past',
                        default => 'upcoming',
                    };

                    $totalStays = Reservations::where('guest_id', $latestReservation->guest_id)
                        ->where('status', '!=', 'Cancelled')
                        ->count();

                    Historys::where('guest_id', $latestReservation->guest_id)->delete();

                    Historys::create([
                        'guest_id' => $latestReservation->guest_id,
                        'room_id' => $latestReservation->room_id,
                        'reservation_id' => $latestReservation->id,
                        'total_stays' => $totalStays,
                        'status' => $historyStatus,
                    ]);
                }

                return response()->json(['message' => 'Reservation cancelled successfully']);
            });
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Reservation not found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to cancel reservation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function cancelPublic($id): JsonResponse
    {
        return $this->destroy($id);
    }

    public function confirmPayment(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['sometimes', Rule::in(['Confirmed'])],
        ]);

        try {
            return DB::transaction(function () use ($id, $validated) {
                $reservation = Reservations::with(['guest', 'room'])->findOrFail($id);

                if ($reservation->status === 'Cancelled') {
                    return response()->json([
                        'message' => 'Cannot confirm payment for a cancelled reservation.',
                    ], 422);
                }

                $oldStatus = $reservation->status;

                $statusToUpdate = $validated['status'] ?? 'Confirmed';
                if ($statusToUpdate === 'Confirmed' && \Carbon\Carbon::parse($reservation->check_in)->isToday() && \Carbon\Carbon::now()->hour >= 12) {
                    $statusToUpdate = 'Checked In';
                }

                $reservation->update([
                    'status' => $statusToUpdate,
                ]);

                $reservation->refresh()->load(['guest', 'room']);

                if ($reservation->room_id) {
                    if ($reservation->status === 'Checked In') {
                        Room::where('id', $reservation->room_id)->update(['status' => 'occupied']);
                    } elseif (in_array($reservation->status, ['Checked Out', 'Cancelled'])) {
                        Room::where('id', $reservation->room_id)->update(['status' => 'available']);
                    }
                }

                $historyStatus = match ($reservation->status) {
                    'Checked In' => 'current',
                    'Checked Out' => 'past',
                    'Cancelled' => 'past',
                    default => 'upcoming',
                };

                $totalStays = Reservations::where('guest_id', $reservation->guest_id)
                    ->where('status', '!=', 'Cancelled')
                    ->count();

                Historys::where('guest_id', $reservation->guest_id)->delete();

                Historys::create([
                    'guest_id' => $reservation->guest_id,
                    'room_id' => $reservation->room_id,
                    'reservation_id' => $reservation->id,
                    'total_stays' => $totalStays,
                    'status' => $historyStatus,
                ]);

                if ($oldStatus !== $reservation->status) {
                    $this->telegramController->sendStatusUpdateNotification(
                        $reservation->toArray(),
                        $oldStatus
                    );
                }

                return response()->json($reservation);
            });
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Reservation not found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to confirm payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

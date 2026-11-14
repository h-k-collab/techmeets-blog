<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Reservation;
use App\Repositories\ReservationRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservationService
{
    public function __construct(
        private ReservationRepository $reservationRepository
    ) {}

    // 予約の作成
    public function createReservation(Event $event, array $data)
    {
        return DB::transaction(function () use ($event, $data) {
            $data['event_id'] = $event->id; // どのイベントの予約かを記録する

            $reservation = $this->reservationRepository->create($data);

            Log::info('Reservation created', [
                'reservation_id' => $reservation->id,
                'event_id'       => $event->id,
            ]);

            return $reservation;
        });
    }

    // 予約のキャンセル
    public function cancelReservation(Reservation $reservation)
    {
        return DB::transaction(function () use ($reservation) {
            $this->reservationRepository->delete($reservation);

            Log::info('Reservation canceled', ['reservation_id' => $reservation->id]);
        });
    }
}
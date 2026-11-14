<?php

namespace App\Repositories;

use App\Models\Reservation;

class ReservationRepository
{
    // 予約一覧(イベント名も、一緒に取得)
    public function getPaginated()
    {
        return Reservation::with('event')->latest()->paginate(10);
    }

    public function create(array $data)
    {
        return Reservation::create($data);
    }

    public function delete(Reservation $reservation)
    {
        return $reservation->delete();
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Reservation;
use App\Repositories\ReservationRepository;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        private ReservationService $reservationService,
        private ReservationRepository $reservationRepository
    ) {}

    // 予約一覧
    public function index()
    {
        $reservations = $this->reservationRepository->getPaginated();
        return view('reservations.index', compact('reservations'));
    }

    // 予約の作成
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name'        => 'required|max:100',
            'email'       => 'required|email|max:255',
            'people'      => 'required|integer|min:1|max:20',
            'reserved_at' => 'required|date',
        ]);

        $this->reservationService->createReservation($event, $validated);

        return redirect()->route('reservations.index')
            ->with('success', '予約しました');
    }

    // 予約のキャンセル
    public function destroy(Reservation $reservation)
    {
        $this->reservationService->cancelReservation($reservation);

        return redirect()->route('reservations.index')
            ->with('success', '予約をキャンセルしました');
    }
}
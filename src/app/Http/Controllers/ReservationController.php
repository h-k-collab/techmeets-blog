<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    // 予約一覧
    public function index()
    {
        $reservations = Reservation::with('event')->latest()->paginate(10);
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

        $event->reservations()->create($validated);

        return redirect()->route('reservations.index')
            ->with('success', '予約しました');
    }

    // 予約のキャンセル
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', '予約をキャンセルしました');
    }
}
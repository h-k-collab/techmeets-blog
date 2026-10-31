@extends('layouts.app')

@section('title', '予約一覧')

@section('content')
    <h1>予約一覧</h1>

    <p><a href="{{ route('events.index') }}">イベント一覧へ</a></p>

    <table border="1" cellpadding="6">
        <tr>
            <th>イベント</th>
            <th>お名前</th>
            <th>メール</th>
            <th>人数</th>
            <th>予約日時</th>
            <th></th>
        </tr>
        @forelse ($reservations as $reservation)
            <tr>
                <td>{{ $reservation->event->title }}</td>
                <td>{{ $reservation->name }}</td>
                <td>{{ $reservation->email }}</td>
                <td>{{ $reservation->people }}名</td>
                <td>{{ $reservation->reserved_at->format('Y年m月d日 H:i') }}</td>
                <td>
                    <form action="{{ route('reservations.destroy', $reservation) }}" method="POST"
                          onsubmit="return confirm('この予約をキャンセルしますか?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit">キャンセル</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">まだ予約がありません。</td>
            </tr>
        @endforelse
    </table>

    {{ $reservations->links() }}
@endsection
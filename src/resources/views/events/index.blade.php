@extends('layouts.app')

@section('title', 'イベント一覧')

@section('content')
    <h1>イベント一覧</h1>

    <p><a href="{{ route('reservations.index') }}">予約一覧を見る</a></p>

    @forelse ($events as $event)
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
            <h2>{{ $event->title }}</h2>
            <p>日時:{{ $event->event_date->format('Y年m月d日 H:i') }}</p>
            <p>場所:{{ $event->location }}</p>
            <a href="{{ route('events.show', $event) }}">詳細・予約する</a>
        </div>
    @empty
        <p>イベントはまだありません。</p>
    @endforelse

    {{ $events->links() }}
@endsection
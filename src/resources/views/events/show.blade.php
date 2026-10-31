@extends('layouts.app')

@section('title', $event->title)

@section('content')
    <h1>{{ $event->title }}</h1>

    <p>日時:{{ $event->event_date->format('Y年m月d日 H:i') }}</p>
    <p>場所:{{ $event->location }}</p>
    <div>{!! nl2br(e($event->description)) !!}</div>

    <hr>

    <h2>このイベントを予約する</h2>

    @if ($errors->any())
        <ul style="color:red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('reservations.store', $event) }}" method="POST">
        @csrf

        <p>
            <label>お名前</label><br>
            <input type="text" name="name" value="{{ old('name') }}">
        </p>
        <p>
            <label>メールアドレス</label><br>
            <input type="email" name="email" value="{{ old('email') }}">
        </p>
        <p>
            <label>人数</label><br>
            <input type="number" name="people" min="1" max="20" value="{{ old('people', 1) }}">
        </p>
        <p>
            <label>予約日時</label><br>
            <input type="datetime-local" name="reserved_at" value="{{ old('reserved_at') }}">
        </p>

        <button type="submit">予約する</button>
    </form>

    <p><a href="{{ route('events.index') }}">イベント一覧へ戻る</a></p>
@endsection
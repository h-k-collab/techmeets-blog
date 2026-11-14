@extends('layouts.blog')

@section('title', 'タスク管理')

@section('content')
    <h1>タスク管理</h1>

    @if ($errors->any())
        <div class="errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 追加フォーム --}}
    <div class="card">
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <label>タスク名</label>
            <input type="text" name="title" value="{{ old('title') }}">

            <label>期限(任意)</label>
            <input type="date" name="due_date" value="{{ old('due_date') }}">

            <button type="submit" class="btn">追加する</button>
        </form>
    </div>

    {{-- 一覧 --}}
    @forelse ($tasks as $task)
        <div class="card">
            @if ($task->is_completed)
                <s>{{ $task->title }}</s>
            @else
                {{ $task->title }}
            @endif

            @if ($task->due_date)
                <small>(期限:{{ $task->due_date->format('Y年m月d日') }})</small>
            @endif

            <form action="{{ route('tasks.toggle', $task) }}" method="POST" style="display:inline;">
                @csrf
                @method('PATCH')
                <button type="submit">{{ $task->is_completed ? '未完了に戻す' : '完了にする' }}</button>
            </form>

            <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline;"
                  onsubmit="return confirm('このタスクを削除しますか?');">
                @csrf
                @method('DELETE')
                <button type="submit">削除</button>
            </form>
        </div>
    @empty
        <p>まだタスクがありません。</p>
    @endforelse
@endsection
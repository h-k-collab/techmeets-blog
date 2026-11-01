@extends('layouts.blog')

@section('title', $post->title)

@section('content')
    <h1>{{ $post->title }}</h1>

    <p>カテゴリ:{{ $post->category }}</p>
    <p>投稿日:{{ $post->created_at->format('Y年m月d日') }}</p>

    <div>{!! nl2br(e($post->content)) !!}</div>

    <hr>

    <a href="{{ route('posts.index') }}">一覧へ戻る</a>

    @if (auth()->id() == $post->user_id)
        <a href="{{ route('posts.edit', $post) }}">編集</a>

        <form action="{{ route('posts.destroy', $post) }}" method="POST"
              style="display:inline;"
              onsubmit="return confirm('本当に削除しますか?');">
            @csrf
            @method('DELETE')
            <button type="submit">削除</button>
        </form>
    @endif

    <hr>

    <h2>コメント</h2>

    {{-- コメント一覧:ログインしていなくても見られる --}}
    @forelse ($post->comments()->with('user')->latest()->get() as $comment)
        <div style="border:1px solid #ccc; padding:8px; margin-bottom:8px;">
            <p>
                <strong>{{ $comment->user->name }}</strong>
                <small>{{ $comment->created_at->format('Y年m月d日 H:i') }}</small>
            </p>
            <div>{!! nl2br(e($comment->content)) !!}</div>

            {{-- 自分のコメントにだけ、削除ボタン --}}
            @if (auth()->id() == $comment->user_id)
                <form action="{{ route('comments.destroy', $comment) }}" method="POST"
                      onsubmit="return confirm('このコメントを削除しますか?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit">削除</button>
                </form>
            @endif
        </div>
    @empty
        <p>まだコメントがありません。</p>
    @endforelse

    {{-- 書き込みフォーム:ログインしている人だけ --}}
    @auth
        <h3>コメントを書く</h3>

        @if ($errors->any())
            <ul style="color:red;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('comments.store', $post) }}" method="POST">
            @csrf
            <p>
                <textarea name="content" rows="4" cols="50">{{ old('content') }}</textarea>
            </p>
            <button type="submit">コメントする</button>
        </form>
    @else
        <p><a href="{{ route('login') }}">ログイン</a>すると、コメントを書き込めます。</p>
    @endauth
@endsection
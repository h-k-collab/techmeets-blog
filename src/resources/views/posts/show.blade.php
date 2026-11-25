{{-- 共通のレイアウト(ヘッダーなど)を使う --}}
@extends('layouts.blog')

{{-- ブラウザのタブに出るタイトル(記事のタイトル) --}}
@section('title', $post->title)

{{-- ここから、ページの本文 --}}
@section('content')
    {{-- 記事のタイトル --}}
    <h1>{{ $post->title }}</h1>

    {{-- カテゴリと、投稿日 --}}
    <p>カテゴリ:{{ $post->category }}</p>
    <p>投稿日:{{ $post->created_at->format('Y年m月d日') }}</p>

    {{-- 本文:e() で特殊文字を無害化(XSS 対策)してから、改行を <br> に変えて表示する --}}
    <div>{!! nl2br(e($post->content)) !!}</div>

    {{-- 画像(S3):画像のパスがある投稿だけ表示する --}}
    @if ($post->image_path)
        <p>
            {{-- 10分だけ有効な一時的な URL を作って、非公開の S3 の画像を表示する --}}
            <img src="{{ Storage::disk('s3')->temporaryUrl($post->image_path, now()->addMinutes(10)) }}"
                 alt="{{ $post->title }}" style="max-width:100%;">
        </p>
    @endif

    <hr>

    {{-- 記事の一覧に戻るリンク --}}
    <a href="{{ route('posts.index') }}">一覧へ戻る</a>

    {{-- 編集・削除:この記事を書いた本人にだけ表示する --}}
    @if (auth()->id() == $post->user_id)
        {{-- 編集画面へのリンク --}}
        <a href="{{ route('posts.edit', $post) }}">編集</a>

        {{-- 削除フォーム:押すと、確認のダイアログが出てから送信される --}}
        <form action="{{ route('posts.destroy', $post) }}" method="POST"
              style="display:inline;"
              onsubmit="return confirm('本当に削除しますか?');">
            {{-- CSRF 対策のトークン --}}
            @csrf
            {{-- HTML のフォームは POST しか送れないので、「本当は DELETE」と Laravel に伝える --}}
            @method('DELETE')
            <button type="submit">削除</button>
        </form>
    @endif

    {{-- 応援ボタン:ログインしている人にだけ表示する --}}
    @auth
        {{-- 押すと、supports.create のルートに POST で送られる($post は、どの記事かを示す) --}}
        <form method="POST" action="{{ route('supports.create', $post) }}">
            {{-- CSRF 対策のトークン(Laravel の POST フォームには必須) --}}
            @csrf
            <button type="submit">この記事の書き手を応援する</button>
        </form>
    @endauth

    <hr>

    {{-- ここから、コメント欄 --}}
    <h2>コメント</h2>

    {{-- コメント一覧:ログインしていなくても見られる。ユーザー情報も一緒に取って(with)、新しい順に並べる --}}
    @forelse ($post->comments()->with('user')->latest()->get() as $comment)
        <div style="border:1px solid #ccc; padding:8px; margin-bottom:8px;">
            <p>
                {{-- コメントを書いた人の名前と、書いた日時 --}}
                <strong>{{ $comment->user->name }}</strong>
                <small>{{ $comment->created_at->format('Y年m月d日 H:i') }}</small>
            </p>
            {{-- コメントの本文(XSS 対策をして、改行を <br> に変える) --}}
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
    {{-- コメントが1件もないときの表示 --}}
    @empty
        <p>まだコメントがありません。</p>
    @endforelse

    {{-- 書き込みフォーム:ログインしている人だけ --}}
    @auth
        <h3>コメントを書く</h3>

        {{-- 入力エラーがあれば、赤い文字で一覧にして表示する --}}
        @if ($errors->any())
            <ul style="color:red;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        {{-- コメントの送信フォーム(この記事 $post に対して送る) --}}
        <form action="{{ route('comments.store', $post) }}" method="POST">
            @csrf
            <p>
                {{-- old('content') は、エラーで戻ったときに、入力した内容を残す --}}
                <textarea name="content" rows="4" cols="50">{{ old('content') }}</textarea>
            </p>
            <button type="submit">コメントする</button>
        </form>
    {{-- ログインしていない人には、ログインを促すメッセージを出す --}}
    @else
        <p><a href="{{ route('login') }}">ログイン</a>すると、コメントを書き込めます。</p>
    @endauth
@endsection
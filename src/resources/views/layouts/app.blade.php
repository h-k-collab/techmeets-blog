<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TechMeets Blog')</title>
    <style>
        body { margin: 0; font-family: sans-serif; background: #f5f5f5; color: #333; }
        header { background: #2c3e50; padding: 16px; }
        header a { color: #fff; text-decoration: none; margin-right: 16px; }
        main { max-width: 800px; margin: 24px auto; padding: 0 16px; }
        .flash { background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 16px; }
        .errors { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 16px; }
        .card { background: #fff; padding: 16px; border-radius: 4px; margin-bottom: 12px; }
        .btn { display: inline-block; background: #3498db; color: #fff; padding: 8px 16px; border: none; border-radius: 4px; text-decoration: none; cursor: pointer; }
        label { display: block; margin-bottom: 4px; font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; box-sizing: border-box; margin-bottom: 16px; }
        .pagination { display: flex; gap: 8px; list-style: none; padding: 0; }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('posts.index') }}">TechMeets Blog</a>
        <a href="{{ route('posts.create') }}">新規投稿</a>
    </header>

    <main>
        @if (session('success'))
            <div class="flash">{{ session('success') }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>

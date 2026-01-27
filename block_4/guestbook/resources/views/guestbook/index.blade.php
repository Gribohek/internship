<!-- resources/views/guestbook/index.blade.php -->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Гостевая страница</title>
    <style>
        body { font-family: Arial; max-width: 800px; margin: 0 auto; padding: 20px; }
        .post { border: 1px solid #ccc; padding: 15px; margin-bottom: 15px; }
        .post-date { color: #666; font-size: 0.9em; }
        form input, form textarea { width: 100%; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>Гостевая страница</h1>

    <form method="POST" action="/posts">
        @csrf
        <input type="text" name="author_name" placeholder="Ваше имя" required><br>
        <textarea name="content" placeholder="Ваше сообщение" rows="4" required></textarea><br>
        <button type="submit">Отправить</button>
    </form>

    <h2>Сообщения:</h2>
    
    @foreach($posts as $post)
        <div class="post">
            <strong>{{ $post->author_name }}</strong>
            <div class="post-date">{{ $post->created_at->format('d.m.Y H:i') }}</div>
            <p>{{ $post->content }}</p>
        </div>
    @endforeach
</body>
</html>
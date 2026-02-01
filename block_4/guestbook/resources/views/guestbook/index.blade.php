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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
       <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Гостевая страница</h1>

    <form method="POST" action="{{ route('guestbook.store') }}" id="add-post-form" >
        @csrf
        <input type="text" name="author_name" id="author_name" placeholder="Ваше имя" required><br>
        <textarea name="content" id="content" placeholder="Ваше сообщение" rows="4" required></textarea><br>
        <button type="submit" >Отправить</button>
    </form>

    <h2>Сообщения:</h2>
    <button type="button"  id="refresh-btn" >Обновить список</button>
    <div id="posts-container">
    @foreach($posts as $post)
        <div class="post" data-post-id="{{ $post->id }}">>
            <strong>{{ $post->author_name }}</strong>
            <div class="post-date">{{ $post->created_at->format('d.m.Y H:i') }}</div>
            <p>{{ $post->content }}</p>
                   <button type="button" class="delete-btn" data-post-id="{{ $post->id }}" >Удалить</button>
        </div>
    @endforeach
</div>
 <script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("#add-post-form").submit(function(event) {
            event.preventDefault();
            
            let formData = {
                author_name: $("#author_name").val(),
                content: $("#content").val(),
            };
            
            $.ajax({
                url: "{{ route('guestbook.store') }}",
                type: "POST",
                data: formData,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $('#add-post-form')[0].reset();
                        alert('Пост успешно добавлен!');
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert('Ошибка при добавлении поста');
                }
            });
        });
        $(document).on("click", ".delete-btn", function() {
            let postId = $(this).data("post-id");
            let postElement = $(this).closest('.post');
            
            if (!confirm('Удалить этот пост?')) {
                return;
            }

            $.ajax({
                url: "{{ route('guestbook.delete', ':id') }}".replace(':id', postId),
                type: "DELETE",
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        postElement.remove();
                        alert('Пост успешно удален');
                    }
                },
                error: function(xhr) {
                    alert('Ошибка при удалении поста');
                    console.log(xhr.responseText);
                }
            });
        });
            $("#refresh-btn").click(function(){
                refreshPosts();
            });
           function refreshPosts (){ 
            $.ajax({
            url:"{{ route('guestbook.update') }}",
            type: "GET",
            dataType:"json",
            success: function(response) {
                    if (response.success) {
                        $('#posts-container').html(response.html);
                    }
                },
                error: function() {
                    alert('Ошибка при обновлении');
                }
           })}
    });
</script>
</body>
</html>

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

@if($posts->isEmpty())
<div class="alert alert-info">
    Пока нет постов
</div>
@endif
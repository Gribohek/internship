<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Hashtag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
  public function index(Request $request)
{
    $user = $request->user() ?? User::findOrFail(1);
    $posts = Post::feedForUser($user->id)
        ->withFollowStatus($user->id)
        ->with([
            'user' => function ($q) {
                $q->withCount(['followers', 'following']);
            },
            'hashtags',
            'mentions'
        ])
        ->latest()
        ->paginate(20);
    $posts->through(function ($post) {
        $post->user->is_following = $post->is_following ?? false;
        return $post;
    });
    return response()->json($posts);
}
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:280'
        ]);
        $user = $request->user() ?? User::find(1);
        DB::beginTransaction();
        try {
            $post = Post::create([
                'user_id' => $user->id,
                'content' => $request->content
            ]);
            $this->syncHashtags($post, $request->content);
            $this->syncMentions($post, $request->content);
            DB::commit();
            $post->load(['user', 'hashtags', 'mentions']);
            $post->user->is_following = false; 
            $post->user->followers_count = $post->user->followers()->count();
            $post->user->following_count = $post->user->following()->count();

            return response()->json($post, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Ошибка при создании поста'], 500);
        }
    }
 public function userPosts($username, Request $request)
{
    $currentUser = $request->user() ?? User::findOrFail(1);

    $user = User::where('username', $username)
        ->withCount(['followers', 'following', 'posts'])
        ->firstOrFail();

    $posts = Post::where('user_id', $user->id)
        ->orWhereHas('mentions', fn($q) =>
            $q->where('mentioned_user_id', $user->id)
        )
        ->with([
            'user' => fn($q) => $q->withCount(['followers', 'following']),
            'hashtags',
            'mentions'
        ])
        ->latest()
        ->get();
    $followingIds = DB::table('follows')
        ->where('follower_id', $currentUser->id)
        ->pluck('following_id')
        ->toArray();

    foreach ($posts as $post) {
        $post->user->is_following = in_array($post->user_id, $followingIds);
    }

    $isFollowing = in_array($user->id, $followingIds);

    return response()->json([
        'user' => [
            'id' => $user->id,
            'username' => $user->username,
            'name' => $user->name,
            'is_following' => $isFollowing,
            'followers_count' => $user->followers_count,
            'following_count' => $user->following_count,
            'posts_count' => $user->posts_count
        ],
        'posts' => $posts
    ]);
}

    public function hashtagPosts($hashtag, Request $request)
    {
        $currentUser = $request->user() ?? User::find(1);
        $hashtagModel = Hashtag::where('name', strtolower($hashtag))->firstOrFail();
        
        $posts = $hashtagModel->posts()
            ->withFollowStatus($currentUser->id)
            ->with(['user', 'hashtags', 'mentions'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);


        $posts->through(function($post) use ($currentUser) {
            $post->user->is_following = $post->is_following ?? false;
            return $post;
        });

        return response()->json([
            'hashtag' => $hashtagModel,
            'posts' => $posts
        ]);
    }
private function syncHashtags(Post $post, string $content): void
{
    preg_match_all('/#([\w\p{Cyrillic}]+)/u', $content, $matches);

    $tagNames = collect($matches[1] ?? [])
        ->map(fn($tag) => strtolower($tag))
        ->unique()
        ->values();

    if ($tagNames->isEmpty()) {
        return;
    }
    $existing = Hashtag::whereIn('name', $tagNames)->get()->keyBy('name');

    $newTags = $tagNames->diff($existing->keys());

    if ($newTags->isNotEmpty()) {
        $insertData = $newTags->map(fn($name) => ['name' => $name])->toArray();
        Hashtag::insert($insertData);

        $existing = Hashtag::whereIn('name', $tagNames)->get()->keyBy('name');
    }

    $post->hashtags()->sync($existing->pluck('id'));
}

   private function syncMentions(Post $post, string $content): void
{
    preg_match_all('/@([\w\p{Cyrillic}]+)/u', $content, $matches);
    $usernames = collect($matches[1] ?? [])
        ->unique()
        ->values();
    if ($usernames->isEmpty()) {
        return;
    }
    $users = User::whereIn('username', $usernames)
        ->where('id', '!=', $post->user_id)
        ->pluck('id');
    $post->mentions()->sync($users);
}

}
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
        $user = User::find(1);
        
        $posts = Post::whereIn('user_id', function($query) use ($user) {
            $query->select('following_id')
                  ->from('follows')
                  ->where('follower_id', $user->id);
        })
        ->orWhere('user_id', $user->id)
        ->orWhereIn('id', function($query) use ($user) {
            $query->select('post_id')
                  ->from('mentions')
                  ->where('mentioned_user_id', $user->id);
        })
        ->with(['user', 'hashtags', 'mentions'])
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:280'
        ]);

        $user = User::find(1);

        DB::beginTransaction();

        try {
            $post = Post::create([
                'user_id' => $user->id,
                'content' => $request->content
            ]);
            preg_match_all('/#([\w\p{Cyrillic}]+)/u', $request->content, $hashtagMatches);
            
            foreach ($hashtagMatches[1] ?? [] as $tagName) {
                $tagName = strtolower($tagName);
                $hashtag = Hashtag::firstOrCreate(['name' => $tagName]);
                $post->hashtags()->attach($hashtag->id);
            }

            preg_match_all('/@([\w\p{Cyrillic}]+)/u', $request->content, $mentionMatches);
            
            foreach ($mentionMatches[1] ?? [] as $username) {
                $mentionedUser = User::where('username', $username)->first();
                if ($mentionedUser) {
                    DB::table('mentions')->insert([
                        'post_id' => $post->id,
                        'mentioned_user_id' => $mentionedUser->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            DB::commit();

            return response()->json($post->load(['user', 'hashtags', 'mentions']), 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Ошибка при создании поста'], 500);
        }
    }

  public function userPosts($username)
{
    $user = User::where('username', $username)->first();
    
    if (!$user) {
        return response()->json([
            'message' => 'Пользователь не найден'
        ], 404);
    }
    
    $posts = Post::where('user_id', $user->id)
        ->orWhereIn('id', function($query) use ($user) {
            $query->select('post_id')
                  ->from('mentions')
                  ->where('mentioned_user_id', $user->id);
        })
        ->with(['user', 'hashtags'])
        ->orderBy('created_at', 'desc')
        ->get();
    

    return response()->json([
        'user' => $user,
        'posts' => $posts
    ]);
}

    public function hashtagPosts($hashtag)
{
    $hashtagModel = Hashtag::where('name', strtolower($hashtag))->first();
    
    if (!$hashtagModel) {
        return response()->json([
            'message' => 'Хэштег не найден'
        ], 404);
    }
    
    $posts = $hashtagModel->posts()
        ->with(['user', 'hashtags', 'mentions'])
        ->orderBy('created_at', 'desc')
        ->paginate(20);

    
    return response()->json([
        'hashtag' => $hashtagModel,
        'posts' => $posts
    ]);
}    
}
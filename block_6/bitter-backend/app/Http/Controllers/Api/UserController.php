<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)
            ->withCount(['posts', 'followers', 'following'])
            ->firstOrFail();
        
        return response()->json($user);
    }


    public function follow(Request $request, $username)
    {
        $userToFollow = User::where('username', $username)->firstOrFail();
        $currentUser = User::find(1);

        if ($currentUser->id === $userToFollow->id) {
            return response()->json(['message' => 'Нельзя подписаться на себя'], 400);
        }
        $currentUser->following()->syncWithoutDetaching([$userToFollow->id]);
        return response()->json(['message' => 'Подписка оформлена']);
    }

    public function unfollow(Request $request, $username)
    {
        $userToUnfollow = User::where('username', $username)->firstOrFail();
        $currentUser = User::find(1);
        $currentUser->following()->detach($userToUnfollow->id);

        return response()->json(['message' => 'Подписка отменена']);
    }

    public function followers($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $followers = $user->followers()->paginate(20);
        
        return response()->json($followers);
    }

    public function following($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $following = $user->following()->paginate(20);
        
        return response()->json($following);
    }
}
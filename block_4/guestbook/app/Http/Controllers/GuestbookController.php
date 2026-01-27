<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class GuestbookController extends Controller
{
    public function index(){
        $posts = Post::latest()->get();
        return view('guestbook.index', compact('posts'));
    }
   public function store(Request $request)
{
    $validated = $request->validate([
        'author_name' => 'required|string|max:100',
        'content' => 'required|string|max:1000'  
    ]);
    
    Post::create($validated);
    return redirect()->route('guestbook.index')->with('success', 'Пост успешно добавлен!');
}
}

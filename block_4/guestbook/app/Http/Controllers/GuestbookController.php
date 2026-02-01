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
    
   $post=Post::create($validated);
   if($request->ajax())
    {
        return response()->json([
            'success' => true,
            'message' => 'Пост успешно добавлен!',
            'data' => $post 
        ]);
    }
    return redirect()->route('guestbook.index')->with('success', 'Пост успешно добавлен!');
}

    public function delete(int $id){
           $post=Post::find($id);
          if (!$post) {
            return response()->json(['success' => false], 404);
        }
        
        $post->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Пост успешно добавлен'
        ]
        );
    }
       public function update(Request $request){
            $posts = Post::latest()->get();
        $html = view('guestbook.posts-list', compact('posts'))->render();
        
        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }
}

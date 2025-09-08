<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->paginate(2);
        return view('posts.index', compact('posts'));
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.show', compact('post'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string|min:10',
        ]);

        // дописываем user_id вручную
        $validated['user_id'] = auth()->id();

        Post::create($validated);

        return redirect('/posts')->with('success', 'Пост успешно создан!');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.update', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string|min:10',
        ]);

        $validated['user_id'] = auth()->id();

        $post = Post::findOrFail($id);

        $post -> update($validated);

        return redirect('/posts')->with('success_', 'Пост успешно отредактирован!');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect('/posts')->with('success', 'Пост удален');
    }
}

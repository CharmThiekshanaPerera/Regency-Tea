<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(): View
    {
        return view('media.index', [
            'posts'      => Post::published()->with('categories')->latest('published_at')->paginate(12),
            'categories' => PostCategory::withCount('posts')->orderBy('name')->get(),
        ]);
    }

    public function category(PostCategory $postCategory): View
    {
        return view('media.index', [
            'posts'      => $postCategory->posts()->published()->latest('published_at')->paginate(12),
            'categories' => PostCategory::withCount('posts')->orderBy('name')->get(),
            'current'    => $postCategory,
        ]);
    }

    public function show(Post $post): View
    {
        return view('media.show', [
            'post'    => $post->load('categories'),
            'related' => Post::published()
                ->where('id', '!=', $post->id)
                ->latest('published_at')->take(3)->get(),
        ]);
    }
}

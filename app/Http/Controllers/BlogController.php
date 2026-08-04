<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Contracts\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = BlogPost::query()
            ->published()
            ->latest('published_at')
            ->paginate(9);

        return view('frontend.pages.blog', [
            'posts' => $posts,
        ]);
    }

    public function show(string $slug): View
    {
        $post = BlogPost::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('frontend.pages.blog-show', [
            'post' => $post,
        ]);
    }
}

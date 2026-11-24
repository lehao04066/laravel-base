<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class ContentHubController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('category')
            ->where('is_published', true)
            ->where(function ($builder) {
                $builder->whereNull('published_at')->orWhere('published_at', '<=', now());
            })
            ->latest('published_at');

        if ($request->filled('q')) {
            $keyword = $request->string('q')->toString();
            $query->where(function ($builder) use ($keyword) {
                $builder->where('title', 'like', "%{$keyword}%")
                    ->orWhere('summary', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($category) => $category->where('slug', $request->string('category')->toString()));
        }

        $posts = $query->paginate(6)->withQueryString();
        $categories = PostCategory::withCount(['posts' => fn ($post) => $post->where('is_published', true)])
            ->orderBy('name')->get();

        return view('customer.hub', compact('posts', 'categories'));
    }

    public function show(Post $post)
    {
        abort_unless($post->is_published && (!$post->published_at || $post->published_at->isPast()), 404);
        $post->load('category');

        $relatedPosts = Post::with('category')
            ->where('is_published', true)
            ->whereKeyNot($post->id)
            ->when($post->post_category_id, fn ($query) => $query->where('post_category_id', $post->post_category_id))
            ->latest('published_at')->take(3)->get();

        return view('customer.hub_detail', compact('post', 'relatedPosts'));
    }
}

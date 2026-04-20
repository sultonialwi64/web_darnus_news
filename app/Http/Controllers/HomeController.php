<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::all();
        
        $allPosts = Post::with(['category', 'region'])
            ->where('is_published', true)
            ->latest()
            ->get();
            
        $featuredPost = $allPosts->where('is_featured', true)->first() ?? $allPosts->first();
        $latestPosts = $allPosts->reject(function ($post) use ($featuredPost) {
            return $featuredPost && $post->id === $featuredPost->id;
        })->take(4);
        
        $popularPosts = clone $allPosts;
        $popularPosts = $popularPosts->sortByDesc('views')->take(5);

        // Use pagination for the rest
        $otherPosts = Post::with(['category', 'region'])
            ->where('is_published', true)
            ->when($featuredPost, function($q) use ($featuredPost) {
                return $q->where('id', '!=', $featuredPost->id);
            })
            ->when($latestPosts->count() > 0, function($q) use ($latestPosts) {
                return $q->whereNotIn('id', $latestPosts->pluck('id'));
            })
            ->latest()
            ->paginate(12);

        return view('welcome', compact('categories', 'featuredPost', 'latestPosts', 'popularPosts', 'otherPosts'));
    }

    public function show($slug)
    {
        $categories = \App\Models\Category::all();
        $post = Post::with(['category', 'region', 'author', 'editor'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $post->increment('views');

        // Ambil 4 berita terkait dari kategori yang sama
        $relatedPosts = Post::with(['category', 'region'])
            ->where('is_published', true)
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(4)
            ->get();

        return view('news.show', compact('post', 'categories', 'relatedPosts'));
    }

    public function search(\Illuminate\Http\Request $request)
    {
        $categories = \App\Models\Category::all();
        $query = $request->input('q');

        $posts = collect();
        if ($query) {
            $posts = Post::with(['category', 'region'])
                ->where('is_published', true)
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('content', 'like', "%{$query}%")
                      ->orWhereHas('category', function($q) use ($query) {
                          $q->where('name', 'like', "%{$query}%");
                      })
                      ->orWhereHas('region', function($q) use ($query) {
                          $q->where('name', 'like', "%{$query}%");
                      });
                })
                ->latest()
                ->paginate(12);
        }

        return view('news.search', compact('posts', 'categories', 'query'));
    }
}

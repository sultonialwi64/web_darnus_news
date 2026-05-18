<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Cache categories agar tidak nge-query DB terus tiap kali ada yang buka beranda
        $categories = \Illuminate\Support\Facades\Cache::remember('categories_v2', 3600, function() {
            return \App\Models\Category::all();
        });
        
        $featuredPost = Post::with(['category', 'region'])
            ->live()
            ->where('is_featured', true)
            ->latest('published_at')
            ->orderByDesc('id')
            ->first();

        // Jika tidak ada featured post khusus, ambil berita terbaru
        if (!$featuredPost) {
            $featuredPost = Post::with(['category', 'region'])
                ->live()
                ->latest('published_at')
                ->orderByDesc('id')
                ->first();
        }

        // Ambil 4 berita terbaru untuk grid
        $latestPosts = collect();
        if ($featuredPost) {
            $latestPosts = Post::with(['category', 'region'])
                ->live()
                ->where('id', '!=', $featuredPost->id)
                ->latest('published_at')
                ->orderByDesc('id')
                ->take(4)
                ->get();
        }

        // Ambil 5 berita terpopuler langsung dari database, cache selama 1 jam
        $popularPosts = \Illuminate\Support\Facades\Cache::remember('popular_posts_v2', 3600, function() {
            return Post::live()
                ->orderByDesc('views')
                ->take(5)
                ->get();
        });

        // Ambil sisa berita untuk bagian bawah
        $excludeIds = collect([$featuredPost?->id])->merge($latestPosts->pluck('id'))->filter();
        
        $otherPosts = Post::with(['category', 'region'])
            ->live()
            ->whereNotIn('id', $excludeIds)
            ->latest('published_at')
            ->orderByDesc('id')
            ->paginate(12);

        return view('welcome', compact('categories', 'featuredPost', 'latestPosts', 'popularPosts', 'otherPosts'));
    }

    public function show($slug)
    {
        $categories = \App\Models\Category::all();
        $post = Post::with(['category', 'region', 'author', 'editor'])
            ->where('slug', $slug)
            ->live()
            ->firstOrFail();

        $post->increment('views');

        $manualRelatedIds = $post->related_posts ?? [];
        $relatedPosts = collect();
        
        if (!empty($manualRelatedIds)) {
            $relatedPosts = Post::with(['category', 'region'])
                ->whereIn('id', $manualRelatedIds)
                ->live()
                ->get()
                ->sortBy(fn($item) => array_search($item->id, $manualRelatedIds));
        }
        
        if ($relatedPosts->count() < 4) {
            $extraRelated = Post::with(['category', 'region'])
                ->live()
                ->where('category_id', $post->category_id)
                ->where('id', '!=', $post->id)
                ->whereNotIn('id', $relatedPosts->pluck('id'))
                ->latest('published_at')
                ->take(4 - $relatedPosts->count())
                ->get();
                
            $relatedPosts = $relatedPosts->concat($extraRelated);
        }

        return view('news.show', compact('post', 'categories', 'relatedPosts'));
    }

    public function search(Request $request)
    {
        $categories = \App\Models\Category::all();
        $query = $request->input('q');
        $categorySlug = $request->input('category');
        $currentCategory = null;

        $postsQuery = Post::with(['category', 'region'])->live();

        if ($categorySlug) {
            $currentCategory = \App\Models\Category::where('slug', $categorySlug)->first();
            if ($currentCategory) {
                $postsQuery->where('category_id', $currentCategory->id);
            }
        }

        if ($query) {
            $postsQuery->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            });
        }

        $posts = $postsQuery->latest('published_at')->paginate(12);

        return view('news.search', compact('posts', 'categories', 'query', 'currentCategory'));
    }

    public function about()
    {
        $categories = \App\Models\Category::all();
        return view('pages.about', compact('categories'));
    }

    public function editorial()
    {
        $categories = \App\Models\Category::all();
        return view('pages.editorial', compact('categories'));
    }

    public function tag($slug)
    {
        $categories = \App\Models\Category::all();
        $tag = \App\Models\Tag::where('slug', $slug)->firstOrFail();
        $posts = $tag->posts()
            ->with(['category', 'region'])
            ->live()
            ->latest('published_at')
            ->orderByDesc('id')
            ->paginate(12);
        
        return view('news.tag', compact('tag', 'posts', 'categories'));
    }
}

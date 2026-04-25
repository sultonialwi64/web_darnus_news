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

        // Ambil berita terkait (Prioritas Manual -> Fallback Kategori)
        $manualRelatedIds = $post->related_posts ?? [];
        $relatedPosts = collect();
        
        if (!empty($manualRelatedIds)) {
            $relatedPosts = Post::with(['category', 'region'])
                ->whereIn('id', $manualRelatedIds)
                ->where('is_published', true)
                ->get()
                ->sortBy(fn($item) => array_search($item->id, $manualRelatedIds));
        }
        
        if ($relatedPosts->count() < 4) {
            $extraRelated = Post::with(['category', 'region'])
                ->where('is_published', true)
                ->where('category_id', $post->category_id)
                ->where('id', '!=', $post->id)
                ->whereNotIn('id', $relatedPosts->pluck('id'))
                ->latest()
                ->take(4 - $relatedPosts->count())
                ->get();
                
            $relatedPosts = $relatedPosts->concat($extraRelated);
        }

        return view('news.show', compact('post', 'categories', 'relatedPosts'));
    }

    public function search(\Illuminate\Http\Request $request)
    {
        $categories = \App\Models\Category::all();
        $query = $request->input('q');
        $categorySlug = $request->input('category');
        $currentCategory = null;

        $postsQuery = Post::with(['category', 'region'])->where('is_published', true);

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

        $posts = $postsQuery->latest()->paginate(12);

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
            ->where('is_published', true)
            ->latest()
            ->paginate(12);
        
        return view('news.tag', compact('tag', 'posts', 'categories'));
    }
}

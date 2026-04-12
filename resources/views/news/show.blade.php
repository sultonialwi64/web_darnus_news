<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $post->title }} - Darnus News</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-ui { font-family: 'Inter', sans-serif; }
        .font-serif-news { font-family: 'PT Serif', serif; }
        
        .article-body p {
            margin-bottom: 1.5rem;
            font-size: 1.125rem;
            line-height: 1.8;
            color: #333;
        }
        .article-body h2 {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            color: #111;
        }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased min-h-screen flex flex-col font-ui">

    <!-- Top Navy Header -->
    <header class="bg-[#0b1c34] text-white">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Left: Search & Newsletters -->
                <div class="flex items-center space-x-6 text-sm font-semibold tracking-wide">
                    <form action="{{ route('search') }}" method="GET" class="flex items-center bg-[#152844] rounded-full px-3 py-1">
                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" name="q" placeholder="Search..." class="bg-transparent text-white focus:outline-none w-32 text-sm">
                    </form>
                </div>

                <!-- Center: Logo -->
                <div class="text-center">
                    <a href="{{ route('home') }}" class="font-ui text-2xl tracking-[0.2em] font-bold uppercase hover:opacity-80 transition-opacity">
                        DARNUSNEWS
                    </a>
                </div>

                <!-- Right: Account/Login -->
                <div class="flex items-center space-x-6 text-sm font-semibold">
                    <a href="{{ url('/admin') }}" class="flex items-center hover:text-gray-300 transition-colors">
                        <span class="hidden md:inline">Account</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Categories Navigation -->
    <nav class="border-b-2 border-gray-900 bg-white sticky top-0 z-40">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <ul class="flex flex-wrap items-center justify-center space-x-4 md:space-x-8 h-12 text-xs font-bold tracking-widest uppercase text-gray-800">
                @foreach($categories ?? [] as $cat)
                <li><a href="#" class="hover:text-blue-700 hover:underline underline-offset-4 decoration-2 {{ $post->category->id === $cat->id ? 'text-blue-700 underline' : '' }}">{{ $cat->name }}</a></li>
                @endforeach
            </ul>
        </div>
    </nav>


    <main class="flex-grow max-w-[800px] mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full">
        <article>
            <div class="mb-8 text-center">
                <div class="flex items-center justify-center space-x-2 text-xs font-bold tracking-widest uppercase text-blue-800 mb-4">
                    <span>{{ $post->category->name }}</span>
                    <span class="text-gray-400">&bull;</span>
                    <span class="text-gray-600">{{ $post->region->name }}</span>
                </div>
                
                <h1 class="font-serif-news text-4xl md:text-5xl lg:text-6xl font-bold leading-tight text-gray-900 mb-6">
                    {{ $post->title }}
                </h1>
                
                <div class="flex justify-center items-center text-sm text-gray-500 font-semibold uppercase tracking-wider space-x-4 border-b border-gray-200 pb-8">
                    <span>Published {{ $post->created_at->format('F d, Y') }}</span>
                </div>
            </div>

            @if($post->image)
            <figure class="mb-12">
                <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full object-cover aspect-[21/9] bg-gray-100">
                <figcaption class="mt-3 text-sm text-gray-500 font-serif-news italic text-right">
                    Image via DarnusNews / {{ $post->region->name }}
                </figcaption>
            </figure>
            @endif

            <div class="article-body font-serif-news prose prose-lg max-w-none prose-a:text-blue-700 hover:prose-a:text-blue-900">
                {!! $post->content !!}
            </div>
        </article>
    </main>

    <!-- Dark Footer -->
    <footer class="bg-[#0b1c34] text-white mt-24 py-16">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="mb-8 md:mb-0">
                    <h2 class="font-ui text-3xl font-bold tracking-[0.2em] uppercase mb-4">DARNUSNEWS</h2>
                    <p class="text-gray-400 font-serif-news italic">Global perspective, local intelligence.</p>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-700 text-sm text-gray-500">
                &copy; {{ date('Y') }} DarnusNews Media. All rights reserved. 
            </div>
        </div>
    </footer>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Darnus News - Berita Terkini</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .font-ui { font-family: 'Inter', sans-serif; }
        .font-serif-news { font-family: 'PT Serif', serif; }
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
                        <input type="text" name="q" placeholder="Search..." class="bg-transparent text-white focus:outline-none w-32 md:w-48 text-sm">
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
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
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
                <li><a href="#" class="hover:text-blue-700 hover:underline underline-offset-4 decoration-2">LATEST</a></li>
                @foreach($categories ?? [] as $cat)
                <li><a href="#" class="hover:text-blue-700 hover:underline underline-offset-4 decoration-2">{{ $cat->name }}</a></li>
                @endforeach
            </ul>
        </div>
    </nav>




    <main class="flex-grow max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
        <!-- Hero Section: Featured & Latest -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 mb-16">
            
            <!-- Left Column: Featured Post (70%) -->
            @if($featuredPost)
            <div class="lg:col-span-8 group cursor-pointer relative">
                <a href="{{ route('news.show', $featuredPost->slug) }}" class="block">
                    <!-- Image -->
                    <div class="w-full aspect-[16/9] mb-5 overflow-hidden bg-gray-100 object-cover">
                        @if($featuredPost->image)
                            <img src="{{ Storage::url($featuredPost->image) }}" alt="{{ $featuredPost->title }}" class="w-full h-full object-cover transition-opacity duration-300 group-hover:opacity-90">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 font-serif-news text-2xl">No Image Available</div>
                        @endif
                    </div>
                    
                    <!-- Metadata -->
                    <div class="flex items-center text-xs font-bold tracking-wider uppercase text-blue-800 mb-3">
                        <span class="mr-4">{{ $featuredPost->category->name }}</span>
                        <span class="text-gray-500">{{ $featuredPost->region->name }}</span>
                    </div>

                    <!-- Title -->
                    <h1 class="font-serif-news text-4xl leading-tight md:text-5xl md:leading-[1.1] font-bold text-gray-900 group-hover:text-blue-800 transition-colors mb-4">
                        {{ $featuredPost->title }}
                    </h1>
                    
                    <!-- Excerpt / Meta -->
                    <p class="font-serif-news text-lg text-gray-700 leading-relaxed mb-4 line-clamp-2">
                        {!! strip_tags($featuredPost->content) !!}
                    </p>
                    <time class="text-xs text-gray-500 font-semibold uppercase tracking-wider">
                        {{ $featuredPost->created_at->format('F d, Y') }}
                    </time>
                </a>
            </div>
            @endif

            <!-- Right Column: Latest Posts (30%) -->
            @if($latestPosts && count($latestPosts) > 0)
            <div class="lg:col-span-4 border-t-4 border-gray-900 pt-2 lg:border-t-0 lg:pt-0 lg:border-l lg:border-gray-200 lg:pl-8 flex flex-col h-full">
                <h2 class="text-2xl font-bold font-serif-news text-gray-900 mb-6 flex items-center">
                    Latest News
                </h2>
                <div class="flex-grow flex flex-col space-y-6">
                    @foreach($latestPosts as $post)
                    <div class="group border-b border-gray-100 pb-6 last:border-0 last:pb-0">
                        <a href="{{ route('news.show', $post->slug) }}" class="flex flex-col sm:flex-row lg:flex-col xl:flex-row gap-4">
                            @if($post->image)
                                <div class="w-full sm:w-1/3 lg:w-full xl:w-1/3 aspect-[4/3] bg-gray-100 flex-shrink-0">
                                    <img src="{{ Storage::url($post->image) }}" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                                </div>
                            @endif
                            <div class="flex-grow flex flex-col justify-center">
                                <h3 class="font-serif-news text-lg font-bold text-gray-900 leading-snug group-hover:text-blue-800 transition-colors">
                                    {{ $post->title }}
                                </h3>
                                <time class="text-[10px] uppercase text-gray-500 font-bold tracking-widest mt-2 block">
                                    {{ $post->created_at->diffForHumans() }}
                                </time>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Popular Posts Section -->
            @if($popularPosts && count($popularPosts) > 0)
            <div class="lg:col-span-4 lg:col-start-9 border-t-4 border-b border-gray-900 pt-2 pb-6 mt-8 lg:mt-0 lg:border-t-0 lg:border-b-0 lg:pt-0 lg:pb-0 lg:border-l lg:border-gray-200 lg:pl-8 flex flex-col h-full">
                <h2 class="text-2xl font-bold font-serif-news text-gray-900 mb-6 flex items-center text-red-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    Trending Now
                </h2>
                <div class="flex-grow flex flex-col space-y-4">
                    @foreach($popularPosts as $index => $post)
                    <div class="group flex items-start space-x-3">
                        <span class="text-3xl font-bold text-gray-200 mt-1">{{ $index + 1 }}</span>
                        <a href="{{ route('news.show', $post->slug) }}" class="flex-grow">
                            <h3 class="font-serif-news text-md font-bold text-gray-900 leading-snug group-hover:text-blue-800 transition-colors">
                                {{ $post->title }}
                            </h3>
                            <div class="flex items-center mt-1 space-x-2 text-[10px] uppercase text-gray-500 font-bold tracking-widest">
                                <span>{{ $post->category->name }}</span>
                                <span class="text-red-700 font-ui flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    {{ number_format($post->views) }}
                                </span>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Section Border -->
        @if($otherPosts && count($otherPosts) > 0)
        <div class="w-full border-t-2 border-gray-900 mt-8 pt-8">
            <h2 class="text-3xl font-bold font-serif-news text-gray-900 mb-8">More from DarnusNews</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-12">
                @foreach($otherPosts as $post)
                <div class="group relative">
                    <a href="{{ route('news.show', $post->slug) }}" class="block">
                        <div class="w-full aspect-[3/2] mb-4 bg-gray-100">
                            @if($post->image)
                                <img src="{{ Storage::url($post->image) }}" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                            @endif
                        </div>
                        <div class="text-[10px] font-bold tracking-wider uppercase text-blue-800 mb-2">
                            {{ $post->category->name }}
                        </div>
                        <h3 class="font-serif-news text-xl font-bold text-gray-900 leading-snug group-hover:text-blue-800 transition-colors mb-2">
                            {{ $post->title }}
                        </h3>
                        <time class="text-xs text-gray-500 font-semibold uppercase tracking-wider">
                            {{ $post->created_at->format('M d, Y') }}
                        </time>
                    </a>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-16 pt-8 border-t border-gray-200">
                {{ $otherPosts->links() }}
            </div>
        </div>
        @endif

    </main>

    <!-- Dark Footer -->
    <footer class="bg-[#0b1c34] text-white mt-auto py-16">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="mb-8 md:mb-0">
                    <h2 class="font-ui text-3xl font-bold tracking-[0.2em] uppercase mb-4">DARNUSNEWS</h2>
                    <p class="text-gray-400 font-serif-news italic">Global perspective, local intelligence.</p>
                </div>
                <div class="flex space-x-6 text-sm font-semibold tracking-wider uppercase">
                    <a href="#" class="hover:text-blue-300">About</a>
                    <a href="#" class="hover:text-blue-300">Contact</a>
                    <a href="#" class="hover:text-blue-300">Terms</a>
                    <a href="#" class="hover:text-blue-300">Privacy</a>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-700 text-sm text-gray-500">
                &copy; {{ date('Y') }} DarnusNews Media. All rights reserved. 
            </div>
        </div>
    </footer>
</body>
</html>

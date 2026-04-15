<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DarnusNews - Berita Terpercaya</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-sz { font-family: 'PT Serif', serif; }
        .hide-scroll-bar::-webkit-scrollbar { display: none; }
        .hide-scroll-bar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Editorial Color Palette (Deep Navy / Slate) */
        .border-editorial { border-color: #1E293B; } /* Slate 800 */
        .bg-editorial-dark { background-color: #0F172A; } /* Slate 900 */
        .bg-editorial-header { background-color: #020617; } /* Slate 950 */
        .bg-editorial-card { background-color: #1E293B; } /* Slate 800 */
        .text-editorial-muted { color: #94A3B8; } /* Slate 400 */
        .text-editorial-accent { color: #FBBF24; } /* Amber 400 - Gold Accent */
    </style>
</head>
<body class="bg-editorial-dark text-gray-100 antialiased min-h-screen flex flex-col selection:bg-gray-600 selection:text-white">

    <!-- Editorial Header (Süddeutsche Style) -->
    <header class="bg-editorial-header border-b border-editorial">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Top Utility Bar -->
            <div class="flex justify-between items-center h-20">
                <!-- Left: Menu & Search -->
                <div class="flex items-center space-x-6 w-1/3">
                    <button class="flex items-center text-gray-300 hover:text-white transition-colors">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                        <span class="text-sm tracking-widest uppercase hidden sm:inline">Menu</span>
                    </button>
                    <form action="{{ route('search') }}" method="GET" class="hidden md:flex items-center relative">
                        <svg class="w-5 h-5 text-gray-400 absolute left-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" name="q" placeholder="" class="bg-transparent border-b border-gray-600 pl-8 pb-1 text-sm focus:outline-none focus:border-white transition-colors w-32 focus:w-48 text-white">
                    </form>
                </div>

                <!-- Center: Newspaper Logo -->
                <div class="text-center w-1/3 flex justify-center">
                    <a href="{{ route('home') }}" class="font-sz text-3xl md:text-5xl font-bold tracking-tight hover:opacity-80 transition-opacity whitespace-nowrap">
                        DarnusNews
                    </a>
                </div>

                <!-- Right: Login -->
                <div class="flex items-center justify-end w-1/3 space-x-6">
                    <a href="{{ url('/admin') }}" class="text-xs font-semibold tracking-widest uppercase text-gray-300 hover:text-white transition-colors">
                        Login
                    </a>
                </div>
            </div>
            
            <!-- Category Nav (Top Aligned, Minimal) -->
            <nav class="h-12 flex items-center justify-center border-t border-editorial mt-2">
                <ul class="flex overflow-x-auto hide-scroll-bar space-x-6 md:space-x-10 text-[11px] font-semibold tracking-widest uppercase text-gray-400 pb-1">
                    <li><a href="#" class="hover:text-white transition-colors text-white">Beranda</a></li>
                    @foreach($categories ?? [] as $cat)
                    <li><a href="#" class="hover:text-white transition-colors whitespace-nowrap">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </header>

    <main class="flex-grow max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        
        <!-- Hero Section: Single Massive Feature (Like SZ) -->
        <div class="mb-16 border-b border-editorial pb-12">
            @if($featuredPost)
            <div class="group relative block w-full max-w-5xl mx-auto cursor-pointer">
                <a href="{{ route('news.show', $featuredPost->slug) }}" class="block">
                    <!-- Image Area -->
                    @if($featuredPost->image)
                        <div class="w-full relative bg-editorial-header border border-editorial">
                            <img src="{{ Storage::url($featuredPost->image) }}" alt="{{ $featuredPost->title }}" class="w-full h-[50vh] md:h-[60vh] object-cover mix-blend-luminosity opacity-80 group-hover:mix-blend-normal group-hover:opacity-100 transition-all duration-700">
                        </div>
                    @endif
                    
                    <!-- Overlapping Dark Title Card -->
                    <div class="bg-editorial-card p-6 md:p-10 mx-auto {{ $featuredPost->image ? '-mt-16 sm:-mt-24' : 'mt-8' }} relative z-10 w-11/12 max-w-4xl border border-editorial shadow-2xl">
                        <!-- Badges -->
                        <div class="flex items-center space-x-3 mb-4 text-[11px] font-bold tracking-widest uppercase">
                            <span class="text-editorial-accent bg-emerald-900/30 px-2 py-1">{{ $featuredPost->category->name }}</span>
                            <span class="text-gray-500">·</span>
                            <span class="text-gray-400">{{ $featuredPost->region->name }}</span>
                        </div>

                        <!-- Title -->
                        <h1 class="font-sz text-3xl sm:text-5xl lg:text-6xl font-bold leading-tight text-white mb-6 group-hover:text-editorial-accent transition-colors">
                            {{ $featuredPost->title }}
                        </h1>
                        
                        <!-- Excerpt & Meta -->
                        <div class="w-full h-px bg-editorial mb-6 border-b border-editorial block"></div>
                        <div class="flex flex-col md:flex-row md:items-end justify-between">
                            <p class="text-gray-400 text-base md:text-lg leading-relaxed max-w-2xl font-sz mb-4 md:mb-0 line-clamp-3">
                                {!! strip_tags($featuredPost->content) !!}
                            </p>
                            <div class="text-xs text-editorial-muted font-bold uppercase tracking-wider whitespace-nowrap md:ml-6 flex items-center justify-between md:flex-col md:items-end">
                                <span>{{ $featuredPost->created_at->format('d.m.Y') }}</span>
                                <span class="flex items-center mt-1">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    {{ number_format($featuredPost->views) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endif
        </div>

        <!-- 4 Column Sub-Heroes (Just In) -->
        @if($latestPosts && count($latestPosts) > 0)
        <div class="mb-16 border-b border-editorial pb-16">
            <h2 class="text-center font-sz text-3xl font-bold text-gray-200 mb-10 pb-4 border-b border-editorial mx-auto w-max px-12">Berita Terkini</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($latestPosts->take(4) as $post)
                <a href="{{ route('news.show', $post->slug) }}" class="group block h-full flex flex-col bg-editorial-card border border-editorial p-5 hover:border-gray-500 transition-colors">
                    @if($post->image)
                        <div class="w-full aspect-[4/3] mb-5 overflow-hidden bg-editorial-dark filter grayscale group-hover:grayscale-0 transition-all duration-500">
                            <img src="{{ Storage::url($post->image) }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <div class="flex flex-col flex-grow">
                        <div class="text-[10px] font-bold tracking-widest uppercase text-editorial-accent mb-3">
                            {{ $post->region->name }} <!-- SZ style uses location as pre-title -->
                        </div>
                        <h3 class="font-sz text-xl font-bold text-gray-100 leading-snug group-hover:text-editorial-accent transition-colors mb-4 pb-4 border-b border-editorial">
                            {{ $post->title }}
                        </h3>
                        <time class="mt-auto text-[10px] text-editorial-muted font-bold uppercase tracking-wider">
                            {{ $post->created_at->diffForHumans() }}
                        </time>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- List/Dense Layout for Trending and More -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <!-- Left Column: More Articles (70%) -->
            @if($otherPosts && count($otherPosts) > 0)
            <div class="lg:col-span-8 lg:border-r lg:border-editorial lg:pr-12">
                <h2 class="font-sz text-3xl font-bold flex items-center text-white mb-8">Berita Terpenting</h2>
                
                <div class="flex flex-col">
                    @foreach($otherPosts as $post)
                    <a href="{{ route('news.show', $post->slug) }}" class="group flex flex-col sm:flex-row items-center py-8 border-b border-editorial last:border-0 hover:bg-editorial-card transition-colors p-4 -mx-4 rounded">
                        @if($post->image)
                            <div class="w-full sm:w-1/3 aspect-[3/2] border border-editorial flex-shrink-0 sm:mr-6 mb-4 sm:mb-0 bg-editorial-dark filter grayscale group-hover:grayscale-0 transition-all duration-300">
                                <img src="{{ Storage::url($post->image) }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="flex-grow flex flex-col justify-center">
                            <div class="flex items-center text-[10px] font-bold tracking-widest uppercase mb-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-600 mr-2 opacity-80"></span>
                                <span class="text-gray-300 mr-2">{{ $post->category->name }}</span>
                                <span class="text-editorial-muted">{{ $post->region->name }}</span>
                            </div>
                            <h3 class="font-sz text-2xl font-bold text-gray-100 leading-snug group-hover:text-editorial-accent transition-colors mb-2">
                                {{ $post->title }}
                            </h3>
                            <p class="text-gray-400 font-sz text-base leading-relaxed line-clamp-2 mb-4">
                                {!! strip_tags($post->content) !!}
                            </p>
                            <time class="text-[10px] text-editorial-muted font-bold uppercase tracking-wider">
                                {{ $post->created_at->format('d.m.Y - H:i') }}
                            </time>
                        </div>
                    </a>
                    @endforeach
                </div>

                <!-- Pagination Elements -->
                <div class="mt-8 pt-6">
                    {{ $otherPosts->links() }}
                </div>
            </div>
            @endif

            <!-- Right Sidebar: Trending Now (30%) -->
            @if($popularPosts && count($popularPosts) > 0)
            <div class="lg:col-span-4">
                <h2 class="font-sz text-xl font-bold text-gray-300 mb-8 pb-4 border-b border-editorial tracking-wide uppercase">
                    Paling Banyak Dibaca
                </h2>
                <div class="flex flex-col">
                    @foreach($popularPosts->take(6) as $index => $post)
                    <a href="{{ route('news.show', $post->slug) }}" class="group flex items-start py-5 border-b border-editorial last:border-0 relative">
                        <span class="font-sz text-4xl italic font-bold text-gray-600 mr-4 flex-shrink-0 group-hover:text-editorial-accent transition-colors">{{ $index + 1 }}</span>
                        <div class="flex flex-col">
                            <span class="text-[10px] uppercase tracking-widest font-bold text-editorial-accent mb-1">{{ $post->category->name }}</span>
                            <h3 class="font-sz text-base font-bold text-gray-200 leading-snug group-hover:text-white transition-colors line-clamp-3 mb-2">
                                {{ $post->title }}
                            </h3>
                            <div class="flex items-center text-[10px] text-editorial-muted font-bold uppercase tracking-wider">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                {{ number_format($post->views) }}
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                
                <!-- Ad Placeholder simulating real news site -->
                <div class="mt-12 bg-editorial-header border border-editorial p-8 text-center flex flex-col items-center justify-center h-64 opacity-50">
                    <span class="text-[10px] text-gray-500 uppercase tracking-widest mb-2 border-b border-gray-700 pb-1">ANZEIGE / Iklan</span>
                    <span class="text-gray-600 font-bold tracking-widest">Ruang Iklan Tersedia</span>
                </div>
            </div>
            @endif

        </div>
    </main>

    <!-- Deep Dark Footer -->
    <footer class="bg-editorial-header border-t-2 border-editorial mt-20 py-16">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
                <div class="mb-10 lg:mb-0">
                    <h2 class="font-sz text-4xl w-full font-bold mb-4 text-white hover:text-gray-300 transition-colors">DarnusNews</h2>
                    <p class="text-editorial-muted font-ui text-sm max-w-md">Jurnalistik mendalam, analitis, dan berwibawa internasional. Menyajikan laporan tangan pertama yang kredibel.</p>
                </div>
                <div class="flex flex-wrap gap-x-12 gap-y-6 text-xs font-bold tracking-widest uppercase">
                    <div class="flex flex-col space-y-4">
                        <a href="#" class="text-gray-500 hover:text-white transition-colors">Tentang Kami</a>
                        <a href="#" class="text-gray-500 hover:text-white transition-colors">Redaksi</a>
                    </div>
                    <div class="flex flex-col space-y-4">
                        <a href="#" class="text-gray-500 hover:text-white transition-colors">Pedoman Jurnalistik</a>
                        <a href="#" class="text-gray-500 hover:text-white transition-colors">Kontak</a>
                    </div>
                </div>
            </div>
            <div class="mt-16 pt-8 border-t border-gray-800 flex flex-col sm:flex-row justify-between items-center text-[10px] text-gray-600 uppercase tracking-widest font-bold">
                <p>&copy; {{ date('Y') }} Darnus Media Publishing.</p>
                <div class="mt-4 sm:mt-0 flex space-x-4 items-center">
                    <span>Edisi Digital</span>
                    <span class="hidden sm:inline">Hak Cipta Dilindungi Undang-Undang</span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>


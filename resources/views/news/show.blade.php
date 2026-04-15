<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $post->title }} - Darnus</title>
    <meta name="description" content="{{ Str::limit(strip_tags($post->content), 160) }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,800;1,400;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0F172A; color: #E2E8F0; }
        .font-sz { font-family: 'Playfair Display', serif; }
        .hide-scroll-bar::-webkit-scrollbar { display: none; }
        .hide-scroll-bar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Shared Editorial Palette */
        .border-editorial { border-color: #1E293B; }
        .bg-editorial-dark { background-color: #0F172A; }
        .bg-editorial-header { background-color: #020617; }
        .bg-editorial-card { background-color: #1E293B; }
        .text-editorial-muted { color: #94A3B8; }
        .text-editorial-accent { color: #FBBF24; }

        /* Article body typography */
        .article-body p {
            margin-bottom: 1.75rem;
            font-size: 1.125rem;
            line-height: 1.9;
            color: #CBD5E1;
            font-family: 'Playfair Display', serif;
        }
        .article-body h2 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.75rem;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            color: #F1F5F9;
            border-left: 3px solid #FBBF24;
            padding-left: 1rem;
        }
        .article-body a { color: #FBBF24; text-decoration: underline; }
        .article-body a:hover { color: #FDE68A; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    <!-- Modern Digital-Native Header -->
    <header class="bg-editorial-header sticky top-0 z-50 border-b border-editorial shadow-sm">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Left: Logo -->
                <div class="flex items-center flex-shrink-0">
                    <a href="{{ route('home') }}" class="font-sz text-3xl md:text-4xl font-bold tracking-tight text-white hover:text-editorial-accent transition-colors whitespace-nowrap">
                        DarnusNews
                    </a>
                </div>
                
                <!-- Center: Desktop Category Nav -->
                <nav class="hidden lg:flex flex-1 justify-center px-8 overflow-hidden">
                    <ul class="flex space-x-6 xl:space-x-8 text-[10px] font-bold tracking-widest uppercase text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-editorial-accent transition-colors {{ !isset($post) ? 'text-white' : '' }}">Beranda</a></li>
                        @foreach($categories ?? [] as $cat)
                        <li><a href="{{ route('search', ['q' => $cat->name]) }}" class="hover:text-editorial-accent transition-colors whitespace-nowrap {{ isset($post) && $post->category->id === $cat->id ? 'text-editorial-accent' : '' }}">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </nav>

                <!-- Right: Nav, Search, Login -->
                <div class="flex items-center space-x-2 sm:space-x-4 justify-end">
                    <!-- Search Form -->
                    <form action="{{ route('search') }}" method="GET" class="flex items-center relative group">
                        <input type="text" name="q" placeholder="Cari..." class="w-20 md:w-32 lg:w-40 focus:w-32 sm:focus:w-48 bg-editorial-card border border-editorial rounded-full px-4 py-1.5 text-sm focus:outline-none focus:border-gray-500 transition-all text-white mr-1 md:mr-2 placeholder-gray-500">
                        <button type="submit" class="text-gray-300 hover:text-editorial-accent transition-colors p-2" aria-label="Search">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </form>

                    <!-- Login Pill -->
                    <a href="{{ url('/admin') }}" class="hidden sm:block text-[10px] font-bold tracking-widest uppercase text-editorial-dark bg-editorial-accent hover:bg-white px-4 py-2 rounded-full transition-colors">
                        Login
                    </a>
                    
                    <a href="{{ url('/admin') }}" class="sm:hidden text-gray-300 hover:text-editorial-accent transition-colors p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </a>
                </div>
            </div>
            
            <!-- Mobile/Tablet Category Nav (Scrollable) -->
            <nav class="lg:hidden h-12 flex items-center overflow-x-auto hide-scroll-bar border-t border-editorial">
                <ul class="flex space-x-6 text-[10px] font-bold tracking-widest uppercase text-gray-400 px-2 min-w-max pb-1">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors {{ !isset($post) ? 'text-white' : '' }}">Beranda</a></li>
                    @foreach($categories ?? [] as $cat)
                    <li><a href="{{ route('search', ['q' => $cat->name]) }}" class="hover:text-white transition-colors whitespace-nowrap {{ isset($post) && $post->category->id === $cat->id ? 'text-editorial-accent' : '' }}">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </header>

    <main class="flex-grow max-w-[860px] mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full">
        <article>
            <!-- Article Header -->
            <div class="mb-10 text-center">
                <div class="flex items-center justify-center space-x-3 text-[11px] font-bold tracking-widest uppercase mb-6">
                    <span class="text-editorial-accent bg-amber-900/20 px-2 py-1">{{ $post->category->name }}</span>
                    <span class="text-gray-500">·</span>
                    <span class="text-gray-400">{{ $post->region->name }}</span>
                </div>

                <h1 class="font-sz text-4xl md:text-5xl lg:text-6xl font-bold leading-tight text-white mb-8">
                    {{ $post->title }}
                </h1>

                <div class="flex items-center justify-center space-x-6 text-xs text-editorial-muted font-bold uppercase tracking-wider border-t border-b border-editorial py-4">
                    <span>{{ $post->created_at->format('d F Y') }}</span>
                    <span class="text-gray-600">·</span>
                    <span class="flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        {{ number_format($post->views) }} pembaca
                    </span>
                </div>
            </div>

            <!-- Featured Image -->
            @if($post->image)
            <figure class="mb-12 border border-editorial rounded-2xl overflow-hidden shadow-lg">
                <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full object-cover aspect-[21/9]">
                <figcaption class="mt-0 text-xs text-editorial-muted font-bold uppercase tracking-wider text-right px-4 py-2 border-t border-editorial">
                    Foto: DarnusNews / {{ $post->region->name }}
                </figcaption>
            </figure>
            @endif

            <!-- Article Body -->
            <div class="article-body">
                {!! $post->content !!}
            </div>

            <!-- Back Link -->
            <div class="mt-16 pt-8 border-t border-editorial">
                <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-bold uppercase tracking-widest text-editorial-muted hover:text-editorial-accent transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Beranda
                </a>
            </div>
        </article>
    </main>

    <!-- Modern Digital Media Footer -->
    <footer class="bg-editorial-header border-t border-editorial mt-20 py-16">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 lg:gap-8">
                <!-- Brand & About -->
                <div class="md:col-span-2">
                    <h2 class="font-sz text-3xl font-bold mb-4 text-white hover:text-editorial-accent transition-colors">DarnusNews</h2>
                    <p class="text-gray-400 text-sm max-w-sm mb-6 leading-relaxed">Portal berita terpercaya untuk liputan tajam, akurat, dan independen di seluruh nusantara. Kami menjunjung tinggi integritas jurnalistik.</p>
                    <div class="flex space-x-3">
                        <div class="w-8 h-8 rounded-full bg-editorial-card border border-editorial flex items-center justify-center hover:bg-editorial-accent hover:text-editorial-dark transition-colors cursor-pointer text-xs font-bold text-gray-300">X</div>
                        <div class="w-8 h-8 rounded-full bg-editorial-card border border-editorial flex items-center justify-center hover:bg-editorial-accent hover:text-editorial-dark transition-colors cursor-pointer text-xs font-bold text-gray-300">fb</div>
                        <div class="w-8 h-8 rounded-full bg-editorial-card border border-editorial flex items-center justify-center hover:bg-editorial-accent hover:text-editorial-dark transition-colors cursor-pointer text-xs font-bold text-gray-300">ig</div>
                    </div>
                </div>
                
                <!-- Links 1 -->
                <div>
                    <h3 class="text-white font-bold text-xs tracking-widest uppercase mb-5">Perusahaan</h3>
                    <ul class="space-y-3 text-sm text-gray-400 font-medium">
                        <li><a href="#" class="hover:text-editorial-accent transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-editorial-accent transition-colors">Susunan Redaksi</a></li>
                        <li><a href="#" class="hover:text-editorial-accent transition-colors">Info Karir</a></li>
                    </ul>
                </div>
                
                <!-- Links 2 -->
                <div>
                    <h3 class="text-white font-bold text-xs tracking-widest uppercase mb-5">Informasi</h3>
                    <ul class="space-y-3 text-sm text-gray-400 font-medium">
                        <li><a href="#" class="hover:text-editorial-accent transition-colors">Pedoman Siber</a></li>
                        <li><a href="#" class="hover:text-editorial-accent transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-editorial-accent transition-colors">Hubungi Kami</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="mt-16 pt-8 border-t border-editorial flex flex-col md:flex-row justify-between items-center text-[11px] font-bold tracking-widest uppercase text-gray-500">
                <p class="mb-4 md:mb-0">&copy; {{ date('Y') }} Darnus Media. All rights reserved.</p>
                <div class="flex flex-wrap space-x-6 justify-center">
                    <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-white transition-colors">Kode Etik</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

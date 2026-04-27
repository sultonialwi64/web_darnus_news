<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $title = isset($currentCategory) ? "Rubrik: " . $currentCategory->name : (isset($query) && $query ? "Hasil Pencarian: " . $query : "Pencarian Berita");
    @endphp
    <title>{{ $title }} - DMN NEWS</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="Temukan berita terbaru dan terpercaya di DarnusNews.">
    <meta property="og:image" content="{{ asset('images/logo.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ request()->fullUrl() }}">
    <meta property="twitter:title" content="{{ $title }}">
    <meta property="twitter:description" content="Temukan berita terbaru dan terpercaya di DarnusNews.">
    <meta property="twitter:image" content="{{ asset('images/logo.jpg') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Roboto:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ================================================
         * Classic Authority Theme — DarnusNews
         * ================================================ */
        body { font-family: 'Inter', sans-serif; background-color: #F5F5F5; color: #374151; }
        .font-sz { font-family: 'Inter', sans-serif; letter-spacing: -0.02em; }
        .hide-scroll-bar::-webkit-scrollbar { display: none; }
        .hide-scroll-bar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Fix search autofill background */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus {
            -webkit-text-fill-color: white !important;
            -webkit-box-shadow: 0 0 0px 1000px #0A192F inset !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        /* Palette */
        .bg-navy           { background-color: #0A192F; }
        .border-navy        { border-color: #1E3A5F; }
        .bg-section         { background-color: #F8F9FA; }
        .text-accent        { color: #F59E0B; }
        .bg-accent          { background-color: #F59E0B; }
        .text-muted         { color: #6B7280; }
        .text-heading       { color: #111827; }
        .border-light       { border-color: #E5E7EB; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    <!-- Header: Navy Dark (Identitas Tetap Kuat) -->
    <header class="bg-navy sticky top-0 z-50 border-b border-navy shadow-md">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Top Row: Logo & Actions -->
            <div class="flex justify-between items-center h-16 sm:h-20">
                <!-- Left: Logo -->
                <div class="flex items-center flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center py-1">
                        <img src="{{ asset('images/logo.jpg') }}" alt="DMN NEWS Logo" class="h-12 md:h-16 w-auto">
                    </a>
                </div>

                <!-- Right: Search & Login -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <button type="button" onclick="document.getElementById('mobileSearchOverlay').classList.remove('hidden'); document.getElementById('mobileSearchInput').focus();" class="text-gray-300 hover:text-accent transition-colors p-2 cursor-pointer" aria-label="Buka Pencarian">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>

                    <!-- Login Pill -->

                </div>
            </div>

            <!-- Bottom Row: Category Nav -->
            <nav class="h-11 flex items-center overflow-x-auto hide-scroll-bar border-t border-navy">
                <ul class="flex space-x-6 sm:space-x-8 text-[10px] font-bold tracking-widest uppercase text-gray-400 min-w-max">
                    <li><a href="{{ route('home') }}" class="hover:text-accent transition-colors text-white">Beranda</a></li>
                    @foreach($categories ?? [] as $cat)
                    <li><a href="{{ route('search', ['category' => $cat->slug]) }}" class="hover:text-accent transition-colors whitespace-nowrap {{ (isset($currentCategory) && $currentCategory->id === $cat->id) ? 'text-accent border-b-2 border-amber-400 pb-3 sm:pb-3' : '' }}">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </nav>
        </div>

        <!-- Full-Width Search Overlay -->
        <div id="mobileSearchOverlay" class="hidden absolute inset-0 bg-navy z-[60] flex items-center px-4 sm:px-6 lg:px-8 border-b border-navy shadow-2xl">
            <form action="{{ route('search') }}" method="GET" class="flex-1 flex items-center max-w-[1280px] mx-auto h-full w-full">
                <svg class="w-6 h-6 text-gray-400 mr-3 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" id="mobileSearchInput" name="q" placeholder="Cari berita terkini..." autocomplete="off" value="{{ $query ?? '' }}" class="flex-1 bg-transparent text-white focus:outline-none placeholder-gray-500 border-none outline-none ring-0 py-2 text-lg">
                <button type="submit" class="text-white hover:text-accent font-bold tracking-widest uppercase text-sm ml-2 px-2 transition-colors">Cari</button>
                <button type="button" onclick="document.getElementById('mobileSearchOverlay').classList.add('hidden');" class="ml-2 sm:ml-4 text-gray-400 hover:text-white p-2 transition-colors" aria-label="Tutup">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </form>
        </div>
    </header>

    <main class="flex-grow max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">

        <!-- Search Header -->
        <div class="mb-12 border-b border-gray-200 pb-8">
            <p class="text-[11px] font-bold tracking-widest uppercase text-muted mb-2">
                @if(isset($currentCategory)) Rubrik @else Hasil Pencarian @endif
            </p>
            <h1 class="font-sz text-4xl md:text-5xl font-bold text-heading">
                @if(isset($currentCategory)) 
                    {{ $currentCategory->name }}
                @else
                    "{{ $query }}"
                @endif
            </h1>
            @if($posts instanceof \Illuminate\Pagination\LengthAwarePaginator && $posts->total() > 0)
            <p class="text-muted text-sm mt-3">Ditemukan <span class="text-heading font-bold">{{ $posts->total() }}</span> artikel</p>
            @endif
        </div>

        @if(isset($posts) && $posts->count() > 0)
        <!-- Results Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
            @foreach($posts as $post)
            <a href="{{ route('news.show', $post->slug) }}" class="group flex flex-col bg-white rounded-2xl shadow-md border border-gray-200 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                @if($post->image)
                    <div class="w-full aspect-[3/2] overflow-hidden bg-gray-100 flex-shrink-0">
                        <img src="{{ Storage::url($post->image) }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
                    </div>
                @endif
                <div class="flex flex-col flex-grow p-5">
                    <div class="text-[10px] font-bold tracking-widest uppercase text-accent mb-3">
                        {{ $post->category->name }} @if($post->region) · {{ $post->region->name }} @endif
                    </div>
                    <h3 class="font-sz text-xl font-bold text-heading leading-snug group-hover:text-amber-700 transition-colors mb-3">
                        {{ $post->title }}
                    </h3>
                    <p class="text-muted text-xs line-clamp-2 mb-4">
                        {{ $post->summary }}
                    </p>
                    <time class="mt-auto text-[10px] text-muted font-bold uppercase tracking-wider">
                        {{ $post->created_at->diffForHumans() }}
                    </time>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8 pt-6 border-t border-light">
            {{ $posts->appends(['q' => $query])->links() }}
        </div>

        @else
        <!-- Empty State -->
        <div class="py-32 text-center">
            <p class="font-sz text-6xl font-bold text-gray-200 mb-6">?</p>
            <h2 class="font-sz text-3xl font-bold text-heading mb-4">Tidak ada hasil ditemukan</h2>
            <p class="text-muted text-lg mb-8">Tidak ada artikel yang cocok dengan kata kunci "<span class="text-heading font-semibold">{{ $query }}</span>"</p>
            <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-bold uppercase tracking-widest text-amber-700 hover:text-amber-900 transition-colors border border-amber-300 px-6 py-3 rounded-lg hover:bg-amber-50">
                Kembali ke Beranda
            </a>
        </div>
        @endif
    </main>

    <!-- Footer: Navy Dark (Identitas Tetap Kuat) -->
    <footer class="bg-navy border-t border-navy mt-20 py-16">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 lg:gap-8">
                <!-- Brand & About -->
                <div class="md:col-span-2">
                    <h2 class="font-sz text-3xl font-bold mb-4 text-white hover:text-accent transition-colors">DMN NEWS</h2>
                    <p class="text-gray-400 text-sm max-w-sm mb-6 leading-relaxed">Portal berita terpercaya untuk liputan tajam, akurat, dan independen di seluruh nusantara. Kami menjunjung tinggi integritas jurnalistik.</p>
                    <div class="flex space-x-3">
                        <a href="https://web.facebook.com/profile.php?id=61567364752520" target="_blank" class="w-10 h-10 rounded-full bg-[#1877F2] flex items-center justify-center hover:opacity-80 transition-opacity">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="https://x.com/DNews18638" target="_blank" class="w-10 h-10 rounded-full bg-black flex items-center justify-center hover:opacity-80 transition-opacity border border-white/10">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="https://www.instagram.com/dmnews8989/" target="_blank" class="w-10 h-10 rounded-full bg-[#E4405F] flex items-center justify-center hover:opacity-80 transition-opacity">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="https://www.youtube.com/@DMN_News" target="_blank" class="w-10 h-10 rounded-full bg-[#FF0000] flex items-center justify-center hover:opacity-80 transition-opacity">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Links 1 -->
                <div>
                    <h3 class="text-white font-bold text-xs tracking-widest uppercase mb-5">Perusahaan</h3>
                    <ul class="space-y-3 text-sm text-gray-400 font-medium">
                        <li><a href="{{ route('about') }}" class="hover:text-accent transition-colors">Tentang Kami</a></li>
                        <li><a href="{{ route('editorial') }}" class="hover:text-accent transition-colors">Susunan Redaksi</a></li>
                        <li><a href="#" class="hover:text-accent transition-colors">Info Karir</a></li>
                    </ul>
                </div>

                <!-- Links 2 -->
                <div>
                    <h3 class="text-white font-bold text-xs tracking-widest uppercase mb-5">Informasi</h3>
                    <ul class="space-y-3 text-sm text-gray-400 font-medium">
                        <li><a href="#" class="hover:text-accent transition-colors">Pedoman Siber</a></li>
                        <li><a href="#" class="hover:text-accent transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="https://wa.me/6287756655758" target="_blank" class="hover:text-accent transition-colors">WA Redaksi: 0877-5665-5758</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-16 pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center text-[11px] font-bold tracking-widest uppercase text-gray-500">
                <p class="mb-4 md:mb-0">&copy; {{ date('Y') }} DMN Media. All rights reserved.</p>
                <div class="flex flex-wrap space-x-6 justify-center">
                    <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-white transition-colors">Kode Etik</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

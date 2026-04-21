<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hasil Pencarian: {{ $query }} - DarnusNews</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,800;1,400;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ================================================
         * Classic Authority Theme — DarnusNews
         * ================================================ */
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F5F5F5; color: #374151; }
        .font-sz { font-family: 'Playfair Display', serif; }
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
                    <a href="{{ route('home') }}" class="font-sz text-3xl md:text-4xl font-bold tracking-tight text-white hover:text-accent transition-colors whitespace-nowrap">
                        DarnusNews
                    </a>
                </div>

                <!-- Right: Search & Login -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <button type="button" onclick="document.getElementById('mobileSearchOverlay').classList.remove('hidden'); document.getElementById('mobileSearchInput').focus();" class="text-gray-300 hover:text-accent transition-colors p-2 cursor-pointer" aria-label="Buka Pencarian">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>

                    <!-- Login Pill -->
                    <a href="{{ url('/admin') }}" class="hidden sm:block text-[10px] font-bold tracking-widest uppercase bg-accent hover:bg-white px-5 py-2 rounded-full transition-colors shadow-lg" style="color: #0A192F;">
                        Login
                    </a>
                    <a href="{{ url('/admin') }}" class="sm:hidden text-gray-300 hover:text-accent transition-colors p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </a>
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
                    <div class="w-full aspect-[4/3] overflow-hidden bg-gray-100 flex-shrink-0">
                        <img src="{{ Storage::url($post->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
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
                    <h2 class="font-sz text-3xl font-bold mb-4 text-white hover:text-accent transition-colors">DarnusNews</h2>
                    <p class="text-gray-400 text-sm max-w-sm mb-6 leading-relaxed">Portal berita terpercaya untuk liputan tajam, akurat, dan independen di seluruh nusantara. Kami menjunjung tinggi integritas jurnalistik.</p>
                    <div class="flex space-x-3">
                        <div class="w-8 h-8 rounded-full bg-white/10 border border-white/20 flex items-center justify-center hover:bg-accent transition-colors cursor-pointer text-xs font-bold text-gray-300">X</div>
                        <div class="w-8 h-8 rounded-full bg-white/10 border border-white/20 flex items-center justify-center hover:bg-accent transition-colors cursor-pointer text-xs font-bold text-gray-300">fb</div>
                        <div class="w-8 h-8 rounded-full bg-white/10 border border-white/20 flex items-center justify-center hover:bg-accent transition-colors cursor-pointer text-xs font-bold text-gray-300">ig</div>
                    </div>
                </div>

                <!-- Links 1 -->
                <div>
                    <h3 class="text-white font-bold text-xs tracking-widest uppercase mb-5">Perusahaan</h3>
                    <ul class="space-y-3 text-sm text-gray-400 font-medium">
                        <li><a href="#" class="hover:text-accent transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-accent transition-colors">Susunan Redaksi</a></li>
                        <li><a href="#" class="hover:text-accent transition-colors">Info Karir</a></li>
                    </ul>
                </div>

                <!-- Links 2 -->
                <div>
                    <h3 class="text-white font-bold text-xs tracking-widest uppercase mb-5">Informasi</h3>
                    <ul class="space-y-3 text-sm text-gray-400 font-medium">
                        <li><a href="#" class="hover:text-accent transition-colors">Pedoman Siber</a></li>
                        <li><a href="#" class="hover:text-accent transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-accent transition-colors">Hubungi Kami</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-16 pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center text-[11px] font-bold tracking-widest uppercase text-gray-500">
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

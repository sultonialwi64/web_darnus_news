<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0F172A; color: #E2E8F0; }
        .font-sz { font-family: 'Playfair Display', serif; }
        .hide-scroll-bar::-webkit-scrollbar { display: none; }
        .hide-scroll-bar { -ms-overflow-style: none; scrollbar-width: none; }

        .border-editorial { border-color: #1E293B; }
        .bg-editorial-dark { background-color: #0F172A; }
        .bg-editorial-header { background-color: #020617; }
        .bg-editorial-card { background-color: #1E293B; }
        .text-editorial-muted { color: #94A3B8; }
        .text-editorial-accent { color: #FBBF24; }
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
                        <li><a href="{{ route('home') }}" class="hover:text-editorial-accent transition-colors text-white">Beranda</a></li>
                        @foreach($categories ?? [] as $cat)
                        <li><a href="{{ route('search', ['q' => $cat->name]) }}" class="hover:text-editorial-accent transition-colors whitespace-nowrap">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </nav>

                <!-- Right: Nav, Search, Login -->
                <div class="flex items-center space-x-2 sm:space-x-4 justify-end">
                    <!-- Search Form (Visible) -->
                    <form action="{{ route('search') }}" method="GET" class="flex items-center">
                        <input type="text" name="q" placeholder="Cari..." value="{{ request('q') }}" class="w-32 sm:w-48 bg-editorial-card border border-gray-500 rounded-full px-4 py-1.5 text-sm focus:outline-none focus:border-white transition-all text-white mr-1 placeholder-gray-500">
                        <button type="submit" class="text-gray-300 hover:text-editorial-accent transition-colors p-2" aria-label="Search">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </form>

                    <!-- Login Pill & Mobile Login Icon -->
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
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors text-white">Beranda</a></li>
                    @foreach($categories ?? [] as $cat)
                    <li><a href="{{ route('search', ['q' => $cat->name]) }}" class="hover:text-white transition-colors whitespace-nowrap">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </header>

    <main class="flex-grow max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">

        <!-- Search Header -->
        <div class="mb-12 border-b border-editorial pb-8">
            <p class="text-[11px] font-bold tracking-widest uppercase text-editorial-muted mb-2">Hasil Pencarian</p>
            <h1 class="font-sz text-4xl md:text-5xl font-bold text-white">
                "{{ $query }}"
            </h1>
            @if($posts instanceof \Illuminate\Pagination\LengthAwarePaginator && $posts->total() > 0)
            <p class="text-editorial-muted text-sm mt-3">Ditemukan <span class="text-white font-bold">{{ $posts->total() }}</span> artikel</p>
            @endif
        </div>

        @if(isset($posts) && $posts->count() > 0)
        <!-- Results Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
            @foreach($posts as $post)
            <a href="{{ route('news.show', $post->slug) }}" class="group block flex flex-col bg-editorial-card rounded-2xl border border-editorial p-5 hover:border-gray-500 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                @if($post->image)
                    <div class="w-full aspect-[4/3] mb-5 overflow-hidden rounded-xl bg-editorial-dark filter grayscale group-hover:grayscale-0 transition-all duration-500">
                        <img src="{{ Storage::url($post->image) }}" class="w-full h-full object-cover">
                    </div>
                @endif
                <div class="flex flex-col flex-grow">
                    <div class="text-[10px] font-bold tracking-widest uppercase text-editorial-accent mb-3">
                        {{ $post->category->name }} · {{ $post->region->name }}
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

        <!-- Pagination -->
        <div class="mt-8 pt-6 border-t border-editorial">
            {{ $posts->appends(['q' => $query])->links() }}
        </div>

        @else
        <!-- Empty State -->
        <div class="py-32 text-center">
            <p class="font-sz text-6xl font-bold text-gray-700 mb-6">?</p>
            <h2 class="font-sz text-3xl font-bold text-gray-300 mb-4">Tidak ada hasil ditemukan</h2>
            <p class="text-editorial-muted text-lg mb-8">Tidak ada artikel yang cocok dengan kata kunci "<span class="text-white font-semibold">{{ $query }}</span>"</p>
            <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-bold uppercase tracking-widest text-editorial-accent hover:text-amber-300 transition-colors border border-editorial px-6 py-3">
                Kembali ke Beranda
            </a>
        </div>
        @endif        </div>
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

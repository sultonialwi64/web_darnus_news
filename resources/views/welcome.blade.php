<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DarnusNews - Berita Terpercaya</title>
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
<body class="bg-white text-body antialiased min-h-screen flex flex-col selection:bg-amber-100 selection:text-amber-900">

    <!-- Header: Navy Dark (Identitas Tetap Kuat) -->
    <header class="bg-navy sticky top-0 z-50 border-b border-navy shadow-md">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Top Row: Logo & Actions -->
            <div class="flex justify-between items-center h-16 sm:h-20">
                <!-- Left: Logo -->
                <div class="flex items-center flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center py-1">
                        <img src="{{ asset('images/logo.jpg') }}" alt="DarnusNews Logo" class="h-12 md:h-16 w-auto">
                    </a>
                </div>

                <!-- Right: Nav, Search, Login -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <button type="button" onclick="document.getElementById('mobileSearchOverlay').classList.remove('hidden'); document.getElementById('mobileSearchInput').focus();" class="text-gray-300 hover:text-accent transition-colors p-2 cursor-pointer" aria-label="Buka Pencarian">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>

                    <!-- Login Pill -->
                    <a href="{{ url('/admin') }}" class="hidden sm:block text-[10px] font-bold tracking-widest uppercase text-navy bg-accent hover:bg-white hover:text-navy px-5 py-2 rounded-full transition-colors shadow-lg" style="color: #0A192F;">
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
                    <li><a href="{{ route('home') }}" class="hover:text-accent transition-colors text-white border-b-2 border-amber-400 pb-3 sm:pb-3">Beranda</a></li>
                    @foreach($categories ?? [] as $cat)
                    <li><a href="{{ route('search', ['category' => $cat->slug]) }}" class="hover:text-accent transition-colors whitespace-nowrap">{{ $cat->name }}</a></li>
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

    <main class="flex-grow max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">

        <!-- Hero Section: Featured Headline -->
        <div class="mb-16 border-b border-light pb-12">
            @if($featuredPost)
            <div class="group w-full max-w-5xl mx-auto cursor-pointer block hover:-translate-y-1 transition-transform duration-500 hover:shadow-2xl rounded-2xl overflow-hidden border border-light shadow-md">
                <a href="{{ route('news.show', $featuredPost->slug) }}" class="flex flex-col w-full bg-white overflow-hidden">

                    <!-- Cinematic Title & Image Block -->
                    <div class="relative w-full overflow-hidden bg-gray-100" style="aspect-ratio: 16/9; max-height: 520px; min-height: 350px;">
                        @if($featuredPost->image)
                            <img src="{{ Storage::url($featuredPost->image) }}" alt="{{ $featuredPost->title }}" class="absolute inset-0 w-full h-full object-cover object-top brightness-[0.85] group-hover:brightness-100 transition-all duration-700 ease-in-out z-0">
                        @else
                            <div class="w-full aspect-video bg-gray-100"></div>
                        @endif

                        <!-- Gradient Layer -->
                        <div class="absolute inset-x-0 bottom-0 top-1/4 bg-gradient-to-t from-black via-black/40 to-transparent z-10 opacity-90"></div>

                        <!-- Title on Image -->
                        <div class="absolute inset-0 z-20 p-6 sm:p-8 lg:p-12 flex flex-col justify-center items-center text-center">
                            <div class="flex flex-wrap items-center justify-center gap-2 mb-4 sm:mb-6 text-[10px] sm:text-[11px] font-bold tracking-widest uppercase">
                                <span class="bg-accent px-4 py-1.5 rounded-full shadow-lg" style="color: #0A192F;">{{ $featuredPost->category->name }}</span>
                            </div>
                            <h1 class="w-full max-w-3xl mx-auto font-sz font-bold text-white transition-colors drop-shadow-[0_8px_16px_rgba(0,0,0,1)]" style="font-size: clamp(1.75rem, 3vw + 0.5rem, 3.25rem); line-height: 1.25; padding: 0 1rem;">
                                {{ $featuredPost->title }}
                            </h1>
                        </div>
                    </div>

                    <!-- Description Block -->
                    <div class="w-full flex-grow flex flex-col p-6 sm:p-8 lg:p-12 items-center text-center pb-8 z-30 relative bg-white shadow-md border-t-4 border-amber-400">
                        <!-- Meta -->
                        <div class="flex items-center justify-center gap-2 mb-5 text-[10px] sm:text-[11px] font-bold tracking-widest uppercase text-muted">
                            @if($featuredPost->region)
                                <span>{{ $featuredPost->region->name }}</span>
                                <span>·</span>
                            @endif
                            <span>{{ $featuredPost->created_at->format('d M Y') }}</span>
                        </div>

                        <!-- Excerpt -->
                        <p class="w-full text-body text-base sm:text-lg leading-relaxed font-sz mb-8 line-clamp-3 md:line-clamp-4">
                            {{ htmlspecialchars_decode($featuredPost->excerpt ?: Str::limit(strip_tags($featuredPost->content), 200)) }}
                        </p>

                        <!-- Footer Info -->
                        <div class="w-full mt-auto text-[10px] sm:text-xs text-muted font-bold uppercase tracking-wider flex items-center justify-between pt-6 border-t border-light">
                            <span class="group-hover:text-amber-700 transition-colors">Baca Selengkapnya &rarr;</span>
                            <span class="flex items-center group-hover:text-heading transition-colors">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                {{ number_format($featuredPost->views) }} Views
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            @endif
        </div>

        <!-- 4 Column Breakout News -->
        @if($latestPosts && count($latestPosts) > 0)
        <div class="mb-16 border-b border-light pb-16">
            <h2 class="text-center font-sz text-3xl font-bold text-heading mb-10 pb-4 border-b border-light mx-auto w-max px-12 tracking-wide">Berita Terkini</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($latestPosts->take(4) as $post)
                <a href="{{ route('news.show', $post->slug) }}" class="group flex flex-col bg-white rounded-2xl shadow-md border border-gray-200 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                    @if($post->image)
                        {{-- Card dengan gambar: image di atas, konten di bawah --}}
                        <div class="w-full aspect-[4/3] overflow-hidden bg-gray-100 flex-shrink-0">
                            <img src="{{ Storage::url($post->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
                        </div>
                        <div class="flex flex-col flex-grow p-5">
                            <div class="text-[10px] font-bold tracking-widest uppercase mb-2 flex items-center">
                                <span class="text-amber-600 mr-1">{{ $post->category->name }}</span>
                                @if($post->region)<span class="text-muted ml-1">· {{ $post->region->name }}</span>@endif
                            </div>
                            <h3 class="font-sz text-lg font-bold text-heading leading-snug group-hover:text-amber-700 transition-colors mb-3">
                                {{ $post->title }}
                            </h3>
                            @if($post->excerpt)<p class="text-muted text-xs leading-relaxed line-clamp-2 mb-3">{{ $post->excerpt }}</p>@endif
                            <time class="mt-auto text-[10px] text-muted font-bold uppercase tracking-wider">{{ $post->created_at->diffForHumans() }}</time>
                        </div>
                    @else
                        {{-- Card tanpa gambar: pakai aksen amber di kiri sebagai pengganti visual --}}
                        <div class="flex flex-col flex-grow p-5 border-l-4 border-amber-400">
                            <div class="text-[10px] font-bold tracking-widest uppercase mb-2 flex items-center">
                                <span class="text-amber-600 mr-1">{{ $post->category->name }}</span>
                                @if($post->region)<span class="text-muted ml-1">· {{ $post->region->name }}</span>@endif
                            </div>
                            <h3 class="font-sz text-xl font-bold text-heading leading-snug group-hover:text-amber-700 transition-colors mb-3">
                                {{ $post->title }}
                            </h3>
                            @if($post->excerpt)<p class="text-muted text-sm leading-relaxed line-clamp-3 mb-4">{{ $post->excerpt }}</p>@endif
                            <time class="mt-auto text-[10px] text-muted font-bold uppercase tracking-wider">{{ $post->created_at->diffForHumans() }}</time>
                        </div>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Main Feed + Sidebar -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

            <!-- Left Column: More Articles (70%) -->
            @if($otherPosts && count($otherPosts) > 0)
            <div class="lg:col-span-8 lg:border-r lg:border-light lg:pr-12">
                <h2 class="font-sz text-3xl font-bold flex items-center text-heading mb-8">Berita Terpenting</h2>

                <div class="flex flex-col">
                    @foreach($otherPosts as $post)
                    <a href="{{ route('news.show', $post->slug) }}" class="group flex flex-col sm:flex-row items-center mb-4 bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 p-5 overflow-hidden">
                        @if($post->image)
                            <div class="w-full sm:w-1/3 aspect-[4/3] rounded-xl overflow-hidden border border-light flex-shrink-0 sm:mr-6 mb-4 sm:mb-0 bg-gray-100">
                                <img src="{{ Storage::url($post->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
                            </div>
                        @endif
                        <div class="flex-grow flex flex-col justify-center">
                            <div class="flex items-center text-[10px] font-bold tracking-widest uppercase mb-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2 opacity-80"></span>
                                <span class="text-heading mr-2">{{ $post->category->name }}</span>
                                @if($post->region)
                                <span class="text-muted">{{ $post->region->name }}</span>
                                @endif
                            </div>
                            <h3 class="font-sz text-2xl font-bold text-heading leading-snug group-hover:text-amber-700 transition-colors mb-2">
                                {{ $post->title }}
                            </h3>
                            <p class="text-muted font-sz text-base leading-relaxed line-clamp-2 mb-4">
                                {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 150) }}
                            </p>
                            <time class="text-[10px] text-muted font-bold uppercase tracking-wider">
                                {{ $post->created_at->format('d.m.Y - H:i') }}
                            </time>
                        </div>
                    </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8 pt-6">
                    {{ $otherPosts->links() }}
                </div>
            </div>
            @endif

            <!-- Right Sidebar: Paling Banyak Dibaca (30%) -->
            @if($popularPosts && count($popularPosts) > 0)
            <div class="lg:col-span-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 pb-4 border-b border-gray-100">
                        <h2 class="font-sz text-lg font-bold text-heading tracking-wide uppercase flex items-center gap-2">
                            <span class="inline-block w-2 h-5 bg-amber-400 rounded-sm"></span>
                            Paling Banyak Dibaca
                        </h2>
                    </div>
                    <div class="flex flex-col divide-y divide-gray-100">
                        @foreach($popularPosts->take(6) as $index => $post)
                        <a href="{{ route('news.show', $post->slug) }}" class="group flex items-start gap-4 p-5 hover:bg-gray-50 transition-colors duration-200">
                            <!-- Number Badge -->
                            <span class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-100 text-amber-700 text-sm font-black flex items-center justify-center group-hover:bg-amber-400 group-hover:text-white transition-colors">
                                {{ $loop->iteration }}
                            </span>
                            <div class="flex flex-col min-w-0">
                                <span class="text-[10px] uppercase tracking-widest font-bold text-accent mb-1">{{ $post->category->name }}</span>
                                <h3 class="font-sz text-base font-bold text-heading leading-snug group-hover:text-amber-700 transition-colors line-clamp-3 mb-2">
                                    {{ $post->title }}
                                </h3>
                                <div class="flex items-center text-[10px] text-muted font-bold uppercase tracking-wider">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    {{ number_format($post->views) }} dilihat
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Ad Placeholder -->
                <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-200 p-8 text-center flex flex-col items-center justify-center h-52">
                    <span class="text-[10px] text-muted uppercase tracking-widest mb-2">ADVERTISEMENT / Iklan</span>
                    <span class="text-gray-300 font-bold tracking-widest text-sm">Ruang Iklan Tersedia</span>
                </div>
            </div>
            @endif

        </div>
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
                        <div class="w-8 h-8 rounded-full bg-white/10 border border-white/20 flex items-center justify-center hover:bg-accent hover:text-navy transition-colors cursor-pointer text-xs font-bold text-gray-300" style="--tw-text-navy: #0A192F;">X</div>
                        <div class="w-8 h-8 rounded-full bg-white/10 border border-white/20 flex items-center justify-center hover:bg-accent hover:text-navy transition-colors cursor-pointer text-xs font-bold text-gray-300">fb</div>
                        <div class="w-8 h-8 rounded-full bg-white/10 border border-white/20 flex items-center justify-center hover:bg-accent hover:text-navy transition-colors cursor-pointer text-xs font-bold text-gray-300">ig</div>
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

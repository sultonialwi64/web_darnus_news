<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-sz { font-family: 'Playfair Display', serif; }
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

    <!-- Modern Digital-Native Header -->
    <header class="bg-editorial-header sticky top-0 z-50 border-b border-editorial shadow-sm">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Top Row: Logo & Actions -->
            <div class="flex justify-between items-center h-16 sm:h-20">
                <!-- Left: Logo -->
                <div class="flex items-center flex-shrink-0">
                    <a href="{{ route('home') }}" class="font-sz text-3xl md:text-4xl font-bold tracking-tight text-white hover:text-editorial-accent transition-colors whitespace-nowrap">
                        DarnusNews
                    </a>
                </div>
                
                <!-- Right: Nav, Search, Login -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- Desktop/Mobile Search Toggle -->
                    <button type="button" onclick="document.getElementById('mobileSearchOverlay').classList.remove('hidden'); document.getElementById('mobileSearchInput').focus();" class="text-gray-300 hover:text-editorial-accent transition-colors p-2 cursor-pointer" aria-label="Buka Pencarian">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>

                    <!-- Login Pill -->
                    <a href="{{ url('/admin') }}" class="hidden sm:block text-[10px] font-bold tracking-widest uppercase text-editorial-dark bg-editorial-accent hover:bg-white px-5 py-2 rounded-full transition-colors shadow-lg">
                        Login
                    </a>
                    
                    <a href="{{ url('/admin') }}" class="sm:hidden text-gray-300 hover:text-editorial-accent transition-colors p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </a>
                </div>
            </div>
            
            <!-- Bottom Row: Desktop & Mobile Category Nav (Rubrik) -->
            <nav class="h-12 flex items-center overflow-x-auto hide-scroll-bar border-t border-editorial/50">
                <ul class="flex space-x-6 sm:space-x-8 text-[10px] font-bold tracking-widest uppercase text-gray-400 min-w-max">
                    <li><a href="{{ route('home') }}" class="hover:text-editorial-accent transition-colors text-white border-b-2 border-editorial-accent pb-3 sm:pb-3.5">Beranda</a></li>
                    @foreach($categories ?? [] as $cat)
                    <li><a href="{{ route('search', ['q' => $cat->name]) }}" class="hover:text-editorial-accent transition-colors whitespace-nowrap">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </nav>
        </div>

        <!-- Full-Width Search Overlay -->
        <div id="mobileSearchOverlay" class="hidden absolute inset-0 bg-editorial-header z-[60] flex items-center px-4 sm:px-6 lg:px-8 border-b border-editorial shadow-2xl">
            <form action="{{ route('search') }}" method="GET" class="flex-1 flex items-center max-w-[1280px] mx-auto h-full w-full">
                <svg class="w-6 h-6 text-gray-400 mr-3 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" id="mobileSearchInput" name="q" placeholder="Cari berita terkini..." class="w-full h-full bg-transparent text-white sm:text-auto focus:outline-none placeholder-gray-500 border-none outline-none ring-0">
                <button type="submit" class="text-white hover:text-editorial-accent font-bold tracking-widest uppercase text-sm ml-2 px-2 transition-colors">Cari</button>
                <button type="button" onclick="document.getElementById('mobileSearchOverlay').classList.add('hidden');" class="ml-2 sm:ml-4 text-gray-400 hover:text-white p-2 transition-colors" aria-label="Tutup">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </form>
        </div>
    </header>

    <main class="flex-grow max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        
        <!-- Hero Section: Featured Headline -->
        <div class="mb-16 border-b border-editorial pb-12">
            @if($featuredPost)
            <div class="group w-full max-w-5xl mx-auto cursor-pointer block hover:-translate-y-1 transition-transform duration-500 hover:shadow-2xl rounded-2xl overflow-hidden border border-editorial shadow-lg">
                <a href="{{ route('news.show', $featuredPost->slug) }}" class="flex flex-col w-full bg-editorial-card overflow-hidden">
                    
                    <!-- Cinematic Title & Image Block -->
                    <div class="relative w-full overflow-hidden bg-editorial-dark" style="aspect-ratio: 16/9; max-height: 520px; min-height: 350px;">
                        @if($featuredPost->image)
                            <img src="{{ Storage::url($featuredPost->image) }}" alt="{{ $featuredPost->title }}" class="absolute inset-0 w-full h-full object-cover object-top brightness-[0.8] group-hover:brightness-100 transition-all duration-700 ease-in-out z-0">
                        @else
                            <div class="w-full aspect-video bg-editorial-dark"></div>
                        @endif
                        
                        <!-- Contrast Layer (Gradient Only) -->
                        <div class="absolute inset-x-0 bottom-0 top-1/4 bg-gradient-to-t from-black via-black/40 to-transparent z-10 opacity-90"></div>

                        <!-- Title Overhead Content (Centered) -->
                        <div class="absolute inset-0 z-20 p-6 sm:p-8 lg:p-12 flex flex-col justify-center items-center text-center">
                            <!-- Badges -->
                            <div class="flex flex-wrap items-center justify-center gap-2 mb-4 sm:mb-6 text-[10px] sm:text-[11px] font-bold tracking-widest uppercase">
                                <span class="text-editorial-dark bg-editorial-accent px-4 py-1.5 rounded-full shadow-lg">{{ $featuredPost->category->name }}</span>
                            </div>

                            <!-- Tituler Masterpiece -->
                            <h1 class="w-full max-w-3xl mx-auto font-sz font-bold text-white transition-colors drop-shadow-[0_8px_16px_rgba(0,0,0,1)]" style="font-size: clamp(1.75rem, 3vw + 0.5rem, 3.25rem); line-height: 1.25; padding: 0 1rem;">
                                {{ $featuredPost->title }}
                            </h1>
                        </div>
                    </div>

                    <!-- Solid Background Deskripsi Block -->
                    <div class="w-full flex-grow flex flex-col p-6 sm:p-8 lg:p-12 items-center text-center pb-8 z-30 relative bg-[#1b2533]">
                        <!-- Meta Date & Region -->
                        <div class="flex items-center justify-center gap-2 mb-5 text-[10px] sm:text-[11px] font-bold tracking-widest uppercase text-gray-400">
                            @if($featuredPost->region)
                                <span>{{ $featuredPost->region->name }}</span>
                                <span>·</span>
                            @endif
                            <span>{{ $featuredPost->created_at->format('d M Y') }}</span>
                        </div>
 
                        <!-- Excerpt -->
                        <p class="w-full text-gray-300 text-base sm:text-lg leading-relaxed font-sz mb-8 line-clamp-3 md:line-clamp-4">
                            {{ htmlspecialchars_decode($featuredPost->excerpt ?: Str::limit(strip_tags($featuredPost->content), 200)) }}
                        </p>
                        
                        <!-- Footer Edge Info -->
                        <div class="w-full mt-auto text-[10px] sm:text-xs text-editorial-muted font-bold uppercase tracking-wider flex items-center justify-between pt-6 border-t border-gray-600/50">
                            <span class="group-hover:text-editorial-accent transition-colors">Baca Selengkapnya &rarr;</span>
                            <span class="flex items-center group-hover:text-white transition-colors">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                {{ number_format($featuredPost->views) }} Views
                            </span>
                        </div>>
                    </div>
                </a>
            </div>
            @endif
        </div>

        <!-- 4 Column Breakout News -->
        @if($latestPosts && count($latestPosts) > 0)
        <div class="mb-16 border-b border-editorial pb-16">
            <h2 class="text-center font-sz text-3xl font-bold text-gray-200 mb-10 pb-4 border-b border-gray-700 mx-auto w-max px-12 tracking-wide">Berita Terkini</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($latestPosts->take(4) as $post)
                <a href="{{ route('news.show', $post->slug) }}" class="group block h-full flex flex-col bg-editorial-card rounded-2xl border border-editorial p-5 hover:border-gray-500 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                    @if($post->image)
                        <div class="w-full aspect-[4/3] mb-5 overflow-hidden rounded-xl bg-editorial-dark">
                            <img src="{{ Storage::url($post->image) }}" class="w-full h-full object-cover brightness-[0.8] group-hover:brightness-100 transition-all duration-500">
                        </div>
                    @endif
                    <div class="flex flex-col flex-grow">
                        @if($post->region)
                        <div class="text-[10px] font-bold tracking-widest uppercase text-editorial-accent mb-3 flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-editorial-accent mr-2"></span> {{ $post->region->name }}
                        </div>
                        @else
                        <div class="mb-3"></div>
                        @endif
                        <h3 class="font-sz text-xl font-bold text-gray-100 leading-snug group-hover:text-editorial-accent transition-colors mb-4 pb-4 border-b border-editorial opacity-90">
                            {{ $post->title }}
                        </h3>
                        <time class="mt-auto text-[10px] text-gray-500 font-bold uppercase tracking-wider">
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
                    <a href="{{ route('news.show', $post->slug) }}" class="group flex flex-col sm:flex-row items-center py-6 mb-4 bg-editorial-card rounded-2xl border border-editorial hover:border-gray-500 hover:shadow-lg transition-all p-5">
                        @if($post->image)
                            <div class="w-full sm:w-1/3 aspect-[4/3] rounded-xl overflow-hidden border border-editorial flex-shrink-0 sm:mr-6 mb-4 sm:mb-0 bg-editorial-dark">
                                <img src="{{ Storage::url($post->image) }}" class="w-full h-full object-cover brightness-[0.8] group-hover:brightness-100 transition-all duration-500">
                            </div>
                        @endif
                        <div class="flex-grow flex flex-col justify-center">
                            <div class="flex items-center text-[10px] font-bold tracking-widest uppercase mb-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-600 mr-2 opacity-80"></span>
                                <span class="text-gray-300 mr-2">{{ $post->category->name }}</span>
                                @if($post->region)
                                <span class="text-editorial-muted">{{ $post->region->name }}</span>
                                @endif
                            </div>
                            <h3 class="font-sz text-2xl font-bold text-gray-100 leading-snug group-hover:text-editorial-accent transition-colors mb-2">
                                {{ $post->title }}
                            </h3>
                            <p class="text-gray-400 font-sz text-base leading-relaxed line-clamp-2 mb-4">
                                {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 150) }}
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
                <h2 class="font-sz text-xl font-bold text-slate-500 mb-8 pb-4 border-b border-slate-200 tracking-wide uppercase">
                    Paling Banyak Dibaca
                </h2>
                <div class="flex flex-col">
                    @foreach($popularPosts->take(6) as $index => $post)
                    <a href="{{ route('news.show', $post->slug) }}" class="group flex items-start py-5 border-b border-slate-200 last:border-0 relative">
                        <span class="font-sz text-4xl italic font-bold text-slate-200 mr-4 flex-shrink-0 group-hover:text-editorial-accent transition-colors">{{ $index + 1 }}</span>
                        <div class="flex flex-col">
                            <span class="text-[10px] uppercase tracking-widest font-bold text-editorial-accent mb-1">{{ $post->category->name }}</span>
                            <h3 class="font-sz text-base font-bold text-gray-200 leading-snug group-hover:text-white transition-colors line-clamp-3 mb-2">
                                {{ $post->title }}
                            </h3>
                            <div class="flex items-center text-[10px] text-editorial-muted font-bold uppercase tracking-wider">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                {{ number_format($post->views) }} dilihat
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                
                <!-- Ad Placeholder simulating real news site -->
                <div class="mt-12 bg-editorial-header border border-editorial p-8 text-center flex flex-col items-center justify-center h-64 opacity-50">
                    <span class="text-[10px] text-gray-500 uppercase tracking-widest mb-2 border-b border-gray-700 pb-1">ADVERTISEMENT / Iklan</span>
                    <span class="text-gray-600 font-bold tracking-widest">Ruang Iklan Tersedia</span>
                </div>
            </div>
            @endif

        </div>
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


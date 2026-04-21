<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $post->title }} - DarnusNews</title>
    <meta name="description" content="{{ $post->summary }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,800;1,400;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ================================================
         * Classic Authority Theme — DarnusNews
         * Body: #FFFFFF | Header/Footer: #0A192F (Navy)
         * Card: #F8F9FA | Heading: #111827 | Body: #374151
         * Accent: #F59E0B (Amber)
         * ================================================ */
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FFFFFF; color: #374151; }
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
        .bg-navy-deep      { background-color: #060F1E; }
        .border-navy        { border-color: #1E3A5F; }
        .bg-section         { background-color: #F8F9FA; }
        .text-accent        { color: #F59E0B; }
        .bg-accent          { background-color: #F59E0B; }
        .text-muted         { color: #6B7280; }
        .text-heading       { color: #111827; }
        .text-body          { color: #374151; }
        .border-light       { border-color: #E5E7EB; }

        /* Article body typography */
        .article-body p {
            margin-bottom: 1.75rem;
            font-size: 1.125rem;
            line-height: 1.9;
            color: #374151;
            font-family: 'Playfair Display', serif;
        }
        .article-body h2 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.75rem;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            color: #111827;
            border-left: 3px solid #F59E0B;
            padding-left: 1rem;
        }
        .article-body a { color: #D97706; text-decoration: underline; }
        .article-body a:hover { color: #92400E; }
        .article-body strong { color: #111827; }
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
                    <a href="{{ url('/admin') }}" class="hidden sm:block text-[10px] font-bold tracking-widest uppercase text-navy bg-accent hover:bg-white hover:text-navy px-5 py-2 rounded-full transition-colors shadow-lg">
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
                    <li><a href="{{ route('search', ['q' => $cat->name]) }}" class="hover:text-accent transition-colors whitespace-nowrap {{ (isset($post) && $post->category_id === $cat->id) ? 'text-accent border-b-2 border-amber-400 pb-3 sm:pb-3' : '' }}">{{ $cat->name }}</a></li>
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

    <main class="flex-grow max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
        <article>
            <!-- Article Header -->
            <div class="mb-10 text-center">
                <div class="flex items-center justify-center space-x-3 text-[11px] font-bold tracking-widest uppercase mb-6">
                    <span class="text-amber-600 bg-amber-50 border border-amber-200 px-3 py-1 rounded">{{ $post->category->name }}</span>
                    @if($post->region)
                        <span class="text-gray-300">·</span>
                        <span class="text-muted">{{ $post->region->name }}</span>
                    @endif
                </div>

                <h1 class="font-sz text-4xl md:text-5xl lg:text-6xl font-bold leading-tight text-heading mb-8">
                    {{ $post->title }}
                </h1>

                <div class="flex items-center justify-center space-x-6 text-xs text-muted font-bold uppercase tracking-wider border-t border-b border-light py-4">
                    <span>{{ $post->created_at->format('d F Y') }}</span>
                    <span class="text-gray-300">·</span>
                    <span class="flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        {{ number_format($post->views) }} pembaca
                    </span>
                </div>
            </div>

            <!-- Featured Image -->
            @if($post->image)
            <figure class="mb-12 border border-light rounded-2xl overflow-hidden shadow-sm">
                <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full object-cover object-top aspect-[16/9]">
                <figcaption class="mt-0 text-[11px] text-muted font-medium px-4 py-2 border-t border-light italic bg-section">
                    {{ $post->image_caption ?: 'Foto: DarnusNews' . ($post->region ? ' / ' . $post->region->name : '') }}
                </figcaption>
            </figure>
            @endif

            <!-- Article Body -->
            <div class="article-body">
                {!! $post->rendered_content !!}
            </div>

            <!-- Berita Terkait / Related Posts -->
            @if(isset($relatedPosts) && $relatedPosts->count() > 0)
            <div class="mt-12 mb-8 bg-gray-50 rounded-2xl p-6 border-l-4 border-amber-400 shadow-sm">
                <h3 class="font-sz text-xl font-bold text-heading mb-6 flex items-center">
                    Berita Terkait
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($relatedPosts as $related)
                    <a href="{{ route('news.show', $related->slug) }}" class="group flex flex-col p-4 bg-white shadow-sm rounded-xl hover:shadow-md transition-all duration-300">
                        <div class="flex items-center text-[10px] font-bold tracking-widest uppercase mb-2">
                            <span class="text-accent mr-2">{{ $related->category->name }}</span>
                            @if($related->region)
                            <span class="text-muted">{{ $related->region->name }}</span>
                            @endif
                        </div>
                        <h4 class="font-sz text-lg font-bold text-heading leading-snug group-hover:text-amber-700 transition-colors line-clamp-2">
                            {{ $related->title }}
                        </h4>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Editorial Credits -->
            <div class="mt-12 p-6 bg-section rounded-2xl border border-light">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-accent font-bold text-lg">
                            {{ substr($post->author->name ?? 'D', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-[10px] font-bold tracking-widest uppercase text-muted">Penulis</p>
                            <p class="text-heading font-bold">{{ $post->author->name ?? 'Redaksi Darnus' }}</p>
                        </div>
                    </div>
                    @if($post->editor)
                    <div class="flex items-center space-x-4 border-t sm:border-t-0 sm:border-l border-light pt-4 sm:pt-0 sm:pl-8">
                        <div>
                            <p class="text-[10px] font-bold tracking-widest uppercase text-muted">Editor</p>
                            <p class="text-heading font-bold">{{ $post->editor->name }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                @if($post->source)
                <div class="mt-6 pt-4 border-t border-light text-[11px] text-muted italic">
                    Sumber: {{ $post->source }}
                </div>
                @endif
            </div>

            <!-- Back Link -->
            <div class="mt-16 pt-8 border-t border-light">
                <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-bold uppercase tracking-widest text-muted hover:text-amber-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Beranda
                </a>
            </div>
        </article>
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
                        <div class="w-8 h-8 rounded-full bg-white/10 border border-white/20 flex items-center justify-center hover:bg-accent hover:text-navy transition-colors cursor-pointer text-xs font-bold text-gray-300">X</div>
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

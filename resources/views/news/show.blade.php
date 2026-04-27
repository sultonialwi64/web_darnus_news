<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $post->title }} - DMN NEWS</title>
    <meta name="description" content="{{ $post->summary }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ $post->summary }}">
    <meta property="og:image" content="{{ $post->image ? url(Storage::url($post->image)) : asset('images/logo.jpg') }}">
    <meta property="article:published_time" content="{{ $post->created_at->toIso8601String() }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ request()->url() }}">
    <meta property="twitter:title" content="{{ $post->title }}">
    <meta property="twitter:description" content="{{ $post->summary }}">
    <meta property="twitter:image" content="{{ $post->image ? url(Storage::url($post->image)) : asset('images/logo.jpg') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Roboto:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ================================================
         * Classic Authority Theme — DarnusNews
         * Body: #FFFFFF | Header/Footer: #0A192F (Navy)
         * Card: #F8F9FA | Heading: #111827 | Body: #374151
         * Accent: #F59E0B (Amber)
         * ================================================ */
        body { font-family: 'Inter', sans-serif; background-color: #FFFFFF; color: #374151; }
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
            font-family: 'Roboto', sans-serif;
        }
        .article-body h2 {
            font-family: 'Inter', sans-serif;
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
                    <a href="{{ route('home') }}" class="flex items-center py-1">
                        <img src="{{ asset('images/logo.jpg') }}" alt="DMN NEWS Logo" class="h-12 md:h-16 w-auto">
                    </a>
                </div>

                <!-- Right: Search & Login -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <button type="button" onclick="document.getElementById('mobileSearchOverlay').classList.remove('hidden'); document.getElementById('mobileSearchInput').focus();" class="text-gray-300 hover:text-accent transition-colors p-2 cursor-pointer" aria-label="Buka Pencarian">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>


                </div>
            </div>

            <!-- Bottom Row: Category Nav -->
            <nav class="h-11 flex items-center overflow-x-auto hide-scroll-bar border-t border-navy">
                <ul class="flex space-x-6 sm:space-x-8 text-[10px] font-bold tracking-widest uppercase text-gray-400 min-w-max">
                    <li><a href="{{ route('home') }}" class="hover:text-accent transition-colors text-white">Beranda</a></li>
                    @foreach($categories ?? [] as $cat)
                    <li><a href="{{ route('search', ['category' => $cat->slug]) }}" class="hover:text-accent transition-colors whitespace-nowrap {{ (isset($post) && $post->category_id === $cat->id) ? 'text-accent border-b-2 border-amber-400 pb-3 sm:pb-3' : '' }}">{{ $cat->name }}</a></li>
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

                <h1 class="font-sz text-3xl md:text-4xl lg:text-5xl font-bold leading-tight text-heading mb-6">
                    {{ $post->title }}
                </h1>
            </div>

            <div class="flex flex-col md:flex-row md:items-center justify-between border-t border-b border-gray-100 py-4 mb-8 mt-2 gap-4">
                    <!-- Left Side: Author Info -->
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-8 h-11 bg-gray-50 rounded-full flex items-center justify-center border border-gray-100">
                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                        <div class="flex flex-col items-start text-left">
                            <p class="text-[13px] font-bold leading-tight">
                                <span class="text-amber-600">{{ $post->author->name ?? 'Redaksi' }}</span>, 
                                <span class="text-gray-900">dmnnews.com</span>
                            </p>
                            <p class="text-[11px] text-gray-400 font-medium mt-0.5">
                                {{ $post->created_at->translatedFormat('l, d F Y | H:i') }}
                            </p>
                        </div>
                    </div>

                    <!-- Right Side: Share Buttons -->
                    <div class="flex items-center space-x-2">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mr-1">Bagikan:</span>
                        <div class="flex items-center space-x-1.5">
                            <!-- WhatsApp -->
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . "\n\n" . request()->url()) }}" target="_blank" class="w-8 h-8 rounded-full flex items-center justify-center text-white transition hover:opacity-80 shadow-sm" style="background-color: #25D366;">
                                <svg class="w-4 h-4" fill="white" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.878-.788-1.47-1.761-1.643-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                            </a>
                            
                            <!-- Facebook -->
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="w-8 h-8 rounded-full flex items-center justify-center text-white transition hover:opacity-80 shadow-sm" style="background-color: #3b5998;">
                                <svg class="w-4 h-4" fill="white" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                            </a>
                            
                            <!-- X (Twitter) -->
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(request()->url()) }}" target="_blank" class="w-8 h-8 rounded-full flex items-center justify-center text-white transition hover:opacity-80 shadow-sm" style="background-color: #000000;">
                                <svg class="w-4 h-4" fill="white" viewBox="0 0 24 24"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/></svg>
                            </a>

                            <!-- Copy Link -->
                            <button type="button" onclick="navigator.clipboard.writeText('{{ request()->url() }}'); alert('Link berhasil disalin!')" class="w-8 h-8 rounded-full flex items-center justify-center text-white transition hover:opacity-80 shadow-sm" style="background-color: #9CA3AF;">
                                <svg class="w-4 h-4" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                            </button>
                    </div>
                </div>
            </div>

            <!-- Featured Image -->
            @if($post->image)
            <figure class="mb-12 border border-light rounded-2xl overflow-hidden shadow-sm">
                <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" 
                     class="w-full object-cover h-auto">
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

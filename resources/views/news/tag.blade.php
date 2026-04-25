<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>#{{ $tag->name }} - DMN NEWS</title>
    <meta name="description" content="Kumpulan berita terbaru mengenai #{{ $tag->name }} di DMN NEWS.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #FFFFFF; color: #374151; }
        .font-sz { font-family: 'Inter', sans-serif; letter-spacing: -0.02em; }
        .hide-scroll-bar::-webkit-scrollbar { display: none; }
        .hide-scroll-bar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Palette */
        .bg-navy           { background-color: #0A192F; }
        .bg-navy-deep      { background-color: #060F1E; }
        .border-navy        { border-color: #1E3A5F; }
        .text-accent        { color: #F59E0B; }
        .bg-accent          { background-color: #F59E0B; }
        .text-muted         { color: #6B7280; }
        .text-heading       { color: #111827; }
        .border-light       { border-color: #E5E7EB; }

        .tag-header {
            background: linear-gradient(135deg, #0A192F 0%, #111827 100%);
            padding: 4rem 1rem;
            text-align: center;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-navy sticky top-0 z-50 border-b border-navy shadow-md">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <div class="flex items-center flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center py-1">
                        <img src="{{ asset('images/logo.jpg') }}" alt="DMN NEWS Logo" class="h-12 md:h-16 w-auto">
                    </a>
                </div>

                <div class="flex items-center space-x-2 sm:space-x-4">
                    <button type="button" onclick="window.location='{{ route('home') }}#search'" class="text-gray-300 hover:text-accent transition-colors p-2 cursor-pointer">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                    <a href="{{ url('/admin') }}" class="hidden sm:block text-[10px] font-bold tracking-widest uppercase text-navy bg-accent hover:bg-white hover:text-navy px-5 py-2 rounded-full transition-colors shadow-lg">Login</a>
                </div>
            </div>

            <nav class="h-11 flex items-center overflow-x-auto hide-scroll-bar border-t border-navy">
                <ul class="flex space-x-6 sm:space-x-8 text-[10px] font-bold tracking-widest uppercase text-gray-400 min-w-max">
                    <li><a href="{{ route('home') }}" class="hover:text-accent transition-colors text-white">Beranda</a></li>
                    @foreach($categories ?? [] as $cat)
                    <li><a href="{{ route('search', ['category' => $cat->slug]) }}" class="hover:text-accent transition-colors whitespace-nowrap">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </header>

    <!-- Tag Hero -->
    <section class="tag-header">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4">
                <span class="text-accent">#</span>{{ $tag->name }}
            </h1>
            <p class="text-gray-400 text-sm md:text-base font-medium tracking-wide border-t border-white/10 pt-4 inline-block">
                Kumpulan berita terbaru seputar {{ $tag->name }}
            </p>
        </div>
    </section>

    <!-- News List -->
    <main class="flex-grow max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
        @if($posts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts as $p)
            <article class="flex flex-col group bg-white border border-light rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300">
                <a href="{{ route('news.show', $p->slug) }}" class="relative overflow-hidden aspect-[16/10]">
                    <img src="{{ $p->image ? Storage::url($p->image) : asset('images/logo.jpg') }}" 
                         alt="{{ $p->title }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="absolute top-4 left-4">
                        <span class="bg-accent text-navy text-[9px] font-extrabold px-2 py-1 rounded-sm uppercase tracking-widest shadow-sm">
                            {{ $p->category->name }}
                        </span>
                    </div>
                </a>
                <div class="p-6 flex flex-col flex-1">
                    <div class="flex items-center text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">
                        <span class="text-amber-600">{{ $p->region->name ?? 'Nasional' }}</span>
                        <span class="mx-2">·</span>
                        <span>{{ $p->created_at->translatedFormat('d M Y') }}</span>
                    </div>
                    <h2 class="text-xl font-bold text-heading leading-tight mb-4 group-hover:text-amber-700 transition-colors">
                        <a href="{{ route('news.show', $p->slug) }}">{{ $p->title }}</a>
                    </h2>
                    <p class="text-gray-500 text-sm line-clamp-3 mb-6 flex-1">
                        {{ $p->summary }}
                    </p>
                    <a href="{{ route('news.show', $p->slug) }}" class="inline-flex items-center text-[10px] font-extrabold uppercase tracking-widest text-navy bg-gray-50 group-hover:bg-accent group-hover:text-navy px-4 py-2 rounded transition-all w-fit">
                        Baca Selengkapnya
                        <svg class="w-3 h-3 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </article>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-16">
            {{ $posts->links() }}
        </div>
        @else
        <div class="text-center py-20 bg-gray-50 rounded-3xl border border-dashed border-gray-200">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM14 4v4h4"></path></svg>
            <h3 class="text-xl font-bold text-gray-500">Belum ada berita untuk tag ini.</h3>
            <p class="text-gray-400 mt-2">Daftar berita akan muncul setelah tim redaksi menerbitkan berita dengan tag #{{ $tag->name }}.</p>
            <a href="{{ route('home') }}" class="inline-block mt-8 text-accent font-bold hover:underline">Kembali ke Beranda</a>
        </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-navy border-t border-navy mt-20 py-16">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mt-16 pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center text-[11px] font-bold tracking-widest uppercase text-gray-500">
                <p>&copy; {{ date('Y') }} DMN Media. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="{{ route('about') }}" class="hover:text-white">Tentang Kami</a>
                    <a href="{{ route('editorial') }}" class="hover:text-white">Redaksi</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

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

    <!-- Editorial Header -->
    <header class="bg-editorial-header border-b border-editorial">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Left: Search -->
                <div class="flex items-center space-x-6 w-1/3">
                    <form action="{{ route('search') }}" method="GET" class="hidden md:flex items-center relative">
                        <svg class="w-5 h-5 text-gray-400 absolute left-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" name="q" placeholder="Cari berita..." class="bg-transparent border-b border-gray-600 pl-8 pb-1 text-sm focus:outline-none focus:border-white transition-colors w-32 focus:w-48 text-white">
                    </form>
                </div>

                <!-- Center: Logo -->
                <div class="text-center w-1/3 flex justify-center">
                    <a href="{{ route('home') }}" class="font-sz text-3xl md:text-5xl font-bold tracking-tight hover:opacity-80 transition-opacity whitespace-nowrap">
                        DarnusNews
                    </a>
                </div>

                <!-- Right: Login -->
                <div class="flex items-center justify-end w-1/3">
                    <a href="{{ url('/admin') }}" class="text-xs font-semibold tracking-widest uppercase text-gray-300 hover:text-white transition-colors">
                        Login
                    </a>
                </div>
            </div>

            <!-- Category Nav -->
            <nav class="h-12 flex items-center justify-center border-t border-editorial mt-2">
                <ul class="flex overflow-x-auto hide-scroll-bar space-x-6 md:space-x-10 text-[11px] font-semibold tracking-widest uppercase text-gray-400 pb-1">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a></li>
                    @foreach($categories ?? [] as $cat)
                    <li><a href="#" class="hover:text-white transition-colors whitespace-nowrap {{ $post->category->id === $cat->id ? 'text-editorial-accent' : '' }}">{{ $cat->name }}</a></li>
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

    <!-- Footer -->
    <footer class="bg-editorial-header border-t-2 border-editorial mt-20 py-16">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
                <div class="mb-10 lg:mb-0">
                    <h2 class="font-sz text-4xl font-bold mb-4 text-white">DarnusNews</h2>
                    <p class="text-editorial-muted text-sm max-w-md">Jurnalistik mendalam, analitis, dan berwibawa internasional. Menyajikan laporan tangan pertama yang kredibel.</p>
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

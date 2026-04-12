<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hasil Pencarian: {{ $query }} - DarnusNews</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0F172A; color: #E2E8F0; }
        .font-sz { font-family: 'PT Serif', serif; }
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

    <!-- Editorial Header -->
    <header class="bg-editorial-header border-b border-editorial">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Left: Search -->
                <div class="flex items-center space-x-6 w-1/3">
                    <form action="{{ route('search') }}" method="GET" class="hidden md:flex items-center relative">
                        <svg class="w-5 h-5 text-gray-400 absolute left-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" name="q" value="{{ $query }}" placeholder="Cari berita..." class="bg-transparent border-b border-gray-600 pl-8 pb-1 text-sm focus:outline-none focus:border-white transition-colors w-40 focus:w-56 text-white">
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
                    <li><a href="#" class="hover:text-white transition-colors whitespace-nowrap">{{ $cat->name }}</a></li>
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
            <a href="{{ route('news.show', $post->slug) }}" class="group block flex flex-col bg-editorial-card border border-editorial p-5 hover:border-gray-500 transition-colors">
                @if($post->image)
                    <div class="w-full aspect-[4/3] mb-5 overflow-hidden bg-editorial-dark filter grayscale group-hover:grayscale-0 transition-all duration-500">
                        <img src="{{ Storage::url($post->image) }}" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="w-full aspect-[4/3] mb-5 bg-editorial-dark flex items-center justify-center border border-editorial">
                        <span class="text-editorial-muted text-xs tracking-widest uppercase">No Image</span>
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
        @endif

    </main>

    <!-- Footer -->
    <footer class="bg-editorial-header border-t-2 border-editorial mt-auto py-16">
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

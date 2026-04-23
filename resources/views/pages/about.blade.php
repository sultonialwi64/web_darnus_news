<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tentang Kami - DMN NEWS</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #F9FAFB; 
            color: #374151; 
            line-height: 1.6;
        }
        .bg-navy { background-color: #0A192F; }
        .text-navy { color: #0A192F; }
        .border-navy { border-color: #1E3A5F; }
        .text-accent { color: #F59E0B; }
        .bg-accent-soft { background-color: #FFFBEB; }
        
        .font-plus { font-family: 'Plus Jakarta Sans', sans-serif; }

        .main-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .statement-card {
            background: white;
            border: 1px solid #E5E7EB;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }

        .pillar-card {
            background: white;
            border: 1px solid #F3F4F6;
            transition: all 0.3s ease;
        }
        .pillar-card:hover {
            border-color: #FCD34D;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        .quote-accent {
            width: 4px;
            background: linear-gradient(to bottom, #F59E0B, #D97706);
            border-radius: 2px;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-navy sticky top-0 z-50 border-b border-navy shadow-sm">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                <div class="flex items-center">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/logo.jpg') }}" alt="DMN NEWS Logo" class="h-10 md:h-14 w-auto">
                    </a>
                </div>
                <div>
                    <a href="{{ route('home') }}" class="text-[10px] font-bold uppercase tracking-widest text-gray-300 hover:text-accent transition-colors">Beranda</a>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow py-12 sm:py-20 px-4 sm:px-6 text-center">
        <div class="main-container">
            
            <!-- Page Heading -->
            <div class="mb-16">
                <h1 class="font-plus text-4xl sm:text-5xl font-extrabold text-[#0A192F] tracking-tight mb-4">Tentang Kami</h1>
                <div class="flex justify-center items-center space-x-2 text-[10px] sm:text-xs font-bold uppercase tracking-[0.3em] text-amber-600">
                    <span>Integritas</span>
                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                    <span>Akurasi</span>
                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                    <span>Independensi</span>
                </div>
            </div>

            <!-- Content Section -->
            <div class="statement-card rounded-2xl overflow-hidden mb-12">
                <div class="p-8 sm:p-12 lg:p-16">
                    <!-- Manifesto Text -->
                    <div class="relative mb-12">
                        <p class="text-xl sm:text-3xl font-plus font-bold text-gray-900 leading-snug italic text-center">
                            "Media ini hadir sebagai ruang informasi publik yang <span class="text-amber-600">akurat, berimbang, dan bertanggung jawab</span>. Kami berkomitmen mengawal pemerintahan Presiden Prabowo Subianto dan Wakil Presiden Gibran Rakabuming Raka melalui pemberitaan yang konstruktif, berbasis data, dan tetap kritis. Dukungan kami berpijak pada kepentingan rakyat, dengan menjaga integritas jurnalistik dan independensi redaksi."
                        </p>
                    </div>

                    <div class="w-16 h-1 bg-gray-100 mx-auto mb-16"></div>

                    <!-- Pillars List -->
                    <div class="space-y-6">
                        <!-- Akurat -->
                        <div class="pillar-card p-10 rounded-2xl text-center">
                            <div class="w-16 h-1 bg-amber-100 rounded-full mx-auto mb-8 hidden sm:block"></div>
                            <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-amber-100">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-sm font-black uppercase tracking-[0.2em] text-gray-900 mb-3">Akurat</h3>
                            <p class="text-sm text-gray-500 max-w-sm mx-auto leading-relaxed font-medium">Verifikasi data yang mendalam sebelum dipublikasikan untuk menjaga kepercayaan publik.</p>
                        </div>

                        <!-- Berimbang -->
                        <div class="pillar-card p-10 rounded-2xl text-center">
                            <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-amber-100">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                            </div>
                            <h3 class="text-sm font-black uppercase tracking-[0.2em] text-gray-900 mb-3">Berimbang</h3>
                            <p class="text-sm text-gray-500 max-w-sm mx-auto leading-relaxed font-medium">Menyajikan opini dan fakta dari berbagai perspektif demi jurnalisme yang adil.</p>
                        </div>

                        <!-- Kritis -->
                        <div class="pillar-card p-10 rounded-2xl text-center">
                            <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-amber-100">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <h3 class="text-sm font-black uppercase tracking-[0.2em] text-gray-900 mb-3">Kritis</h3>
                            <p class="text-sm text-gray-500 max-w-sm mx-auto leading-relaxed font-medium">Mengawal setiap kebijakan pemerintah demi kepentingan rakyat banyak.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Action -->
            <div class="text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-navy transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-50 border-t border-gray-100 py-12">
        <div class="text-center px-4">
            <p class="text-gray-400 text-[10px] font-bold tracking-widest uppercase">DMN Media Group &sdot; {{ date('Y') }}</p>
        </div>
    </footer>

</body>
</html>

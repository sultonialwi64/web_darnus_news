<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Susunan Redaksi - DMN NEWS</title>
    
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
        
        .font-plus { font-family: 'Plus Jakarta Sans', sans-serif; }

        .main-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .editorial-card {
            background: white;
            border: 1px solid #E5E7EB;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border-radius: 1.5rem;
        }

        .member-card {
            background: #FDFDFD;
            border: 1px solid #F3F4F6;
            transition: all 0.2s ease-in-out;
            border-radius: 1rem;
        }
        .member-card:hover {
            border-color: #FCD34D;
            background: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        .role-badge {
            display: inline-block;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.25em;
            color: #F59E0B;
            background: #FFFBEB;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            margin-bottom: 0.75rem;
            border: 1px solid #FEF3C7;
        }

        .name-text {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            color: #0A192F;
            letter-spacing: -0.01em;
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

    <main class="flex-grow py-12 sm:py-20 px-4 sm:px-6">
        <div class="main-container">
            
            <!-- Page Heading -->
            <div class="text-center mb-16">
                <h1 class="font-plus text-4xl sm:text-5xl font-extrabold text-[#0A192F] tracking-tight mb-4">Susunan Redaksi</h1>
                <div class="flex justify-center items-center space-x-2 text-[10px] sm:text-xs font-bold uppercase tracking-[0.3em] text-gray-400">
                    <span>DMN Media Group</span>
                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                    <span>Integritas Jurnalis</span>
                </div>
            </div>

            <!-- Team Sections -->
            <div class="space-y-12">
                
                <!-- Leadership Group -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-16">
                    <div class="member-card p-8 text-center sm:col-span-2">
                        <span class="role-badge">Pemimpin Perusahaan</span>
                        <h2 class="name-text">Taufik Ismail</h2>
                    </div>

                    <div class="member-card p-8 text-center">
                        <span class="role-badge">Pemimpin Redaksi</span>
                        <h2 class="name-text">Mbah Roso</h2>
                    </div>

                    <div class="member-card p-8 text-center">
                        <span class="role-badge">Koordinator Redaksi Pelaksana</span>
                        <h2 class="name-text">Wawan Kurniawan</h2>
                    </div>

                    <div class="member-card p-8 text-center">
                        <span class="role-badge">Koordinator Liputan</span>
                        <h2 class="name-text">Andy Gris</h2>
                    </div>

                    <div class="member-card p-8 text-center">
                        <span class="role-badge">Sekretaris Redaksi</span>
                        <h2 class="name-text">Chichie</h2>
                    </div>
                </div>



                <!-- Team Section: Reporters & IT -->
                <div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="member-card p-10 text-center">
                            <span class="role-badge">Reporter / Jurnalis</span>
                            <h3 class="name-text">Galoeh Kurniawan</h3>
                        </div>
                        <div class="member-card p-10 text-center">
                            <span class="role-badge">Reporter / Jurnalis</span>
                            <h3 class="name-text">Vian</h3>
                        </div>
                        <div class="member-card p-10 text-center">
                            <span class="role-badge">Reporter / Jurnalis</span>
                            <h3 class="name-text">Abay</h3>
                        </div>
                        <div class="member-card p-10 text-center">
                            <span class="role-badge">Information Technology (IT)</span>
                            <h3 class="name-text">Sulton</h3>
                        </div>
                    </div>
                </div>

                <!-- Contact Redaksi Section -->
                <div class="mt-20 py-12 border-t border-gray-100 text-center">
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-gray-500 mb-6">Hubungi Redaksi</p>
                    <a href="https://wa.me/62877566655758" target="_blank" 
                       style="background-color: #25D366; color: white; display: inline-flex; align-items: center; padding: 1rem 2.5rem; border-radius: 1rem; text-decoration: none; font-weight: bold; box-shadow: 0 10px 15px -3px rgba(37, 211, 102, 0.2);"
                       class="hover:opacity-90 transition-all hover:-translate-y-1">
                        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.067 2.877 1.215 3.076.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        <span>Hubungi Redaksi:0877-5666-55758</span>
                    </a>
                </div>
            </div>

            <!-- Footer Action -->
            <div class="text-center mt-12">
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

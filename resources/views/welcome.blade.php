<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    {{-- META & TITLE --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PawCareer — Klinik Hewan Sahabat Satwa</title>

    {{-- FONTS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- STYLES & SCRIPTS --}}
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        forest: {
                            DEFAULT: '#183828',
                            dark: '#11291D',
                            light: '#244F39',
                        },
                        cream: {
                            DEFAULT: '#FAF7F2',
                            warm: '#F5EDE0',
                        },
                        terracotta: {
                            DEFAULT: '#E68A3E',
                            hover: '#D4782C',
                            soft: '#FDF2E6',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-[#FAF7F2] via-[#FBF6EE] to-[#F3E5D0] text-forest antialiased selection:bg-terracotta/20 selection:text-forest">

    {{-- NAVBAR --}}
    <header class="sticky top-0 z-40 w-full backdrop-blur-md bg-[#FAF7F2]/80 border-b border-forest/5">
        <div class="max-w-7xl mx-auto px-6 lg:px-10 h-20 flex items-center justify-between">
            {{-- BRAND LOGO --}}
            <a href="{{ url('/') }}" class="flex items-center gap-3.5 group">
                <div class="w-11 h-11 rounded-2xl overflow-hidden bg-forest shadow-sm ring-2 ring-forest/10 group-hover:scale-105 transition-transform">
                    <img src="{{ asset('images/logo-paw.jpg') }}" alt="Logo PawCareer" class="w-full h-full object-cover">
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-extrabold tracking-tight text-forest leading-none">PawCareer</span>
                    <span class="text-xs font-medium text-forest/65 mt-1">Klinik Hewan Sahabat Satwa</span>
                </div>
            </a>

            {{-- NAVIGATION LINKS --}}
            @if (Route::has('login'))
                <nav class="flex items-center gap-6 sm:gap-8">
                    <a href="#lowongan"
                       class="text-sm font-semibold text-forest/80 hover:text-forest transition-colors">
                        Lowongan
                    </a>

                    @auth
                        <a href="{{ url('/jobs') }}"
                           class="inline-flex items-center justify-center px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-forest hover:bg-forest-dark shadow-sm transition-all">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-sm font-semibold text-forest/80 hover:text-forest transition-colors">
                            Masuk
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center justify-center px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-forest hover:bg-forest-dark shadow-sm hover:shadow transition-all">
                                Daftar
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    {{-- MAIN HERO SECTION --}}
    <main class="max-w-7xl mx-auto px-6 lg:px-10 pt-10 pb-20 lg:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-10 items-center">

            {{-- KIRI: HEADLINE & TOMBOL AKSI --}}
            <div class="lg:col-span-6 space-y-7">
                {{-- BADGE INFO --}}
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/90 border border-forest/10 shadow-xs">
                    <span class="text-terracotta text-sm">✦</span>
                    <span class="text-xs font-bold text-forest/80 tracking-wide">
                        @if(isset($jobPostings) && $jobPostings->count() > 0)
                            {{ $jobPostings->count() }} lowongan sedang dibuka
                        @else
                            Lowongan karier sedang dibuka
                        @endif
                    </span>
                </div>

                {{-- JUDUL UTAMA --}}
                <div class="space-y-2">
                    <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-extrabold text-forest leading-[1.12] tracking-tight">
                        Rawat mereka,<br>
                        tumbuh bersama<br>
                        <span class="relative inline-block">
                            kami
                            <svg class="w-full h-3.5 -mt-1 text-terracotta" viewBox="0 0 280 14" fill="none" preserveAspectRatio="none">
                                <path d="M3 10.5C75 4.5 190 3.5 277 8.5" stroke="currentColor" stroke-width="5.5" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </h1>
                </div>

                {{-- DESKRIPSI --}}
                <p class="text-base sm:text-lg text-forest/70 leading-relaxed max-w-xl font-normal">
                    Bergabung dengan tim Klinik Hewan Sahabat Satwa. Lamar posisi impian, pantau setiap tahap seleksi, dan ketahui hasilnya langsung dari satu dashboard.
                </p>

                {{-- TOMBOL CTA --}}
                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="#lowongan"
                       class="inline-flex items-center gap-2.5 px-7 py-3.5 rounded-full text-sm font-bold text-forest bg-terracotta hover:bg-terracotta-hover shadow-md shadow-terracotta/25 hover:shadow-lg transition-all">
                        <span>Lihat lowongan</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center justify-center px-7 py-3.5 rounded-full text-sm font-bold text-forest bg-white hover:bg-cream border border-forest/15 shadow-xs transition-all">
                            Buat akun pelamar
                        </a>
                    @endif
                </div>
            </div>

            {{-- KANAN: KARTU GAMBAR ILUSTRASI --}}
            <div class="lg:col-span-6 flex justify-center lg:justify-end">
                <div class="relative w-full max-w-lg">
                    <div class="absolute -inset-3 rounded-[2.25rem] bg-gradient-to-tr from-terracotta/20 via-amber-200/20 to-forest/10 blur-xl -z-10"></div>

                    <div class="rounded-[2rem] p-3.5 bg-white/85 backdrop-blur-sm border border-forest/10 shadow-xl shadow-forest/5">
                        <div class="rounded-[1.5rem] overflow-hidden bg-[#EFE6D8] aspect-[4/4.2]">
                            <img src="{{ asset('images/pet-background.jpg') }}"
                                 alt="Ilustrasi Hewan Peliharaan PawCareer"
                                 class="w-full h-full object-cover object-center hover:scale-[1.02] transition-transform duration-500">
                        </div>
                    </div>

                    {{-- KARTU FLOATING --}}
                    <div class="hidden sm:flex items-center gap-3 absolute -bottom-5 -left-5 bg-white px-4 py-3 rounded-2xl shadow-lg border border-forest/10">
                        <div class="w-10 h-10 rounded-xl overflow-hidden bg-forest shrink-0">
                            <img src="{{ asset('images/logo-paw.jpg') }}" alt="Paw Icon" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="text-xs font-extrabold text-forest">Proses Transparan</p>
                            <p class="text-[11px] text-forest/65">Pantau status lamaran real-time</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- SECTION LOWONGAN --}}
        <section id="lowongan" class="mt-24 pt-12 border-t border-forest/10">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-terracotta">Posisi Tersedia</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-forest mt-1">Lowongan Terbaru</h2>
                </div>
            </div>

            @if(isset($jobPostings) && $jobPostings->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($jobPostings as $job)
                        <div class="bg-white/90 rounded-2xl p-6 border border-forest/10 shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-cream-warm text-forest">
                                        {{ $job->division->name ?? 'Klinik' }}
                                    </span>
                                    <span class="text-xs text-forest/55">
                                        {{ $job->employment_type ?? 'Full-time' }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-forest">{{ $job->title ?? ($job->position->name ?? 'Posisi Tersedia') }}</h3>
                                <p class="text-sm text-forest/70 mt-2 line-clamp-2">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($job->description ?? ''), 110) }}
                                </p>
                            </div>

                            <div class="mt-6 pt-4 border-t border-forest/5 flex items-center justify-between">
                                <span class="text-xs font-medium text-forest/60">
                                    {{ $job->location ?? 'Klinik Utama' }}
                                </span>
                                <a href="{{ route('login') }}"
                                   class="text-sm font-bold text-terracotta hover:text-terracotta-hover">
                                    Masuk untuk lamar →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white/80 rounded-2xl p-8 border border-forest/10 text-center">
                    <p class="text-sm font-semibold text-forest">Belum ada lowongan aktif saat ini.</p>
                    <p class="text-xs text-forest/60 mt-1">Buat akun pelamar terlebih dahulu agar siap melamar saat posisi dibuka.</p>
                </div>
            @endif
        </section>
    </main>

</body>
</html>
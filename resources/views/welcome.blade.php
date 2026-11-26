<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    {{-- META & TITLE --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0F2D23">
    <title>PawCareer — Klinik Hewan Sahabat Satwa</title>

    {{-- FONTS: Bricolage Grotesque (display) + Figtree (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;500;600;700;800&family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

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
                        sans: ['Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        display: ['"Bricolage Grotesque"', 'Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        forest: {
                            DEFAULT: '#0F2D23',
                            dark: '#072D25',
                            light: '#1B3D32',
                        },
                        brand: {
                            DEFAULT: '#06614B',
                            soft: '#D2F0E4',
                            hover: '#054D3B',
                        },
                        cream: {
                            DEFAULT: '#FAF6EE',
                            warm: '#F2ECE1',
                        },
                        apricot: {
                            DEFAULT: '#F09A53',
                            hover: '#E08843',
                            soft: '#FFEBD2',
                            fg: '#401F0A',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        html { -webkit-font-smoothing: antialiased; }
        body {
            font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;
            color: #0E271F;
            background-color: #FAF6EE;
        }
        h1, h2, h3, h4 {
            font-family: 'Bricolage Grotesque', 'Figtree', ui-sans-serif, system-ui, sans-serif;
            letter-spacing: -0.02em;
        }
        .bg-hero {
            background-color: #FAF6EE;
            background-image:
                radial-gradient(120% 80% at 100% 0%, rgba(240, 154, 83, 0.28) 0%, transparent 55%),
                radial-gradient(90% 70% at 0% 100%, rgba(6, 97, 75, 0.18) 0%, transparent 60%);
        }
        .paw-dots {
            background-image: radial-gradient(rgba(6, 97, 75, 0.14) 1px, transparent 1px);
            background-size: 18px 18px;
        }
        .bg-forest {
            background: linear-gradient(135deg, #065743 0%, #072D25 100%);
        }
        .shadow-soft {
            box-shadow: 0 1px 2px rgba(14, 39, 31, 0.06), 0 8px 24px -12px rgba(14, 39, 31, 0.18);
        }
        .shadow-float {
            box-shadow: 0 2px 6px rgba(14, 39, 31, 0.10), 0 18px 40px -16px rgba(14, 39, 31, 0.35);
        }
    </style>
</head>
<body class="min-h-screen bg-[#FAF6EE] text-[#0E271F] antialiased selection:bg-[#F09A53]/25 selection:text-[#0E271F]">

    {{-- NAVBAR --}}
    <header class="sticky top-0 z-40 w-full backdrop-blur-md bg-[#FAF6EE]/90 border-b border-[#E3DDD1]">
        <div class="max-w-7xl mx-auto px-6 lg:px-10 h-20 flex items-center justify-between">
            {{-- BRAND LOGO --}}
            <a href="{{ url('/') }}" class="flex items-center gap-3.5 group">
                <div class="w-11 h-11 rounded-full flex items-center justify-center bg-[#F09A53] shadow-sm group-hover:scale-105 transition-transform shrink-0">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="12" cy="17" rx="5" ry="4" fill="#0F2D23"/>
                        <ellipse cx="6.5" cy="13" rx="2.2" ry="3" fill="#0F2D23"/>
                        <ellipse cx="17.5" cy="13" rx="2.2" ry="3" fill="#0F2D23"/>
                        <ellipse cx="9" cy="9.5" rx="1.8" ry="2.5" fill="#0F2D23"/>
                        <ellipse cx="15" cy="9.5" rx="1.8" ry="2.5" fill="#0F2D23"/>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-extrabold tracking-tight text-[#0E271F] leading-none font-display">PawCareer</span>
                    <span class="text-xs font-medium text-[#536961] mt-1">Klinik Hewan Sahabat Satwa</span>
                </div>
            </a>

            {{-- NAVIGATION LINKS --}}
            @if (Route::has('login'))
                <nav class="flex items-center gap-6 sm:gap-8">
                    <a href="#lowongan"
                       class="text-sm font-semibold text-[#536961] hover:text-[#0E271F] transition-colors">
                        Lowongan
                    </a>

                    @auth
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center justify-center px-5 py-2.5 rounded-full text-sm font-semibold text-[#FCF8F0] bg-[#06614B] hover:bg-[#054D3B] shadow-soft transition-all">
                            Portal
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center px-5 py-2.5 rounded-full text-sm font-semibold text-[#FCF8F0] bg-[#06614B] hover:bg-[#054D3B] shadow-soft transition-all">
                            Masuk
                        </a>
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    {{-- MAIN HERO SECTION --}}
    <section class="bg-hero paw-dots border-b border-[#E3DDD1]">
        <div class="max-w-7xl mx-auto px-6 lg:px-10 pt-12 pb-20 lg:py-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-10 items-center">

                {{-- KIRI: HEADLINE & TOMBOL AKSI --}}
                <div class="lg:col-span-6 space-y-7">
                    {{-- BADGE INFO --}}
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#FFFDFA] border border-[#E3DDD1] shadow-soft">
                        <span class="text-[#F09A53] text-sm">✦</span>
                        <span class="text-xs font-bold text-[#0E271F] tracking-wide">
                            @if(isset($jobPostings) && $jobPostings->count() > 0)
                                {{ $jobPostings->count() }} lowongan sedang dibuka
                            @else
                                Lowongan karier sedang dibuka
                            @endif
                        </span>
                    </div>

                    {{-- JUDUL UTAMA --}}
                    <h1 class="font-display text-4xl sm:text-5xl lg:text-[3.75rem] font-extrabold text-[#0E271F] leading-[1.08] tracking-tight">
                        Rawat mereka,<br>
                        tumbuh bersama<br>
                        <span class="relative inline-block whitespace-nowrap">
                            kami.
                            <svg class="absolute left-0 -bottom-1.5 w-full h-3 text-[#F09A53]" viewBox="0 0 280 14" fill="none" preserveAspectRatio="none">
                                <path d="M3 10.5C75 4.5 190 3.5 277 8.5" stroke="currentColor" stroke-width="5.5" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </h1>

                    {{-- DESKRIPSI --}}
                    <p class="text-base sm:text-lg text-[#536961] leading-relaxed max-w-xl font-normal">
                        Bergabung dengan tim Klinik Hewan Sahabat Satwa. Lamar posisi impian, pantau setiap tahap seleksi, dan ketahui hasilnya langsung dari satu portal.
                    </p>

                    {{-- TOMBOL CTA --}}
                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a href="#lowongan"
                           class="inline-flex items-center gap-2.5 px-7 py-3.5 rounded-2xl text-sm font-bold text-[#401F0A] bg-[#F09A53] hover:bg-[#E08843] shadow-soft transition-all">
                            <span>Lihat lowongan</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center justify-center px-7 py-3.5 rounded-2xl text-sm font-bold text-[#0E271F] bg-[#FFFDFA] hover:bg-[#F2ECE1] border border-[#E3DDD1] shadow-soft transition-all">
                                Mulai sebagai pelamar
                            </a>
                        @endif
                    </div>
                </div>

                {{-- KANAN: KARTU GAMBAR ILUSTRASI --}}
                <div class="lg:col-span-6 flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-lg">
                        <div class="absolute -inset-3 rounded-4xl bg-[#FFEBD2] rotate-2 -z-10"></div>

                        <div class="rounded-3xl p-3 bg-[#FFFDFA] border border-[#E3DDD1] shadow-soft">
                            <div class="rounded-2xl overflow-hidden bg-[#F2ECE1] aspect-[4/4.2]">
                                <img src="{{ asset('images/pet-background.jpg') }}"
                                     alt="Ilustrasi Hewan Peliharaan PawCareer"
                                     class="w-full h-full object-cover object-center hover:scale-[1.02] transition-transform duration-500">
                            </div>
                        </div>

                        {{-- KARTU FLOATING --}}
                        <div class="hidden sm:flex items-center gap-3 absolute -bottom-5 -left-5 bg-[#FFFDFA] px-4 py-3 rounded-2xl shadow-float border border-[#E3DDD1]">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-[#D2F0E4] text-[#06614B] shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-extrabold text-[#0E271F]">Proses Transparan</p>
                                <p class="text-[11px] text-[#536961]">Pantau status lamaran secara langsung</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- SECTION LOWONGAN --}}
    <section id="lowongan" class="max-w-7xl mx-auto px-6 lg:px-10 py-20">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-[#06614B]">Lowongan terbuka</span>
                <h2 class="font-display text-3xl font-extrabold text-[#0E271F] mt-1">Posisi yang sedang kami cari</h2>
            </div>
            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="text-sm font-bold text-[#06614B] hover:underline">
                    Masuk untuk melamar →
                </a>
            @endif
        </div>

        @if(isset($jobPostings) && $jobPostings->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($jobPostings as $job)
                    <div class="bg-[#FFFDFA] rounded-3xl p-6 border border-[#E3DDD1] shadow-soft hover:shadow-md transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-[#D2F0E4] text-[#06614B] flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.073a2.25 2.25 0 01-2.25 2.25h-12a2.25 2.25 0 01-2.25-2.25v-4.073M16.5 9.75V6.75A2.25 2.25 0 0014.25 4.5h-4.5A2.25 2.25 0 007.5 6.75v3"/>
                                    </svg>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#D2F0E4] text-[#06614B]">
                                    {{ $job->division->name ?? ($job->requisition->position->division->name ?? 'Klinik') }}
                                </span>
                            </div>
                            <h3 class="font-display text-lg font-bold text-[#0E271F]">{{ $job->title ?? ($job->position->name ?? 'Posisi Tersedia') }}</h3>
                            <p class="text-sm text-[#536961] mt-2 line-clamp-2">
                                {{ \Illuminate\Support\Str::limit(strip_tags($job->description ?? ''), 110) }}
                            </p>
                        </div>

                        <div class="mt-6 pt-4 border-t border-[#E3DDD1] flex items-center justify-between">
                            <span class="text-xs font-medium text-[#536961]">
                                {{ $job->deadline ? 'Tutup ' . $job->deadline->translatedFormat('j M Y') : 'Terbuka' }}
                            </span>
                            <a href="{{ route('login') }}"
                               class="px-3.5 py-1.5 rounded-xl bg-[#D2F0E4] text-[#06614B] text-xs font-bold hover:bg-[#06614B] hover:text-[#FCF8F0] transition-colors">
                                Masuk untuk lamar →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-[#FFFDFA] rounded-3xl p-10 border border-[#E3DDD1] text-center shadow-soft">
                <p class="font-display text-base font-bold text-[#0E271F]">Belum ada lowongan aktif saat ini.</p>
                <p class="text-xs text-[#536961] mt-1">Buat akun pelamar terlebih dahulu agar siap melamar saat posisi dibuka.</p>
            </div>
        @endif
    </section>

    {{-- SECTION CARA MELAMAR --}}
    <section class="bg-forest text-[#FCF8F0] py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="mb-10">
                <span class="text-xs font-bold uppercase tracking-widest text-[#F09A53]">Alur Seleksi</span>
                <h2 class="font-display text-3xl font-extrabold mt-1">Cara melamar di PawCareer</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="rounded-3xl border border-white/15 bg-white/5 p-6 relative">
                    <span class="font-display text-3xl font-extrabold text-white/15 absolute top-5 right-6">01</span>
                    <div class="w-10 h-10 rounded-xl bg-[#F09A53] text-[#401F0A] flex items-center justify-center font-bold mb-4">1</div>
                    <h3 class="font-display text-lg font-bold mb-1.5">Lengkapi Profil</h3>
                    <p class="text-sm text-[#95AB9E]">Isi nomor telepon dan pendidikan terakhir di akun pelamar Anda.</p>
                </div>
                <div class="rounded-3xl border border-white/15 bg-white/5 p-6 relative">
                    <span class="font-display text-3xl font-extrabold text-white/15 absolute top-5 right-6">02</span>
                    <div class="w-10 h-10 rounded-xl bg-[#F09A53] text-[#401F0A] flex items-center justify-center font-bold mb-4">2</div>
                    <h3 class="font-display text-lg font-bold mb-1.5">Kirim Lamaran</h3>
                    <p class="text-sm text-[#95AB9E]">Pilih posisi yang sesuai dan unggah CV berformat PDF (maks. 2 MB).</p>
                </div>
                <div class="rounded-3xl border border-white/15 bg-white/5 p-6 relative">
                    <span class="font-display text-3xl font-extrabold text-white/15 absolute top-5 right-6">03</span>
                    <div class="w-10 h-10 rounded-xl bg-[#F09A53] text-[#401F0A] flex items-center justify-center font-bold mb-4">3</div>
                    <h3 class="font-display text-lg font-bold mb-1.5">Wawancara</h3>
                    <p class="text-sm text-[#95AB9E]">Jika lolos penyaringan berkas, HRD menjadwalkan sesi wawancara.</p>
                </div>
                <div class="rounded-3xl border border-white/15 bg-white/5 p-6 relative">
                    <span class="font-display text-3xl font-extrabold text-white/15 absolute top-5 right-6">04</span>
                    <div class="w-10 h-10 rounded-xl bg-[#F09A53] text-[#401F0A] flex items-center justify-center font-bold mb-4">4</div>
                    <h3 class="font-display text-lg font-bold mb-1.5">Keputusan Jelas</h3>
                    <p class="text-sm text-[#95AB9E]">Hasil akhir dan notifikasi dikirim langsung ke portal Anda.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION NILAI --}}
    <section class="max-w-7xl mx-auto px-6 lg:px-10 py-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-[#FFFDFA] rounded-3xl border border-[#E3DDD1] p-7 shadow-soft">
                <div class="w-10 h-10 rounded-xl bg-[#D2F0E4] text-[#06614B] flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-lg font-bold text-[#0E271F]">Adil dan tercatat</h3>
                <p class="text-sm text-[#536961] mt-1.5">Setiap tahapan seleksi tercatat rapi sehingga keputusan dapat dipertanggungjawabkan.</p>
            </div>
            <div class="bg-[#FFFDFA] rounded-3xl border border-[#E3DDD1] p-7 shadow-soft">
                <div class="w-10 h-10 rounded-xl bg-[#D2F0E4] text-[#06614B] flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/>
                    </svg>
                </div>
                <h3 class="font-display text-lg font-bold text-[#0E271F]">Berbasis wawancara</h3>
                <p class="text-sm text-[#536961] mt-1.5">Penilaian kandidat dilakukan melalui evaluasi wawancara yang terstruktur oleh tim terkait.</p>
            </div>
            <div class="bg-[#FFFDFA] rounded-3xl border border-[#E3DDD1] p-7 shadow-soft">
                <div class="w-10 h-10 rounded-xl bg-[#D2F0E4] text-[#06614B] flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31"/>
                    </svg>
                </div>
                <h3 class="font-display text-lg font-bold text-[#0E271F]">Selalu diberi kabar</h3>
                <p class="text-sm text-[#536961] mt-1.5">Anda tidak dibiarkan menunggu tanpa kepastian — setiap perubahan status langsung diberitahukan.</p>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-[#FFFDFA] border-t border-[#E3DDD1] py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-10 grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">
            <div>
                <p class="font-display text-base font-extrabold text-[#0E271F]">PawCareer</p>
                <p class="text-xs text-[#536961] mt-2 leading-relaxed">
                    Sistem rekrutmen resmi Klinik Hewan Sahabat Satwa untuk pengelolaan pengajuan staf, seleksi, hingga penerimaan karyawan baru.
                </p>
            </div>
            <div>
                <p class="font-display text-sm font-bold text-[#0E271F]">Pelamar</p>
                <ul class="mt-2 space-y-1.5 text-xs text-[#536961]">
                    <li><a href="#lowongan" class="hover:text-[#06614B]">Lowongan terbuka</a></li>
                    @if (Route::has('login'))
                        <li><a href="{{ route('login') }}" class="hover:text-[#06614B]">Masuk</a></li>
                    @endif
                </ul>
            </div>
            <div>
                <p class="font-display text-sm font-bold text-[#0E271F]">Privasi</p>
                <p class="text-xs text-[#536961] mt-2 leading-relaxed">
                    Sesuai UU No. 27 Tahun 2022, data dan CV pelamar yang tidak diterima akan dihapus setelah 12 bulan.
                </p>
            </div>
        </div>
    </footer>

</body>
</html>

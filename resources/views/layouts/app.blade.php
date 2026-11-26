<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- META & TITLE --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0F2D23">
    <title>@yield('title', 'PawCareer')</title>

    {{-- FONTS: Bricolage Grotesque (display) + Figtree (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;500;600;700;800&family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- ICONS & ALPINE (untuk interaksi panel/dropdown ringan) --}}
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- TAILWIND CDN + BRAND CONFIG --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans:    ['Figtree', 'system-ui', '-apple-system', 'sans-serif'],
                        display: ['"Bricolage Grotesque"', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        sidebar: {
                            DEFAULT:    '#0F2D23',
                            fg:         '#F3EEE3',
                            muted:      '#95AB9E',
                            accent:     '#1B3D32',
                            primary:    '#F09A53',
                            border:     '#27443A',
                        },
                        brand: {
                            DEFAULT:    '#06614B',
                            hover:      '#054D3B',
                        },
                        apricot:        '#F09A53',
                        canvas:         '#FAF6EE',
                        card:           '#FFFDFA',
                        'page-border':  '#E3DDD1',
                        fg:             '#0E271F',
                        'fg-muted':     '#536961',
                    },
                }
            }
        }
    </script>

    {{-- BASE STYLES --}}
    <style>
        /* Typography defaults */
        html { -webkit-font-smoothing: antialiased; }
        body {
            font-family: 'Figtree', system-ui, -apple-system, sans-serif;
            color: #0E271F;
            background-color: #FAF6EE;
        }
        h1, h2, h3, h4 {
            font-family: 'Bricolage Grotesque', 'Figtree', system-ui, sans-serif;
            letter-spacing: -0.02em;
        }
        [x-cloak] { display: none !important; }
        .shadow-soft {
            box-shadow: 0 1px 2px rgba(14, 39, 31, 0.06), 0 8px 24px -12px rgba(14, 39, 31, 0.18);
        }
        .bg-forest {
            background: linear-gradient(135deg, #065743 0%, #072D25 100%);
        }

        /* Sidebar scroll */
        #sidebar-nav::-webkit-scrollbar { width: 4px; }
        #sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        #sidebar-nav::-webkit-scrollbar-thumb { background: #27443A; border-radius: 4px; }

        /* Prose-system (forms / tables in content) */
        .prose-system { width: 100%; overflow-x: auto; }
        .prose-system h1 {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 1.5rem; font-weight: 700; color: #0E271F;
            letter-spacing: -0.02em; margin-bottom: 0.35rem;
        }
        .prose-system p { color: #536961; font-size: 0.9rem; }
        .prose-system table {
            border-collapse: collapse; width: 100%; margin-top: 1.25rem;
            background: #FFFDFA; border: 1px solid #E3DDD1;
            border-radius: 0.75rem; overflow: hidden; font-size: 0.875rem;
        }
        .prose-system th {
            background: #FAF6EE; color: #536961; font-weight: 600;
            font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em;
            padding: 0.85rem 1rem; border-bottom: 1px solid #E3DDD1;
            text-align: left; white-space: nowrap;
        }
        .prose-system td {
            padding: 0.9rem 1rem; border-bottom: 1px solid #F1EDE5;
            color: #0E271F; vertical-align: top; overflow-wrap: break-word;
        }
        .prose-system tr:last-child td { border-bottom: none; }
        .prose-system tr:hover td { background: #FAF6EE; }
        .prose-system label {
            display: block; font-size: 0.84rem; font-weight: 600;
            color: #0E271F; margin-top: 1.1rem; margin-bottom: 0.4rem;
        }
        .prose-system input:not([type="checkbox"]):not([type="radio"]):not([type="file"]),
        .prose-system select,
        .prose-system textarea {
            width: 100%; max-width: 38rem; padding: 0.6rem 0.85rem;
            font-size: 0.875rem; color: #0E271F; background: #FFFDFA;
            border: 1px solid #E3DDD1; border-radius: 0.5rem;
            outline: none; transition: border-color 0.15s, box-shadow 0.15s;
        }
        .prose-system input:focus,
        .prose-system select:focus,
        .prose-system textarea:focus {
            border-color: #06614B;
            box-shadow: 0 0 0 3px rgba(6, 97, 75, 0.12);
        }
        .prose-system textarea { min-height: 6.5rem; }
        .prose-system button[type="submit"]:not(.btn-reset) {
            margin-top: 0.75rem; padding: 0.55rem 1.1rem;
            font-size: 0.82rem; font-weight: 600; color: #FFFFFF;
            background: #06614B; border: none; border-radius: 0.5rem;
            cursor: pointer; white-space: nowrap; transition: background 0.15s;
        }
        .prose-system button[type="submit"]:not(.btn-reset):hover { background: #054D3B; }
        .prose-system .alert {
            padding: 0.85rem 1rem; margin: 1rem 0; border-radius: 0.5rem;
            font-size: 0.875rem; background: #EEF7F3; border: 1px solid #BDD9CE; color: #06614B;
        }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen antialiased bg-canvas" style="background:#FAF6EE;">

    <x-sidebar />

    {{-- ═══════════════════════════════════════════════════
         MAIN CONTENT AREA (offset by sidebar width)
    ═══════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col lg:pl-72 min-w-0 min-h-screen" style="background:#FAF6EE;">

        {{-- ── TOP BAR ── --}}
        <header class="sticky top-0 z-20 h-16 flex items-center justify-between px-6 sm:px-8"
                style="background:#FFFDFA; border-bottom:1px solid #E3DDD1;">

            {{-- Left: mobile brand OR desktop date --}}
            <div class="flex items-center gap-3">
                {{-- Mobile brand --}}
                <div class="flex items-center gap-2 lg:hidden">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0"
                         style="background:#F09A53;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                            <ellipse cx="12" cy="17" rx="5" ry="4" fill="#0F2D23"/>
                            <ellipse cx="6.5" cy="13" rx="2.2" ry="3" fill="#0F2D23"/>
                            <ellipse cx="17.5" cy="13" rx="2.2" ry="3" fill="#0F2D23"/>
                            <ellipse cx="9" cy="9.5" rx="1.8" ry="2.5" fill="#0F2D23"/>
                            <ellipse cx="15" cy="9.5" rx="1.8" ry="2.5" fill="#0F2D23"/>
                        </svg>
                    </div>
                    <span class="text-sm font-bold" style="color:#0E271F; font-family:'Bricolage Grotesque',sans-serif;">PawCareer</span>
                </div>

                {{-- Desktop: today's date --}}
                <span class="hidden lg:inline text-sm" style="color:#536961;">
                    {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, j F Y') }} &middot; WIB
                </span>
            </div>

            {{-- Right: bell + mobile logout --}}
            <div class="flex items-center gap-2">
                {{-- Notification Bell --}}
                @auth
                    @php
                        $unreadCount = \App\Models\ApplicationNotification::query()
                            ->where('user_id', auth()->id())
                            ->whereNull('read_at')
                            ->count();
                        $notifRoute = auth()->user()->role === 'applicant'
                            ? route('applicant.notifications.index')
                            : route('portal.notifications.index');
                    @endphp
                    <a href="{{ $notifRoute }}"
                       class="relative w-9 h-9 flex items-center justify-center rounded-full transition-colors"
                       style="border:1px solid #E3DDD1; color:#536961; background:#FFFDFA;"
                       onmouseover="this.style.background='#FAF6EE'"
                       onmouseout="this.style.background='#FFFDFA'"
                       title="Notifikasi">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 2a6 6 0 00-6 6v3l-1.5 2.5A1 1 0 003.4 15h13.2a1 1 0 00.9-1.5L16 11V8a6 6 0 00-6-6z" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8 15a2 2 0 004 0" stroke="currentColor" stroke-width="1.5" fill="none"/>
                        </svg>
                        @if($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full text-[10px] font-bold flex items-center justify-center"
                                  style="background:#F09A53; color:#401F0A;">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </a>
                @endauth

                {{-- Mobile logout --}}
                @auth
                    <form action="{{ route('logout') }}" method="POST" class="m-0 lg:hidden">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Keluar dari akun?')"
                                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors"
                                style="color:#DC2626; background:#FEF2F2; border:1px solid #FECACA;">
                            Keluar
                        </button>
                    </form>
                @endauth
            </div>
        </header>

        {{-- ── MOBILE NAV STRIP ── --}}
        <nav class="flex lg:hidden items-center gap-1.5 overflow-x-auto px-5 py-2.5 text-xs"
             style="border-bottom:1px solid #E3DDD1; background:#FFFDFA;">
            @if(auth()->user()?->role === 'hrd')
                <a href="{{ route('dashboard.hrd') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap font-medium {{ request()->routeIs('dashboard.hrd') ? 'text-white' : 'text-[#536961]' }}" style="{{ request()->routeIs('dashboard.hrd') ? 'background:#06614B;' : 'background:#FAF6EE; border:1px solid #E3DDD1;' }}">Dasbor</a>
                <a href="{{ route('job_postings.review') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap font-medium {{ request()->routeIs('job_postings.review') ? 'text-white' : 'text-[#536961]' }}" style="{{ request()->routeIs('job_postings.review') ? 'background:#06614B;' : 'background:#FAF6EE; border:1px solid #E3DDD1;' }}">Review</a>
                <a href="{{ route('job-postings.index') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap font-medium {{ request()->routeIs('job-postings.*') ? 'text-white' : 'text-[#536961]' }}" style="{{ request()->routeIs('job-postings.*') ? 'background:#06614B;' : 'background:#FAF6EE; border:1px solid #E3DDD1;' }}">Lowongan</a>
                <a href="{{ route('hrd.applications.index') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap font-medium {{ request()->routeIs('hrd.applications.*') ? 'text-white' : 'text-[#536961]' }}" style="{{ request()->routeIs('hrd.applications.*') ? 'background:#06614B;' : 'background:#FAF6EE; border:1px solid #E3DDD1;' }}">Kandidat</a>
                <a href="{{ route('hrd.interviews.index') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap font-medium {{ request()->routeIs('hrd.interviews.*') ? 'text-white' : 'text-[#536961]' }}" style="{{ request()->routeIs('hrd.interviews.*') ? 'background:#06614B;' : 'background:#FAF6EE; border:1px solid #E3DDD1;' }}">Wawancara</a>
                <a href="{{ route('divisions.index') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap font-medium {{ request()->routeIs('divisions.*') || request()->routeIs('positions.*') ? 'text-white' : 'text-[#536961]' }}" style="{{ request()->routeIs('divisions.*') || request()->routeIs('positions.*') ? 'background:#06614B;' : 'background:#FAF6EE; border:1px solid #E3DDD1;' }}">Divisi & Posisi</a>
            @elseif(auth()->user()?->role === 'division_manager')
                <a href="{{ route('dashboard.manager') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap font-medium {{ request()->routeIs('dashboard.manager') ? 'text-white' : 'text-[#536961]' }}" style="{{ request()->routeIs('dashboard.manager') ? 'background:#06614B;' : 'background:#FAF6EE; border:1px solid #E3DDD1;' }}">Dasbor</a>
                <a href="{{ route('requisitions.index') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap font-medium {{ request()->routeIs('requisitions.*') ? 'text-white' : 'text-[#536961]' }}" style="{{ request()->routeIs('requisitions.*') ? 'background:#06614B;' : 'background:#FAF6EE; border:1px solid #E3DDD1;' }}">Pengajuan</a>
                <a href="{{ route('employees.index') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap font-medium {{ request()->routeIs('employees.*') ? 'text-white' : 'text-[#536961]' }}" style="{{ request()->routeIs('employees.*') ? 'background:#06614B;' : 'background:#FAF6EE; border:1px solid #E3DDD1;' }}">Karyawan</a>
                <a href="{{ route('trash.index') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap font-medium {{ request()->routeIs('trash.*') ? 'text-white' : 'text-[#536961]' }}" style="{{ request()->routeIs('trash.*') ? 'background:#06614B;' : 'background:#FAF6EE; border:1px solid #E3DDD1;' }}">Sampah</a>
            @elseif(auth()->user()?->role === 'applicant')
                <a href="{{ route('dashboard.applicant') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap font-medium {{ request()->routeIs('dashboard.applicant') ? 'text-white' : 'text-[#536961]' }}" style="{{ request()->routeIs('dashboard.applicant') ? 'background:#06614B;' : 'background:#FAF6EE; border:1px solid #E3DDD1;' }}">Dasbor</a>
                <a href="{{ route('applicant.jobs.index') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap font-medium {{ request()->routeIs('applicant.jobs.*') ? 'text-white' : 'text-[#536961]' }}" style="{{ request()->routeIs('applicant.jobs.*') ? 'background:#06614B;' : 'background:#FAF6EE; border:1px solid #E3DDD1;' }}">Cari Lowongan</a>
                <a href="{{ route('applications.index') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap font-medium {{ request()->routeIs('applications.*') ? 'text-white' : 'text-[#536961]' }}" style="{{ request()->routeIs('applications.*') ? 'background:#06614B;' : 'background:#FAF6EE; border:1px solid #E3DDD1;' }}">Lamaran Saya</a>
                <a href="{{ route('applicant.profile.edit') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap font-medium {{ request()->routeIs('applicant.profile.*') ? 'text-white' : 'text-[#536961]' }}" style="{{ request()->routeIs('applicant.profile.*') ? 'background:#06614B;' : 'background:#FAF6EE; border:1px solid #E3DDD1;' }}">Profil</a>
                <a href="{{ route('applicant.notifications.index') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap font-medium {{ request()->routeIs('applicant.notifications.*') ? 'text-white' : 'text-[#536961]' }}" style="{{ request()->routeIs('applicant.notifications.*') ? 'background:#06614B;' : 'background:#FAF6EE; border:1px solid #E3DDD1;' }}">Notifikasi</a>
            @endif
        </nav>

        {{-- ── MAIN CONTENT ── --}}
        <main class="flex-1 px-6 sm:px-8 py-8">
            @yield('content')
        </main>

    </div>{{-- /main content area --}}

    @stack('scripts')
</body>
</html>

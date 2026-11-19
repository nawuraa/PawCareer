<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- META & TITLE --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#FFFFFF">
    <title>@yield('title', 'PawCareer — Klinik Hewan Sahabat Satwa')</title>

    {{-- FONTS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- TAILWIND & CONFIG --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Inter"', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            DEFAULT: '#163A28',
                            hover: '#0F291C',
                            soft: '#EEF5F0',
                            border: '#CFE0D5',
                        },
                        canvas: '#F6F8F7',
                        line: '#E2E8E5',
                    }
                }
            }
        }
    </script>

    {{-- STYLING STANDAR & FALLBACK HALAMAN INTERNAL --}}
    <style>
        body {
            font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #0F172A;
            background-color: #F6F8F7;
        }

        .prose-system {
            width: 100%;
            overflow-x: auto;
        }
        .prose-system h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0F172A;
            letter-spacing: -0.02em;
            margin-bottom: 0.35rem;
        }
        .prose-system p {
            color: #475569;
            font-size: 0.9rem;
        }
        .prose-system table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 1.25rem;
            background: #FFFFFF;
            border: 1px solid #E2E8E5;
            border-radius: 0.75rem;
            overflow: hidden;
            font-size: 0.875rem;
        }
        .prose-system th {
            background: #F9FBFA;
            color: #475569;
            font-weight: 600;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #E2E8E5;
            text-align: left;
            white-space: nowrap;
            word-break: normal;
        }
        .prose-system td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid #F1F5F3;
            color: #1E293B;
            vertical-align: top;
            word-break: normal;
            overflow-wrap: break-word;
        }
        .prose-system tr:last-child td { border-bottom: none; }
        .prose-system tr:hover td { background: #F9FBFA; }

        .prose-system label {
            display: block;
            font-size: 0.84rem;
            font-weight: 600;
            color: #1E293B;
            margin-top: 1.1rem;
            margin-bottom: 0.4rem;
        }
        .prose-system input:not([type="checkbox"]):not([type="radio"]):not([type="file"]),
        .prose-system select,
        .prose-system textarea {
            width: 100%;
            max-width: 38rem;
            padding: 0.6rem 0.85rem;
            font-size: 0.875rem;
            color: #0F172A;
            background: #FFFFFF;
            border: 1px solid #CBD5E1;
            border-radius: 0.5rem;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .prose-system input:focus,
        .prose-system select:focus,
        .prose-system textarea:focus {
            border-color: #163A28;
            box-shadow: 0 0 0 3px rgba(22, 58, 40, 0.12);
        }
        .prose-system textarea { min-height: 6.5rem; }
        .prose-system button[type="submit"]:not(.btn-reset) {
            margin-top: 0.75rem;
            padding: 0.55rem 1.1rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: #FFFFFF;
            background: #163A28;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.15s;
        }
        .prose-system button[type="submit"]:not(.btn-reset):hover {
            background: #0F291C;
        }
        .prose-system .alert {
            padding: 0.85rem 1rem;
            margin: 1rem 0;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            background: #EEF5F0;
            border: 1px solid #CFE0D5;
            color: #163A28;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen bg-canvas text-slate-900 antialiased flex">

    {{-- SIDEBAR KIRI (DESKTOP) --}}
    <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 bg-white border-r border-line z-30">
        {{-- BRAND HEADER --}}
        <div class="h-16 px-5 flex items-center gap-3 border-b border-line shrink-0">
            <img src="{{ asset('images/logo-paw.jpg') }}"
                 alt="PawCareer"
                 class="w-9 h-9 rounded-lg object-cover border border-slate-200 shrink-0">
            <div class="min-w-0">
                <span class="block text-sm font-bold text-slate-900 truncate">PawCareer</span>
                <span class="block text-[11px] text-slate-500 truncate">Klinik Hewan Sahabat Satwa</span>
            </div>
        </div>

        {{-- MENU NAVIGASI --}}
        <div class="flex-1 overflow-y-auto px-3.5 py-5 space-y-6">
            @if (auth()->user()?->role === 'hrd')
                <div>
                    <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                        Organisasi
                    </p>
                    <nav class="space-y-1">
                        <a href="{{ route('divisions.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('divisions.*') ? 'bg-brand text-white font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 font-medium' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                            </svg>
                            <span>Divisions</span>
                        </a>

                        <a href="{{ route('positions.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('positions.*') ? 'bg-brand text-white font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 font-medium' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"/>
                            </svg>
                            <span>Positions</span>
                        </a>
                    </nav>
                </div>

                <div>
                    <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                        Rekrutmen &amp; Seleksi
                    </p>
                    <nav class="space-y-1">
                        <a href="{{ route('job_postings.review') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('job_postings.review') ? 'bg-brand text-white font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 font-medium' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Requisition Review</span>
                        </a>

                        <a href="{{ route('job-postings.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('job-postings.*') ? 'bg-brand text-white font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 font-medium' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                            <span>Job Postings</span>
                        </a>

                        <a href="{{ route('hrd.applications.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('hrd.applications.*') ? 'bg-brand text-white font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 font-medium' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                            </svg>
                            <span>Applications</span>
                        </a>

                        <a href="{{ route('hrd.interviews.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('hrd.interviews.*') ? 'bg-brand text-white font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 font-medium' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                            <span>Interviews</span>
                        </a>
                    </nav>
                </div>

            @elseif (auth()->user()?->role === 'division_manager')
                <div>
                    <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                        Manajer Divisi
                    </p>
                    <nav class="space-y-1">
                        <a href="{{ route('dashboard.manager') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('dashboard.manager') ? 'bg-brand text-white font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 font-medium' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <a href="{{ route('requisitions.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('requisitions.*') ? 'bg-brand text-white font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 font-medium' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                            <span>Requisitions</span>
                        </a>

                        <a href="{{ route('trash.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('trash.*') ? 'bg-brand text-white font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 font-medium' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                            </svg>
                            <span>Trash</span>
                        </a>
                    </nav>
                </div>

            @elseif (auth()->user()?->role === 'applicant')
                <div>
                    <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                        Portal Pelamar
                    </p>
                    <nav class="space-y-1">
                        <a href="{{ route('applicant.jobs.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('applicant.jobs.*') ? 'bg-brand text-white font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 font-medium' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                            </svg>
                            <span>Lowongan Kerja</span>
                        </a>

                        <a href="{{ route('applications.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('applications.*') ? 'bg-brand text-white font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 font-medium' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                            </svg>
                            <span>Lamaran Saya</span>
                        </a>

                        <a href="{{ route('applicant.profile.edit') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('applicant.profile.*') ? 'bg-brand text-white font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 font-medium' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                            <span>Profil Saya</span>
                        </a>
                    </nav>
                </div>
            @endif
        </div>

        {{-- USER PROFILE & LOGOUT FOOTER IN SIDEBAR --}}
        @auth
            <div class="p-4 border-t border-line bg-slate-50/60 shrink-0">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-lg bg-brand text-white flex items-center justify-center text-xs font-bold shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-semibold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-slate-500 capitalize truncate">
                            {{ str_replace('_', ' ', auth()->user()->role) }}
                        </p>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Apakah Anda yakin ingin keluar dari akun?')"
                            class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-rose-50 hover:text-rose-700 border border-line hover:border-rose-200 transition-colors cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        @endauth
    </aside>

    {{-- MAIN CONTENT AREA --}}
    <div class="flex-1 flex flex-col lg:pl-64 min-w-0">

        {{-- TOP HEADER BAR --}}
        <header class="sticky top-0 z-20 bg-white/95 backdrop-blur-sm border-b border-line">
            <div class="h-16 px-5 sm:px-8 flex items-center justify-between gap-4">
                {{-- MOBILE BRAND --}}
                <div class="flex items-center gap-3 lg:hidden">
                    <img src="{{ asset('images/logo-paw.jpg') }}" alt="PawCareer" class="w-8 h-8 rounded-lg object-cover border border-slate-200">
                    <span class="text-sm font-bold text-slate-900">PawCareer</span>
                </div>

                {{-- DESKTOP CONTEXT TITLE --}}
                <div class="hidden lg:flex items-center gap-2 text-xs text-slate-500">
                    <span>Portal Rekrutmen &amp; SDM</span>
                    <span>/</span>
                    <span class="font-medium text-slate-900">@yield('breadcrumb', 'Workspace')</span>
                </div>

                {{-- RIGHT META & MOBILE LOGOUT --}}
                <div class="flex items-center gap-3">
                    <span class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-medium bg-brand-soft text-brand border border-brand-border">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand"></span>
                        {{ ucwords(str_replace('_', ' ', auth()->user()?->role ?? 'User')) }}
                    </span>

                    @auth
                        <form action="{{ route('logout') }}" method="POST" class="m-0 lg:hidden">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('Apakah Anda yakin ingin keluar dari akun?')"
                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold text-rose-700 bg-rose-50 border border-rose-200">
                                Keluar
                            </button>
                        </form>
                    @endauth
                </div>
            </div>

            {{-- MOBILE NAV STRIP --}}
            <nav class="flex lg:hidden items-center gap-1.5 overflow-x-auto px-5 py-2.5 border-t border-slate-100 text-xs bg-white">
                @if (auth()->user()?->role === 'hrd')
                    <a href="{{ route('divisions.index') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap {{ request()->routeIs('divisions.*') ? 'bg-brand text-white font-semibold' : 'bg-slate-100 text-slate-700' }}">Divisions</a>
                    <a href="{{ route('positions.index') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap {{ request()->routeIs('positions.*') ? 'bg-brand text-white font-semibold' : 'bg-slate-100 text-slate-700' }}">Positions</a>
                    <a href="{{ route('job_postings.review') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap {{ request()->routeIs('job_postings.review') ? 'bg-brand text-white font-semibold' : 'bg-slate-100 text-slate-700' }}">Review</a>
                    <a href="{{ route('job-postings.index') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap {{ request()->routeIs('job-postings.*') ? 'bg-brand text-white font-semibold' : 'bg-slate-100 text-slate-700' }}">Job Postings</a>
                    <a href="{{ route('hrd.applications.index') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap {{ request()->routeIs('hrd.applications.*') ? 'bg-brand text-white font-semibold' : 'bg-slate-100 text-slate-700' }}">Applications</a>
                    <a href="{{ route('hrd.interviews.index') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap {{ request()->routeIs('hrd.interviews.*') ? 'bg-brand text-white font-semibold' : 'bg-slate-100 text-slate-700' }}">Interviews</a>
                @elseif (auth()->user()?->role === 'division_manager')
                    <a href="{{ route('dashboard.manager') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap {{ request()->routeIs('dashboard.manager') ? 'bg-brand text-white font-semibold' : 'bg-slate-100 text-slate-700' }}">Dashboard</a>
                    <a href="{{ route('requisitions.index') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap {{ request()->routeIs('requisitions.*') ? 'bg-brand text-white font-semibold' : 'bg-slate-100 text-slate-700' }}">Requisitions</a>
                    <a href="{{ route('trash.index') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap {{ request()->routeIs('trash.*') ? 'bg-brand text-white font-semibold' : 'bg-slate-100 text-slate-700' }}">Trash</a>
                @elseif (auth()->user()?->role === 'applicant')
                    <a href="{{ route('applicant.jobs.index') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap {{ request()->routeIs('applicant.jobs.*') ? 'bg-brand text-white font-semibold' : 'bg-slate-100 text-slate-700' }}">Lowongan Kerja</a>
                    <a href="{{ route('applications.index') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap {{ request()->routeIs('applications.*') ? 'bg-brand text-white font-semibold' : 'bg-slate-100 text-slate-700' }}">Lamaran Saya</a>
                    <a href="{{ route('applicant.profile.edit') }}" class="px-3 py-1.5 rounded-md whitespace-nowrap {{ request()->routeIs('applicant.profile.*') ? 'bg-brand text-white font-semibold' : 'bg-slate-100 text-slate-700' }}">Profil Saya</a>
                @endif
            </nav>
        </header>

        {{-- WORKSPACE CONTAINER --}}
        <main class="flex-1 max-w-7xl w-full mx-auto px-5 sm:px-8 py-8">
            @hasSection('custom_layout')
                @yield('content')
            @else
                <div class="bg-white border border-line rounded-xl p-6 sm:p-8 prose-system">
                    @yield('content')
                </div>
            @endif
        </main>

        {{-- FOOTER --}}
        <footer class="border-t border-line bg-white mt-auto">
            <div class="max-w-7xl mx-auto px-5 sm:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-400">
                <span>© {{ date('Y') }} Klinik Hewan Sahabat Satwa</span>
                <span>PawCareer HR &amp; Recruitment System</span>
            </div>
        </footer>

    </div>

    @stack('scripts')
</body>
</html>
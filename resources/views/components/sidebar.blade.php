{{-- ═══════════════════════════════════════════════════
     SIDEBAR — fixed left, full height, dark forest
═══════════════════════════════════════════════════ --}}
@php
    $user = auth()->user();
    $role = $user?->role;

    $navItem = function (bool $active): string {
        if ($active) {
            return 'flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all bg-[#FAF6EE] text-[#0F2D23]';
        }
        return 'flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all text-[#95AB9E] hover:text-[#F3EEE3] hover:bg-[#1B3D32]';
    };

    $unreadNotifCount = $user
        ? \App\Models\ApplicationNotification::query()->where('user_id', $user->id)->whereNull('read_at')->count()
        : 0;

    $hrdPendingReqCount = $role === 'hrd'
        ? \App\Models\Requisition::whereIn('status', ['Diajukan', 'submitted'])->count()
        : 0;

    $hrdPendingAppCount = $role === 'hrd'
        ? \App\Models\Application::where('status', 'submitted')->count()
        : 0;
@endphp

<aside class="hidden lg:flex lg:flex-col lg:w-72 lg:fixed lg:inset-y-0 z-30"
       style="background:#0F2D23; border-right:1px solid #27443A;">

    {{-- ── 1. LOGO AREA ── --}}
    <a href="{{ route('home') }}" class="shrink-0 flex items-center gap-3 px-5 py-5 hover:opacity-95 transition-opacity" style="border-bottom:1px solid #27443A;">
        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0" style="background:#F09A53;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <ellipse cx="12" cy="17" rx="5" ry="4" fill="#0F2D23"/>
                <ellipse cx="6.5" cy="13" rx="2.2" ry="3" fill="#0F2D23"/>
                <ellipse cx="17.5" cy="13" rx="2.2" ry="3" fill="#0F2D23"/>
                <ellipse cx="9" cy="9.5" rx="1.8" ry="2.5" fill="#0F2D23"/>
                <ellipse cx="15" cy="9.5" rx="1.8" ry="2.5" fill="#0F2D23"/>
            </svg>
        </div>
        <div class="min-w-0">
            <span class="block text-sm font-bold truncate" style="color:#F3EEE3; font-family:'Bricolage Grotesque',sans-serif;">PawCareer</span>
            <span class="block text-[11px] truncate" style="color:#95AB9E;">Klinik Hewan Sahabat Satwa</span>
        </div>
    </a>

    {{-- ── 2. PORTAL LABEL BOX ── --}}
    <div class="mx-3 mt-3 px-4 py-2.5 rounded-xl" style="background:#1B3D32;">
        <p class="text-xs font-medium leading-tight" style="color:#95AB9E;">
            Portal
            <span class="font-bold" style="color:#F3EEE3;">
                @if($role === 'hrd')
                    HRD
                @elseif($role === 'division_manager')
                    Manajer Divisi
                @elseif($role === 'applicant')
                    Pelamar
                @else
                    {{ ucwords(str_replace('_', ' ', $role ?? 'User')) }}
                @endif
            </span>
            @if($role === 'division_manager' && $user?->division)
                <span style="color:#95AB9E;">· {{ $user->division->name }}</span>
            @endif
        </p>
    </div>

    {{-- ── 3. NAVIGATION MENU ── --}}
    <nav id="sidebar-nav" class="flex-1 overflow-y-auto mt-4 px-3 pb-4 space-y-5">

        {{-- ════════════ HRD MENU ════════════ --}}
        @if($role === 'hrd')

            {{-- Main --}}
            <div>
                <a href="{{ route('dashboard.hrd') }}" class="{{ $navItem(request()->routeIs('dashboard.hrd')) }}">
                    <span class="flex items-center gap-3 min-w-0">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                            <rect x="3" y="3" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
                            <rect x="11" y="3" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
                            <rect x="3" y="11" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
                            <rect x="11" y="11" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                        <span>Dasbor</span>
                    </span>
                </a>
            </div>

            {{-- Grup Rekrutmen --}}
            <div>
                <p class="px-3.5 mb-1.5 text-[10px] font-semibold uppercase tracking-widest" style="color:#95AB9E;">Rekrutmen</p>
                <div class="space-y-1">
                    <a href="{{ route('job_postings.review') }}" class="{{ $navItem(request()->routeIs('job_postings.review')) }}">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                                <rect x="4" y="3" width="12" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M7.5 8h5M7.5 11.5h3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            <span>Review Pengajuan</span>
                        </span>
                        @if($hrdPendingReqCount > 0)
                            <span class="min-w-[20px] h-5 px-1.5 rounded-full text-[11px] font-bold flex items-center justify-center shrink-0" style="background:#F09A53; color:#341A09;">
                                {{ $hrdPendingReqCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('job-postings.index') }}" class="{{ $navItem(request()->routeIs('job-postings.*')) }}">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                                <rect x="3" y="6" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M7 6V4.5A1.5 1.5 0 018.5 3h3A1.5 1.5 0 0113 4.5V6" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                            <span>Lowongan</span>
                        </span>
                    </a>

                    <a href="{{ route('hrd.applications.index') }}" class="{{ $navItem(request()->routeIs('hrd.applications.*')) }}">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                                <rect x="4" y="2.5" width="12" height="15" rx="2" stroke="currentColor" stroke-width="1.5"/>
                                <circle cx="10" cy="8" r="2" stroke="currentColor" stroke-width="1.4"/>
                                <path d="M7 14c0-1.5 1.3-2.5 3-2.5s3 1 3 2.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                            </svg>
                            <span>Kandidat</span>
                        </span>
                        @if($hrdPendingAppCount > 0)
                            <span class="min-w-[20px] h-5 px-1.5 rounded-full text-[11px] font-bold flex items-center justify-center shrink-0" style="background:#F09A53; color:#341A09;">
                                {{ $hrdPendingAppCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('hrd.interviews.index') }}" class="{{ $navItem(request()->routeIs('hrd.interviews.*')) }}">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                                <rect x="3" y="4" width="14" height="13" rx="2" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M3 8h14M7 2v3M13 2v3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            <span>Wawancara</span>
                        </span>
                    </a>

                    <a href="{{ route('hrd.employees.index') }}" class="{{ $navItem(request()->routeIs('hrd.employees.*')) }}">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                                <circle cx="8.5" cy="7" r="3" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M3 16.5c0-2.8 2.4-5 5.5-5s5.5 2.2 5.5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M15 8v4M17 10h-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            <span>Karyawan Baru</span>
                        </span>
                    </a>
                </div>
            </div>

            {{-- Grup Pengelolaan --}}
            <div>
                <p class="px-3.5 mb-1.5 text-[10px] font-semibold uppercase tracking-widest" style="color:#95AB9E;">Pengelolaan</p>
                <div class="space-y-1">
                    <a href="{{ route('divisions.index') }}" class="{{ $navItem(request()->routeIs('divisions.*') || request()->routeIs('positions.*')) }}">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                                <path d="M4 17V5a2 2 0 012-2h8a2 2 0 012 2v12M2 17h16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M8 7h4M8 10h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            <span>Divisi &amp; Posisi</span>
                        </span>
                    </a>

                    <a href="{{ route('hrd.users.index') }}" class="{{ $navItem(request()->routeIs('hrd.users.*')) }}">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                                <circle cx="7.5" cy="7" r="2.8" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M2.5 16c0-2.5 2.2-4.5 5-4.5s5 2 5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <circle cx="14" cy="8" r="2" stroke="currentColor" stroke-width="1.4"/>
                                <path d="M14 12.2c2 0 3.5 1.3 3.5 3.3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                            </svg>
                            <span>Akun Pengguna</span>
                        </span>
                    </a>

                    <a href="{{ route('hrd.status_histories.index') }}" class="{{ $navItem(request()->routeIs('hrd.status_histories.*')) }}">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                                <path d="M3.5 10a6.5 6.5 0 101.8-4.5M3.5 4v2.5H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10 6.8V10l2.2 1.4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            <span>Riwayat Status</span>
                        </span>
                    </a>

                    <a href="{{ route('hrd.trash.index') }}" class="{{ $navItem(request()->routeIs('hrd.trash.*')) }}">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                                <path d="M4 6h12l-1 10a2 2 0 01-2 1.8H7a2 2 0 01-2-1.8L4 6z" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M2.5 6h15M8 6V4a1 1 0 011-1h2a1 1 0 011 1v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            <span>Tempat Sampah</span>
                        </span>
                    </a>
                </div>
            </div>

            {{-- Grup Lainnya --}}
            <div>
                <p class="px-3.5 mb-1.5 text-[10px] font-semibold uppercase tracking-widest" style="color:#95AB9E;">Lainnya</p>
                <div class="space-y-1">
                    <a href="{{ route('hrd.reports.index') }}" class="{{ $navItem(request()->routeIs('hrd.reports.*')) }}">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                                <circle cx="10" cy="10" r="7" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M10 3v7h7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            <span>Laporan</span>
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold tracking-wider border border-dashed {{ request()->routeIs('hrd.reports.*') ? 'border-[#0F2D23]/40 text-[#0F2D23]' : 'border-[#95AB9E]/40 text-[#95AB9E]' }}">
                            RILIS 3
                        </span>
                    </a>

                    <a href="{{ route('portal.notifications.index') }}" class="{{ $navItem(request()->routeIs('portal.notifications.*')) }}">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                                <path d="M10 2.5a5.5 5.5 0 00-5.5 5.5v3l-1.5 2.5A1 1 0 003.9 15h12.2a1 1 0 00.9-1.5L15.5 11V8A5.5 5.5 0 0010 2.5z" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M8 15a2 2 0 004 0" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                            <span>Notifikasi</span>
                        </span>
                        @if($unreadNotifCount > 0)
                            <span class="min-w-[20px] h-5 px-1.5 rounded-full text-[11px] font-bold flex items-center justify-center shrink-0" style="background:#F09A53; color:#341A09;">
                                {{ $unreadNotifCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

        {{-- ════════════ MANAJER DIVISI MENU ════════════ --}}
        @elseif($role === 'division_manager')

            <div class="space-y-1">
                <a href="{{ route('dashboard.manager') }}" class="{{ $navItem(request()->routeIs('dashboard.manager')) }}">
                    <span class="flex items-center gap-3 min-w-0">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                            <rect x="3" y="3" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
                            <rect x="11" y="3" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
                            <rect x="3" y="11" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
                            <rect x="11" y="11" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                        <span>Dasbor</span>
                    </span>
                </a>

                <a href="{{ route('requisitions.index') }}" class="{{ $navItem(request()->routeIs('requisitions.*')) }}">
                    <span class="flex items-center gap-3 min-w-0">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                            <rect x="4" y="3" width="12" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M7.5 8h5M7.5 11.5h3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <span>Pengajuan Kebutuhan</span>
                    </span>
                </a>

                <a href="{{ route('employees.index') }}" class="{{ $navItem(request()->routeIs('employees.*')) }}">
                    <span class="flex items-center gap-3 min-w-0">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                            <circle cx="8.5" cy="7" r="3" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M3 16.5c0-2.8 2.4-5 5.5-5s5.5 2.2 5.5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M15 8v4M17 10h-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <span>Karyawan Baru</span>
                    </span>
                </a>

                <a href="{{ route('trash.index') }}" class="{{ $navItem(request()->routeIs('trash.*')) }}">
                    <span class="flex items-center gap-3 min-w-0">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                            <path d="M4 6h12l-1 10a2 2 0 01-2 1.8H7a2 2 0 01-2-1.8L4 6z" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M2.5 6h15M8 6V4a1 1 0 011-1h2a1 1 0 011 1v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <span>Tempat Sampah</span>
                    </span>
                </a>
            </div>

            {{-- Grup Lainnya --}}
            <div>
                <p class="px-3.5 mb-1.5 text-[10px] font-semibold uppercase tracking-widest" style="color:#95AB9E;">Lainnya</p>
                <div class="space-y-1">
                    <a href="{{ route('portal.notifications.index') }}" class="{{ $navItem(request()->routeIs('portal.notifications.*')) }}">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                                <path d="M10 2.5a5.5 5.5 0 00-5.5 5.5v3l-1.5 2.5A1 1 0 003.9 15h12.2a1 1 0 00.9-1.5L15.5 11V8A5.5 5.5 0 0010 2.5z" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M8 15a2 2 0 004 0" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                            <span>Notifikasi</span>
                        </span>
                        @if($unreadNotifCount > 0)
                            <span class="min-w-[20px] h-5 px-1.5 rounded-full text-[11px] font-bold flex items-center justify-center shrink-0" style="background:#F09A53; color:#341A09;">
                                {{ $unreadNotifCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

        {{-- ════════════ PELAMAR MENU ════════════ --}}
        @elseif($role === 'applicant')

            <div class="space-y-1">
                <a href="{{ route('dashboard.applicant') }}" class="{{ $navItem(request()->routeIs('dashboard.applicant')) }}">
                    <span class="flex items-center gap-3 min-w-0">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                            <rect x="3" y="3" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
                            <rect x="11" y="3" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
                            <rect x="3" y="11" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
                            <rect x="11" y="11" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                        <span>Dasbor</span>
                    </span>
                </a>

                <a href="{{ route('applicant.jobs.index') }}" class="{{ $navItem(request()->routeIs('applicant.jobs.*')) }}">
                    <span class="flex items-center gap-3 min-w-0">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                            <circle cx="9" cy="9" r="5.5" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M13 13l4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <span>Cari Lowongan</span>
                    </span>
                </a>

                <a href="{{ route('applications.index') }}" class="{{ $navItem(request()->routeIs('applications.*')) }}">
                    <span class="flex items-center gap-3 min-w-0">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                            <rect x="4" y="2.5" width="12" height="15" rx="2" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M7 7h6M7 10h6M7 13h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <span>Lamaran Saya</span>
                    </span>
                </a>

                <a href="{{ route('applicant.profile.edit') }}" class="{{ $navItem(request()->routeIs('applicant.profile.*')) }}">
                    <span class="flex items-center gap-3 min-w-0">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                            <circle cx="10" cy="7" r="3.5" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M3 17.5c0-3.5 3.1-6.5 7-6.5s7 3 7 6.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <span>Profil</span>
                    </span>
                </a>
            </div>

            {{-- Grup Lainnya --}}
            <div>
                <p class="px-3.5 mb-1.5 text-[10px] font-semibold uppercase tracking-widest" style="color:#95AB9E;">Lainnya</p>
                <div class="space-y-1">
                    <a href="{{ route('applicant.notifications.index') }}" class="{{ $navItem(request()->routeIs('applicant.notifications.*')) }}">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="shrink-0">
                                <path d="M10 2.5a5.5 5.5 0 00-5.5 5.5v3l-1.5 2.5A1 1 0 003.9 15h12.2a1 1 0 00.9-1.5L15.5 11V8A5.5 5.5 0 0010 2.5z" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M8 15a2 2 0 004 0" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                            <span>Notifikasi</span>
                        </span>
                        @if($unreadNotifCount > 0)
                            <span class="min-w-[20px] h-5 px-1.5 rounded-full text-[11px] font-bold flex items-center justify-center shrink-0" style="background:#F09A53; color:#341A09;">
                                {{ $unreadNotifCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

        @endif
    </nav>

    {{-- ── 4. SIDEBAR FOOTER — user info & logout ── --}}
    @auth
        <div class="shrink-0 p-4" style="border-top:1px solid #27443A;">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 text-xs font-bold"
                         style="background:#F09A53; color:#341A09;">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold truncate leading-tight" style="color:#F3EEE3;">
                            {{ $user->name }}
                        </p>
                        <p class="text-[11px] truncate leading-tight" style="color:#95AB9E;">
                            {{ $user->email }}
                        </p>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="shrink-0 m-0">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Keluar dari akun?')"
                            title="Keluar"
                            class="flex items-center justify-center w-8 h-8 rounded-lg transition-colors cursor-pointer"
                            style="color:#95AB9E;"
                            onmouseover="this.style.color='#F87171'"
                            onmouseout="this.style.color='#95AB9E'">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                            <path d="M7 3H4a1 1 0 00-1 1v12a1 1 0 001 1h3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13 7l4 3-4 3M17 10H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    @endauth
</aside>

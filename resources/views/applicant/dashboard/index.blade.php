@extends('layouts.app')

@section('title', 'Dasbor Pelamar — PawCareer')

@section('content')
<div class="max-w-7xl mx-auto">

    @php
        $firstName = explode(' ', auth()->user()->name)[0] ?? auth()->user()->name;
        $quotaPct = min(100, round(($activeCount / 3) * 100));
        $statusMap = [
            'submitted'        => ['label' => 'Dikirim',                  'text' => '#225984', 'bg' => '#DBEEFE', 'step' => 1, 'failed' => false],
            'screening_passed' => ['label' => 'Direview',                 'text' => '#00655C', 'bg' => '#CBF1EB', 'step' => 2, 'failed' => false],
            'interview'        => ['label' => 'Wawancara',                'text' => '#915200', 'bg' => '#FFECC9', 'step' => 3, 'failed' => false],
            'accepted'         => ['label' => 'Diterima',                 'text' => '#00682A', 'bg' => '#CCF4D3', 'step' => 4, 'failed' => false],
            'screening_failed' => ['label' => 'Ditolak (Berkas)',         'text' => '#B2354A', 'bg' => '#FFE2E4', 'step' => 2, 'failed' => true],
            'failed'           => ['label' => 'Ditolak (Berkas)',         'text' => '#B2354A', 'bg' => '#FFE2E4', 'step' => 2, 'failed' => true],
            'rejected'         => ['label' => 'Ditolak (Keputusan Akhir)','text' => '#B32322', 'bg' => '#FFE2DD', 'step' => 4, 'failed' => true],
            'cancelled'        => ['label' => 'Dibatalkan',               'text' => '#5B6662', 'bg' => '#E3EAE7', 'step' => 1, 'failed' => true],
        ];
    @endphp

    {{-- 1. BANNER SAPAAN (bg-forest) --}}
    <div class="bg-forest rounded-3xl p-6 sm:p-8 text-[#FCF8F0] mb-6 shadow-soft flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <p class="text-sm text-[#95AB9E] font-medium mb-1">Halo,</p>
            <h1 class="font-display text-3xl sm:text-4xl font-extrabold tracking-tight flex items-center gap-2">
                <span>{{ $firstName }}</span>
                <span>👋</span>
            </h1>
            <p class="text-sm text-[#F3EEE3]/90 mt-2">
                Anda memiliki
                <span class="font-bold text-[#F09A53]">{{ $activeCount }} lamaran aktif</span>
                dan {{ $upcomingInterviewCount }} wawancara mendatang.
            </p>
        </div>

        {{-- Panel Kuota Transparan --}}
        <div class="w-full lg:w-80 rounded-2xl bg-white/10 border border-white/15 p-4 backdrop-blur-sm">
            <div class="flex items-center justify-between text-xs mb-2">
                <span class="text-[#F3EEE3]/90 font-medium">Kuota lamaran aktif</span>
                <span class="font-display text-base font-bold text-[#FCF8F0]">{{ $activeCount }}/3</span>
            </div>
            <div class="w-full h-2 rounded-full bg-white/15 overflow-hidden mb-2.5">
                <div class="h-full rounded-full bg-[#F09A53] transition-all" style="width: {{ $quotaPct }}%"></div>
            </div>
            <p class="text-[11px] text-[#95AB9E] leading-snug">
                Status akhir tidak dihitung. Tiap lamaran aktif untuk posisi berbeda.
            </p>
        </div>
    </div>

    {{-- 2. DUA KOLOM UTAMA --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">

        {{-- KOLOM KIRI (7 cols) --}}
        <div class="lg:col-span-7 space-y-6">

            {{-- Kartu Wawancara Mendatang --}}
            @if($upcomingInterview)
                @php
                    $ivDt = $upcomingInterview->scheduled_at->timezone('Asia/Jakarta');
                @endphp
                <div class="rounded-3xl border border-[#E1A035]/40 bg-[#FFECC9]/60 p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-soft">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#FFECC9] border border-[#E1A035]/30 text-[#915200] flex items-center justify-center shrink-0">
                            <i class="ph ph-calendar-blank text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-widest text-[#915200]">Wawancara mendatang</p>
                            <h3 class="font-display text-lg font-bold text-[#0E271F] mt-0.5">
                                {{ $upcomingInterview->application->jobPosting->title ?? 'Wawancara' }}
                            </h3>
                            <p class="text-xs font-medium text-[#0E271F]/80 mt-0.5">
                                {{ $ivDt->translatedFormat('D, j M Y · H.i') }} WIB
                            </p>
                            <p class="text-xs text-[#536961] mt-1 flex items-center gap-1">
                                <i class="ph ph-map-pin text-xs"></i>
                                Klinik Hewan Sahabat Satwa
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('applications.index') }}"
                       class="px-4 py-2 rounded-xl bg-[#FFFDFA] border border-[#E3DDD1] text-xs font-semibold text-[#0E271F] hover:bg-[#FAF6EE] transition-colors shrink-0 text-center">
                        Detail
                    </a>
                </div>
            @endif

            {{-- Lamaran saya --}}
            <div class="bg-[#FFFDFA] rounded-3xl border border-[#E3DDD1] shadow-soft p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="font-display text-lg font-bold text-[#0E271F]">Lamaran saya</h2>
                        <p class="text-xs text-[#536961]">Status terbaru setiap lamaran</p>
                    </div>
                    <a href="{{ route('applications.index') }}"
                       class="text-xs font-semibold text-[#0E271F] hover:text-[#06614B] inline-flex items-center gap-1 transition-colors">
                        Semua <i class="ph ph-arrow-right"></i>
                    </a>
                </div>

                <div class="divide-y divide-[#E3DDD1]">
                    @forelse($applications->take(4) as $app)
                        @php
                            $st = $statusMap[$app->status] ?? ['label' => ucfirst($app->status), 'text' => '#536961', 'bg' => '#E3EAE7', 'step' => 1, 'failed' => false];
                        @endphp
                        <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="min-w-0">
                                <p class="font-bold text-sm text-[#0E271F] truncate">{{ $app->jobPosting->title }}</p>
                                <p class="text-xs text-[#536961] mt-0.5">
                                    {{ $app->jobPosting->requisition->position->division->name ?? 'Divisi' }}
                                    · dikirim {{ $app->submitted_at->timezone('Asia/Jakarta')->translatedFormat('j M Y') }}
                                </p>
                            </div>

                            <div class="flex items-center gap-4 shrink-0">
                                {{-- Stepper ringkas 4 langkah --}}
                                <div class="hidden sm:flex items-center gap-1.5">
                                    @for($i = 1; $i <= 4; $i++)
                                        @php
                                            $isFailHere = $st['failed'] && $i === $st['step'];
                                            $isDone = !$st['failed'] ? ($i <= $st['step']) : ($i < $st['step']);
                                            $isCurrent = !$st['failed'] && $i === $st['step'];
                                        @endphp
                                        @if($isFailHere)
                                            <span class="w-6 h-6 rounded-full bg-[#B32322] text-white text-[10px] font-bold flex items-center justify-center">✕</span>
                                        @elseif($isDone)
                                            <span class="w-6 h-6 rounded-full bg-[#06614B] text-white text-[10px] font-bold flex items-center justify-center {{ $isCurrent ? 'ring-4 ring-[#D2F0E4]' : '' }}">✓</span>
                                        @else
                                            <span class="w-6 h-6 rounded-full border border-[#DDD7C9] text-[#536961] text-[10px] font-semibold flex items-center justify-center">{{ $i }}</span>
                                        @endif
                                        @if($i < 4)
                                            <span class="w-3 h-0.5 {{ $i < $st['step'] ? 'bg-[#06614B]' : 'bg-[#E3DDD1]' }}"></span>
                                        @endif
                                    @endfor
                                </div>

                                {{-- Badge status --}}
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold"
                                      style="color:{{ $st['text'] }}; background-color:{{ $st['bg'] }};">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background-color:{{ $st['text'] }};"></span>
                                    {{ $st['label'] }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center">
                            <p class="text-sm font-semibold text-[#0E271F]">Belum ada lamaran dikirim</p>
                            <p class="text-xs text-[#536961] mt-1">Jelajahi lowongan yang dibuka untuk mulai melamar.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN (5 cols) --}}
        <div class="lg:col-span-5 space-y-6">

            {{-- Kelengkapan profil --}}
            <div class="bg-[#FFFDFA] rounded-3xl border border-[#E3DDD1] shadow-soft p-6">
                <h2 class="font-display text-lg font-bold text-[#0E271F] mb-4">Kelengkapan profil</h2>
                <div class="flex items-center gap-4 mb-5">
                    @php
                        $circumference = 2 * pi() * 24;
                        $dashOffset = $circumference - ($completeness / 100) * $circumference;
                    @endphp
                    <div class="relative w-16 h-16 shrink-0 flex items-center justify-center">
                        <svg class="w-16 h-16 -rotate-90" viewBox="0 0 60 60">
                            <circle cx="30" cy="30" r="24" stroke="#F2ECE1" stroke-width="5" fill="none"/>
                            <circle cx="30" cy="30" r="24" stroke="#06614B" stroke-width="5" fill="none"
                                    stroke-linecap="round"
                                    stroke-dasharray="{{ $circumference }}"
                                    stroke-dashoffset="{{ $dashOffset }}"/>
                        </svg>
                        <span class="absolute text-xs font-extrabold text-[#0E271F]">{{ $completeness }}%</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-[#0E271F] flex items-center gap-1.5">
                            @if($completeness === 100)
                                <i class="ph ph-check-circle text-[#06614B]"></i> Siap melamar
                            @else
                                <i class="ph ph-warning-circle text-[#F09A53]"></i> Profil belum lengkap
                            @endif
                        </p>
                        <p class="text-xs text-[#536961] mt-0.5">
                            {{ $profile?->education ?? 'Lengkapi nomor telepon & pendidikan' }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('applicant.profile.edit') }}"
                   class="w-full py-2.5 px-4 rounded-xl bg-[#D2F0E4] hover:bg-[#B8E8D4] text-[#06614B] text-xs font-bold text-center transition-colors block">
                    Kelola profil
                </a>
            </div>

            {{-- Notifikasi terbaru --}}
            <div class="bg-[#FFFDFA] rounded-3xl border border-[#E3DDD1] shadow-soft p-6">
                <h2 class="font-display text-lg font-bold text-[#0E271F] mb-4">Notifikasi terbaru</h2>

                <div class="divide-y divide-[#E3DDD1]">
                    @forelse($notifications as $notif)
                        <div class="py-3 first:pt-0 last:pb-0">
                            <p class="text-xs font-bold text-[#0E271F] truncate">
                                {{ $notif->data['title'] ?? ($notif->data['message'] ?? 'Pembaruan lamaran') }}
                            </p>
                            @if(!empty($notif->data['message']))
                                <p class="text-xs text-[#536961] truncate mt-0.5">{{ $notif->data['message'] }}</p>
                            @endif
                            <p class="text-[11px] text-[#536961] mt-1">
                                {{ \Carbon\Carbon::parse($notif->created_at)->timezone('Asia/Jakarta')->diffForHumans() }}
                            </p>
                        </div>
                    @empty
                        <p class="text-xs text-[#536961] py-6 text-center">Belum ada notifikasi baru.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- 3. LOWONGAN LAIN UNTUK ANDA --}}
    <div class="bg-[#FFFDFA] rounded-3xl border border-[#E3DDD1] shadow-soft p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-display text-lg font-bold text-[#0E271F] flex items-center gap-2">
                <span class="text-[#F09A53]">✦</span>
                <span>Lowongan lain untuk Anda</span>
            </h2>
            <a href="{{ route('applicant.jobs.index') }}"
               class="text-xs font-semibold text-[#0E271F] hover:text-[#06614B] inline-flex items-center gap-1 transition-colors">
                Semua <i class="ph ph-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse($recommendedJobs as $job)
                @php
                    $daysLeft = (int) now('Asia/Jakarta')->startOfDay()->diffInDays($job->deadline->copy()->startOfDay(), false);
                    $isToday = $daysLeft === 0;
                @endphp
                <a href="{{ route('applicant.jobs.show', $job) }}"
                   class="rounded-2xl border border-[#E3DDD1] bg-[#FAF6EE]/50 hover:bg-[#FAF6EE] p-5 transition-colors block">
                    <div class="w-9 h-9 rounded-xl bg-[#D2F0E4] text-[#06614B] flex items-center justify-center mb-3">
                        <i class="ph ph-file-text text-lg"></i>
                    </div>
                    <h3 class="font-display text-base font-bold text-[#0E271F] truncate">{{ $job->title }}</h3>
                    <p class="text-xs text-[#536961] mt-0.5 mb-3">
                        {{ $job->requisition->position->division->name ?? 'Divisi' }}
                    </p>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] font-semibold {{ $isToday ? 'bg-[#FFEBD2] text-[#915200]' : 'bg-[#D2F0E4] text-[#06614B]' }}">
                        <i class="ph ph-calendar-blank text-xs"></i>
                        @if($isToday)
                            Hari terakhir · s.d. 23.59 WIB
                        @else
                            {{ $daysLeft }} hari lagi
                        @endif
                    </span>
                </a>
            @empty
                <div class="col-span-full py-6 text-center text-xs text-[#536961]">
                    Semua lowongan terbuka sudah Anda lamar atau belum ada lowongan baru.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection

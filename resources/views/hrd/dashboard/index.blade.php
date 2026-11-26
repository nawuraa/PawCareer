@extends('layouts.app')

@section('title', 'Dasbor HRD — PawCareer')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="mb-7">
        <p class="text-[11px] font-bold uppercase tracking-widest text-[#06614B] mb-1">HRD</p>
        <h1 class="font-display text-3xl sm:text-[34px] font-bold text-[#0E271F] leading-tight">Ringkasan rekrutmen</h1>
        <p class="mt-1 text-sm text-[#536961]">Semua yang perlu Anda tindak lanjuti hari ini.</p>
    </div>

    {{-- 4 Kartu Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Pengajuan menunggu --}}
        <div class="bg-[#FFFDFA] border border-[#E3DDD1] rounded-2xl p-5 shadow-soft relative">
            <p class="text-xs font-medium text-[#536961] mb-2">Pengajuan menunggu</p>
            <p class="font-display text-3xl font-bold text-[#0E271F] mb-1.5">{{ $metrics['pending_requisitions'] }}</p>
            <p class="text-[11px] text-[#536961]">Target review &le; 2 hari kerja</p>
            <div class="absolute top-5 right-5 w-9 h-9 rounded-xl bg-[#FFEBD2] text-[#F09A53] flex items-center justify-center">
                <i class="ph ph-clipboard-text text-lg"></i>
            </div>
        </div>

        {{-- Lamaran perlu disaring --}}
        <div class="bg-[#FFFDFA] border border-[#E3DDD1] rounded-2xl p-5 shadow-soft relative">
            <p class="text-xs font-medium text-[#536961] mb-2">Lamaran perlu disaring</p>
            <p class="font-display text-3xl font-bold text-[#0E271F] mb-1.5">{{ $metrics['need_screening'] }}</p>
            <p class="text-[11px] text-[#536961]">Target &le; 3 hari kerja</p>
            <div class="absolute top-5 right-5 w-9 h-9 rounded-xl bg-[#D2F0E4] text-[#06614B] flex items-center justify-center">
                <i class="ph ph-file-text text-lg"></i>
            </div>
        </div>

        {{-- Wawancara terjadwal --}}
        <div class="bg-[#FFFDFA] border border-[#E3DDD1] rounded-2xl p-5 shadow-soft relative">
            <p class="text-xs font-medium text-[#536961] mb-2">Wawancara terjadwal</p>
            <p class="font-display text-3xl font-bold text-[#0E271F] mb-1.5">{{ $metrics['scheduled_interviews'] }}</p>
            <p class="text-[11px] text-[#536961]">&nbsp;</p>
            <div class="absolute top-5 right-5 w-9 h-9 rounded-xl bg-[#CCF4D3] text-[#00682A] flex items-center justify-center">
                <i class="ph ph-calendar-blank text-lg"></i>
            </div>
        </div>

        {{-- Siap diputuskan --}}
        <div class="bg-[#FFFDFA] border border-[#E3DDD1] rounded-2xl p-5 shadow-soft relative">
            <p class="text-xs font-medium text-[#536961] mb-2">Siap diputuskan</p>
            <p class="font-display text-3xl font-bold text-[#0E271F] mb-1.5">{{ $metrics['ready_decision'] }}</p>
            <p class="text-[11px] text-[#536961]">Wawancara selesai &amp; dinilai</p>
            <div class="absolute top-5 right-5 w-9 h-9 rounded-xl bg-[#FFE2DD] text-[#B32322] flex items-center justify-center">
                <i class="ph ph-gauge text-lg"></i>
            </div>
        </div>
    </div>

    {{-- Baris Dua Kolom: Corong Seleksi & Wawancara Mendatang --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">

        {{-- Corong seleksi --}}
        <div class="lg:col-span-7 bg-[#FFFDFA] rounded-3xl border border-[#E3DDD1] shadow-soft p-6">
            <div class="mb-6">
                <h2 class="font-display text-lg font-bold text-[#0E271F]">Corong seleksi</h2>
                <p class="text-xs text-[#536961]">Seluruh lamaran sepanjang siklus berjalan</p>
            </div>

            @php
                $maxFunnel = max(1, max($funnel));
                $chartTop = max(20, (int) (ceil($maxFunnel / 5) * 5));
                $ticks = [
                    $chartTop,
                    (int) ($chartTop * 0.75),
                    (int) ($chartTop * 0.5),
                    (int) ($chartTop * 0.25),
                    0,
                ];
            @endphp

            <div class="flex gap-4 items-stretch h-56 pt-2">
                {{-- Y-Axis --}}
                <div class="flex flex-col justify-between text-[11px] text-[#536961] pb-7 text-right w-6 shrink-0">
                    @foreach($ticks as $tick)
                        <span>{{ $tick }}</span>
                    @endforeach
                </div>

                {{-- Chart Grid & Bars --}}
                <div class="flex-1 flex flex-col">
                    <div class="flex-1 relative border-b border-[#E3DDD1] flex items-end justify-around gap-4 px-4">
                        {{-- Grid lines --}}
                        <div class="absolute inset-0 flex flex-col justify-between pointer-events-none">
                            @foreach($ticks as $idx => $tick)
                                @if($idx < 4)
                                    <div class="border-t border-dashed border-[#E3DDD1]/80 w-full"></div>
                                @endif
                            @endforeach
                        </div>

                        @foreach($funnel as $label => $count)
                            @php
                                $pct = $chartTop > 0 ? round(($count / $chartTop) * 100) : 0;
                                $barHeight = $count > 0 ? max(8, $pct) : 0;
                            @endphp
                            <div class="relative z-10 flex-1 max-w-[64px] h-full flex items-end justify-center group">
                                <div class="w-full rounded-t-xl bg-[#06614B] transition-all duration-300 relative"
                                     style="height: {{ $barHeight }}%;"
                                     title="{{ $label }}: {{ $count }}">
                                    @if($count > 0)
                                        <span class="absolute -top-5 left-1/2 -translate-x-1/2 text-[11px] font-bold text-[#0E271F]">
                                            {{ $count }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- X-Axis Labels --}}
                    <div class="flex justify-around gap-4 px-4 pt-2.5 text-xs text-[#536961]">
                        @foreach($funnel as $label => $count)
                            <span class="flex-1 text-center truncate">{{ $label }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Wawancara mendatang --}}
        <div class="lg:col-span-5 bg-[#FFFDFA] rounded-3xl border border-[#E3DDD1] shadow-soft p-6 flex flex-col">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-display text-lg font-bold text-[#0E271F]">Wawancara mendatang</h2>
                <a href="{{ route('hrd.interviews.index') }}"
                   class="text-xs font-semibold text-[#0E271F] hover:text-[#06614B] inline-flex items-center gap-1 transition-colors">
                    Agenda <i class="ph ph-arrow-right"></i>
                </a>
            </div>

            <div class="space-y-3.5 flex-1">
                @forelse($upcomingInterviews as $interview)
                    @php
                        $dt = $interview->scheduled_at->timezone('Asia/Jakarta');
                        $daysShort = ['Sun'=>'MIN','Mon'=>'SEN','Tue'=>'SEL','Wed'=>'RAB','Thu'=>'KAM','Fri'=>'JUM','Sat'=>'SAB'];
                        $dayLabel = $daysShort[$dt->format('D')] ?? strtoupper($dt->format('D'));
                    @endphp
                    <div class="flex items-center gap-3.5 pb-3.5 border-b border-[#E3DDD1] last:border-0 last:pb-0">
                        <div class="w-12 h-12 rounded-xl bg-[#FFECC9] text-[#915200] flex flex-col items-center justify-center shrink-0">
                            <span class="text-[10px] font-bold leading-none">{{ $dayLabel }}</span>
                            <span class="text-xs font-extrabold mt-0.5 leading-none">{{ $dt->format('H.i') }}</span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-[#0E271F] truncate">{{ $interview->application->applicant->name ?? 'Kandidat' }}</p>
                            <p class="text-xs text-[#536961] truncate">
                                {{ $interview->application->jobPosting->title ?? 'Lowongan' }}
                                · {{ $interview->interviewer->name ?? 'HRD' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center text-xs text-[#536961]">
                        Belum ada jadwal wawancara mendatang.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Baris Tiga Kolom: Pengajuan Terbaru, Lowongan Segera Ditutup, Metrik Keberhasilan --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-4">

        {{-- Pengajuan terbaru --}}
        <div class="bg-[#FFFDFA] rounded-3xl border border-[#E3DDD1] shadow-soft p-6 flex flex-col justify-between">
            <div>
                <h2 class="font-display text-lg font-bold text-[#0E271F] mb-5">Pengajuan terbaru</h2>
                <div class="space-y-4">
                    @forelse($latestRequisitions as $req)
                        <div class="pb-3.5 border-b border-[#E3DDD1] last:border-0 last:pb-0">
                            <p class="text-sm font-bold text-[#0E271F]">
                                {{ $req->position->name ?? 'Posisi' }}
                                <span class="font-normal text-[#536961]">· {{ $req->quantity }} orang</span>
                            </p>
                            <p class="text-xs text-[#536961] mt-0.5">
                                {{ $req->position->division->name ?? 'Divisi' }} · {{ $req->requester->name ?? 'Manajer' }}
                            </p>
                        </div>
                    @empty
                        <p class="text-xs text-[#536961] py-4 text-center">Tidak ada pengajuan menunggu.</p>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('job_postings.review') }}"
               class="mt-6 w-full py-2.5 px-4 rounded-xl bg-[#D2F0E4] hover:bg-[#B8E8D4] text-[#06614B] text-xs font-bold text-center transition-colors block">
                Review pengajuan
            </a>
        </div>

        {{-- Lowongan segera ditutup --}}
        <div class="bg-[#FFFDFA] rounded-3xl border border-[#E3DDD1] shadow-soft p-6">
            <div class="flex items-center gap-2 mb-5">
                <i class="ph ph-clock text-[#F09A53] text-lg"></i>
                <h2 class="font-display text-lg font-bold text-[#0E271F]">Lowongan segera ditutup</h2>
            </div>

            <div class="space-y-3">
                @forelse($closingSoonJobs as $job)
                    @php
                        $daysLeft = (int) now('Asia/Jakarta')->startOfDay()->diffInDays($job->deadline->copy()->startOfDay(), false);
                        $isToday = $daysLeft === 0;
                    @endphp
                    <div class="rounded-2xl border border-[#E3DDD1] bg-[#FAF6EE]/60 p-4 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-[#0E271F] truncate">{{ $job->title }}</p>
                            <p class="text-xs text-[#536961] mt-0.5">
                                {{ $job->accepted_count ?? 0 }}/{{ $job->quota }} terisi · {{ $job->applications_count ?? 0 }} pelamar
                            </p>
                        </div>
                        <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] font-semibold {{ $isToday ? 'bg-[#FFEBD2] text-[#915200]' : 'bg-[#D2F0E4] text-[#06614B]' }}">
                            <i class="ph ph-calendar-blank text-xs"></i>
                            @if($daysLeft < 0)
                                Lewat batas
                            @elseif($isToday)
                                Hari terakhir · s.d. 23.59 WIB
                            @else
                                {{ $daysLeft }} hari lagi
                            @endif
                        </span>
                    </div>
                @empty
                    <p class="text-xs text-[#536961] py-6 text-center">Belum ada lowongan aktif.</p>
                @endforelse
            </div>
        </div>

        {{-- Metrik keberhasilan --}}
        <div class="bg-[#FFFDFA] rounded-3xl border border-[#E3DDD1] shadow-soft p-6">
            <h2 class="font-display text-lg font-bold text-[#0E271F]">Metrik keberhasilan</h2>
            <p class="text-xs text-[#536961] mt-0.5 mb-5">Target usulan PRD, disesuaikan setelah satu siklus</p>

            <div class="space-y-3.5 text-xs">
                <div class="flex items-center justify-between gap-3">
                    <span class="flex items-center gap-2 text-[#536961]">
                        <i class="ph ph-clock text-sm"></i> Median review pengajuan
                    </span>
                    <div class="text-right shrink-0">
                        <p class="font-bold text-[#0E271F]">1,5 hari</p>
                        <p class="text-[10px] text-[#536961]">target &le; 2 hari kerja</p>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <span class="flex items-center gap-2 text-[#536961]">
                        <i class="ph ph-clock text-sm"></i> Median penyaringan berkas
                    </span>
                    <div class="text-right shrink-0">
                        <p class="font-bold text-[#0E271F]">2,0 hari</p>
                        <p class="text-[10px] text-[#536961]">target &le; 3 hari kerja</p>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <span class="flex items-center gap-2 text-[#536961]">
                        <i class="ph ph-clock text-sm"></i> Waktu pengisian formasi
                    </span>
                    <div class="text-right shrink-0">
                        <p class="font-bold text-[#0E271F]">23 hari</p>
                        <p class="text-[10px] text-[#536961]">target &le; 30 hari</p>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <span class="flex items-center gap-2 text-[#536961]">
                        <i class="ph ph-shield-check text-sm"></i> Pelanggaran kuota / ganda
                    </span>
                    <div class="text-right shrink-0">
                        <p class="font-bold text-[#0E271F]">0</p>
                        <p class="text-[10px] text-[#536961]">target 0 kejadian</p>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <span class="flex items-center gap-2 text-[#536961]">
                        <i class="ph ph-check-circle text-sm"></i> Kelengkapan jejak
                    </span>
                    <div class="text-right shrink-0">
                        <p class="font-bold text-[#0E271F]">100%</p>
                        <p class="text-[10px] text-[#536961]">target 100%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Catatan waktu data --}}
    <p class="text-[11px] text-[#536961] mt-2">
        Data per {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('D, j M Y · H.i') }} WIB
    </p>

</div>
@endsection

@extends('layouts.app')

@section('title', 'Lamaran Saya — PawCareer')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-[#06614B] mb-1">PORTAL PELAMAR</p>
            <h1 class="font-display text-3xl font-bold text-[#0E271F]">Lamaran Saya</h1>
            <p class="mt-1 text-sm text-[#536961]">Pantau status seleksi, jadwal wawancara, riwayat status, dan keputusan akhir lamaran Anda.</p>
        </div>
        <a href="{{ route('applicant.jobs.index') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#F09A53] hover:bg-[#E08843] text-[#401F0A] text-sm font-semibold transition-colors shadow-soft w-fit">
            <span>Cari Lowongan</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
            </svg>
        </a>
    </div>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-xl border border-[#298646]/30 bg-[#CCF4D3] px-4 py-3 text-sm font-medium text-[#00682A]" role="status">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @php
        $statusMap = [
            'submitted'        => ['label' => 'Dikirim',                  'text' => '#225984', 'bg' => '#DBEEFE'],
            'screening_passed' => ['label' => 'Direview',                 'text' => '#00655C', 'bg' => '#CBF1EB'],
            'interview'        => ['label' => 'Wawancara',                'text' => '#915200', 'bg' => '#FFECC9'],
            'accepted'         => ['label' => 'Diterima',                 'text' => '#00682A', 'bg' => '#CCF4D3'],
            'screening_failed' => ['label' => 'Ditolak (Berkas)',         'text' => '#B2354A', 'bg' => '#FFE2E4'],
            'failed'           => ['label' => 'Ditolak (Berkas)',         'text' => '#B2354A', 'bg' => '#FFE2E4'],
            'rejected'         => ['label' => 'Ditolak (Keputusan Akhir)','text' => '#B32322', 'bg' => '#FFE2DD'],
            'cancelled'        => ['label' => 'Dibatalkan',               'text' => '#5B6662', 'bg' => '#E3EAE7'],
        ];
    @endphp

    @if ($applications->isEmpty())
        <div class="bg-[#FFFDFA] rounded-3xl border border-[#E3DDD1] p-12 text-center shadow-soft">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-[#D2F0E4] text-[#06614B] flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="font-display text-lg font-bold text-[#0E271F]">Belum ada lamaran</h3>
            <p class="text-sm text-[#536961] mt-1 mb-6">Anda belum mengirimkan lamaran ke posisi mana pun. Jelajahi lowongan yang tersedia sekarang.</p>
            <a href="{{ route('applicant.jobs.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#06614B] hover:bg-[#054D3B] text-[#FCF8F0] text-sm font-semibold transition-colors">
                Jelajahi Lowongan
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($applications as $application)
                @php
                    $st = $statusMap[$application->status] ?? ['label' => str($application->status)->replace('_', ' ')->title(), 'text' => '#536961', 'bg' => '#F2ECE1'];
                @endphp
                <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] p-6 shadow-soft">
                    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4 pb-5 border-b border-[#E3DDD1]">
                        <div>
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <h2 class="font-display text-xl font-bold text-[#0E271F]">{{ $application->jobPosting->title }}</h2>
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold"
                                      style="color:{{ $st['text'] }}; background-color:{{ $st['bg'] }};">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background-color:{{ $st['text'] }};"></span>
                                    {{ $st['label'] }}
                                </span>
                            </div>
                            <p class="text-sm text-[#536961] mt-1">
                                <span class="font-semibold text-[#06614B]">{{ $application->jobPosting->requisition->position->division->name }}</span>
                                <span class="mx-1.5 text-[#E3DDD1]">·</span>
                                Dikirim {{ $application->submitted_at->timezone('Asia/Jakarta')->translatedFormat('j M Y · H.i') }} WIB
                            </p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('applications.cv', $application) }}"
                               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-[#E3DDD1] bg-[#FAF6EE] hover:bg-[#D2F0E4] text-xs font-semibold text-[#06614B] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                Unduh CV Saya
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-5">
                        {{-- Jadwal Wawancara --}}
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#536961] mb-2">Jadwal Wawancara</p>
                            @forelse ($application->interviews as $interview)
                                <div class="rounded-xl border border-[#E1A035]/30 bg-[#FFECC9] px-3.5 py-2.5 mb-2 text-xs text-[#915200]">
                                    <p class="font-bold">{{ $interview->scheduled_at->timezone('Asia/Jakarta')->translatedFormat('D, j M Y · H.i') }} WIB</p>
                                    <p class="mt-0.5">Status: {{ ucfirst($interview->status) }}</p>
                                </div>
                            @empty
                                <p class="text-xs text-[#536961]">Belum ada jadwal wawancara.</p>
                            @endforelse
                        </div>

                        {{-- Keputusan Akhir --}}
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#536961] mb-2">Keputusan Akhir</p>
                            @if ($application->hiringDecision)
                                @php
                                    $isAccepted = strtolower($application->hiringDecision->result) === 'accepted';
                                @endphp
                                <div class="rounded-xl px-3.5 py-2.5 text-xs {{ $isAccepted ? 'bg-[#CCF4D3] text-[#00682A]' : 'bg-[#FFE2DD] text-[#B32322]' }}">
                                    <p class="font-bold">Hasil: {{ $isAccepted ? 'Diterima' : str($application->hiringDecision->result)->title() }}</p>
                                    @if ($application->hiringDecision->start_date)
                                        <p class="mt-0.5">Tanggal mulai: {{ $application->hiringDecision->start_date->translatedFormat('j M Y') }}</p>
                                    @endif
                                </div>
                            @else
                                <p class="text-xs text-[#536961]">Belum ada keputusan akhir.</p>
                            @endif
                        </div>

                        {{-- Riwayat Status --}}
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#536961] mb-2">Riwayat Status</p>
                            <details class="group">
                                <summary class="cursor-pointer text-xs font-semibold text-[#06614B] hover:underline select-none">
                                    Lihat riwayat ({{ $application->statusHistories->count() }})
                                </summary>
                                <ol class="mt-2.5 space-y-2 border-l-2 border-[#E3DDD1] pl-3">
                                    @foreach ($application->statusHistories as $history)
                                        <li class="text-xs">
                                            <span class="font-semibold text-[#0E271F]">
                                                {{ $statusMap[$history->new_status]['label'] ?? str($history->new_status)->replace('_', ' ')->title() }}
                                            </span>
                                            <span class="text-[#536961]">
                                                · {{ $history->created_at->timezone('Asia/Jakarta')->format('d M Y, H.i') }} WIB
                                            </span>
                                            @if ($history->reason)
                                                <p class="text-[#536961] mt-0.5">{{ $history->reason }}</p>
                                            @endif
                                        </li>
                                    @endforeach
                                </ol>
                            </details>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection

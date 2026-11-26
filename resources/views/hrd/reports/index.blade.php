@extends('layouts.app')

@section('title', 'Laporan Rekrutmen — HRD')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-widest text-[#06614B] mb-1">HRD · PENGEMBANGAN LANJUT</p>
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="font-display text-3xl sm:text-[34px] font-bold text-[#0E271F] leading-tight">Laporan rekrutmen</h1>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider border border-dashed border-[#536961]/50 text-[#536961]">
                    RILIS 3
                </span>
            </div>
            <p class="mt-1 text-sm text-[#536961]">Ringkasan dan ekspor direncanakan pada Rilis 3, ditampilkan di sini sebagai gambaran.</p>
        </div>

        <a href="{{ route('hrd.reports.export') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#06614B] hover:bg-[#054D3B] text-[#FCF8F0] text-xs font-semibold transition-colors shadow-soft w-fit">
            <i class="ph ph-download-simple text-base"></i>
            <span>Ekspor CSV</span>
        </a>
    </div>

    {{-- Dua Kolom Utama --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">

        {{-- Kiri: Distribusi status lamaran (Donut Chart) --}}
        <div class="lg:col-span-5 bg-[#FFFDFA] rounded-3xl border border-[#E3DDD1] shadow-soft p-6">
            <h2 class="font-display text-lg font-bold text-[#0E271F] mb-6">Distribusi status lamaran</h2>

            @php
                $segments = [
                    ['key' => 'submitted',        'label' => 'Dikirim',                   'color' => '#225984', 'count' => $statusCounts['submitted'] ?? 0],
                    ['key' => 'screening_passed', 'label' => 'Direview',                  'color' => '#00655C', 'count' => $statusCounts['screening_passed'] ?? 0],
                    ['key' => 'screening_failed', 'label' => 'Ditolak (Berkas)',          'color' => '#B2354A', 'count' => $statusCounts['screening_failed'] ?? 0],
                    ['key' => 'interview',        'label' => 'Wawancara',                 'color' => '#915200', 'count' => $statusCounts['interview'] ?? 0],
                    ['key' => 'accepted',         'label' => 'Diterima',                  'color' => '#00682A', 'count' => $statusCounts['accepted'] ?? 0],
                    ['key' => 'rejected',         'label' => 'Ditolak (Keputusan Akhir)', 'color' => '#B32322', 'count' => $statusCounts['rejected'] ?? 0],
                    ['key' => 'cancelled',        'label' => 'Dibatalkan',                'color' => '#5B6662', 'count' => $statusCounts['cancelled'] ?? 0],
                ];
                $totalApps = array_sum(array_column($segments, 'count'));
                $radius = 56;
                $circumference = 2 * pi() * $radius;
                $offset = 0;
            @endphp

            <div class="flex justify-center my-4">
                <svg width="176" height="176" viewBox="0 0 160 160" class="-rotate-90">
                    @if($totalApps === 0)
                        <circle cx="80" cy="80" r="{{ $radius }}" fill="none" stroke="#F2ECE1" stroke-width="24"/>
                    @else
                        @foreach($segments as $seg)
                            @if($seg['count'] > 0)
                                @php
                                    $len = ($seg['count'] / $totalApps) * $circumference;
                                    $gap = $totalApps > $seg['count'] ? 2.5 : 0;
                                    $dash = max(1, $len - $gap);
                                @endphp
                                <circle cx="80" cy="80" r="{{ $radius }}"
                                        fill="none"
                                        stroke="{{ $seg['color'] }}"
                                        stroke-width="24"
                                        stroke-dasharray="{{ $dash }} {{ $circumference - $dash }}"
                                        stroke-dashoffset="-{{ $offset }}"/>
                                @php $offset += $len; @endphp
                            @endif
                        @endforeach
                    @endif
                </svg>
            </div>

            {{-- Legend 2 kolom --}}
            <div class="grid grid-cols-2 gap-x-6 gap-y-3 mt-6 pt-2 text-xs">
                @foreach($segments as $seg)
                    <div class="flex items-center justify-between gap-2">
                        <span class="flex items-center gap-2 text-[#536961] min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color:{{ $seg['color'] }};"></span>
                            <span class="truncate">{{ $seg['label'] }}</span>
                        </span>
                        <span class="font-bold text-[#0E271F] shrink-0">{{ $seg['count'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Kanan: Ringkasan per lowongan --}}
        <div class="lg:col-span-7 bg-[#FFFDFA] rounded-3xl border border-[#E3DDD1] shadow-soft p-6">
            <h2 class="font-display text-lg font-bold text-[#0E271F] mb-5">Ringkasan per lowongan</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-[#E3DDD1] text-[11px] font-semibold uppercase tracking-wider text-[#536961]">
                            <th class="pb-3 pr-4">Lowongan</th>
                            <th class="pb-3 px-3 text-right">Pelamar</th>
                            <th class="pb-3 px-3 text-right">Wawancara</th>
                            <th class="pb-3 pl-3 text-right">Terisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E3DDD1]">
                        @forelse($jobSummaries as $job)
                            <tr class="hover:bg-[#FAF6EE]/60 transition-colors">
                                <td class="py-3.5 pr-4">
                                    <p class="font-semibold text-[#0E271F]">{{ $job->title }}</p>
                                    <p class="text-xs text-[#536961]">{{ $job->requisition->position->division->name ?? 'Divisi' }}</p>
                                </td>
                                <td class="py-3.5 px-3 text-right text-[#0E271F]">{{ $job->applications_count }}</td>
                                <td class="py-3.5 px-3 text-right text-[#0E271F]">{{ $job->interview_count }}</td>
                                <td class="py-3.5 pl-3 text-right font-bold text-[#0E271F]">{{ $job->accepted_count }}/{{ $job->quota }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-10 text-center text-xs text-[#536961]">Belum ada data lowongan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Catatan Bawah --}}
    <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] px-5 py-3.5 shadow-soft flex items-center gap-3 text-xs text-[#536961]">
        <i class="ph ph-file-text text-[#06614B] text-base shrink-0"></i>
        <span>Ekspor PDF dan arsip riwayat tahunan juga direncanakan pada Rilis 3.</span>
    </div>

</div>
@endsection

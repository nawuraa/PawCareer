@extends('layouts.app')

@section('title', 'Cari Lowongan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="mb-8">
        <p class="text-xs font-bold tracking-widest uppercase text-[#06614B] mb-2">PORTAL PELAMAR</p>
        <h1 class="text-3xl font-bold text-[#0E271F]" style="font-family: 'Bricolage Grotesque', sans-serif;">Cari Lowongan</h1>
        <p class="mt-2 text-[#536961]">Temukan posisi yang sesuai. Deadline ditampilkan dalam waktu WIB.</p>
    </div>

    {{-- Job Cards Grid --}}
    @if ($jobPostings->isEmpty())
        {{-- Empty State --}}
        <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] p-12 shadow-sm flex flex-col items-center justify-center text-center">
            <div class="w-16 h-16 bg-[#D2F0E4] text-[#06614B] rounded-2xl flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.073a2.25 2.25 0 01-2.25 2.25h-12a2.25 2.25 0 01-2.25-2.25v-4.073M16.5 9.75V6.75A2.25 2.25 0 0014.25 4.5h-4.5A2.25 2.25 0 007.5 6.75v3M12 12.75h.008v.008H12v-.008z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-[#0E271F] mb-1" style="font-family: 'Bricolage Grotesque', sans-serif;">Belum ada lowongan aktif</h3>
            <p class="text-sm text-[#536961]">Pantau terus halaman ini untuk lowongan terbaru.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach ($jobPostings as $jobPosting)
                @php
                    $daysLeft = now()->diffInDays($jobPosting->deadline, false);
                    $isUrgent = $daysLeft <= 7;
                @endphp
                <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] p-6 shadow-sm hover:shadow-md transition-shadow flex flex-col">

                    {{-- Icon --}}
                    <div class="w-10 h-10 bg-[#D2F0E4] text-[#06614B] rounded-xl flex items-center justify-center mb-4 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.073a2.25 2.25 0 01-2.25 2.25h-12a2.25 2.25 0 01-2.25-2.25v-4.073M16.5 9.75V6.75A2.25 2.25 0 0014.25 4.5h-4.5A2.25 2.25 0 007.5 6.75v3M12 12.75h.008v.008H12v-.008z" />
                        </svg>
                    </div>

                    {{-- Title --}}
                    <h3 class="text-base font-bold text-[#0E271F] mb-1 leading-snug" style="font-family: 'Bricolage Grotesque', sans-serif;">
                        {{ $jobPosting->title }}
                    </h3>

                    {{-- Division --}}
                    <p class="text-sm text-[#06614B] font-medium mb-3">
                        {{ $jobPosting->requisition->position->division->name }}
                    </p>

                    {{-- Deadline Badge --}}
                    <div class="mb-2">
                        @if ($daysLeft < 0)
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-[#FFE2DD] text-[#B32322]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5" />
                                </svg>
                                Sudah Tutup
                            </span>
                        @elseif ($isUrgent)
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-[#FFEBD2] text-[#915200]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5" />
                                </svg>
                                Tutup {{ $jobPosting->deadline->timezone('Asia/Jakarta')->format('d M Y') }} ({{ $daysLeft }} hari lagi)
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-[#D2F0E4] text-[#06614B]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5" />
                                </svg>
                                Tutup {{ $jobPosting->deadline->timezone('Asia/Jakarta')->format('d M Y') }}
                            </span>
                        @endif
                    </div>

                    {{-- Quota --}}
                    <p class="text-xs text-[#536961] mb-4">Kuota: {{ $jobPosting->quota }} posisi</p>

                    {{-- Spacer --}}
                    <div class="flex-1"></div>

                    {{-- CTA Button --}}
                    <a href="{{ route('applicant.jobs.show', $jobPosting) }}"
                       class="inline-block bg-[#D2F0E4] text-[#06614B] rounded-xl px-4 py-2 font-semibold text-sm hover:bg-[#c0e4d4] transition-colors text-center">
                        Lihat Detail →
                    </a>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection

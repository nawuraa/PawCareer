@extends('layouts.app')

@section('title', 'Karyawan Baru — HRD')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-widest text-[#06614B] mb-1">REKRUTMEN · HRD</p>
        <h1 class="font-display text-3xl font-bold text-[#0E271F]">Karyawan Baru</h1>
        <p class="mt-1 text-sm text-[#536961]">Daftar kandidat yang telah dinyatakan diterima di seluruh divisi.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($newEmployees as $emp)
            @php
                $empName = $emp->applicant->name ?? 'Karyawan';
                $empInitials = collect(explode(' ', $empName))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
            @endphp
            <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] p-6 shadow-soft">
                <div class="flex items-center gap-4 mb-5">
                    <div class="w-12 h-12 rounded-full bg-[#D2F0E4] text-[#06614B] font-bold text-sm flex items-center justify-center uppercase shrink-0">
                        {{ $empInitials }}
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-display font-bold text-[#0E271F] text-base truncate">{{ $empName }}</h3>
                        <p class="text-xs font-semibold text-[#06614B] mt-0.5 truncate">{{ $emp->jobPosting->requisition->position->name ?? 'Posisi' }}</p>
                    </div>
                </div>
                <div class="bg-[#FFEBD2] rounded-xl p-3.5 mb-4">
                    <p class="text-[11px] text-[#915200]">Diterima pada</p>
                    <p class="font-bold text-sm text-[#401F0A]">{{ $emp->updated_at->translatedFormat('D, j F Y') }}</p>
                </div>
                <div class="space-y-1.5 text-xs text-[#536961]">
                    <p class="font-medium text-[#0E271F]">{{ $emp->jobPosting->requisition->position->division->name ?? 'Divisi' }}</p>
                    <p>{{ $emp->applicant->email ?? '—' }}</p>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-[#FFFDFA] rounded-3xl border border-[#E3DDD1] p-12 text-center shadow-soft">
                <p class="font-display text-base font-bold text-[#0E271F]">Belum ada karyawan baru</p>
                <p class="text-xs text-[#536961] mt-1">Kandidat yang diterima oleh HRD akan tampil di sini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

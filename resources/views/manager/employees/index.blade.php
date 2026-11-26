@extends('layouts.app')
@section('title', 'Karyawan Baru')
@section('content')

<div class="p-8 max-w-7xl mx-auto">

    {{-- Header Halaman --}}
    <div class="mb-8">
        <p class="text-[11px] font-bold text-[#06614B] tracking-wider mb-1 uppercase">
            {{ auth()->user()->division->name ?? 'Divisi Anda' }}
        </p>
        <h1 class="text-3xl font-bold text-[#0E271F] mb-2 font-[Bricolage_Grotesque,sans-serif]">Karyawan Baru</h1>
        <p class="text-sm text-[#536961]">Kandidat yang diterima di divisi Anda. Data pribadi hanya terlihat untuk yang diterima.</p>
    </div>

    {{-- Grid Kartu Karyawan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

        @forelse($newEmployees as $emp)
            @php
                $empInitials = collect(explode(' ', $emp->user->name))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                $divisionName = $emp->jobPosting->requisition->position->division->name ?? 'Divisi Anda';
            @endphp

            {{-- Kartu Karyawan --}}
            <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] p-6 shadow-sm">

                {{-- Header Kartu --}}
                <div class="flex items-center gap-4 mb-5">
                    <div class="w-12 h-12 rounded-full bg-[#D2F0E4] text-[#06614B] font-bold text-sm flex items-center justify-center uppercase flex-shrink-0">
                        {{ $empInitials }}
                    </div>
                    <div>
                        <h3 class="font-bold text-[#0E271F] text-[15px]">{{ $emp->user->name }}</h3>
                        <p class="text-[13px] text-[#06614B] mt-0.5">{{ $emp->jobPosting->requisition->position->name ?? 'Posisi' }}</p>
                    </div>
                </div>

                {{-- Kotak Tanggal Mulai --}}
                <div class="bg-[#FFEBD2] rounded-xl p-4 mb-5">
                    <p class="text-xs text-[#536961] mb-1">Mulai bekerja sejak</p>
                    <p class="font-bold text-[#0E271F]">{{ $emp->updated_at->translatedFormat('D, d F Y') }}</p>
                </div>

                {{-- Info Kontak --}}
                <div class="space-y-2.5">
                    <p class="text-[13px] text-[#536961]">{{ $divisionName }}</p>
                    <div class="flex items-center gap-2 text-[13px] text-[#536961]">
                        <i class="ph ph-envelope-simple text-[#536961] text-lg"></i>
                        {{ $emp->user->email }}
                    </div>
                    <div class="flex items-center gap-2 text-[13px] text-[#536961]">
                        <i class="ph ph-phone text-[#536961] text-lg"></i>
                        {{ $emp->user->phone ?? 'Belum ada nomor telepon' }}
                    </div>
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="col-span-full bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] p-10 text-center shadow-sm">
                <i class="ph ph-users text-[#E3DDD1] text-5xl mb-3 block"></i>
                <p class="text-[#0E271F] font-semibold">Belum ada karyawan baru di divisi ini.</p>
                <p class="text-sm text-[#536961] mt-1">Karyawan akan muncul setelah pengajuan disetujui dan kandidat diterima.</p>
            </div>
        @endforelse

    </div>

</div>

@endsection

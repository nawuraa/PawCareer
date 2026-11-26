@extends('layouts.app')
@section('title', 'Dasbor Manajer')
@section('content')

<div class="max-w-7xl mx-auto">

    @php
        $hour = now('Asia/Jakarta')->hour;
        $greeting = match (true) {
            $hour >= 4 && $hour < 11  => 'Selamat pagi',
            $hour >= 11 && $hour < 15 => 'Selamat siang',
            $hour >= 15 && $hour < 18 => 'Selamat sore',
            default                   => 'Selamat malam',
        };
    @endphp

    {{-- Header Dasbor --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-8">
        <div>
            <p class="text-[11px] font-bold text-[#06614B] tracking-widest mb-1 uppercase">
                Divisi {{ auth()->user()->division->name ?? '' }}
            </p>
            <h1 class="text-3xl font-bold text-[#0E271F] mb-2 font-display">
                {{ $greeting }}, {{ explode(' ', auth()->user()->name)[0] }}
            </h1>
            <p class="text-sm text-[#536961]">Ajukan kebutuhan staf dan pantau prosesnya sampai karyawan baru bergabung.</p>
        </div>
        <a href="{{ route('requisitions.create') }}"
           class="bg-[#F09A53] text-[#401F0A] px-5 py-2.5 rounded-xl font-semibold text-sm inline-flex items-center gap-2 hover:bg-[#e08843] transition-colors shadow-soft w-fit">
            <i class="ph ph-plus font-bold"></i> Ajukan kebutuhan
        </a>
    </div>

    {{-- 4 Kartu Metrik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Kartu: Menunggu HRD --}}
        <div class="bg-[#FFFDFA] border border-[#E3DDD1] rounded-2xl p-5 shadow-sm relative">
            <p class="text-xs text-[#536961] font-medium mb-1">Menunggu HRD</p>
            <h3 class="text-2xl font-bold text-[#0E271F] mb-2">{{ $metrics['pending'] ?? 0 }}</h3>
            <p class="text-[10px] text-[#536961]">Pengajuan berstatus Diajukan</p>
            <div class="absolute top-5 right-5 w-8 h-8 rounded-full bg-[#FFEBD2] text-[#F09A53] flex items-center justify-center">
                <i class="ph ph-clock text-lg"></i>
            </div>
        </div>

        {{-- Kartu: Disetujui --}}
        <div class="bg-[#FFFDFA] border border-[#E3DDD1] rounded-2xl p-5 shadow-sm relative">
            <p class="text-xs text-[#536961] font-medium mb-1">Disetujui</p>
            <h3 class="text-2xl font-bold text-[#0E271F] mb-2">{{ $metrics['approved'] ?? 0 }}</h3>
            <p class="text-[10px] text-[#536961]">&nbsp;</p>
            <div class="absolute top-5 right-5 w-8 h-8 rounded-full bg-[#D2F0E4] text-[#06614B] flex items-center justify-center">
                <i class="ph ph-check-circle text-lg"></i>
            </div>
        </div>

        {{-- Kartu: Ditolak --}}
        <div class="bg-[#FFFDFA] border border-[#E3DDD1] rounded-2xl p-5 shadow-sm relative">
            <p class="text-xs text-[#536961] font-medium mb-1">Ditolak</p>
            <h3 class="text-2xl font-bold text-[#0E271F] mb-2">{{ $metrics['rejected'] ?? 0 }}</h3>
            <p class="text-[10px] text-[#536961]">Final, buat pengajuan baru</p>
            <div class="absolute top-5 right-5 w-8 h-8 rounded-full bg-[#FFE2DD] text-[#B32322] flex items-center justify-center">
                <i class="ph ph-x-circle text-lg"></i>
            </div>
        </div>

        {{-- Kartu: Karyawan Baru --}}
        <div class="bg-[#FFFDFA] border border-[#E3DDD1] rounded-2xl p-5 shadow-sm relative">
            <p class="text-xs text-[#536961] font-medium mb-1">Karyawan Baru</p>
            <h3 class="text-2xl font-bold text-[#0E271F] mb-2">{{ $metrics['new_employees'] ?? 0 }}</h3>
            <p class="text-[10px] text-[#536961]">&nbsp;</p>
            <div class="absolute top-5 right-5 w-8 h-8 rounded-full bg-[#D2F0E4] text-[#06614B] flex items-center justify-center">
                <i class="ph ph-user-plus text-lg"></i>
            </div>
        </div>
    </div>

    {{-- Konten Dua Kolom --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 pb-10">

        {{-- Kolom Kiri: Pengajuan Divisi --}}
        <div class="lg:col-span-7 bg-[#FFFDFA] rounded-3xl border border-[#E3DDD1] shadow-soft p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="font-bold text-[#0E271F] text-lg font-display">Pengajuan divisi</h3>
                    <p class="text-xs text-[#536961]">Termasuk pengajuan Manajer lain di divisi yang sama (hanya lihat)</p>
                </div>
                <a href="{{ route('requisitions.index') }}"
                   class="text-xs font-semibold text-[#536961] hover:text-[#0E271F] flex items-center gap-1">
                    Semua <i class="ph ph-arrow-right"></i>
                </a>
            </div>

            <div class="space-y-4">
                @forelse($requisitions ?? [] as $req)
                    <div class="flex items-center justify-between pb-4 border-b border-[#E3DDD1] last:border-0 last:pb-0">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-[#D2F0E4] text-[#06614B] flex items-center justify-center">
                                <i class="ph ph-clipboard-text text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold text-sm text-[#0E271F]">
                                    {{ $req->position->name ?? 'Posisi' }}
                                    <span class="font-normal text-[#536961]">· {{ $req->quantity }} orang</span>
                                </p>
                                <p class="text-xs text-[#536961] mt-0.5">
                                    Oleh {{ $req->requested_by === auth()->id() ? 'Anda' : ($req->requester->name ?? 'Manajer') }}
                                    · {{ $req->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </div>

                        @if(in_array($req->status, ['Diajukan', 'submitted']))
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-[#DBEEFE] text-[#225984] uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#225984]"></span> Diajukan
                            </span>
                        @elseif(in_array($req->status, ['Disetujui', 'approved']))
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-[#CCF4D3] text-[#00682A] uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#00682A]"></span> Disetujui
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-[#FFE2DD] text-[#B32322] uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#B32322]"></span> Ditolak
                            </span>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-[#536961] text-center py-4">Belum ada pengajuan.</p>
                @endforelse
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div class="lg:col-span-5 space-y-6">

            {{-- Lowongan Divisi --}}
            <div class="bg-[#FFFDFA] rounded-3xl border border-[#E3DDD1] shadow-soft p-6">
                <h3 class="font-bold text-[#0E271F] text-lg font-display mb-5">Lowongan divisi</h3>

                <div class="space-y-5">
                    @forelse($jobPostings ?? [] as $job)
                        @php
                            $filled = $job->accepted_count ?? 0;
                            $quota = max(1, (int) $job->quota);
                            $pct = min(100, round(($filled / $quota) * 100));
                        @endphp
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center gap-2 text-sm font-semibold text-[#0E271F]">
                                    <i class="ph ph-briefcase text-[#536961] text-lg"></i> {{ $job->title }}
                                </div>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $job->status === 'open' ? 'bg-[#CCF4D3] text-[#00682A]' : 'bg-[#E3EAE7] text-[#5B6662]' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $job->status === 'open' ? 'bg-[#00682A]' : 'bg-[#5B6662]' }}"></span>
                                    {{ $job->status === 'open' ? 'Dibuka' : 'Ditutup' }}
                                </span>
                            </div>
                            <div class="w-full bg-[#F2ECE1] rounded-full h-1.5 mb-1 overflow-hidden">
                                <div class="bg-[#06614B] h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="text-[11px] text-[#536961] text-right">{{ $filled }}/{{ $job->quota }} terisi</p>
                        </div>
                    @empty
                        <p class="text-sm text-[#536961] text-center py-2">Tidak ada lowongan aktif.</p>
                    @endforelse
                </div>
            </div>

            {{-- Karyawan Baru --}}
            <div class="bg-[#FFFDFA] rounded-3xl border border-[#E3DDD1] shadow-soft p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-[#0E271F] text-lg font-display">Karyawan baru</h3>
                    <a href="{{ route('employees.index') }}"
                       class="text-xs font-semibold text-[#536961] hover:text-[#0E271F] flex items-center gap-1">
                        Lihat <i class="ph ph-arrow-right"></i>
                    </a>
                </div>

                @forelse(collect($newEmployees ?? [])->take(3) as $emp)
                    @php
                        $empName = $emp->applicant->name ?? 'Karyawan';
                        $empInitials = collect(explode(' ', $empName))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                    @endphp
                    <div class="flex items-center gap-3 mb-4 last:mb-0">
                        <div class="w-10 h-10 rounded-full bg-[#D2F0E4] text-[#06614B] font-bold text-sm flex items-center justify-center uppercase shrink-0">
                            {{ $empInitials }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-sm text-[#0E271F] truncate">{{ $empName }}</p>
                            <p class="text-xs text-[#536961] truncate">
                                {{ $emp->jobPosting->requisition->position->name ?? 'Posisi' }}
                                · mulai {{ $emp->updated_at->translatedFormat('j M Y') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-[#536961] text-center py-2">Belum ada karyawan baru.</p>
                @endforelse
            </div>

        </div>
    </div>
</div>

@endsection

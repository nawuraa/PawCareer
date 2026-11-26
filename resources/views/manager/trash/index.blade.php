@extends('layouts.app')
@section('title', 'Tempat Sampah')
@section('content')

<div class="px-8 py-9 max-w-5xl mx-auto">

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-6 px-4 py-3 rounded-xl bg-[#CCF4D3] text-[#00682A] border border-[#A3E6B0] flex items-center gap-2 text-sm">
            <i class="ph-fill ph-check-circle text-lg"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Breadcrumb & Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-2 text-xs text-[#536961] mb-3">
            <span>Portal Manajer Divisi</span>
            <span>/</span>
            <span class="font-medium text-[#0E271F]">Tempat Sampah</span>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <span class="inline-block text-[11px] font-bold text-[#06614B] tracking-wider uppercase mb-1">
                    Soft Delete · Retensi 30 Hari
                </span>
                <h1 class="text-2xl sm:text-3xl font-bold text-[#0E271F] tracking-tight font-[Bricolage_Grotesque,sans-serif]">
                    Tempat Sampah
                </h1>
                <p class="text-sm text-[#536961] mt-1">
                    Pengajuan milik Anda yang dihapus dapat dipulihkan kembali sebelum batas waktu 30 hari berakhir.
                </p>
            </div>

            <div class="text-xs text-[#536961] bg-[#FFFDFA] border border-[#E3DDD1] px-3.5 py-2 rounded-lg shrink-0">
                Total item tersimpan: <strong class="text-[#0E271F]">{{ $trashedRequisitions->count() }}</strong>
            </div>
        </div>
    </div>

    {{-- List Item yang Dihapus --}}
    @forelse($trashedRequisitions as $req)
        <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] p-5 mb-4 shadow-sm hover:border-[#06614B]/30 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-5">

            {{-- Kiri: Badge & Info --}}
            <div class="flex items-start sm:items-center gap-4">
                <span class="bg-[#D2F0E4] text-[#06614B] border border-[#A3E6B0] text-[10px] font-bold px-3 py-1.5 rounded-lg tracking-wider shrink-0">
                    PENGAJUAN
                </span>
                <div>
                    <h3 class="font-semibold text-[#0E271F] text-[15px]">
                        {{ $req->position->name ?? 'Posisi' }} · {{ $req->quantity }} orang
                    </h3>
                    <p class="text-[13px] text-[#536961] mt-0.5">
                        Alasan: {{ \Illuminate\Support\Str::limit($req->reason, 60) }}
                        · Dihapus pada {{ $req->deleted_at->translatedFormat('d M Y') }}
                    </p>
                </div>
            </div>

            {{-- Kanan: Progress Bar & Tombol Pulihkan --}}
            <div class="flex items-center justify-between sm:justify-end gap-6 pt-3 sm:pt-0 border-t sm:border-t-0 border-[#E3DDD1]">
                {{-- Countdown bar --}}
                <div class="w-36">
                    <div class="flex items-center justify-between text-[11px] mb-1.5">
                        <span class="text-[#536961]">Sisa waktu</span>
                        <span class="text-[#06614B] font-semibold">{{ $req->days_left }} hari</span>
                    </div>
                    <div class="w-full bg-[#E3DDD1] rounded-full h-1.5 overflow-hidden">
                        <div class="bg-[#06614B] h-1.5 rounded-full" style="width: {{ $req->retention_pct }}%"></div>
                    </div>
                </div>

                {{-- Tombol Pulihkan --}}
                <form action="{{ route('trash.requisitions.restore', $req->id) }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Pulihkan pengajuan {{ $req->position->name ?? 'ini' }} ke daftar aktif?')"
                            class="inline-flex items-center gap-1.5 bg-[#D2F0E4] hover:bg-[#B8E8D4] text-[#06614B] text-xs font-semibold px-4 py-2.5 rounded-xl transition-colors border border-[#A3E6B0] cursor-pointer shrink-0">
                        <i class="ph ph-arrow-counter-clockwise text-sm font-bold"></i>
                        <span>Pulihkan</span>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] p-10 text-center shadow-sm">
            <i class="ph ph-trash text-[#E3DDD1] text-5xl mb-3 block"></i>
            <p class="font-semibold text-[#0E271F]">Tempat sampah kosong</p>
            <p class="text-sm text-[#536961] mt-1">Tidak ada pengajuan yang telah dihapus.</p>
        </div>
    @endforelse

    {{-- Banner Info Aturan --}}
    <div class="bg-[#D2F0E4]/50 border border-[#A3E6B0] rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 mt-6">
        <div class="flex items-start gap-3">
            <i class="ph-fill ph-info text-[#06614B] text-lg shrink-0 mt-0.5"></i>
            <p class="text-xs text-[#0E271F] leading-relaxed">
                Posisi tidak dapat dipulihkan bila divisinya juga terhapus. Nama data di tempat sampah tetap terpakai sampai dipulihkan atau dibuang permanen. Pembuangan otomatis harian setelah 30 hari berlaku.
            </p>
        </div>
    </div>

</div>

@endsection

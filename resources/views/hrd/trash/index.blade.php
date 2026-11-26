@extends('layouts.app')

@section('title', 'Tempat Sampah — HRD')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-widest text-[#06614B] mb-1">PENGELOLAAN · HRD</p>
        <h1 class="font-display text-3xl font-bold text-[#0E271F]">Tempat Sampah</h1>
        <p class="mt-1 text-sm text-[#536961]">Daftar pengajuan dan lowongan yang telah dihapus sementara (soft delete).</p>
    </div>

    <div class="space-y-6">
        <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] p-6 shadow-soft">
            <h2 class="font-display text-lg font-bold text-[#0E271F] mb-4">Pengajuan Terhapus ({{ $trashedRequisitions->count() }})</h2>
            <div class="divide-y divide-[#E3DDD1]">
                @forelse($trashedRequisitions as $req)
                    <div class="py-3.5 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-[#0E271F]">{{ $req->position->name ?? 'Posisi' }} · {{ $req->quantity }} orang</p>
                            <p class="text-xs text-[#536961]">{{ $req->position->division->name ?? 'Divisi' }} · Dihapus {{ $req->deleted_at->translatedFormat('j M Y') }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#E3EAE7] text-[#5B6662]">Terhapus</span>
                    </div>
                @empty
                    <p class="text-xs text-[#536961] py-4 text-center">Tidak ada pengajuan di tempat sampah.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] p-6 shadow-soft">
            <h2 class="font-display text-lg font-bold text-[#0E271F] mb-4">Lowongan Terhapus ({{ $trashedJobs->count() }})</h2>
            <div class="divide-y divide-[#E3DDD1]">
                @forelse($trashedJobs as $job)
                    <div class="py-3.5 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-[#0E271F]">{{ $job->title }}</p>
                            <p class="text-xs text-[#536961]">{{ $job->requisition->position->division->name ?? 'Divisi' }} · Dihapus {{ $job->deleted_at->translatedFormat('j M Y') }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#E3EAE7] text-[#5B6662]">Terhapus</span>
                    </div>
                @empty
                    <p class="text-xs text-[#536961] py-4 text-center">Tidak ada lowongan di tempat sampah.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

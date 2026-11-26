@extends('layouts.app')

@section('title', 'Riwayat Status — HRD')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-widest text-[#06614B] mb-1">HRD · AUDIT TRAIL</p>
            <h1 class="font-display text-3xl sm:text-[34px] font-bold text-[#0E271F] leading-tight">Riwayat status</h1>
            <p class="mt-1 text-sm text-[#536961]">Semua perubahan status lamaran, pengajuan, lowongan, dan wawancara tercatat otomatis dan tidak dapat diubah.</p>
        </div>

        <span class="px-3.5 py-1.5 rounded-xl bg-[#FFFDFA] border border-[#E3DDD1] text-xs font-medium text-[#536961] shrink-0 w-fit">
            Total entri: <strong id="totalEntries" class="text-[#0E271F]">{{ $histories->count() }}</strong>
        </span>
    </div>

    @php
        $typeLabels = [
            'Application' => 'Lamaran',
            'Requisition' => 'Pengajuan',
            'JobPosting'  => 'Lowongan',
            'Interview'   => 'Wawancara',
            'Division'    => 'Divisi',
            'Position'    => 'Posisi',
        ];
        $statusLabels = [
            'submitted'        => 'Dikirim',
            'screening_passed' => 'Direview',
            'screening_failed' => 'Ditolak (Berkas)',
            'failed'           => 'Ditolak (Berkas)',
            'interview'        => 'Wawancara',
            'accepted'         => 'Diterima',
            'rejected'         => 'Ditolak (Keputusan Akhir)',
            'cancelled'        => 'Dibatalkan',
            'scheduled'        => 'Terjadwal',
            'completed'        => 'Selesai',
            'open'             => 'Dibuka',
            'closed'           => 'Ditutup',
            'Diajukan'         => 'Diajukan',
            'Disetujui'        => 'Disetujui',
            'Ditolak'          => 'Ditolak',
        ];
    @endphp

    {{-- Search & Filter Pills --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-5">
        <div class="relative w-full md:w-72">
            <i class="ph ph-magnifying-glass text-[#536961] absolute left-3.5 top-1/2 -translate-y-1/2 text-sm pointer-events-none"></i>
            <input type="text" id="historySearch"
                   placeholder="Cari nama, posisi, atau alasan..."
                   class="w-full pl-9 pr-3.5 py-2 text-xs text-[#0E271F] bg-[#FFFDFA] border border-[#DDD7C9] rounded-xl outline-none focus:border-[#06614B] transition-colors">
        </div>

        <div class="flex items-center gap-1.5 flex-wrap" id="typeFilters">
            <button type="button" data-type="all"
                    class="filter-pill px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors cursor-pointer bg-[#06614B] text-[#FCF8F0]">
                Semua
            </button>
            @foreach(['Lamaran', 'Pengajuan', 'Lowongan', 'Wawancara', 'Divisi', 'Posisi'] as $pill)
                <button type="button" data-type="{{ $pill }}"
                        class="filter-pill px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors cursor-pointer bg-[#FFFDFA] text-[#536961] border border-[#E3DDD1] hover:bg-[#F2ECE1]">
                    {{ $pill }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] shadow-soft overflow-hidden mb-5">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-[#E3DDD1] bg-[#FAF6EE] text-[11px] font-semibold uppercase tracking-wider text-[#536961]">
                        <th class="px-5 py-3.5">Waktu</th>
                        <th class="px-5 py-3.5">Objek</th>
                        <th class="px-5 py-3.5">Perubahan</th>
                        <th class="px-5 py-3.5">Oleh</th>
                        <th class="px-5 py-3.5">Alasan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E3DDD1]">
                    @forelse($histories as $h)
                        @php
                            $baseType = class_basename($h->subject_type);
                            $objTypeLabel = $typeLabels[$baseType] ?? $baseType;
                            $oldLabel = $h->old_status ? ($statusLabels[$h->old_status] ?? ucfirst($h->old_status)) : null;
                            $newLabel = $statusLabels[$h->new_status] ?? ucfirst($h->new_status);

                            $subjectDetail = '#' . $h->subject_id;
                            if ($baseType === 'Application' && $h->subject) {
                                $subjectDetail = ($h->subject->applicant->name ?? 'Pelamar') . ' · ' . ($h->subject->jobPosting->title ?? 'Lowongan');
                            } elseif ($baseType === 'Interview' && $h->subject) {
                                $subjectDetail = ($h->subject->application->applicant->name ?? 'Kandidat') . ' · ' . ($h->subject->application->jobPosting->title ?? 'Wawancara');
                            } elseif ($baseType === 'Requisition' && $h->subject) {
                                $subjectDetail = $h->subject->position->name ?? ('Pengajuan #' . $h->subject_id);
                            } elseif ($baseType === 'JobPosting' && $h->subject) {
                                $subjectDetail = $h->subject->title ?? ('Lowongan #' . $h->subject_id);
                            }
                        @endphp
                        <tr class="history-row hover:bg-[#FAF6EE]/70 transition-colors" data-type="{{ $objTypeLabel }}">
                            <td class="px-5 py-3.5 whitespace-nowrap text-xs text-[#536961]">
                                {{ $h->created_at?->timezone('Asia/Jakarta')->translatedFormat('j M Y · H.i') }} WIB
                            </td>
                            <td class="px-5 py-3.5 text-xs">
                                <span class="font-semibold text-[#0E271F] underline decoration-[#E3DDD1] underline-offset-2">{{ $objTypeLabel }}</span>
                                <span class="text-[#536961]">· {{ $subjectDetail }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-xs whitespace-nowrap">
                                @if($oldLabel)
                                    <span class="text-[#536961]">{{ $oldLabel }}</span>
                                    <span class="mx-1 text-[#06614B]">→</span>
                                @endif
                                <span class="font-semibold text-[#0E271F]">{{ $newLabel }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-[#0E271F] whitespace-nowrap">
                                {{ $h->changer->name ?? 'Sistem' }}
                            </td>
                            <td class="px-5 py-3.5 text-xs text-[#536961]">
                                {{ $h->reason ?: '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-[#536961]">Belum ada riwayat perubahan status.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Footer Audit Banner --}}
    <div class="rounded-2xl border border-[#06614B]/20 bg-[#D2F0E4]/50 px-5 py-3.5 flex items-center gap-3 text-xs text-[#06614B]">
        <i class="ph ph-info text-base shrink-0"></i>
        <span>Catatan perubahan bersifat append-only (hanya tambah) untuk kebutuhan audit rekrutmen Klinik Hewan Sahabat Satwa.</span>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('historySearch');
    const pills = document.querySelectorAll('.filter-pill');
    const rows = document.querySelectorAll('.history-row');
    const totalEl = document.getElementById('totalEntries');
    let activeType = 'all';

    function applyFilter() {
        const kw = (searchInput?.value || '').toLowerCase().trim();
        let count = 0;

        rows.forEach(function (row) {
            const typeMatch = activeType === 'all' || row.getAttribute('data-type') === activeType;
            const textMatch = !kw || row.textContent.toLowerCase().includes(kw);
            const show = typeMatch && textMatch;
            row.style.display = show ? '' : 'none';
            if (show) count++;
        });

        if (totalEl) totalEl.textContent = count;
    }

    pills.forEach(function (pill) {
        pill.addEventListener('click', function () {
            activeType = this.getAttribute('data-type');
            pills.forEach(p => {
                p.className = 'filter-pill px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors cursor-pointer bg-[#FFFDFA] text-[#536961] border border-[#E3DDD1] hover:bg-[#F2ECE1]';
            });
            this.className = 'filter-pill px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors cursor-pointer bg-[#06614B] text-[#FCF8F0]';
            applyFilter();
        });
    });

    if (searchInput) searchInput.addEventListener('input', applyFilter);
});
</script>
@endpush

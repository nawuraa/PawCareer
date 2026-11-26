@extends('layouts.app')

@section('title', 'Posisi — PawCareer')
@section('breadcrumb', 'Posisi')
@section('custom_layout', true)

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- ALERT NOTIFICATIONS --}}
    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-[#CCF4D3] border border-[#298646]/30 text-[#00682A] text-sm font-medium flex items-center gap-2.5" role="status">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-[#FFE2DD] border border-[#D33B36]/30 text-[#B32322] text-sm font-medium flex items-center gap-2.5" role="alert">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18 9 9 0 000-18z"/>
            </svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-[#06614B] mb-1">PENGELOLAAN · HRD</p>
            <h1 class="text-3xl font-bold text-[#0E271F] tracking-tight font-display">Posisi</h1>
            <p class="text-sm text-[#536961] mt-1">Kelola posisi dan jabatan pekerjaan di Klinik Hewan Sahabat Satwa.</p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('divisions.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#FFFDFA] border border-[#E3DDD1] rounded-xl text-xs font-semibold text-[#0E271F] hover:bg-[#F2ECE1] transition-colors shadow-soft">
                <svg class="w-4 h-4 text-[#536961]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18"/>
                </svg>
                <span>Kelola Divisi</span>
            </a>

            <a href="{{ route('positions.create') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-[#06614B] rounded-xl text-xs font-semibold text-[#FCF8F0] hover:bg-[#054D3B] transition-colors shadow-soft">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                <span>Tambah Posisi</span>
            </a>
        </div>
    </div>

    {{-- SUMMARY METRICS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-[#FFFDFA] border border-[#E3DDD1] rounded-2xl p-5 flex items-center justify-between shadow-soft">
            <div>
                <p class="text-xs font-medium text-[#536961]">Total Posisi Terdaftar</p>
                <p class="text-3xl font-bold text-[#0E271F] mt-1 font-display">{{ $positions->count() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#D2F0E4] text-[#06614B] flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38"/>
                </svg>
            </div>
        </div>

        <div class="bg-[#FFFDFA] border border-[#E3DDD1] rounded-2xl p-5 flex items-center justify-between shadow-soft">
            <div>
                <p class="text-xs font-medium text-[#536961]">Cakupan Divisi</p>
                <p class="text-3xl font-bold text-[#0E271F] mt-1 font-display">{{ $positions->map(fn($p) => $p->division?->name)->filter()->unique()->count() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#FFEBD2] text-[#F09A53] flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25z"/>
                </svg>
            </div>
        </div>

        <div class="bg-[#FFFDFA] border border-[#E3DDD1] rounded-2xl p-5 flex items-center justify-between shadow-soft">
            <div>
                <p class="text-xs font-medium text-[#536961]">Status Struktur</p>
                <p class="text-sm font-semibold text-[#0E271F] mt-1.5">Aktif untuk Rekrutmen</p>
            </div>
            <span class="px-3 py-1 rounded-full text-[11px] font-semibold bg-[#CCF4D3] text-[#00682A]">
                Terhubung
            </span>
        </div>
    </div>

    {{-- TABLE CONTAINER WITH FILTER & SEARCH --}}
    <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] overflow-hidden shadow-soft">
        {{-- TOOLBAR --}}
        <div class="px-5 py-4 border-b border-[#E3DDD1] flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-[#FFFDFA]">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 w-full sm:w-auto">
                {{-- SEARCH INPUT --}}
                <div class="relative w-full sm:w-64">
                    <svg class="w-4 h-4 text-[#536961] absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
                    </svg>
                    <input type="text"
                           id="positionSearch"
                           placeholder="Cari nama posisi atau deskripsi..."
                           class="w-full pl-9 pr-3 py-2 text-xs text-[#0E271F] bg-[#FAF6EE] border border-[#DDD7C9] rounded-xl outline-none focus:bg-[#FFFDFA] focus:border-[#06614B] transition-colors">
                </div>

                {{-- DIVISION FILTER --}}
                <select id="divisionFilter"
                        class="px-3 py-2 text-xs text-[#0E271F] bg-[#FAF6EE] border border-[#DDD7C9] rounded-xl outline-none focus:bg-[#FFFDFA] focus:border-[#06614B] transition-colors">
                    <option value="">Semua Divisi</option>
                    @foreach ($positions->map(fn($p) => $p->division?->name)->filter()->unique()->sort() as $divName)
                        <option value="{{ strtolower($divName) }}">{{ $divName }}</option>
                    @endforeach
                </select>
            </div>

            <span class="text-xs text-[#536961]">
                Menampilkan <strong id="visibleCount" class="text-[#0E271F]">{{ $positions->count() }}</strong> posisi
            </span>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#FAF6EE] border-b border-[#E3DDD1] text-[11px] font-semibold text-[#536961] uppercase tracking-wider">
                        <th class="py-3.5 px-6 w-14">No</th>
                        <th class="py-3.5 px-6">Divisi</th>
                        <th class="py-3.5 px-6">Nama Posisi</th>
                        <th class="py-3.5 px-6">Deskripsi</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="positionTableBody" class="divide-y divide-[#E3DDD1] text-sm">
                    @forelse ($positions as $index => $position)
                        <tr class="position-row hover:bg-[#FAF6EE] transition-colors"
                            data-division="{{ strtolower($position->division?->name ?? '') }}">
                            <td class="py-4 px-6 text-xs font-medium text-[#536961]">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                @if ($position->division?->name)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#D2F0E4] text-[#06614B]">
                                        {{ $position->division->name }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#E3EAE7] text-[#5B6662]">
                                        Divisi tidak tersedia
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 font-semibold text-[#0E271F] whitespace-nowrap">
                                {{ $position->name }}
                            </td>
                            <td class="py-4 px-6 text-[#536961]">
                                @if ($position->description)
                                    {{ $position->description }}
                                @else
                                    <span class="text-[#536961]/70 text-xs">Belum ada keterangan posisi</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-2">
                                    <a href="{{ route('positions.edit', $position) }}"
                                       class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold text-[#0E271F] bg-[#FFFDFA] hover:bg-[#FAF6EE] border border-[#E3DDD1] transition-colors">
                                        Ubah
                                    </a>

                                    <form action="{{ route('positions.destroy', $position) }}" method="POST" class="inline-block m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Yakin ingin menghapus posisi ini?')"
                                                class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold text-[#B32322] bg-[#FFFDFA] hover:bg-[#FFE2DD] border border-[#E3DDD1] hover:border-[#F5C0BE] transition-colors cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 px-6 text-center">
                                <p class="text-sm font-semibold text-[#0E271F]">Belum ada posisi yang terdaftar</p>
                                <p class="text-xs text-[#536961] mt-1">Klik tombol "Tambah Posisi" di kanan atas untuk membuat jabatan baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('positionSearch');
        const divisionFilter = document.getElementById('divisionFilter');
        const rows = document.querySelectorAll('.position-row');
        const visibleCount = document.getElementById('visibleCount');

        function filterRows() {
            const keyword = (searchInput?.value || '').toLowerCase().trim();
            const selectedDivision = (divisionFilter?.value || '').toLowerCase().trim();
            let count = 0;

            rows.forEach(function (row) {
                const text = row.textContent.toLowerCase();
                const rowDivision = row.getAttribute('data-division') || '';

                const matchesKeyword = text.includes(keyword);
                const matchesDivision = !selectedDivision || rowDivision === selectedDivision;
                const isVisible = matchesKeyword && matchesDivision;

                row.style.display = isVisible ? '' : 'none';
                if (isVisible) count++;
            });

            if (visibleCount) visibleCount.textContent = count;
        }

        if (searchInput) searchInput.addEventListener('input', filterRows);
        if (divisionFilter) divisionFilter.addEventListener('change', filterRows);
    });
</script>
@endpush

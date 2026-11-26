@extends('layouts.app')

@section('title', 'Divisi — PawCareer')
@section('breadcrumb', 'Divisi')
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

    @if ($errors->has('division'))
        <div class="mb-6 p-4 rounded-xl bg-[#FFE2DD] border border-[#D33B36]/30 text-[#B32322] text-sm font-medium flex items-center gap-2.5" role="alert">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18 9 9 0 000-18z"/>
            </svg>
            <span>{{ $errors->first('division') }}</span>
        </div>
    @endif

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-[#06614B] mb-1">PENGELOLAAN · HRD</p>
            <h1 class="text-3xl font-bold text-[#0E271F] tracking-tight font-display">Divisi</h1>
            <p class="text-sm text-[#536961] mt-1">Kelola struktur divisi dan unit kerja di Klinik Hewan Sahabat Satwa.</p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('positions.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#FFFDFA] border border-[#E3DDD1] rounded-xl text-xs font-semibold text-[#0E271F] hover:bg-[#F2ECE1] transition-colors shadow-soft">
                <svg class="w-4 h-4 text-[#536961]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38"/>
                </svg>
                <span>Kelola Posisi</span>
            </a>

            <a href="{{ route('divisions.create') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-[#06614B] rounded-xl text-xs font-semibold text-[#FCF8F0] hover:bg-[#054D3B] transition-colors shadow-soft">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                <span>Tambah Divisi</span>
            </a>
        </div>
    </div>

    {{-- SUMMARY METRICS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-[#FFFDFA] border border-[#E3DDD1] rounded-2xl p-5 flex items-center justify-between shadow-soft">
            <div>
                <p class="text-xs font-medium text-[#536961]">Total Divisi Aktif</p>
                <p class="text-3xl font-bold text-[#0E271F] mt-1 font-display">{{ $divisions->count() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#D2F0E4] text-[#06614B] flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18"/>
                </svg>
            </div>
        </div>

        <div class="bg-[#FFFDFA] border border-[#E3DDD1] rounded-2xl p-5 flex items-center justify-between shadow-soft">
            <div>
                <p class="text-xs font-medium text-[#536961]">Divisi dengan Deskripsi</p>
                <p class="text-3xl font-bold text-[#0E271F] mt-1 font-display">{{ $divisions->whereNotNull('description')->where('description', '!=', '')->count() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#FFEBD2] text-[#F09A53] flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12"/>
                </svg>
            </div>
        </div>

        <div class="bg-[#FFFDFA] border border-[#E3DDD1] rounded-2xl p-5 flex items-center justify-between shadow-soft">
            <div>
                <p class="text-xs font-medium text-[#536961]">Unit Klinik</p>
                <p class="text-sm font-semibold text-[#0E271F] mt-1.5">Sahabat Satwa Utama</p>
            </div>
            <span class="px-3 py-1 rounded-full text-[11px] font-semibold bg-[#CCF4D3] text-[#00682A]">
                Terverifikasi
            </span>
        </div>
    </div>

    {{-- TABLE CONTAINER WITH SEARCH BAR --}}
    <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] overflow-hidden shadow-soft">
        {{-- FILTER TOOLBAR --}}
        <div class="px-5 py-4 border-b border-[#E3DDD1] flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-[#FFFDFA]">
            <div class="relative w-full sm:w-72">
                <svg class="w-4 h-4 text-[#536961] absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
                </svg>
                <input type="text"
                       id="divisionSearch"
                       placeholder="Cari nama atau deskripsi divisi..."
                       class="w-full pl-9 pr-3 py-2 text-xs text-[#0E271F] bg-[#FAF6EE] border border-[#DDD7C9] rounded-xl outline-none focus:bg-[#FFFDFA] focus:border-[#06614B] transition-colors">
            </div>

            <span class="text-xs text-[#536961]">
                Menampilkan <strong id="visibleCount" class="text-[#0E271F]">{{ $divisions->count() }}</strong> divisi
            </span>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#FAF6EE] border-b border-[#E3DDD1] text-[11px] font-semibold text-[#536961] uppercase tracking-wider">
                        <th class="py-3.5 px-6 w-14">No</th>
                        <th class="py-3.5 px-6">Nama Divisi</th>
                        <th class="py-3.5 px-6">Deskripsi</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="divisionTableBody" class="divide-y divide-[#E3DDD1] text-sm">
                    @forelse ($divisions as $index => $division)
                        <tr class="division-row hover:bg-[#FAF6EE] transition-colors">
                            <td class="py-4 px-6 text-xs font-medium text-[#536961]">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-full bg-[#D2F0E4] text-[#06614B] flex items-center justify-center text-xs font-bold shrink-0">
                                        {{ strtoupper(substr($division->name, 0, 2)) }}
                                    </span>
                                    <span class="font-semibold text-[#0E271F]">{{ $division->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-[#536961]">
                                @if ($division->description)
                                    {{ $division->description }}
                                @else
                                    <span class="text-[#536961]/70 text-xs">Belum ada keterangan divisi</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-2">
                                    <a href="{{ route('divisions.edit', $division) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-[#0E271F] bg-[#FFFDFA] hover:bg-[#FAF6EE] border border-[#E3DDD1] transition-colors">
                                        <span>Ubah</span>
                                    </a>

                                    <form action="{{ route('divisions.destroy', $division) }}" method="POST" class="inline-block m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Yakin ingin menghapus divisi ini?')"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-[#B32322] bg-[#FFFDFA] hover:bg-[#FFE2DD] border border-[#E3DDD1] hover:border-[#F5C0BE] transition-colors cursor-pointer">
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-16 px-6 text-center">
                                <p class="text-sm font-semibold text-[#0E271F]">Belum ada divisi yang terdaftar</p>
                                <p class="text-xs text-[#536961] mt-1">Klik tombol "Tambah Divisi" di kanan atas untuk membuat divisi baru.</p>
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
        const searchInput = document.getElementById('divisionSearch');
        const rows = document.querySelectorAll('.division-row');
        const visibleCount = document.getElementById('visibleCount');

        if (!searchInput) return;

        searchInput.addEventListener('input', function () {
            const keyword = this.value.toLowerCase().trim();
            let count = 0;

            rows.forEach(function (row) {
                const text = row.textContent.toLowerCase();
                const match = text.includes(keyword);
                row.style.display = match ? '' : 'none';
                if (match) count++;
            });

            if (visibleCount) visibleCount.textContent = count;
        });
    });
</script>
@endpush

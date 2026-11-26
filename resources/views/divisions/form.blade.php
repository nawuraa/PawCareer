@extends('layouts.app')

@php
    $isEdit = isset($division) && $division->exists;
@endphp

@section('title', ($isEdit ? 'Ubah Divisi' : 'Tambah Divisi') . ' — PawCareer')
@section('breadcrumb', 'Divisi / ' . ($isEdit ? 'Ubah' : 'Tambah Baru'))
@section('custom_layout', true)

@section('content')
    <div class="max-w-3xl mx-auto">
        {{-- PAGE HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <div class="flex items-center gap-2 text-xs text-[#536961] mb-1.5">
                    <a href="{{ route('divisions.index') }}" class="hover:text-[#06614B] transition-colors">Divisi</a>
                    <span>/</span>
                    <span class="text-[#0E271F] font-medium">{{ $isEdit ? 'Ubah Divisi' : 'Tambah Baru' }}</span>
                </div>
                <h1 class="text-3xl font-bold text-[#0E271F] tracking-tight font-display">
                    {{ $isEdit ? 'Ubah Divisi' : 'Tambah Divisi' }}
                </h1>
                <p class="text-sm text-[#536961] mt-1">
                    {{ $isEdit ? 'Perbarui nama atau keterangan unit divisi di Klinik Hewan Sahabat Satwa.' : 'Tambahkan divisi atau unit kerja baru ke dalam sistem PawCareer.' }}
                </p>
            </div>

            <a href="{{ route('divisions.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#FFFDFA] border border-[#E3DDD1] rounded-xl text-xs font-semibold text-[#0E271F] hover:bg-[#F2ECE1] transition-colors w-fit shadow-soft">
                <svg class="w-4 h-4 text-[#536961]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                <span>Kembali ke Daftar</span>
            </a>
        </div>

        {{-- GLOBAL ERROR ALERT --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-[#FFE2DD] border border-[#D33B36]/30 text-[#B32322] text-sm flex items-start gap-2.5" role="alert">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18 9 9 0 000-18z"/>
                </svg>
                <div>
                    <p class="font-semibold">Terdapat kesalahan pada pengisian form:</p>
                    <p class="text-xs mt-0.5">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        {{-- FORM CARD --}}
        <form action="{{ $isEdit ? route('divisions.update', $division) : route('divisions.store') }}"
              method="POST"
              class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] shadow-soft overflow-hidden">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="p-6 sm:p-8 space-y-6">
                {{-- SECTION TITLE --}}
                <div class="pb-4 border-b border-[#E3DDD1]">
                    <h2 class="text-base font-semibold text-[#0E271F] font-display">Informasi Divisi</h2>
                    <p class="text-xs text-[#536961] mt-0.5">Divisi ini akan digunakan sebagai unit induk untuk pengelompokan posisi dan manajer divisi.</p>
                </div>

                {{-- FIELD: DIVISION NAME --}}
                <div>
                    <label for="name" class="block text-xs font-semibold text-[#0E271F] mb-2">
                        Nama Divisi <span class="text-[#D33B36]">*</span>
                    </label>
                    <input id="name"
                           name="name"
                           type="text"
                           value="{{ old('name', $division->name ?? '') }}"
                           placeholder="Contoh: Grooming & Kennel"
                           maxlength="255"
                           required
                           autofocus
                           class="w-full px-3.5 py-2.5 text-sm text-[#0E271F] bg-[#FFFDFA] border {{ $errors->has('name') ? 'border-[#D33B36] focus:border-[#D33B36]' : 'border-[#DDD7C9] focus:border-[#06614B]' }} rounded-xl outline-none focus:ring-2 focus:ring-[#06614B]/20 placeholder:text-[#536961]/60 transition">
                    @error('name')
                        <p class="mt-1.5 text-xs font-medium text-[#B32322]">{{ $message }}</p>
                    @enderror
                </div>

                {{-- FIELD: DESCRIPTION --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="description" class="block text-xs font-semibold text-[#0E271F]">
                            Deskripsi
                        </label>
                        <span class="text-[11px] text-[#536961]">Opsional</span>
                    </div>
                    <textarea id="description"
                              name="description"
                              rows="4"
                              placeholder="Tuliskan deskripsi singkat peran dan fungsi divisi ini..."
                              class="w-full px-3.5 py-2.5 text-sm text-[#0E271F] bg-[#FFFDFA] border {{ $errors->has('description') ? 'border-[#D33B36] focus:border-[#D33B36]' : 'border-[#DDD7C9] focus:border-[#06614B]' }} rounded-xl outline-none focus:ring-2 focus:ring-[#06614B]/20 placeholder:text-[#536961]/60 transition">{{ old('description', $division->description ?? '') }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs font-medium text-[#B32322]">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- FORM FOOTER ACTIONS --}}
            <div class="px-6 sm:px-8 py-4 bg-[#FAF6EE] border-t border-[#E3DDD1] flex items-center justify-end gap-3">
                <a href="{{ route('divisions.index') }}"
                   class="px-4 py-2.5 rounded-xl text-xs font-semibold text-[#0E271F] bg-[#FFFDFA] hover:bg-[#F2ECE1] border border-[#E3DDD1] transition-colors">
                    Batal
                </a>

                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-semibold text-[#FCF8F0] bg-[#06614B] hover:bg-[#054D3B] transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Divisi' }}</span>
                </button>
            </div>
        </form>
    </div>
@endsection

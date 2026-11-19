@extends('layouts.app')

@php
    $isEdit = isset($position) && $position->exists;
@endphp

@section('title', ($isEdit ? 'Edit Posisi' : 'Tambah Posisi') . ' — PawCareer')
@section('breadcrumb', 'Positions / ' . ($isEdit ? 'Edit' : 'Tambah Baru'))
@section('custom_layout', true)

@section('content')
    <div class="max-w-3xl">
        {{-- PAGE HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-500 mb-1.5">
                    <a href="{{ route('positions.index') }}" class="hover:text-brand transition-colors">Positions</a>
                    <span>/</span>
                    <span class="text-slate-700 font-medium">{{ $isEdit ? 'Edit Posisi' : 'Tambah Baru' }}</span>
                </div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                    {{ $isEdit ? 'Edit Position' : 'Create Position' }}
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    {{ $isEdit ? 'Perbarui informasi jabatan atau divisi untuk posisi ini.' : 'Tambahkan posisi atau jabatan pekerjaan baru ke dalam divisi klinik.' }}
                </p>
            </div>

            <a href="{{ route('positions.index') }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 bg-white border border-line rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors w-fit">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                <span>Kembali ke Daftar</span>
            </a>
        </div>

        {{-- GLOBAL ERROR ALERT --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm flex items-start gap-2.5" role="alert">
                <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18 9 9 0 000-18z"/>
                </svg>
                <div>
                    <p class="font-semibold">Terdapat kesalahan pada pengisian form:</p>
                    <p class="text-xs mt-0.5">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        {{-- FORM CARD --}}
        <form action="{{ $isEdit ? route('positions.update', $position) : route('positions.store') }}"
              method="POST"
              class="bg-white rounded-xl border border-line shadow-sm overflow-hidden">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="p-6 sm:p-8 space-y-6">
                {{-- SECTION TITLE --}}
                <div class="pb-4 border-b border-slate-100">
                    <h2 class="text-sm font-semibold text-slate-900">Informasi Jabatan</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Tentukan divisi induk serta nama posisi yang akan digunakan pada pengajuan rekrutmen.</p>
                </div>

                {{-- FIELD: DIVISION --}}
                <div>
                    <label for="division_id" class="block text-xs font-semibold text-slate-800 mb-2">
                        Division <span class="text-rose-600">*</span>
                    </label>
                    <select id="division_id"
                            name="division_id"
                            required
                            class="w-full px-3.5 py-2.5 text-sm text-slate-900 bg-white border {{ $errors->has('division_id') ? 'border-rose-400 focus:border-rose-500' : 'border-slate-300 focus:border-brand' }} rounded-lg outline-none focus:ring-3 focus:ring-brand/10 transition">
                        <option value="" disabled {{ old('division_id', $position->division_id ?? '') ? '' : 'selected' }}>Pilih divisi tujuan...</option>
                        @foreach ($divisions as $division)
                            <option value="{{ $division->id }}" @selected(old('division_id', $position->division_id ?? '') == $division->id)>
                                {{ $division->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('division_id')
                        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- FIELD: POSITION NAME --}}
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-800 mb-2">
                        Position Name <span class="text-rose-600">*</span>
                    </label>
                    <input id="name"
                           name="name"
                           type="text"
                           value="{{ old('name', $position->name ?? '') }}"
                           placeholder="Contoh: Veterinary Resident, Grooming Specialist"
                           maxlength="255"
                           required
                           class="w-full px-3.5 py-2.5 text-sm text-slate-900 bg-white border {{ $errors->has('name') ? 'border-rose-400 focus:border-rose-500' : 'border-slate-300 focus:border-brand' }} rounded-lg outline-none focus:ring-3 focus:ring-brand/10 placeholder:text-slate-400 transition">
                    @error('name')
                        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- FIELD: DESCRIPTION --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="description" class="block text-xs font-semibold text-slate-800">
                            Description
                        </label>
                        <span class="text-[11px] text-slate-400">Opsional</span>
                    </div>
                    <textarea id="description"
                              name="description"
                              rows="4"
                              placeholder="Tuliskan deskripsi ringkas mengenai tanggung jawab dan ruang lingkup posisi ini..."
                              class="w-full px-3.5 py-2.5 text-sm text-slate-900 bg-white border {{ $errors->has('description') ? 'border-rose-400 focus:border-rose-500' : 'border-slate-300 focus:border-brand' }} rounded-lg outline-none focus:ring-3 focus:ring-brand/10 placeholder:text-slate-400 transition">{{ old('description', $position->description ?? '') }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- FORM FOOTER ACTIONS --}}
            <div class="px-6 sm:px-8 py-4 bg-slate-50 border-t border-line flex items-center justify-end gap-3">
                <a href="{{ route('positions.index') }}"
                   class="px-4 py-2.5 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-100 border border-slate-300 transition-colors">
                    Batal
                </a>

                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-xs font-semibold text-white bg-brand hover:bg-brand-hover transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Posisi' }}</span>
                </button>
            </div>
        </form>
    </div>
@endsection
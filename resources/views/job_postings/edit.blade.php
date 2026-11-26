@extends('layouts.app')
@section('title', 'Ubah Lowongan — HRD')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-[#536961] mb-6">
        <a href="{{ route('job-postings.index') }}" class="hover:text-[#06614B] transition-colors">Lowongan</a>
        <span>/</span>
        <span class="font-medium text-[#0E271F]">Ubah Lowongan</span>
    </div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-[#06614B] mb-1">REKRUTMEN · HRD</p>
            <h1 class="font-display text-3xl font-bold text-[#0E271F]">Ubah Detail Lowongan</h1>
            <p class="mt-1 text-sm text-[#536961]">Perbarui judul, deskripsi pekerjaan, persyaratan, atau batas waktu lamaran.</p>
        </div>

        <a href="{{ route('job-postings.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#FFFDFA] border border-[#E3DDD1] text-xs font-semibold text-[#0E271F] hover:bg-[#FAF6EE] transition-colors w-fit shadow-soft">
            <svg class="w-4 h-4 text-[#536961]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    {{-- Error Validation --}}
    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-[#D33B36]/30 bg-[#FFE2DD] px-4 py-3 text-sm text-[#B32322]">
            <p class="font-semibold mb-1">Terdapat kesalahan pada pengisian form:</p>
            <ul class="list-disc pl-4 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Card --}}
    <form action="{{ route('job-postings.update', $jobPosting->id) }}" method="POST"
          class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] shadow-soft overflow-hidden">
        @csrf
        @method('PUT')

        <div class="p-6 sm:p-8 space-y-5">
            <div>
                <label for="title" class="block text-sm font-semibold text-[#0E271F] mb-1.5">
                    Judul Lowongan <span class="text-[#D33B36]">*</span>
                </label>
                <input type="text" id="title" name="title"
                       value="{{ old('title', $jobPosting->title) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-[#DDD7C9] bg-[#FFFDFA] text-sm text-[#0E271F] focus:outline-none focus:ring-2 focus:ring-[#06614B]/20 focus:border-[#06614B] transition">
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-[#0E271F] mb-1.5">
                    Deskripsi Pekerjaan <span class="text-[#D33B36]">*</span>
                </label>
                <textarea id="description" name="description" rows="4" required
                          class="w-full px-4 py-2.5 rounded-xl border border-[#DDD7C9] bg-[#FFFDFA] text-sm text-[#0E271F] focus:outline-none focus:ring-2 focus:ring-[#06614B]/20 focus:border-[#06614B] transition">{{ old('description', $jobPosting->description) }}</textarea>
            </div>

            <div>
                <label for="requirements" class="block text-sm font-semibold text-[#0E271F] mb-1.5">
                    Persyaratan Kandidat
                </label>
                <textarea id="requirements" name="requirements" rows="4"
                          class="w-full px-4 py-2.5 rounded-xl border border-[#DDD7C9] bg-[#FFFDFA] text-sm text-[#0E271F] focus:outline-none focus:ring-2 focus:ring-[#06614B]/20 focus:border-[#06614B] transition">{{ old('requirements', $jobPosting->requirements) }}</textarea>
            </div>

            <div>
                <label for="deadline" class="block text-sm font-semibold text-[#0E271F] mb-1.5">
                    Batas Waktu Lamaran (Deadline) <span class="text-[#D33B36]">*</span>
                </label>
                <input type="date" id="deadline" name="deadline"
                       min="{{ now()->toDateString() }}"
                       value="{{ old('deadline', optional($jobPosting->deadline)->format('Y-m-d')) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-[#DDD7C9] bg-[#FFFDFA] text-sm text-[#0E271F] focus:outline-none focus:ring-2 focus:ring-[#06614B]/20 focus:border-[#06614B] transition">
                <p class="mt-1.5 text-xs text-[#536961]">Deadline berlaku sampai akhir hari (23.59 WIB).</p>
            </div>
        </div>

        <div class="px-6 sm:px-8 py-4 bg-[#FAF6EE] border-t border-[#E3DDD1] flex items-center justify-end gap-3">
            <a href="{{ route('job-postings.index') }}"
               class="px-4 py-2.5 rounded-xl text-xs font-semibold text-[#0E271F] bg-[#FFFDFA] hover:bg-[#F2ECE1] border border-[#E3DDD1] transition-colors">
                Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-semibold text-[#FCF8F0] bg-[#06614B] hover:bg-[#054D3B] transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>

</div>
@endsection

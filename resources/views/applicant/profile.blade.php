@extends('layouts.app')

@section('title', 'Profil Pelamar — PawCareer')

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- Header --}}
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-widest text-[#06614B] mb-1">PORTAL PELAMAR</p>
        <h1 class="font-display text-3xl font-bold text-[#0E271F]">Profil Pelamar</h1>
        <p class="mt-1 text-sm text-[#536961]">Nomor telepon dan pendidikan terakhir wajib dilengkapi sebelum Anda dapat melamar lowongan.</p>
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-xl border border-[#298646]/30 bg-[#CCF4D3] px-4 py-3 text-sm font-medium text-[#00682A]" role="status">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-[#D33B36]/30 bg-[#FFE2DD] px-4 py-3 text-sm text-[#B32322]" role="alert">
            <p class="font-semibold mb-1">Terdapat kesalahan pada pengisian profil:</p>
            <ul class="list-disc pl-4 space-y-1">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    @php
        $hasPhone = filled($profile?->phone);
        $hasEducation = filled($profile?->education);
        $completeness = ($hasPhone && $hasEducation) ? 100 : (($hasPhone || $hasEducation) ? 50 : 0);
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Form Profil --}}
        <div class="lg:col-span-8">
            <form action="{{ route('applicant.profile.update') }}" method="POST"
                  class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] shadow-soft overflow-hidden">
                @csrf
                @method('PUT')

                <div class="p-6 sm:p-8 space-y-6">
                    <div class="pb-4 border-b border-[#E3DDD1]">
                        <h2 class="font-display text-lg font-bold text-[#0E271F]">Data Diri &amp; Pendidikan</h2>
                        <p class="text-xs text-[#536961] mt-0.5">Informasi ini digunakan tim HRD untuk menghubungi Anda dan memverifikasi kualifikasi.</p>
                    </div>

                    {{-- Nama & Email (Read-only dari akun) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#536961] mb-1.5">Nama Lengkap</label>
                            <input type="text" value="{{ auth()->user()->name }}" disabled
                                   class="w-full px-3.5 py-2.5 text-sm text-[#536961] bg-[#FAF6EE] border border-[#E3DDD1] rounded-xl cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#536961] mb-1.5">Email</label>
                            <input type="email" value="{{ auth()->user()->email }}" disabled
                                   class="w-full px-3.5 py-2.5 text-sm text-[#536961] bg-[#FAF6EE] border border-[#E3DDD1] rounded-xl cursor-not-allowed">
                        </div>
                    </div>

                    {{-- Nomor Telepon --}}
                    <div>
                        <label for="phone" class="block text-xs font-semibold text-[#0E271F] mb-2">
                            Nomor Telepon <span class="text-[#D33B36]">*</span>
                        </label>
                        <input id="phone" name="phone" type="text" maxlength="20" required
                               value="{{ old('phone', $profile?->phone) }}"
                               placeholder="Contoh: 081234567890"
                               class="w-full px-3.5 py-2.5 text-sm text-[#0E271F] bg-[#FFFDFA] border border-[#DDD7C9] rounded-xl outline-none focus:border-[#06614B] focus:ring-2 focus:ring-[#06614B]/20 transition">
                    </div>

                    {{-- Pendidikan Terakhir --}}
                    <div>
                        <label for="education" class="block text-xs font-semibold text-[#0E271F] mb-2">
                            Pendidikan Terakhir <span class="text-[#D33B36]">*</span>
                        </label>
                        <input id="education" name="education" type="text" maxlength="150" required
                               value="{{ old('education', $profile?->education) }}"
                               placeholder="Contoh: S1 Kedokteran Hewan — Universitas Airlangga"
                               class="w-full px-3.5 py-2.5 text-sm text-[#0E271F] bg-[#FFFDFA] border border-[#DDD7C9] rounded-xl outline-none focus:border-[#06614B] focus:ring-2 focus:ring-[#06614B]/20 transition">
                    </div>
                </div>

                <div class="px-6 sm:px-8 py-4 bg-[#FAF6EE] border-t border-[#E3DDD1] flex items-center justify-end gap-3">
                    <a href="{{ route('applicant.jobs.index') }}"
                       class="px-4 py-2.5 rounded-xl text-xs font-semibold text-[#0E271F] bg-[#FFFDFA] hover:bg-[#F2ECE1] border border-[#E3DDD1] transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-semibold text-[#FCF8F0] bg-[#06614B] hover:bg-[#054D3B] transition-colors cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan Profil</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Sidebar Info: Kelengkapan & Privasi --}}
        <div class="lg:col-span-4 space-y-5">
            <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] p-6 shadow-soft">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-display text-base font-bold text-[#0E271F]">Kelengkapan Profil</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $completeness === 100 ? 'bg-[#CCF4D3] text-[#00682A]' : 'bg-[#FFEBD2] text-[#915200]' }}">
                        {{ $completeness }}%
                    </span>
                </div>
                <div class="w-full bg-[#F2ECE1] rounded-full h-2 mb-4 overflow-hidden">
                    <div class="bg-[#06614B] h-2 rounded-full transition-all" style="width: {{ $completeness }}%"></div>
                </div>
                <ul class="space-y-2 text-xs">
                    <li class="flex items-center gap-2 {{ $hasPhone ? 'text-[#00682A]' : 'text-[#536961]' }}">
                        <span class="w-2 h-2 rounded-full {{ $hasPhone ? 'bg-[#00682A]' : 'bg-[#DDD7C9]' }}"></span>
                        Nomor telepon aktif
                    </li>
                    <li class="flex items-center gap-2 {{ $hasEducation ? 'text-[#00682A]' : 'text-[#536961]' }}">
                        <span class="w-2 h-2 rounded-full {{ $hasEducation ? 'bg-[#00682A]' : 'bg-[#DDD7C9]' }}"></span>
                        Pendidikan terakhir
                    </li>
                </ul>
            </div>

            <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] p-6 shadow-soft">
                <h3 class="font-display text-sm font-bold text-[#0E271F] mb-1.5">Privasi Data Pelamar</h3>
                <p class="text-xs text-[#536961] leading-relaxed">
                    Data pribadi dan CV Anda disimpan secara privat sesuai UU No. 27 Tahun 2022 tentang Pelindungan Data Pribadi dan hanya dapat diakses oleh tim HRD.
                </p>
            </div>
        </div>

    </div>

</div>
@endsection

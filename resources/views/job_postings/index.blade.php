@extends('layouts.app')
@section('title', 'Lowongan — HRD')

@section('content')
<div class="min-h-screen bg-[#FAF6EE] px-4 py-8 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-widest text-[#06614B] mb-1">REKRUTMEN · HRD</p>
        <h1 class="font-['Bricolage_Grotesque'] text-3xl font-bold text-[#0E271F]">Kelola Lowongan</h1>
        <p class="mt-1 text-sm text-[#536961]">Daftar lowongan aktif dan riwayat publikasi dari pengajuan manajer.</p>
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="mb-4 flex items-center gap-3 rounded-xl border border-[#298646]/30 bg-[#CCF4D3] px-4 py-3 text-sm text-[#00682A]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 flex items-center gap-3 rounded-xl border border-[#D33B36]/30 bg-[#FFE2DD] px-4 py-3 text-sm text-[#B32322]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Table Card --}}
    <div class="rounded-2xl border border-[#E3DDD1] bg-[#FFFDFA] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-[#E3DDD1] bg-[#FAF6EE]">
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#536961]">Lowongan & Divisi</th>
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#536961]">Kuota</th>
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#536961]">Deadline</th>
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#536961]">Status</th>
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#536961] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E3DDD1]">
                    @forelse ($jobPostings as $job)
                        <tr class="hover:bg-[#FAF6EE] transition-colors">

                            {{-- Lowongan & Divisi --}}
                            <td class="px-5 py-4">
                                <p class="font-semibold text-[#0E271F]">{{ $job->title }}</p>
                                <p class="text-xs text-[#536961] mt-0.5 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
                                    {{ $job->requisition->position->division->name ?? 'Divisi tidak ditemukan' }}
                                </p>
                            </td>

                            {{-- Kuota --}}
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center rounded-full bg-[#FFEBD2] px-2.5 py-0.5 text-xs font-semibold text-[#F09A53]">
                                    {{ $job->quota }} Orang
                                </span>
                            </td>

                            {{-- Deadline --}}
                            <td class="px-5 py-4">
                                @if ($job->deadline)
                                    <p class="font-medium text-[#0E271F]">{{ $job->deadline->format('d M Y') }}</p>
                                    @if ($job->deadline < now() && $job->status === 'open')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-[#FFE2DD] px-2 py-0.5 text-xs font-semibold text-[#B32322] mt-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                            Kedaluwarsa
                                        </span>
                                    @endif
                                @else
                                    <span class="text-[#536961]">—</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4">
                                @if ($job->status === 'open')
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium"
                                          style="color:#00682A;background-color:#CCF4D3">
                                        <span class="h-1.5 w-1.5 rounded-full bg-[#00682A]"></span>
                                        Dibuka
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium"
                                          style="color:#5B6662;background-color:#E3EAE7">
                                        <span class="h-1.5 w-1.5 rounded-full bg-[#5B6662]"></span>
                                        Ditutup
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($job->status === 'open')
                                        {{-- Edit --}}
                                        <a href="{{ route('job-postings.edit', $job->id) }}"
                                           title="Ubah Detail"
                                           class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-[#E3DDD1] bg-[#FAF6EE] text-[#536961] hover:border-[#06614B] hover:text-[#06614B] hover:bg-[#D2F0E4] transition-colors">
                                            {{-- Pencil icon --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                        </a>

                                        {{-- Tutup --}}
                                        <form action="{{ route('job-postings.update', $job->id) }}" method="POST" class="m-0"
                                              onsubmit="return confirm('Tutup lowongan ini secara manual? Lowongan tidak dapat dibuka kembali.');">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="close_job" value="1">
                                            <button type="submit" title="Tutup Lowongan"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-[#E3DDD1] bg-[#FAF6EE] text-[#536961] hover:border-[#F09A53] hover:text-[#F09A53] hover:bg-[#FFEBD2] transition-colors">
                                                {{-- Lock icon --}}
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Hapus --}}
                                    <form action="{{ route('job-postings.destroy', $job->id) }}" method="POST" class="m-0"
                                          onsubmit="return confirm('Yakin ingin menghapus lowongan ini ke Tempat Sampah?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Lowongan"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-[#E3DDD1] bg-[#FAF6EE] text-[#536961] hover:border-[#D33B36] hover:text-[#D33B36] hover:bg-[#FFE2DD] transition-colors">
                                            {{-- Trash icon --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-[#536961]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#E3DDD1]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    <p class="font-['Bricolage_Grotesque'] text-lg font-bold text-[#0E271F]">Belum ada lowongan</p>
                                    <p class="text-sm">Lowongan akan otomatis terbit saat pengajuan manajer disetujui.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

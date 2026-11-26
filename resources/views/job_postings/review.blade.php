@extends('layouts.app')
@section('title', 'Review Pengajuan — HRD')

@section('content')
<div class="min-h-screen bg-[#FAF6EE] px-4 py-8 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-widest text-[#06614B] mb-1">REKRUTMEN · HRD</p>
        <h1 class="font-['Bricolage_Grotesque'] text-3xl font-bold text-[#0E271F]">Review Pengajuan Kebutuhan Staf</h1>
        <p class="mt-1 text-sm text-[#536961]">Daftar pengajuan dari Manajer Divisi yang menunggu keputusan HRD.</p>
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-xl border border-[#298646]/30 bg-[#CCF4D3] px-4 py-3 text-sm text-[#00682A]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-[#D33B36]/30 bg-[#FFE2DD] px-4 py-3 text-sm text-[#B32322]">
            <p class="font-semibold mb-1">Terdapat kesalahan:</p>
            <ul class="list-disc pl-4 space-y-1">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Cards --}}
    @forelse ($requisitions as $req)
        <div class="rounded-2xl border border-[#E3DDD1] bg-[#FFFDFA] p-6 shadow-sm mb-4">

            {{-- Card Header --}}
            <div class="flex items-start justify-between gap-4 mb-3">
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="font-['Bricolage_Grotesque'] text-lg font-bold text-[#0E271F]">
                            {{ $req->position->name ?? 'Posisi ID: ' . $req->position_id }}
                        </h2>
                        <span class="inline-flex items-center rounded-full bg-[#FFEBD2] px-2.5 py-0.5 text-xs font-semibold text-[#F09A53]">
                            {{ $req->quantity }} orang
                        </span>
                    </div>
                    <p class="text-sm text-[#536961] mt-0.5">
                        {{ $req->position->division->name ?? '—' }}
                        <span class="mx-1.5 text-[#E3DDD1]">·</span>
                        Diajukan oleh {{ $req->requester->name ?? 'Pengguna' }}
                    </p>
                </div>
            </div>

            {{-- Alasan --}}
            @if ($req->reason)
                <p class="text-sm text-[#536961] leading-relaxed mb-5 line-clamp-3">{{ $req->reason }}</p>
            @endif

            {{-- Divider --}}
            <div class="border-t border-[#E3DDD1] mb-5"></div>

            {{-- Action buttons + inline forms --}}
            <div class="space-y-4">

                {{-- Setujui form --}}
                <div x-data="{ open: false }">
                    <button type="button" @click="open = !open"
                            class="inline-flex items-center gap-2 rounded-xl bg-[#06614B] px-4 py-2 text-sm font-semibold text-white hover:bg-[#044f3c] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Setujui & Terbitkan
                    </button>

                    <div x-show="open" x-cloak x-transition class="mt-3 rounded-xl border border-[#E3DDD1] bg-[#FAF6EE] p-4">
                        <form action="{{ route('job_postings.process_review', $req) }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="action" value="approve">

                            <div>
                                <label class="block text-xs font-semibold text-[#536961] mb-1">Judul Lowongan</label>
                                <input type="text" name="title" maxlength="150" required
                                       value="{{ $req->position->name ?? '' }}"
                                       class="w-full rounded-xl border border-[#E3DDD1] bg-white px-3 py-2 text-sm text-[#0E271F] focus:outline-none focus:ring-2 focus:ring-[#06614B]/30">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#536961] mb-1">Deskripsi Pekerjaan <span class="text-[#D33B36]">*</span></label>
                                <textarea name="description" required rows="4"
                                          class="w-full rounded-xl border border-[#E3DDD1] bg-white px-3 py-2 text-sm text-[#0E271F] placeholder-[#536961] focus:outline-none focus:ring-2 focus:ring-[#06614B]/30 resize-none"></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#536961] mb-1">Persyaratan</label>
                                <textarea name="requirements" rows="3"
                                          class="w-full rounded-xl border border-[#E3DDD1] bg-white px-3 py-2 text-sm text-[#0E271F] placeholder-[#536961] focus:outline-none focus:ring-2 focus:ring-[#06614B]/30 resize-none"></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#536961] mb-1">Batas Waktu Lamaran <span class="text-[#D33B36]">*</span></label>
                                <input type="date" name="deadline" required
                                       min="{{ now()->toDateString() }}"
                                       class="w-full rounded-xl border border-[#E3DDD1] bg-white px-3 py-2 text-sm text-[#0E271F] focus:outline-none focus:ring-2 focus:ring-[#06614B]/30">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#536961] mb-1">Catatan HRD <span class="text-[#536961] font-normal">(opsional)</span></label>
                                <textarea name="review_note" rows="2"
                                          class="w-full rounded-xl border border-[#E3DDD1] bg-white px-3 py-2 text-sm text-[#0E271F] placeholder-[#536961] focus:outline-none focus:ring-2 focus:ring-[#06614B]/30 resize-none"></textarea>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                        class="inline-flex items-center gap-2 rounded-xl bg-[#06614B] px-5 py-2 text-sm font-semibold text-white hover:bg-[#044f3c] transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Setujui & Terbitkan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Tolak form --}}
                <div x-data="{ open: false }">
                    <button type="button" @click="open = !open"
                            class="inline-flex items-center gap-2 rounded-xl border border-[#D33B36] px-4 py-2 text-sm font-semibold text-[#D33B36] hover:bg-[#FFE2DD] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        Tolak
                    </button>

                    <div x-show="open" x-cloak x-transition class="mt-3 rounded-xl border border-[#FFE2E4] bg-[#FFF5F5] p-4">
                        <form action="{{ route('job_postings.process_review', $req) }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="action" value="reject">

                            <div>
                                <label class="block text-xs font-semibold text-[#536961] mb-1">Alasan Penolakan <span class="text-[#D33B36]">*</span></label>
                                <textarea name="review_note" required rows="3" placeholder="Jelaskan alasan penolakan pengajuan ini…"
                                          class="w-full rounded-xl border border-[#FFE2E4] bg-white px-3 py-2 text-sm text-[#0E271F] placeholder-[#536961] focus:outline-none focus:ring-2 focus:ring-[#D33B36]/30 resize-none"></textarea>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                        class="inline-flex items-center gap-2 rounded-xl bg-[#D33B36] px-5 py-2 text-sm font-semibold text-white hover:bg-[#b82e2a] transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    Tolak Pengajuan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    @empty
        <div class="rounded-2xl border border-[#E3DDD1] bg-[#FFFDFA] p-16 text-center shadow-sm">
            <div class="flex flex-col items-center gap-3 text-[#536961]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-[#E3DDD1]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <p class="font-['Bricolage_Grotesque'] text-lg font-bold text-[#0E271F]">Semua pengajuan telah diproses</p>
                <p class="text-sm">Tidak ada pengajuan kebutuhan staf yang menunggu review saat ini.</p>
            </div>
        </div>
    @endforelse

</div>
@endsection

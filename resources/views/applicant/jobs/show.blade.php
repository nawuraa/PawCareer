@extends('layouts.app')

@section('title', $jobPosting->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('applicant.jobs.index') }}"
           class="inline-flex items-center gap-2 text-sm text-[#536961] hover:text-[#06614B] font-medium transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Lowongan
        </a>
    </div>

    {{-- Job Header Card --}}
    <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] p-8 shadow-sm mb-6">
        <div class="flex flex-col sm:flex-row sm:items-start gap-5">
            {{-- Icon --}}
            <div class="w-14 h-14 bg-[#D2F0E4] text-[#06614B] rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.073a2.25 2.25 0 01-2.25 2.25h-12a2.25 2.25 0 01-2.25-2.25v-4.073M16.5 9.75V6.75A2.25 2.25 0 0014.25 4.5h-4.5A2.25 2.25 0 007.5 6.75v3M12 12.75h.008v.008H12v-.008z" />
                </svg>
            </div>

            <div class="flex-1 min-w-0">
                {{-- Title --}}
                <h1 class="text-2xl font-bold text-[#0E271F] leading-tight mb-1" style="font-family: 'Bricolage Grotesque', sans-serif;">
                    {{ $jobPosting->title }}
                </h1>

                {{-- Division --}}
                <p class="text-sm font-semibold text-[#06614B] mb-3">
                    {{ $jobPosting->requisition->position->division->name }}
                </p>

                {{-- Meta --}}
                <div class="flex flex-wrap items-center gap-3 text-sm text-[#536961]">
                    <span class="inline-flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5" />
                        </svg>
                        Tutup: {{ $jobPosting->deadline->timezone('Asia/Jakarta')->isoFormat('D MMM YYYY') }}
                    </span>
                    <span class="text-[#E3DDD1]">|</span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        {{ $jobPosting->quota }} posisi
                    </span>
                </div>
            </div>

            {{-- Status Badge or CTA --}}
            <div class="flex-shrink-0">
                @if ($hasApplied)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-[#CCF4D3] text-[#00682A]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Sudah Dilamar
                    </span>
                @else
                    <a href="#form-apply"
                       class="inline-block bg-[#F09A53] text-white rounded-xl px-5 py-2.5 font-bold text-sm hover:bg-[#e08843] transition-colors shadow-sm">
                        Lamar Sekarang
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="bg-[#FFE2DD] border border-[#D33B36]/30 rounded-xl p-4 mb-6">
            <div class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#D33B36] flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                <ul class="text-sm text-[#B32322] space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Two Column Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Column: Content --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- About the Job --}}
            <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#0E271F] mb-4" style="font-family: 'Bricolage Grotesque', sans-serif;">
                    Tentang Pekerjaan
                </h2>
                <div class="text-sm text-[#536961] leading-relaxed whitespace-pre-wrap">{{ $jobPosting->description }}</div>
            </div>

            {{-- Requirements --}}
            @if ($jobPosting->requirements)
                <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-[#0E271F] mb-4" style="font-family: 'Bricolage Grotesque', sans-serif;">
                        Persyaratan
                    </h2>
                    <div class="text-sm text-[#536961] leading-relaxed whitespace-pre-wrap">{{ $jobPosting->requirements }}</div>
                </div>
            @endif
        </div>

        {{-- Right Column: Sidebar --}}
        <div class="lg:col-span-1 space-y-5">

            {{-- Summary Card --}}
            <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] p-6 shadow-sm">
                <h3 class="text-sm font-bold text-[#0E271F] mb-4 uppercase tracking-wide">Ringkasan</h3>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-xs text-[#536961] uppercase tracking-wide font-semibold mb-0.5">Deadline</dt>
                        <dd class="text-[#0E271F] font-medium">{{ $jobPosting->deadline->timezone('Asia/Jakarta')->isoFormat('D MMMM YYYY') }}</dd>
                    </div>
                    <div class="border-t border-[#E3DDD1] pt-3">
                        <dt class="text-xs text-[#536961] uppercase tracking-wide font-semibold mb-0.5">Kuota</dt>
                        <dd class="text-[#0E271F] font-medium">{{ $jobPosting->quota }} posisi</dd>
                    </div>
                    <div class="border-t border-[#E3DDD1] pt-3">
                        <dt class="text-xs text-[#536961] uppercase tracking-wide font-semibold mb-0.5">Status Lowongan</dt>
                        <dd>
                            @if (now()->gt($jobPosting->deadline))
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FFE2DD] text-[#B32322]">Sudah Tutup</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#CCF4D3] text-[#00682A]">Masih Buka</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Apply Form or Already Applied --}}
            @if ($hasApplied)
                <div class="bg-[#D2F0E4] rounded-2xl border border-[#06614B]/20 p-6 shadow-sm text-center">
                    <div class="w-10 h-10 bg-[#06614B] text-white rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-[#06614B]">Anda sudah melamar</p>
                    <p class="text-xs text-[#536961] mt-1">Pantau status lamaran Anda di halaman "Lamaran Saya".</p>
                </div>
            @else
                <div id="form-apply" class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] p-6 shadow-sm">
                    <h3 class="text-sm font-bold text-[#0E271F] mb-1 uppercase tracking-wide">Kirim Lamaran</h3>
                    <p class="text-xs text-[#536961] mb-4">Upload CV dalam format PDF. Maks. 2 MB.</p>
                    <form action="{{ route('applications.store', $jobPosting) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label for="cv" class="block text-xs font-semibold text-[#0E271F] mb-1.5 uppercase tracking-wide">File CV <span class="text-[#D33B36]">*</span></label>
                            <label for="cv"
                                   class="flex flex-col items-center justify-center w-full h-28 rounded-xl border-2 border-dashed border-[#E3DDD1] bg-white hover:border-[#06614B] hover:bg-[#D2F0E4]/30 transition-colors cursor-pointer text-center px-3 group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#536961] group-hover:text-[#06614B] mb-1.5 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                </svg>
                                <span class="text-xs text-[#536961] group-hover:text-[#06614B] transition-colors">Klik untuk memilih file PDF</span>
                                <span class="text-xs text-[#536961]/60 mt-0.5">Maks. 2 MB</span>
                            </label>
                            <input id="cv" name="cv" type="file" accept=".pdf" required class="sr-only">
                            <p id="cv-filename" class="text-xs text-[#536961] mt-1 hidden"></p>
                        </div>
                        <button type="submit"
                                class="w-full bg-[#F09A53] text-white rounded-xl px-4 py-2.5 font-bold text-sm hover:bg-[#e08843] transition-colors shadow-sm">
                            Kirim Lamaran
                        </button>
                    </form>

                    <script>
                        document.getElementById('cv').addEventListener('change', function () {
                            const label = document.getElementById('cv-filename');
                            if (this.files.length > 0) {
                                label.textContent = '📄 ' + this.files[0].name;
                                label.classList.remove('hidden');
                            }
                        });
                    </script>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

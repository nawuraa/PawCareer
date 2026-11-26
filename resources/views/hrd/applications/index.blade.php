@extends('layouts.app')
@section('title', 'Kandidat — HRD')

@section('content')
<div class="min-h-screen bg-[#FAF6EE] px-4 py-8 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-widest text-[#06614B] mb-1">REKRUTMEN · HRD</p>
        <h1 class="font-['Bricolage_Grotesque'] text-3xl font-bold text-[#0E271F]">Daftar Kandidat</h1>
        <p class="mt-1 text-sm text-[#536961]">Tinjau lamaran, lakukan penyaringan, jadwalkan wawancara, dan catat keputusan akhir.</p>
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
    @if ($errors->any())
        <div class="mb-4 rounded-xl border border-[#D33B36]/30 bg-[#FFE2DD] px-4 py-3 text-sm text-[#B32322]">
            <ul class="list-disc pl-4 space-y-1">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Table Card --}}
    <div class="rounded-2xl border border-[#E3DDD1] bg-[#FFFDFA] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-[#E3DDD1] bg-[#FAF6EE]">
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#536961]">Pelamar</th>
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#536961]">Lowongan & Divisi</th>
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#536961]">Dikirim</th>
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#536961]">Status</th>
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#536961]">CV</th>
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#536961]">Penyaringan</th>
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#536961]">Wawancara</th>
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#536961]">Keputusan Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E3DDD1]">
                    @forelse ($applications as $application)
                        @php
                            $initials = collect(explode(' ', $application->applicant->name))->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('');
                        @endphp
                        <tr class="hover:bg-[#FAF6EE] transition-colors">

                            {{-- Pelamar --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#D2F0E4] text-sm font-bold text-[#06614B]">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-[#0E271F]">{{ $application->applicant->name }}</p>
                                        <p class="text-xs text-[#536961]">{{ $application->applicant->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Lowongan & Divisi --}}
                            <td class="px-5 py-4">
                                <p class="font-semibold text-[#0E271F]">{{ $application->jobPosting->title }}</p>
                                <p class="text-xs text-[#536961]">{{ $application->jobPosting->requisition->position->division->name ?? '—' }}</p>
                            </td>

                            {{-- Dikirim --}}
                            <td class="px-5 py-4 whitespace-nowrap text-[#536961]">
                                {{ $application->submitted_at->timezone('Asia/Jakarta')->format('d M Y, H.i') }} WIB
                            </td>

                            {{-- Status badge --}}
                            <td class="px-5 py-4">
                                @php
                                    $statusMap = [
                                        'submitted'       => ['label' => 'Diajukan',         'text' => '#225984', 'bg' => '#DBEEFE'],
                                        'screening_passed'=> ['label' => 'Direview',          'text' => '#00655C', 'bg' => '#CBF1EB'],
                                        'interview'       => ['label' => 'Wawancara',         'text' => '#915200', 'bg' => '#FFECC9'],
                                        'accepted'        => ['label' => 'Diterima',          'text' => '#00682A', 'bg' => '#CCF4D3'],
                                        'screening_failed'=> ['label' => 'Ditolak Berkas',    'text' => '#B2354A', 'bg' => '#FFE2E4'],
                                        'failed'          => ['label' => 'Ditolak Berkas',    'text' => '#B2354A', 'bg' => '#FFE2E4'],
                                        'rejected'        => ['label' => 'Ditolak Final',     'text' => '#B32322', 'bg' => '#FFE2DD'],
                                        'cancelled'       => ['label' => 'Dibatalkan',        'text' => '#5B6662', 'bg' => '#E3EAE7'],
                                    ];
                                    $s = $statusMap[$application->status] ?? ['label' => ucfirst($application->status), 'text' => '#536961', 'bg' => '#E3DDD1'];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium"
                                      style="color:{{ $s['text'] }};background-color:{{ $s['bg'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full" style="background-color:{{ $s['text'] }}"></span>
                                    {{ $s['label'] }}
                                </span>
                            </td>

                            {{-- CV --}}
                            <td class="px-5 py-4">
                                <a href="{{ route('applications.cv', $application) }}"
                                   class="inline-flex items-center gap-1.5 rounded-xl border border-[#E3DDD1] bg-[#FAF6EE] px-3 py-1.5 text-xs font-medium text-[#06614B] hover:bg-[#D2F0E4] transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    Unduh CV
                                </a>
                            </td>

                            {{-- Penyaringan --}}
                            <td class="px-5 py-4">
                                @if ($application->status === 'submitted')
                                    <form action="{{ route('hrd.applications.screening.update', $application) }}" method="POST" class="space-y-2 min-w-[180px]">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" required
                                                class="w-full rounded-xl border border-[#E3DDD1] bg-white px-3 py-1.5 text-sm text-[#0E271F] focus:outline-none focus:ring-2 focus:ring-[#06614B]/30">
                                            <option value="screening_passed">Lolos Penyaringan</option>
                                            <option value="screening_failed">Tidak Lolos Penyaringan</option>
                                        </select>
                                        <textarea name="reason" maxlength="5000" required placeholder="Catatan keputusan…"
                                                  class="w-full rounded-xl border border-[#E3DDD1] bg-white px-3 py-1.5 text-sm text-[#0E271F] placeholder-[#536961] focus:outline-none focus:ring-2 focus:ring-[#06614B]/30 resize-none" rows="2">{{ old('reason') }}</textarea>
                                        <button type="submit"
                                                class="w-full rounded-xl bg-[#06614B] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#044f3c] transition-colors">
                                            Simpan
                                        </button>
                                    </form>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs text-[#536961]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#298646]" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Selesai
                                    </span>
                                @endif
                            </td>

                            {{-- Wawancara --}}
                            <td class="px-5 py-4">
                                @if (in_array($application->status, ['screening_passed', 'interview'], true))
                                    <div class="space-y-1.5 min-w-[200px]">
                                        @foreach ($application->interviews as $interview)
                                            <div class="rounded-xl border border-[#E3DDD1] bg-[#FAF6EE] px-3 py-2 text-xs text-[#536961]">
                                                <p class="font-medium text-[#0E271F]">{{ $interview->interviewer->name }}</p>
                                                <p>{{ $interview->scheduled_at->timezone('Asia/Jakarta')->format('d M Y, H.i') }} WIB</p>
                                                <p class="text-[#06614B]">{{ ucfirst($interview->status) }}</p>
                                            </div>
                                        @endforeach

                                        <details class="group">
                                            <summary class="cursor-pointer list-none">
                                                <span class="inline-flex items-center gap-1 rounded-xl border border-[#E3DDD1] px-3 py-1.5 text-xs font-medium text-[#06614B] hover:bg-[#D2F0E4] transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                                                    Jadwalkan Wawancara
                                                </span>
                                            </summary>
                                            <form action="{{ route('hrd.applications.interviews.store', $application) }}" method="POST" class="mt-2 space-y-2">
                                                @csrf
                                                <select name="interviewer_id" required
                                                        class="w-full rounded-xl border border-[#E3DDD1] bg-white px-3 py-1.5 text-sm text-[#0E271F] focus:outline-none focus:ring-2 focus:ring-[#06614B]/30">
                                                    <option value="">Pilih Pewawancara</option>
                                                    @foreach ($interviewers as $hrdUser)
                                                        <option value="{{ $hrdUser->id }}">{{ $hrdUser->name }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="datetime-local" name="scheduled_at"
                                                       min="{{ now()->timezone('Asia/Jakarta')->format('Y-m-d\TH:i') }}" required
                                                       class="w-full rounded-xl border border-[#E3DDD1] bg-white px-3 py-1.5 text-sm text-[#0E271F] focus:outline-none focus:ring-2 focus:ring-[#06614B]/30">
                                                <button type="submit"
                                                        class="w-full rounded-xl bg-[#06614B] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#044f3c] transition-colors">
                                                    Simpan Jadwal
                                                </button>
                                            </form>
                                        </details>
                                    </div>
                                @else
                                    <span class="text-xs text-[#536961]">Tersedia setelah lolos penyaringan</span>
                                @endif
                            </td>

                            {{-- Keputusan Akhir --}}
                            <td class="px-5 py-4">
                                @if ($application->hiringDecision)
                                    @php
                                        $res = $application->hiringDecision->result;
                                        $rMap = [
                                            'accepted' => ['label'=>'Diterima','text'=>'#00682A','bg'=>'#CCF4D3'],
                                            'rejected' => ['label'=>'Ditolak Final','text'=>'#B32322','bg'=>'#FFE2DD'],
                                        ];
                                        $r = $rMap[$res] ?? ['label'=>ucfirst($res),'text'=>'#536961','bg'=>'#E3DDD1'];
                                    @endphp
                                    <div class="space-y-1 min-w-[150px]">
                                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium"
                                              style="color:{{ $r['text'] }};background-color:{{ $r['bg'] }}">
                                            <span class="h-1.5 w-1.5 rounded-full" style="background-color:{{ $r['text'] }}"></span>
                                            {{ $r['label'] }}
                                        </span>
                                        @if ($application->hiringDecision->start_date)
                                            <p class="text-xs text-[#536961]">Mulai: {{ $application->hiringDecision->start_date->format('d M Y') }}</p>
                                        @endif
                                        @if ($application->hiringDecision->note)
                                            <p class="text-xs text-[#536961]">{{ $application->hiringDecision->note }}</p>
                                        @endif
                                    </div>
                                @elseif ($application->status === 'interview')
                                    @php
                                        $hasCompletedInterview = $application->interviews->contains('status', 'completed');
                                        $hasPendingInterview   = $application->interviews->contains('status', 'scheduled');
                                    @endphp
                                    @if ($hasCompletedInterview && ! $hasPendingInterview)
                                        <details class="group">
                                            <summary class="cursor-pointer list-none">
                                                <span class="inline-flex items-center gap-1 rounded-xl border border-[#E3DDD1] px-3 py-1.5 text-xs font-medium text-[#06614B] hover:bg-[#D2F0E4] transition-colors">
                                                    Catat Keputusan Akhir
                                                </span>
                                            </summary>
                                            <form action="{{ route('hrd.applications.hiring-decision.store', $application) }}" method="POST" class="mt-2 space-y-2 min-w-[180px]">
                                                @csrf
                                                <select name="result" required
                                                        class="w-full rounded-xl border border-[#E3DDD1] bg-white px-3 py-1.5 text-sm text-[#0E271F] focus:outline-none focus:ring-2 focus:ring-[#06614B]/30">
                                                    <option value="accepted">Terima</option>
                                                    <option value="rejected">Tolak</option>
                                                </select>
                                                <input type="date" name="start_date"
                                                       min="{{ now()->toDateString() }}"
                                                       placeholder="Tanggal mulai (jika diterima)"
                                                       class="w-full rounded-xl border border-[#E3DDD1] bg-white px-3 py-1.5 text-sm text-[#0E271F] focus:outline-none focus:ring-2 focus:ring-[#06614B]/30">
                                                <textarea name="note" maxlength="10000" required placeholder="Catatan keputusan…"
                                                          class="w-full rounded-xl border border-[#E3DDD1] bg-white px-3 py-1.5 text-sm text-[#0E271F] placeholder-[#536961] focus:outline-none focus:ring-2 focus:ring-[#06614B]/30 resize-none" rows="2"></textarea>
                                                <button type="submit"
                                                        class="w-full rounded-xl bg-[#06614B] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#044f3c] transition-colors">
                                                    Simpan Keputusan
                                                </button>
                                            </form>
                                        </details>
                                    @else
                                        <span class="text-xs text-[#536961]">Selesaikan semua wawancara terlebih dahulu</span>
                                    @endif
                                @else
                                    <span class="text-xs text-[#536961]">Tersedia setelah wawancara</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-[#536961]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#E3DDD1]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="font-semibold text-[#0E271F]">Belum ada lamaran yang diterima.</p>
                                    <p class="text-sm">Lamaran dari kandidat akan muncul di sini.</p>
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

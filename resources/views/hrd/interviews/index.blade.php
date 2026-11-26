@extends('layouts.app')
@section('title', 'Wawancara — HRD')

@section('content')
<div class="min-h-screen bg-[#FAF6EE] px-4 py-8 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-widest text-[#06614B] mb-1">REKRUTMEN · HRD</p>
        <h1 class="font-['Bricolage_Grotesque'] text-3xl font-bold text-[#0E271F]">Agenda Wawancara</h1>
        <p class="mt-1 text-sm text-[#536961]">Daftar wawancara yang dijadwalkan dan evaluasi hasil wawancara.</p>
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
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#536961]">Lowongan</th>
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#536961]">Jadwal (WIB)</th>
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#536961]">Status</th>
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#536961]">Evaluasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E3DDD1]">
                    @forelse ($interviews as $interview)
                        @php
                            $applicant = $interview->application->applicant;
                            $initials  = collect(explode(' ', $applicant->name))->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('');

                            $scheduledAt = $interview->scheduled_at->timezone('Asia/Jakarta');
                            $days = ['Sun'=>'Min','Mon'=>'Sen','Tue'=>'Sel','Wed'=>'Rab','Thu'=>'Kam','Fri'=>'Jum','Sat'=>'Sab'];
                            $dayEn = $scheduledAt->format('D');
                            $dayId = $days[$dayEn] ?? $dayEn;
                            $formattedSchedule = $dayId . ', ' . $scheduledAt->format('j M Y') . ' · ' . $scheduledAt->format('H.i') . ' WIB';

                            $statusMap = [
                                'scheduled'  => ['label' => 'Terjadwal', 'text' => '#915200', 'bg' => '#FFECC9'],
                                'completed'  => ['label' => 'Selesai',   'text' => '#00682A', 'bg' => '#CCF4D3'],
                                'cancelled'  => ['label' => 'Dibatalkan','text' => '#5B6662', 'bg' => '#E3EAE7'],
                            ];
                            $st = $statusMap[$interview->status] ?? ['label' => ucfirst($interview->status), 'text' => '#536961', 'bg' => '#E3DDD1'];
                        @endphp
                        <tr class="hover:bg-[#FAF6EE] transition-colors">

                            {{-- Pelamar --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#D2F0E4] text-sm font-bold text-[#06614B]">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-[#0E271F]">{{ $applicant->name }}</p>
                                        <p class="text-xs text-[#536961]">{{ $applicant->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Lowongan --}}
                            <td class="px-5 py-4">
                                <p class="font-medium text-[#0E271F]">{{ $interview->application->jobPosting->title }}</p>
                            </td>

                            {{-- Jadwal --}}
                            <td class="px-5 py-4 whitespace-nowrap text-[#536961]">
                                {{ $formattedSchedule }}
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium"
                                      style="color:{{ $st['text'] }};background-color:{{ $st['bg'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full" style="background-color:{{ $st['text'] }}"></span>
                                    {{ $st['label'] }}
                                </span>
                            </td>

                            {{-- Evaluasi --}}
                            <td class="px-5 py-4">
                                @if ($interview->status === 'scheduled')
                                    <form action="{{ route('hrd.interviews.evaluation.update', $interview) }}" method="POST" class="space-y-2 min-w-[200px]">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="score" id="score{{ $interview->id }}"
                                               min="0" max="100" required placeholder="Skor (0-100)"
                                               class="w-full rounded-xl border border-[#E3DDD1] bg-white px-3 py-1.5 text-sm text-[#0E271F] placeholder-[#536961] focus:outline-none focus:ring-2 focus:ring-[#06614B]/30">
                                        <textarea name="evaluation_notes" id="notes{{ $interview->id }}"
                                                  maxlength="10000" required placeholder="Catatan evaluasi…"
                                                  class="w-full rounded-xl border border-[#E3DDD1] bg-white px-3 py-1.5 text-sm text-[#0E271F] placeholder-[#536961] focus:outline-none focus:ring-2 focus:ring-[#06614B]/30 resize-none" rows="3"></textarea>
                                        <button type="submit"
                                                class="w-full rounded-xl bg-[#06614B] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#044f3c] transition-colors">
                                            Selesai & Simpan
                                        </button>
                                    </form>
                                @else
                                    <div class="space-y-1.5">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-semibold text-[#536961]">Skor:</span>
                                            <span class="inline-flex items-center rounded-full bg-[#D2F0E4] px-2.5 py-0.5 text-xs font-bold text-[#06614B]">
                                                {{ $interview->score ?? '—' }}
                                            </span>
                                        </div>
                                        @if ($interview->evaluation_notes)
                                            <p class="text-xs text-[#536961] leading-relaxed">{{ $interview->evaluation_notes }}</p>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-[#536961]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#E3DDD1]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="font-semibold text-[#0E271F]">Belum ada wawancara yang ditugaskan.</p>
                                    <p class="text-sm">Jadwal wawancara yang ditetapkan oleh HRD akan muncul di sini.</p>
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

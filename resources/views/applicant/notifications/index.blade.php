@extends('layouts.app')

@section('title', 'Notifikasi — PawCareer')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-[#06614B] mb-1">PORTAL PELAMAR</p>
            <h1 class="font-display text-3xl font-bold text-[#0E271F]">Notifikasi</h1>
            <p class="mt-1 text-sm text-[#536961]">Pembaruan status lamaran, jadwal wawancara, dan keputusan HRD.</p>
        </div>

        @if ($notifications->whereNull('read_at')->count() > 0)
            <form action="{{ route('applicant.notifications.read_all') }}" method="POST" class="m-0">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#D2F0E4] hover:bg-[#B8E8D4] text-[#06614B] text-xs font-semibold transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    <span>Tandai semua dibaca</span>
                </button>
            </form>
        @endif
    </div>

    {{-- Flash message --}}
    @if (session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-xl border border-[#298646]/30 bg-[#CCF4D3] px-4 py-3 text-sm font-medium text-[#00682A]" role="status">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Notification List --}}
    <div class="space-y-3">
        @forelse ($notifications as $notification)
            @php
                $isUnread = is_null($notification->read_at);
            @endphp
            <div class="rounded-2xl border {{ $isUnread ? 'border-[#F09A53]/60 bg-[#FFFDFA]' : 'border-[#E3DDD1] bg-[#FFFDFA]/80' }} p-5 shadow-soft flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                <div class="flex items-start gap-3.5 min-w-0">
                    <div class="mt-1.5 w-2.5 h-2.5 rounded-full shrink-0 {{ $isUnread ? 'bg-[#F09A53]' : 'bg-[#E3DDD1]' }}"></div>
                    <div class="min-w-0">
                        <p class="text-sm {{ $isUnread ? 'font-bold text-[#0E271F]' : 'font-medium text-[#0E271F]' }}">
                            {{ $notification->data['message'] ?? 'Pembaruan lamaran' }}
                        </p>

                        @if (!empty($notification->data['reason']))
                            <p class="text-xs text-[#536961] mt-1.5 bg-[#FAF6EE] border border-[#E3DDD1] rounded-lg px-3 py-2">
                                <span class="font-semibold text-[#0E271F]">Catatan:</span> {{ $notification->data['reason'] }}
                            </p>
                        @endif

                        @if (!empty($notification->data['note']))
                            <p class="text-xs text-[#536961] mt-1.5 bg-[#FAF6EE] border border-[#E3DDD1] rounded-lg px-3 py-2">
                                <span class="font-semibold text-[#0E271F]">Catatan:</span> {{ $notification->data['note'] }}
                            </p>
                        @endif

                        @if (!empty($notification->data['start_date']))
                            <p class="text-xs text-[#00682A] font-semibold mt-1.5">
                                Tanggal mulai kerja: {{ $notification->data['start_date'] }}
                            </p>
                        @endif

                        <p class="text-[11px] text-[#536961] mt-2">
                            {{ \Carbon\Carbon::parse($notification->created_at)->timezone('Asia/Jakarta')->translatedFormat('j M Y · H.i') }} WIB
                        </p>
                    </div>
                </div>

                @if ($isUnread)
                    <form action="{{ route('applicant.notifications.read', $notification) }}" method="POST" class="shrink-0 m-0">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="px-3.5 py-1.5 rounded-xl border border-[#E3DDD1] bg-[#FAF6EE] hover:bg-[#D2F0E4] text-xs font-semibold text-[#06614B] transition-colors cursor-pointer">
                            Tandai dibaca
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="bg-[#FFFDFA] rounded-3xl border border-[#E3DDD1] p-12 text-center shadow-soft">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-[#F2ECE1] text-[#536961] flex items-center justify-center mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>
                </div>
                <p class="font-display text-base font-bold text-[#0E271F]">Belum ada notifikasi</p>
                <p class="text-xs text-[#536961] mt-1">Setiap pembaruan mengenai lamaran Anda akan muncul di sini.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection

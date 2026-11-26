@extends('layouts.app')

@section('title', 'Akun Pengguna — HRD')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-widest text-[#06614B] mb-1">PENGELOLAAN · HRD</p>
        <h1 class="font-display text-3xl font-bold text-[#0E271F]">Akun Pengguna</h1>
        <p class="mt-1 text-sm text-[#536961]">Daftar akun terdaftar pada sistem PawCareer beserta peran dan divisinya.</p>
    </div>

    <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-[#E3DDD1] bg-[#FAF6EE] text-xs font-semibold uppercase tracking-wider text-[#536961]">
                        <th class="px-6 py-3.5">Pengguna</th>
                        <th class="px-6 py-3.5">Peran</th>
                        <th class="px-6 py-3.5">Divisi</th>
                        <th class="px-6 py-3.5">Terdaftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E3DDD1]">
                    @forelse($users as $u)
                        <tr class="hover:bg-[#FAF6EE] transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-[#0E271F]">{{ $u->name }}</p>
                                <p class="text-xs text-[#536961]">{{ $u->email }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $roleBadge = match($u->role) {
                                        'hrd' => ['label' => 'HRD', 'bg' => '#D2F0E4', 'text' => '#06614B'],
                                        'division_manager' => ['label' => 'Manajer Divisi', 'bg' => '#FFEBD2', 'text' => '#915200'],
                                        default => ['label' => 'Pelamar', 'bg' => '#DBEEFE', 'text' => '#225984'],
                                    };
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                      style="background:{{ $roleBadge['bg'] }}; color:{{ $roleBadge['text'] }};">
                                    {{ $roleBadge['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-[#536961]">{{ $u->division->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-xs text-[#536961]">{{ $u->created_at?->translatedFormat('j M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-sm text-[#536961]">Belum ada akun pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

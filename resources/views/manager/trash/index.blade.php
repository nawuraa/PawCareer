<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawCareer — Tempat Sampah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #faf7f2; }
        .sidebar { background-color: #0c251e; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
</head>
<body class="text-gray-800 flex h-screen overflow-hidden antialiased">

    <!-- SIDEBAR -->
    <aside class="sidebar relative z-50 w-[260px] h-full flex flex-col text-white flex-shrink-0 border-r border-white/10">
        <!-- Logo & Portal -->
        <div class="p-6 pb-4">
            <div class="flex items-center gap-3 mb-1">
                <div class="w-9 h-9 rounded-xl bg-[#f18c54] flex items-center justify-center text-white shadow-sm">
                    <i class="ph-fill ph-paw-print text-xl"></i>
                </div>
                <div>
                    <h1 class="font-bold text-base tracking-tight leading-tight">PawCareer</h1>
                    <p class="text-[11px] text-gray-400">Klinik Hewan Sahabat Satwa</p>
                </div>
            </div>
            <div class="mt-4 pt-3.5 border-t border-white/10">
                <p class="text-[11px] text-gray-400">
                    Portal <span class="text-white font-semibold">Manajer Divisi</span>
                    @if(auth()->user()->division)
                        · {{ auth()->user()->division->name }}
                    @endif
                </p>
            </div>
        </div>

        <!-- Menu Navigasi -->
        <nav class="flex-1 px-3.5 py-2 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard.manager') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-colors text-sm font-medium">
                <i class="ph ph-squares-four text-lg"></i>
                <span>Dasbor</span>
            </a>

            <a href="{{ route('requisitions.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-colors text-sm font-medium">
                <i class="ph ph-file-text text-lg"></i>
                <span>Pengajuan Kebutuhan</span>
            </a>

            <a href="{{ route('employees.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-colors text-sm font-medium">
                <i class="ph ph-user-plus text-lg"></i>
                <span>Karyawan Baru</span>
            </a>

            <!-- Menu Aktif: Tempat Sampah -->
            <a href="{{ route('trash.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 bg-[#fdfbf6] text-[#0c251e] rounded-xl font-semibold text-sm shadow-xs">
                <i class="ph-fill ph-trash text-lg"></i>
                <span>Tempat Sampah</span>
            </a>
        </nav>

        <!-- Profil Bawah -->
        <div class="p-4 border-t border-white/10 bg-black/15 flex items-center justify-between gap-2">
            <div class="flex items-center gap-3 min-w-0">
                @php
                    $initials = collect(explode(' ', auth()->user()->name))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                @endphp
                <div class="w-9 h-9 rounded-full bg-[#f18c54] flex items-center justify-center font-bold text-xs text-white shrink-0 uppercase">
                    {{ $initials }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-gray-400 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="m-0 shrink-0">
                @csrf
                <button type="submit"
                        title="Keluar"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition-colors cursor-pointer">
                    <i class="ph ph-sign-out text-lg"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 h-full overflow-y-auto relative">
        <div class="px-8 py-9 max-w-5xl mx-auto">

            <!-- Flash message -->
            @if(session('success'))
                <div class="mb-6 px-4 py-3 rounded-xl bg-green-50 text-green-700 border border-green-200 flex items-center gap-2 text-sm">
                    <i class="ph-fill ph-check-circle text-lg"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Breadcrumb & Header Halaman -->
            <div class="mb-8">
                <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                    <span>Portal Manajer Divisi</span>
                    <span>/</span>
                    <span class="font-medium text-gray-800">Tempat Sampah</span>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                    <div>
                        <span class="inline-block text-[11px] font-bold text-[#3f664c] tracking-wider uppercase mb-1">
                            Soft Delete · Retensi 30 Hari
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Tempat sampah</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Pengajuan milik Anda yang dihapus dapat dipulihkan kembali sebelum batas waktu 30 hari berakhir.
                        </p>
                    </div>

                    <div class="text-xs text-gray-500 bg-white border border-gray-200 px-3.5 py-2 rounded-lg shrink-0">
                        Total item tersimpan: <strong class="text-gray-900">{{ $trashedRequisitions->count() }}</strong>
                    </div>
                </div>
            </div>

            <!-- List Data yang Dihapus -->
            @forelse($trashedRequisitions as $req)
                <div class="bg-white rounded-2xl border border-gray-200/90 p-5 mb-4 shadow-2xs hover:border-gray-300 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-5">

                    <!-- Kiri: Badge & Info Data -->
                    <div class="flex items-start sm:items-center gap-4">
                        <span class="bg-[#f3f4f1] text-[#0c251e] border border-gray-200 text-[10px] font-bold px-3 py-1.5 rounded-lg tracking-wider shrink-0">
                            PENGAJUAN
                        </span>
                        <div>
                            <h3 class="font-semibold text-gray-900 text-[15px]">
                                {{ $req->position->name ?? 'Posisi' }} · {{ $req->quantity }} orang
                            </h3>
                            <p class="text-[13px] text-gray-500 mt-0.5">
                                Alasan: {{ \Illuminate\Support\Str::limit($req->reason, 60) }} · Dihapus pada {{ $req->deleted_at->translatedFormat('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <!-- Kanan: Progress Bar & Tombol -->
                    <div class="flex items-center justify-between sm:justify-end gap-6 pt-3 sm:pt-0 border-t sm:border-t-0 border-gray-100">
                        <!-- Countdown bar -->
                        <div class="w-36">
                            <div class="flex items-center justify-between text-[11px] mb-1.5">
                                <span class="text-gray-400">Sisa waktu</span>
                                <span class="text-[#0c251e] font-semibold">{{ $req->days_left }} hari</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-[#0c251e] h-1.5 rounded-full" style="width: {{ $req->retention_pct }}%"></div>
                            </div>
                        </div>

                        <!-- Tombol Pulihkan -->
                        <form action="{{ route('trash.requisitions.restore', $req->id) }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('Pulihkan pengajuan {{ $req->position->name ?? 'ini' }} ke daftar aktif?')"
                                    class="inline-flex items-center gap-1.5 bg-[#e6f4ed] hover:bg-[#d2ecdf] text-[#0c251e] text-xs font-semibold px-4 py-2.5 rounded-xl transition-colors border border-[#c6e5d5] cursor-pointer shrink-0">
                                <i class="ph ph-arrow-counter-clockwise text-sm font-bold"></i>
                                <span>Pulihkan</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-gray-200/90 p-10 text-center shadow-2xs">
                    <i class="ph ph-trash text-gray-300 text-5xl mb-3 block"></i>
                    <p class="font-semibold text-gray-700">Tempat sampah kosong</p>
                    <p class="text-sm text-gray-400 mt-1">Tidak ada pengajuan yang telah dihapus.</p>
                </div>
            @endforelse

            <!-- Banner Info Aturan -->
            <div class="bg-[#edf6f2] border border-[#d3e8de] rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 mt-6">
                <div class="flex items-start gap-3">
                    <i class="ph-fill ph-info text-[#0c251e] text-lg shrink-0 mt-0.5"></i>
                    <p class="text-xs text-[#163a28] leading-relaxed">
                        Posisi tidak dapat dipulihkan bila divisinya juga terhapus. Nama data di tempat sampah tetap terpakai sampai dipulihkan atau dibuang permanen. Pembuangan otomatis harian setelah 30 hari berlaku.
                    </p>
                </div>
            </div>

        </div>
    </main>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=device-width, initial-scale=1.0">
    <title>PawCareer - Karyawan Baru</title>
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
<body class="text-gray-800 flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="sidebar w-[260px] h-full flex flex-col text-white flex-shrink-0">
        <!-- Logo & Portal -->
        <div class="p-6 pb-2">
            <div class="flex items-center gap-3 mb-1">
                <div class="w-8 h-8 rounded-full bg-[#f18c54] flex items-center justify-center text-white">
                    <i class="ph-fill ph-paw-print text-xl"></i>
                </div>
                <div>
                    <h1 class="font-bold text-lg leading-tight">PawCareer</h1>
                    <p class="text-[10px] text-gray-400">Klinik Hewan Sahabat Satwa</p>
                </div>
            </div>
            <div class="mt-4 pb-4 border-b border-white/10">
                <p class="text-[11px] text-gray-400">Portal <span class="text-white font-semibold">Manajer Divisi</span></p>
            </div>
        </div>

        <!-- Menu Navigasi -->
        <nav class="flex-1 px-4 py-2 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard.manager') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-400 hover:text-white transition-colors text-sm">
                <i class="ph ph-squares-four text-lg"></i> Dasbor
            </a>
            <a href="{{ route('requisitions.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-400 hover:text-white transition-colors text-sm">
                <i class="ph ph-file-text text-lg"></i> Pengajuan Kebutuhan
            </a>
            <!-- Menu Aktif: Karyawan Baru -->
            <a href="{{ route('employees.index') }}" class="flex items-center gap-3 px-4 py-2.5 bg-[#fdfbf6] text-[#0c251e] rounded-full font-semibold text-sm">
                <i class="ph-fill ph-user-plus text-lg"></i> Karyawan Baru
            </a>
            <a href="{{ route('trash.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-400 hover:text-white transition-colors text-sm">
                <i class="ph ph-trash text-lg"></i> Tempat Sampah
            </a>
        </nav>

        <!-- Profil Bawah -->
        <div class="p-4 border-t border-white/10 flex items-center justify-between">
            <div class="flex items-center gap-3">
                @php
                    $initials = collect(explode(' ', auth()->user()->name))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                @endphp
                <div class="w-9 h-9 rounded-full bg-[#f18c54] flex items-center justify-center font-bold text-sm text-white uppercase">{{ $initials }}</div>
                <div>
                    <p class="text-sm font-semibold leading-tight truncate w-32">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-gray-400 truncate w-32">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-red-400"><i class="ph ph-sign-out text-xl"></i></button>
            </form>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 h-full overflow-y-auto relative">
        <div class="p-8 max-w-7xl mx-auto">
            
            <div class="flex justify-between items-center mb-8"></div>

            <!-- Header Halaman -->
            <div class="mb-8">
                <!-- Fallback ke "DIVISI ANDA" jika relasi division belum di-load di auth -->
                <p class="text-[11px] font-bold text-[#4a6e55] tracking-wider mb-1 uppercase">{{ $newEmployees->first()->jobPosting->requisition->position->division->name ?? 'DIVISI ANDA' }}</p>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Karyawan baru</h2>
                <p class="text-sm text-gray-500">Kandidat yang diterima di divisi Anda. Data pribadi hanya terlihat untuk yang diterima.</p>
            </div>

            <!-- Kartu Data Karyawan -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                
                @forelse($newEmployees as $emp)
                    @php
                        $empInitials = collect(explode(' ', $emp->user->name))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                        $divisionName = $emp->jobPosting->requisition->position->division->name ?? 'Divisi Anda';
                    @endphp
                    <!-- Kartu Karyawan -->
                    <div class="bg-white rounded-[20px] border border-gray-200 p-6 shadow-sm">
                        
                        <!-- Header Kartu -->
                        <div class="flex items-center gap-4 mb-5">
                            <div class="w-12 h-12 rounded-full bg-[#d9f2e6] text-[#0c251e] font-bold text-sm flex items-center justify-center uppercase">
                                {{ $empInitials }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-[15px]">{{ $emp->user->name }}</h3>
                                <p class="text-[13px] text-[#4a6e55] mt-0.5">{{ $emp->jobPosting->requisition->position->name ?? 'Posisi' }}</p>
                            </div>
                        </div>

                        <!-- Kotak Tanggal Mulai -->
                        <div class="bg-[#f4ebe1] rounded-xl p-4 mb-5">
                            <p class="text-xs text-gray-600 mb-1">Mulai bekerja sejak</p>
                            <p class="font-bold text-gray-900">{{ $emp->updated_at->translatedFormat('D, d F Y') }}</p>
                        </div>

                        <!-- Info Kontak -->
                        <div class="space-y-2.5">
                            <p class="text-[13px] text-gray-500">{{ $divisionName }}</p>
                            <div class="flex items-center gap-2 text-[13px] text-gray-500">
                                <i class="ph ph-envelope-simple text-gray-400 text-lg"></i>
                                {{ $emp->user->email }}
                            </div>
                            <div class="flex items-center gap-2 text-[13px] text-gray-500">
                                <i class="ph ph-phone text-gray-400 text-lg"></i>
                                {{ $emp->user->phone ?? 'Belum ada nomor telepon' }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-[20px] border border-gray-200 p-8 text-center shadow-sm">
                        <i class="ph ph-users text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500 font-medium">Belum ada karyawan baru di divisi ini.</p>
                    </div>
                @endforelse

            </div>

        </div>

    </main>
</body>
</html>
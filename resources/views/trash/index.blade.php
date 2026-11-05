<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=device-width, initial-scale=1.0">
    <title>PawCareer - Tempat Sampah</title>
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
                <p class="text-[11px] text-gray-400">Portal <span class="text-white font-semibold">Manajer Divisi</span> · Medis & Bedah</p>
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
            <a href="" class="flex items-center gap-3 px-4 py-2.5 text-gray-400 hover:text-white transition-colors text-sm">
                <i class="ph ph-user-plus text-lg"></i> Karyawan Baru
            </a>
            <!-- Menu Aktif: Tempat Sampah -->
            <a href="{{ route('trash.index') }}" class="flex items-center gap-3 px-4 py-2.5 bg-[#fdfbf6] text-[#0c251e] rounded-full font-semibold text-sm">
                <i class="ph-fill ph-trash text-lg"></i> Tempat Sampah
            </a>

            <div class="pt-6 pb-2 px-4">
                <p class="text-[10px] font-bold tracking-wider text-gray-500">LAINNYA</p>
            </div>
            <a href="#" class="flex items-center justify-between px-4 py-2.5 text-gray-400 hover:text-white transition-colors text-sm">
                <div class="flex items-center gap-3"><i class="ph ph-bell text-lg"></i> Notifikasi</div>
                <span class="bg-[#f18c54] text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">1</span>
            </a>
        </nav>

        <!-- Profil Bawah -->
        <div class="p-4 border-t border-white/10 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-[#f18c54] flex items-center justify-center font-bold text-sm text-white">BP</div>
                <div>
                    <p class="text-sm font-semibold leading-tight">drh. Bima Prasetyo</p>
                    <p class="text-[10px] text-gray-400">bima@sahabatsatwa.id</p>
                </div>
            </div>
            <button class="text-gray-400 hover:text-white"><i class="ph ph-sign-out text-xl"></i></button>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 h-full overflow-y-auto relative">
        <div class="p-8 max-w-7xl mx-auto">
            
            <div class="flex justify-between items-center mb-8"></div>

            <!-- Header Halaman -->
            <div class="mb-6">
                <p class="text-[11px] font-bold text-[#4a6e55] tracking-wider mb-1 uppercase">Soft Delete</p>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Tempat sampah</h2>
                <p class="text-sm text-gray-500">Pengajuan milik Anda yang dihapus dapat dipulihkan dalam 30 hari.</p>
            </div>

            <!-- List Data yang Dihapus -->
            <div class="bg-white rounded-2xl border border-gray-200 p-4 mb-6 shadow-sm flex items-center justify-between">
                
                <!-- Kiri: Badge & Info Data -->
                <div class="flex items-center gap-4">
                    <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-3 py-1.5 rounded-full tracking-wider">PENGAJUAN</span>
                    <div>
                        <h3 class="font-bold text-gray-900 text-[15px]">Asisten Bedah · 1 orang</h3>
                        <p class="text-[13px] text-gray-500 mt-0.5">Duplikat pengajuan. · dihapus 30 Okt 2026</p>
                    </div>
                </div>
                
                <!-- Kanan: Progress Bar & Tombol -->
                <div class="flex items-center gap-6">
                    <!-- Countdown bar -->
                    <div class="w-36">
                        <p class="text-[11px] text-[#0c251e] font-semibold text-right mb-1.5">24 hari tersisa</p>
                        <div class="w-full bg-gray-100 rounded-full h-1.5 flex justify-end">
                            <div class="bg-[#0c251e] h-1.5 rounded-full" style="width: 80%"></div>
                        </div>
                    </div>
                    
                    <!-- Tombol Pulihkan -->
                    <button class="flex items-center gap-1.5 bg-[#d9f2e6] hover:bg-[#c1e8d5] text-[#0c251e] text-xs font-bold px-4 py-2 rounded-full transition-colors border border-[#c1e8d5]">
                        <i class="ph ph-arrow-counter-clockwise text-sm font-bold"></i> Pulihkan
                    </button>
                </div>
            </div>

            <!-- Banner Info Aturan -->
            <div class="bg-[#e9f5f0] border border-[#d1e8df] rounded-xl p-4 flex items-center justify-between">
                <div class="flex items-start gap-3">
                    <i class="ph ph-info text-[#0c251e] text-lg mt-0.5"></i>
                    <p class="text-[11px] text-[#0c251e] leading-relaxed">
                        Posisi tidak dapat dipulihkan bila divisinya juga terhapus. Nama data di tempat sampah tetap terpakai sampai dipulihkan atau dibuang permanen. Pembuangan otomatis harian setelah 30 hari berlaku pada Rilis 2.
                    </p>
                </div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap ml-4">SDL · A-28</span>
            </div>

        </div>

    </main>
</body>
</html>
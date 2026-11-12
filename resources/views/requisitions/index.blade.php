<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=device-width, initial-scale=1.0">
    <title>PawCareer - Pengajuan Kebutuhan</title>
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
            <!-- Menu Aktif -->
            <a href="{{ route('requisitions.index') }}" class="flex items-center gap-3 px-4 py-2.5 bg-[#fdfbf6] text-[#0c251e] rounded-full font-semibold text-sm">
                <i class="ph-fill ph-file-text text-lg"></i> Pengajuan Kebutuhan
            </a>
            <a href="{{ route('employees.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-400 hover:text-white transition-colors text-sm">
                <i class="ph ph-user-plus text-lg"></i> Karyawan Baru
            </a>
            <a href="{{ route('trash.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-400 hover:text-white transition-colors text-sm">
                <i class="ph ph-trash text-lg"></i> Tempat Sampah
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

            <!-- Header Halaman -->
            <div class="flex justify-between items-end mb-6">
                <div>
                    <p class="text-[11px] font-bold text-[#0c251e] tracking-wider mb-1 uppercase">Manajer Divisi</p>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Pengajuan kebutuhan staf</h2>
                    <p class="text-sm text-gray-500">Ubah dan hapus hanya untuk pengajuan buatan Anda yang masih berstatus Diajukan.</p>
                </div>
                <a href="{{ route('requisitions.create') }}" class="bg-[#0c251e] hover:bg-[#153b30] text-white px-5 py-2.5 rounded-full font-semibold text-sm flex items-center gap-2 transition-colors shadow-sm">
                    <i class="ph ph-plus font-bold"></i> Ajukan kebutuhan
                </a>
            </div>

            <!-- Tab Filter -->
            <div class="flex items-center gap-1 bg-white border border-gray-200 rounded-full p-1 w-max mb-6">
                <button class="px-5 py-1.5 rounded-full bg-[#0c251e] text-white text-sm font-semibold">Semua di divisi</button>
                <button class="px-5 py-1.5 rounded-full text-gray-500 hover:text-gray-900 text-sm font-medium transition-colors">Milik saya</button>
                <button class="px-5 py-1.5 rounded-full text-gray-500 hover:text-gray-900 text-sm font-medium transition-colors">Manajer lain</button>
            </div>

            <!-- Tabel Data -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-white text-[11px] font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="py-4 px-6">Posisi</th>
                            <th class="py-4 px-6">Jumlah</th>
                            <th class="py-4 px-6">Diajukan Oleh</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6">Lowongan</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-800">
                        
                        <!-- Baris 1: Diajukan (Milik Sendiri) -->
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">
                                <p class="font-bold text-gray-900">Asisten Bedah</p>
                                <p class="text-xs text-gray-400 mt-0.5 truncate w-48">Jadwal operasi steril bertambah menjadi 4 hari per...</p>
                            </td>
                            <td class="py-4 px-6">1 orang</td>
                            <td class="py-4 px-6">
                                <p class="font-semibold text-[#0c251e]">Anda</p>
                                <p class="text-xs text-gray-400 mt-0.5">4 Nov 2026</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span> Diajukan
                                </span>
                            </td>
                            <td class="py-4 px-6 text-gray-400">—</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-3 text-gray-400 text-lg">
                                    <button class="hover:text-gray-900"><i class="ph ph-eye"></i></button>
                                    <button class="hover:text-gray-900"><i class="ph ph-pencil-simple"></i></button>
                                    <button class="text-red-400 hover:text-red-600"><i class="ph ph-trash"></i></button>
                                </div>
                            </td>
                        </tr>

                        <!-- Baris 2: Diajukan (Milik Sendiri) -->
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">
                                <p class="font-bold text-gray-900">Asisten Bedah</p>
                                <p class="text-xs text-gray-400 mt-0.5 truncate w-48">Duplikat pengajuan.</p>
                            </td>
                            <td class="py-4 px-6">1 orang</td>
                            <td class="py-4 px-6">
                                <p class="font-semibold text-[#0c251e]">Anda</p>
                                <p class="text-xs text-gray-400 mt-0.5">29 Okt 2026</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span> Diajukan
                                </span>
                            </td>
                            <td class="py-4 px-6 text-gray-400">—</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-3 text-gray-400 text-lg">
                                    <button class="hover:text-gray-900"><i class="ph ph-eye"></i></button>
                                    <button class="hover:text-gray-900"><i class="ph ph-pencil-simple"></i></button>
                                    <button class="text-red-400 hover:text-red-600"><i class="ph ph-trash"></i></button>
                                </div>
                            </td>
                        </tr>

                        <!-- Baris 3: Ditolak (Milik Sendiri) + Catatan HRD -->
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="pt-4 pb-2 px-6">
                                <p class="font-bold text-gray-900">Dokter Hewan Umum</p>
                                <p class="text-xs text-gray-400 mt-0.5 truncate w-48">Antisipasi lonjakan pasien akhir tahun.</p>
                            </td>
                            <td class="pt-4 pb-2 px-6">2 orang</td>
                            <td class="pt-4 pb-2 px-6">
                                <p class="font-semibold text-[#0c251e]">Anda</p>
                                <p class="text-xs text-gray-400 mt-0.5">24 Okt 2026</p>
                            </td>
                            <td class="pt-4 pb-2 px-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-500">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Ditolak
                                </span>
                            </td>
                            <td class="pt-4 pb-2 px-6 text-gray-400">—</td>
                            <td class="pt-4 pb-2 px-6">
                                <div class="flex items-center justify-center gap-4 text-gray-400 text-lg">
                                    <button class="hover:text-gray-900"><i class="ph ph-eye"></i></button>
                                    <i class="ph ph-lock-key opacity-50 cursor-not-allowed"></i>
                                </div>
                            </td>
                        </tr>
                        <!-- Sub-baris Catatan HRD -->
                        <tr>
                            <td colspan="6" class="px-6 pb-4 border-b border-gray-100">
                                <div class="bg-red-50 rounded-xl p-3 px-4 flex items-center justify-between">
                                    <p class="text-xs text-red-800"><span class="font-bold">Catatan HRD:</span> Formasi dokter sudah dibuka melalui pengajuan drh. Sari. Silakan ajukan ulang bila masih kurang setelah formasi terisi.</p>
                                    <button class="bg-white border border-red-200 text-red-600 text-xs font-semibold px-4 py-1.5 rounded-full hover:bg-red-100 transition-colors shadow-sm">Buat pengajuan baru</button>
                                </div>
                            </td>
                        </tr>

                        <!-- Baris 4: Disetujui (Manajer Lain) -->
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">
                                <p class="font-bold text-gray-900">Dokter Hewan Umum</p>
                                <p class="text-xs text-gray-400 mt-0.5 truncate w-48">drh. Andi cuti panjang mulai Desember; butuh dok...</p>
                            </td>
                            <td class="py-4 px-6">1 orang</td>
                            <td class="py-4 px-6">
                                <p class="font-medium text-gray-700">drh. Sari Kusuma</p>
                                <p class="text-xs text-gray-400 mt-0.5">18 Okt 2026</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Disetujui
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[9px] font-bold bg-green-100 text-green-700 uppercase">
                                        <span class="w-1 h-1 rounded-full bg-green-700"></span> Dibuka
                                    </span>
                                    <span class="text-[10px] text-gray-400">0/1</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1"><div class="bg-green-600 h-1 rounded-full" style="width: 0%"></div></div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-4 text-gray-400 text-lg">
                                    <button class="hover:text-gray-900"><i class="ph ph-eye"></i></button>
                                    <i class="ph ph-lock-key opacity-50 cursor-not-allowed"></i>
                                </div>
                            </td>
                        </tr>

                        <!-- Baris 5: Disetujui (Milik Sendiri) -->
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">
                                <p class="font-bold text-gray-900">Paramedis Veteriner</p>
                                <p class="text-xs text-gray-400 mt-0.5 truncate w-48">Volume pasien naik 35% sejak cabang dibuka sam...</p>
                            </td>
                            <td class="py-4 px-6">2 orang</td>
                            <td class="py-4 px-6">
                                <p class="font-semibold text-[#0c251e]">Anda</p>
                                <p class="text-xs text-gray-400 mt-0.5">10 Okt 2026</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Disetujui
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[9px] font-bold bg-green-100 text-green-700 uppercase">
                                        <span class="w-1 h-1 rounded-full bg-green-700"></span> Dibuka
                                    </span>
                                    <span class="text-[10px] text-gray-400">0/2</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1"><div class="bg-green-600 h-1 rounded-full" style="width: 0%"></div></div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-4 text-gray-400 text-lg">
                                    <button class="hover:text-gray-900"><i class="ph ph-eye"></i></button>
                                    <i class="ph ph-lock-key opacity-50 cursor-not-allowed"></i>
                                </div>
                            </td>
                        </tr>

                        <!-- Baris 6: Disetujui (Manajer Lain, Lowongan Ditutup) -->
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">
                                <p class="font-bold text-gray-900">Asisten Bedah</p>
                                <p class="text-xs text-gray-400 mt-0.5 truncate w-48">Asisten bedah senior pensiun.</p>
                            </td>
                            <td class="py-4 px-6">1 orang</td>
                            <td class="py-4 px-6">
                                <p class="font-medium text-gray-700">drh. Sari Kusuma</p>
                                <p class="text-xs text-gray-400 mt-0.5">5 Sep 2026</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Disetujui
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[9px] font-bold bg-gray-100 text-gray-600 uppercase">
                                        <span class="w-1 h-1 rounded-full bg-gray-500"></span> Ditutup
                                    </span>
                                    <span class="text-[10px] text-gray-400">1/1</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1"><div class="bg-[#0c251e] h-1 rounded-full" style="width: 100%"></div></div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-4 text-gray-400 text-lg">
                                    <button class="hover:text-gray-900"><i class="ph ph-eye"></i></button>
                                    <i class="ph ph-lock-key opacity-50 cursor-not-allowed"></i>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>

    </main>
</body>
</html>
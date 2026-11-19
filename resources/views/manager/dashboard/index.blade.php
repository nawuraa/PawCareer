<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=device-width, initial-scale=1.0">
    <title>PawCareer - Dasbor Manajer Divisi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #faf7f2; }
        .sidebar { background-color: #0c251e; }
        .btn-orange { background-color: #f18c54; transition: all 0.2s; }
        .btn-orange:hover { background-color: #e07a45; }
        ::-webkit-scrollbar { width: 6px; }
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
            <a href="{{ route('dashboard.manager') }}" class="flex items-center gap-3 px-4 py-2.5 bg-[#fdfbf6] text-[#0c251e] rounded-full font-semibold text-sm">
                <i class="ph-fill ph-squares-four text-lg"></i> Dasbor
            </a>
            <a href="{{ route('requisitions.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-400 hover:text-white transition-colors text-sm">
                <i class="ph ph-file-text text-lg"></i> Pengajuan Kebutuhan
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
                <!-- Inisial Dinamis (2 Huruf Pertama) -->
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
            
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <i class="ph ph-calendar"></i> {{ now()->translatedFormat('l, d F Y') }} · WIB
                </div>
            </div>

            <!-- Header Dasbor -->
            <div class="flex justify-between items-end mb-8">
                <div>
                    <p class="text-[11px] font-bold text-[#0c251e] tracking-wider mb-1 uppercase">Dasbor Manajer</p>
                    <!-- Mengambil kata pertama dari nama user -->
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat datang, {{ explode(' ', auth()->user()->name)[0] }}</h2>
                    <p class="text-sm text-gray-500">Ajukan kebutuhan staf dan pantau prosesnya sampai karyawan baru bergabung.</p>
                </div>
                <a href="{{ route('requisitions.create') }}" class="btn-orange text-white px-5 py-2.5 rounded-full font-semibold text-sm flex items-center gap-2 shadow-sm">
                    <i class="ph ph-plus font-bold"></i> Ajukan kebutuhan
                </a>
            </div>

            <!-- 4 Kartu Metrik -->
            <div class="grid grid-cols-4 gap-4 mb-6">
                <!-- Kartu 1 -->
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm relative">
                    <p class="text-xs text-gray-500 font-medium mb-1">Menunggu HRD</p>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $metrics['pending'] ?? 0 }}</h3>
                    <p class="text-[10px] text-gray-400">Pengajuan berstatus Diajukan</p>
                    <div class="absolute top-5 right-5 w-8 h-8 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center"><i class="ph ph-clock text-lg"></i></div>
                </div>
                <!-- Kartu 2 -->
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm relative">
                    <p class="text-xs text-gray-500 font-medium mb-1">Disetujui</p>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $metrics['approved'] ?? 0 }}</h3>
                    <p class="text-[10px] text-gray-400">&nbsp;</p>
                    <div class="absolute top-5 right-5 w-8 h-8 rounded-full bg-green-50 text-green-500 flex items-center justify-center"><i class="ph ph-check-circle text-lg"></i></div>
                </div>
                <!-- Kartu 3 -->
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm relative">
                    <p class="text-xs text-gray-500 font-medium mb-1">Ditolak</p>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $metrics['rejected'] ?? 0 }}</h3>
                    <p class="text-[10px] text-gray-400">Final, buat pengajuan baru</p>
                    <div class="absolute top-5 right-5 w-8 h-8 rounded-full bg-red-50 text-red-500 flex items-center justify-center"><i class="ph ph-x-circle text-lg"></i></div>
                </div>
                <!-- Kartu 4 -->
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm relative">
                    <p class="text-xs text-gray-500 font-medium mb-1">Karyawan baru</p>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $metrics['new_employees'] ?? 0 }}</h3>
                    <p class="text-[10px] text-gray-400">&nbsp;</p>
                    <div class="absolute top-5 right-5 w-8 h-8 rounded-full bg-teal-50 text-teal-500 flex items-center justify-center"><i class="ph ph-user-plus text-lg"></i></div>
                </div>
            </div>

            <!-- Konten Dua Kolom -->
            <div class="grid grid-cols-12 gap-6 pb-20">
                
                <!-- Kolom Kiri: Pengajuan Divisi -->
                <div class="col-span-7 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg">Pengajuan divisi</h3>
                            <p class="text-xs text-gray-400">5 Pengajuan terbaru divisimu</p>
                        </div>
                        <a href="{{ route('requisitions.index') }}" class="text-xs font-semibold text-gray-600 hover:text-gray-900 flex items-center gap-1">Semua <i class="ph ph-arrow-right"></i></a>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($requisitions ?? [] as $req)
                            <div class="flex items-center justify-between pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center"><i class="ph ph-clipboard-text text-xl"></i></div>
                                    <div>
                                        <p class="font-bold text-sm text-gray-900">{{ $req->position->name ?? 'Posisi' }} <span class="font-normal text-gray-500">· {{ $req->quantity }} orang</span></p>
                                        <p class="text-xs text-gray-400 mt-0.5">Oleh {{ $req->requested_by === auth()->id() ? 'Anda' : ($req->requester->name ?? 'Manajer') }} · {{ $req->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                                
                                @if(in_array($req->status, ['Diajukan', 'submitted']))
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span> Diajukan
                                    </span>
                                @elseif(in_array($req->status, ['Disetujui', 'approved']))
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-green-50 text-green-600 uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Disetujui
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-red-50 text-red-500 uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Ditolak
                                    </span>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 text-center py-4">Belum ada pengajuan.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-span-5 space-y-6">
                    
                    <!-- Lowongan Divisi -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 text-lg mb-5">Lowongan divisi aktif</h3>
                        
                        <div class="space-y-5">
                            @forelse($jobPostings ?? [] as $job)
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <div class="flex items-center gap-2 text-sm font-semibold text-gray-900">
                                            <i class="ph ph-briefcase text-gray-400 text-lg"></i> {{ $job->title }}
                                        </div>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold {{ $job->status === 'open' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }} uppercase">
                                            <span class="w-1 h-1 rounded-full {{ $job->status === 'open' ? 'bg-green-700' : 'bg-gray-400' }}"></span> {{ $job->status === 'open' ? 'Dibuka' : 'Ditutup' }}
                                        </span>
                                    </div>
                                    <!-- Logika sederhana progress pelamar -->
                                    <div class="w-full bg-gray-100 rounded-full h-1.5 mb-1"><div class="bg-[#0c251e] h-1.5 rounded-full" style="width: {{ $job->status === 'open' ? '0' : '100' }}%"></div></div>
                                    <p class="text-[10px] text-gray-400 text-right">0/{{ $job->quota }} terisi</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 text-center py-2">Tidak ada lowongan aktif.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Karyawan Baru -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-gray-900 text-lg">Karyawan baru</h3>
                            <a href="" class="text-xs font-semibold text-gray-600 hover:text-gray-900 flex items-center gap-1">Lihat <i class="ph ph-arrow-right"></i></a>
                        </div>
                        
                        @forelse(collect($newEmployees ?? [])->take(3) as $emp)
                            @php
                                $empInitials = collect(explode(' ', $emp->user->name))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                            @endphp
                            <div class="flex items-center gap-3 mb-4 last:mb-0">
                                <div class="w-10 h-10 rounded-full bg-teal-100 text-teal-700 font-bold text-sm flex items-center justify-center uppercase">{{ $empInitials }}</div>
                                <div>
                                    <p class="font-bold text-sm text-gray-900">{{ $emp->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $emp->jobPosting->requisition->position->name ?? 'Posisi' }} · {{ $emp->updated_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 text-center py-2">Belum ada karyawan baru.</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </main>
</body>
</html>
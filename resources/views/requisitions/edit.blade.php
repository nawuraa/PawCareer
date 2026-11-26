<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawCareer - Ubah Pengajuan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #faf7f2; }
        .sidebar { background-color: #0c251e; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
</head>
<body class="text-gray-800 flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="sidebar w-[260px] h-full flex flex-col text-white flex-shrink-0">
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

        <nav class="flex-1 px-4 py-2 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard.manager') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-400 hover:text-white transition-colors text-sm">
                <i class="ph ph-squares-four text-lg"></i> Dasbor
            </a>
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

        <div class="p-4 border-t border-white/10 flex items-center justify-between">
            <div class="flex items-center gap-3">
                @php $initials = collect(explode(' ', auth()->user()->name))->map(fn($n) => substr($n, 0, 1))->take(2)->join(''); @endphp
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
    <main class="flex-1 h-full overflow-y-auto">
        <div class="p-8 max-w-2xl mx-auto">

            <!-- Breadcrumb -->
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-6">
                <a href="{{ route('requisitions.index') }}" class="hover:text-gray-800">Pengajuan Kebutuhan</a>
                <span>/</span>
                <span class="font-medium text-gray-800">Ubah Pengajuan</span>
            </div>

            <div class="mb-8">
                <p class="text-[11px] font-bold text-[#0c251e] tracking-wider mb-1 uppercase">Manajer Divisi</p>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Ubah pengajuan</h2>
                <p class="text-sm text-gray-500">Perbarui detail pengajuan kebutuhan staf Anda.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 px-4 py-3 rounded-xl bg-red-50 text-red-700 border border-red-200 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
                <form action="{{ route('requisitions.update', $requisition->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Posisi -->
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-800 mb-1.5">Posisi</label>
                        <select name="position_id" required
                                class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-[#0c251e]/20 focus:border-[#0c251e] p-2.5 shadow-sm">
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}" {{ (old('position_id', $requisition->position_id) == $position->id) ? 'selected' : '' }}>
                                    {{ $position->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jumlah -->
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-800 mb-1.5">Jumlah orang (1–50)</label>
                        <input type="number" name="quantity" required min="1" max="50"
                               value="{{ old('quantity', $requisition->quantity) }}"
                               class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-[#0c251e]/20 focus:border-[#0c251e] p-2.5 shadow-sm">
                    </div>

                    <!-- Alasan -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-800 mb-1.5">Alasan kebutuhan</label>
                        <textarea name="reason" required rows="4"
                                  class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-[#0c251e]/20 focus:border-[#0c251e] p-2.5 shadow-sm">{{ old('reason', $requisition->reason) }}</textarea>
                        <p class="mt-1.5 text-[11px] text-gray-500">Wajib diisi agar HRD memahami justifikasinya.</p>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('requisitions.index') }}"
                           class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-full hover:bg-gray-50 transition-colors shadow-sm">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-5 py-2.5 text-sm font-semibold text-white bg-[#0c251e] hover:bg-[#153b30] rounded-full transition-colors shadow-sm">
                            Simpan perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </main>

</body>
</html>

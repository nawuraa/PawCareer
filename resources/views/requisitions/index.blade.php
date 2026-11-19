<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawCareer - Pengajuan Kebutuhan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #faf7f2; }
        .sidebar { background-color: #0c251e; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
</head>
<body class="text-gray-800 flex h-screen overflow-hidden relative">

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
    <main class="flex-1 h-full overflow-y-auto relative">
        <div class="p-8 max-w-7xl mx-auto">

            <!-- Notifikasi -->
            @if(session('success'))
                <div class="mb-4 px-4 py-3 rounded-xl bg-green-50 text-green-700 border border-green-200 flex items-center gap-2">
                    <i class="ph-fill ph-check-circle text-lg"></i> {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-end mb-6">
                <div>
                    <p class="text-[11px] font-bold text-[#0c251e] tracking-wider mb-1 uppercase">Manajer Divisi</p>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Pengajuan kebutuhan staf</h2>
                    <p class="text-sm text-gray-500">Ubah dan hapus hanya untuk pengajuan buatan Anda yang masih berstatus Diajukan.</p>
                </div>
                <!-- Tombol Trigger Modal Pop-up -->
                <button onclick="toggleModal('modalPengajuan')" class="bg-[#0c251e] hover:bg-[#153b30] text-white px-5 py-2.5 rounded-full font-semibold text-sm flex items-center gap-2 transition-colors shadow-sm cursor-pointer">
                    <i class="ph ph-plus font-bold"></i> Ajukan kebutuhan
                </button>
            </div>

            <div class="flex items-center gap-1 bg-white border border-gray-200 rounded-full p-1 w-max mb-6">
                <button class="px-5 py-1.5 rounded-full bg-[#0c251e] text-white text-sm font-semibold">Semua di divisi</button>
                <button class="px-5 py-1.5 rounded-full text-gray-500 hover:text-gray-900 text-sm font-medium transition-colors">Milik saya</button>
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
                        @forelse($requisitions as $req)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6">
                                    <p class="font-bold text-gray-900">{{ data_get($req, 'position.name', 'Posisi') }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5 truncate w-48" title="{{ $req->reason }}">{{ $req->reason }}</p>
                                </td>
                                <td class="py-4 px-6">{{ $req->quantity }} orang</td>
                                <td class="py-4 px-6">
                                    @if($req->requested_by === auth()->id())
                                        <p class="font-semibold text-[#0c251e]">Anda</p>
                                    @else
                                        <p class="font-medium text-gray-700">{{ data_get($req, 'requester.name', 'Manajer') }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-0.5">{{ optional($req->created_at)->format('d M Y') }}</p>
                                </td>
                                
                                <td class="py-4 px-6">
                                    @if(in_array($req->status, ['Diajukan', 'submitted']))
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span> Diajukan
                                        </span>
                                    @elseif(in_array($req->status, ['Disetujui', 'approved']))
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-500">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Ditolak
                                        </span>
                                    @endif
                                </td>
                                
                                <td class="py-4 px-6">
                                    @if(in_array($req->status, ['Disetujui', 'approved']))
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[9px] font-bold bg-green-100 text-green-700 uppercase">
                                                <span class="w-1 h-1 rounded-full bg-green-700"></span> Dibuka
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-1"><div class="bg-green-600 h-1 rounded-full" style="width: 0%"></div></div>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-3 text-gray-400 text-lg">
                                        @if(in_array($req->status, ['Diajukan', 'submitted']) && $req->requested_by === auth()->id())
                                            <a href="{{ route('requisitions.edit', $req->id) }}" class="hover:text-gray-900"><i class="ph ph-pencil-simple"></i></a>
                                            <form action="{{ route('requisitions.destroy', $req->id) }}" method="POST" class="m-0" onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-600"><i class="ph ph-trash"></i></button>
                                            </form>
                                        @else
                                            <i class="ph ph-lock-key opacity-50 cursor-not-allowed" title="Terkunci"></i>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-400 text-sm">Belum ada data pengajuan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- MODAL POP-UP & CUSTOM DROPDOWN -->
    <div id="modalPengajuan" class="fixed inset-0 z-40 hidden bg-gray-900/40 backdrop-blur-sm flex items-center justify-center transition-opacity">
        <!-- Kotak Modal -->
        <div class="bg-[#fdfbf6] w-full max-w-lg rounded-[1.25rem] shadow-2xl overflow-visible">
            
            <div class="px-6 pt-6 pb-4 flex justify-between items-start border-b border-gray-200/60">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Ajukan kebutuhan staf</h3>
                    <p class="text-[13px] text-gray-500 mt-1">Pengajuan dikirim ke HRD untuk ditinjau. Anda hanya dapat mengajukan posisi dari divisi Anda.</p>
                </div>
                <button onclick="toggleModal('modalPengajuan')" class="text-gray-400 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 p-1.5 rounded-full transition-colors">
                    <i class="ph ph-x text-lg"></i>
                </button>
            </div>

            <form action="{{ route('requisitions.store') }}" method="POST" class="p-6">
                @csrf
                
                <!-- CUSTOM DROPDOWN POSISI -->
                <div class="mb-4 relative" id="custom-dropdown-container">
                    <label class="block text-sm font-semibold text-gray-800 mb-1.5">Posisi</label>
                    
                    <!-- Input Tersembunyi -->
                    <input type="hidden" name="position_id" id="input_position_id" required>

                    <!-- Tombol Pemicu -->
                    <button type="button" id="dropdown-trigger" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-[#0c251e]/20 focus:border-[#0c251e] flex justify-between items-center p-2.5 shadow-sm text-left transition-all">
                        <span id="dropdown-text" class="text-gray-500">Pilih posisi...</span>
                        <i class="ph ph-caret-down text-gray-400"></i>
                    </button>

                    <!-- Menu List -->
                    <div id="dropdown-menu" class="hidden absolute z-50 w-full mt-1.5 bg-[#fdfbf6] border border-gray-200 rounded-xl shadow-xl max-h-48 overflow-y-auto">
                        <div class="p-1.5 space-y-0.5">
                            
                            <!-- Pilihan 1 -->
                            <button type="button" 
                                    class="custom-option w-full text-left px-3 py-2 text-sm rounded-lg flex justify-between items-center text-gray-700 hover:bg-[#e8f0ec] hover:text-[#0c251e] transition-colors"
                                    data-value="1" 
                                    data-text="Dokter Hewan Umum"
                                    onclick="pilihPosisi(this)">
                                <span>Dokter Hewan Umum</span>
                                <i class="ph ph-check font-bold text-[#0c251e] opacity-0 icon-centang"></i>
                            </button>

                            <!-- Pilihan 2 -->
                            <button type="button" 
                                    class="custom-option w-full text-left px-3 py-2 text-sm rounded-lg flex justify-between items-center text-gray-700 hover:bg-[#e8f0ec] hover:text-[#0c251e] transition-colors"
                                    data-value="2" 
                                    data-text="Paramedis Veteriner"
                                    onclick="pilihPosisi(this)">
                                <span>Paramedis Veteriner</span>
                                <i class="ph ph-check font-bold text-[#0c251e] opacity-0 icon-centang"></i>
                            </button>

                            <!-- Pilihan 3 -->
                            <button type="button" 
                                    class="custom-option w-full text-left px-3 py-2 text-sm rounded-lg flex justify-between items-center text-gray-700 hover:bg-[#e8f0ec] hover:text-[#0c251e] transition-colors"
                                    data-value="3" 
                                    data-text="Asisten Bedah"
                                    onclick="pilihPosisi(this)">
                                <span>Asisten Bedah</span>
                                <i class="ph ph-check font-bold text-[#0c251e] opacity-0 icon-centang"></i>
                            </button>

                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-800 mb-1.5">Jumlah orang (1–50)</label>
                    <input type="number" name="quantity" required min="1" max="50" value="1" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-[#0c251e] focus:border-[#0c251e] block p-2.5 shadow-sm">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-800 mb-1.5">Alasan kebutuhan</label>
                    <textarea name="reason" required rows="3" placeholder="Jelaskan mengapa divisi membutuhkan staf ini, mis. volume pasien, shift kosong, pengganti cuti." class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-[#0c251e] focus:border-[#0c251e] block p-2.5 shadow-sm"></textarea>
                    <p class="mt-1.5 text-[11px] text-gray-500">Wajib diisi agar HRD memahami justifikasinya.</p>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="toggleModal('modalPengajuan')" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-full hover:bg-gray-50 transition-colors shadow-sm">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-[#0c251e] hover:bg-[#153b30] rounded-full transition-colors shadow-sm">
                        Kirim ke HRD
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript Control -->
    <script>
        // FUNGSI MODAL
        function toggleModal(modalID){
            document.getElementById(modalID).classList.toggle("hidden");
        }

        // FUNGSI CUSTOM DROPDOWN
        const dropdownContainer = document.getElementById('custom-dropdown-container');
        const dropdownTrigger = document.getElementById('dropdown-trigger');
        const dropdownMenu = document.getElementById('dropdown-menu');
        const inputPosisi = document.getElementById('input_position_id');
        const dropdownText = document.getElementById('dropdown-text');
        const semuaPilihan = document.querySelectorAll('.custom-option');

        // Buka/tutup list
        dropdownTrigger.addEventListener('click', function() {
            dropdownMenu.classList.toggle('hidden');
        });

        // Tutup jika klik di luar form dropdown
        document.addEventListener('click', function(event) {
            if (!dropdownContainer.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });

        // Event saat opsi dipilih
        function pilihPosisi(elemen) {
            const idPosisi = elemen.getAttribute('data-value');
            const namaPosisi = elemen.getAttribute('data-text');

            // Set nilai input tersembunyi
            inputPosisi.value = idPosisi;
            dropdownText.textContent = namaPosisi;
            dropdownText.classList.remove('text-gray-500');
            dropdownText.classList.add('text-gray-900');

            // Hapus gaya aktif dari semua opsi
            semuaPilihan.forEach(opt => {
                opt.classList.remove('bg-[#e8f0ec]', 'text-[#0c251e]', 'font-semibold');
                opt.querySelector('.icon-centang').classList.add('opacity-0');
            });

            // Tambahkan gaya aktif ke opsi terpilih
            elemen.classList.add('bg-[#e8f0ec]', 'text-[#0c251e]', 'font-semibold');
            elemen.querySelector('.icon-centang').classList.remove('opacity-0');

            // Tutup dropdown
            dropdownMenu.classList.add('hidden');
        }
    </script>
</body>
</html>
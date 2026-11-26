@extends('layouts.app')
@section('title', 'Pengajuan Kebutuhan')
@section('content')

<div class="p-8 max-w-7xl mx-auto">

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-xl bg-[#CCF4D3] text-[#00682A] border border-[#A3E6B0] flex items-center gap-2 text-sm">
            <i class="ph-fill ph-check-circle text-lg"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex justify-between items-end mb-6">
        <div>
            <p class="text-[11px] font-bold text-[#06614B] tracking-wider mb-1 uppercase">
                Divisi {{ auth()->user()->division->name ?? '' }}
            </p>
            <h1 class="text-3xl font-bold text-[#0E271F] mb-2 font-[Bricolage_Grotesque,sans-serif]">Pengajuan Kebutuhan Staf</h1>
            <p class="text-sm text-[#536961]">Ubah dan hapus hanya untuk pengajuan buatan Anda yang masih berstatus Diajukan.</p>
        </div>
        {{-- Tombol Trigger Modal --}}
        <button onclick="toggleModal('modalPengajuan')"
                class="bg-[#F09A53] text-[#401F0A] px-5 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-2 hover:bg-[#e08843] transition-colors shadow-sm cursor-pointer">
            <i class="ph ph-plus font-bold"></i> Ajukan Kebutuhan
        </button>
    </div>

    {{-- Tabel Data --}}
    <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] shadow-sm overflow-hidden mb-6">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-[#FFFDFA] text-[11px] font-bold text-[#536961] uppercase tracking-wider border-b border-[#E3DDD1]">
                <tr>
                    <th class="py-4 px-6">Posisi</th>
                    <th class="py-4 px-6">Jumlah</th>
                    <th class="py-4 px-6">Diajukan Oleh</th>
                    <th class="py-4 px-6">Status</th>
                    <th class="py-4 px-6">Lowongan</th>
                    <th class="py-4 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E3DDD1] text-[#0E271F]">
                @forelse($requisitions as $req)
                    <tr class="hover:bg-[#FAF6EE] transition-colors">
                        <td class="py-4 px-6">
                            <p class="font-bold text-[#0E271F]">{{ data_get($req, 'position.name', 'Posisi') }}</p>
                            <p class="text-xs text-[#536961] mt-0.5 truncate w-48" title="{{ $req->reason }}">{{ $req->reason }}</p>
                        </td>
                        <td class="py-4 px-6 text-[#536961]">{{ $req->quantity }} orang</td>
                        <td class="py-4 px-6">
                            @if($req->requested_by === auth()->id())
                                <p class="font-semibold text-[#06614B]">Anda</p>
                            @else
                                <p class="font-medium text-[#0E271F]">{{ data_get($req, 'requester.name', 'Manajer') }}</p>
                            @endif
                            <p class="text-xs text-[#536961] mt-0.5">{{ optional($req->created_at)->format('d M Y') }}</p>
                        </td>

                        <td class="py-4 px-6">
                            @if(in_array($req->status, ['Diajukan', 'submitted']))
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-[#DBEEFE] text-[#225984]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#225984]"></span> Diajukan
                                </span>
                            @elseif(in_array($req->status, ['Disetujui', 'approved']))
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-[#CCF4D3] text-[#00682A]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#00682A]"></span> Disetujui
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-[#FFE2DD] text-[#B32322]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#B32322]"></span> Ditolak
                                </span>
                            @endif
                        </td>

                        <td class="py-4 px-6">
                            @if(in_array($req->status, ['Disetujui', 'approved']))
                                <div class="flex justify-between items-center mb-1">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[9px] font-bold bg-[#CCF4D3] text-[#00682A] uppercase">
                                        <span class="w-1 h-1 rounded-full bg-[#00682A]"></span> Dibuka
                                    </span>
                                </div>
                                <div class="w-full bg-[#E3DDD1] rounded-full h-1">
                                    <div class="bg-[#06614B] h-1 rounded-full" style="width: 0%"></div>
                                </div>
                            @else
                                <span class="text-[#536961]">—</span>
                            @endif
                        </td>

                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center gap-3 text-[#536961] text-lg">
                                @if(in_array($req->status, ['Diajukan', 'submitted']) && $req->requested_by === auth()->id())
                                    <a href="{{ route('requisitions.edit', $req->id) }}" class="hover:text-[#0E271F]">
                                        <i class="ph ph-pencil-simple"></i>
                                    </a>
                                    <form action="{{ route('requisitions.destroy', $req->id) }}" method="POST" class="m-0"
                                          onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[#B32322] hover:text-[#8a1a19]">
                                            <i class="ph ph-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <i class="ph ph-lock-key opacity-40 cursor-not-allowed" title="Terkunci"></i>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-[#536961] text-sm">
                            <i class="ph ph-clipboard-text text-3xl mb-2 block text-[#E3DDD1]"></i>
                            Belum ada data pengajuan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- MODAL PENGAJUAN --}}
<div id="modalPengajuan" class="fixed inset-0 z-40 hidden bg-[#0E271F]/40 backdrop-blur-sm flex items-center justify-center transition-opacity">
    <div class="bg-[#FFFDFA] w-full max-w-lg rounded-2xl shadow-2xl overflow-visible">

        <div class="px-6 pt-6 pb-4 flex justify-between items-start border-b border-[#E3DDD1]">
            <div>
                <h3 class="text-lg font-bold text-[#0E271F]">Ajukan kebutuhan staf</h3>
                <p class="text-[13px] text-[#536961] mt-1">Pengajuan dikirim ke HRD untuk ditinjau. Anda hanya dapat mengajukan posisi dari divisi Anda.</p>
            </div>
            <button onclick="toggleModal('modalPengajuan')"
                    class="text-[#536961] hover:text-[#0E271F] bg-[#FAF6EE] hover:bg-[#E3DDD1] p-1.5 rounded-full transition-colors">
                <i class="ph ph-x text-lg"></i>
            </button>
        </div>

        <form action="{{ route('requisitions.store') }}" method="POST" class="p-6">
            @csrf

            {{-- Custom Dropdown Posisi --}}
            <div class="mb-4 relative" id="custom-dropdown-container">
                <label class="block text-sm font-semibold text-[#0E271F] mb-1.5">Posisi</label>

                <input type="hidden" name="position_id" id="input_position_id" required>

                <button type="button" id="dropdown-trigger"
                        class="w-full px-4 py-2.5 rounded-xl border border-[#E3DDD1] bg-white text-sm focus:ring-2 focus:ring-[#06614B]/20 focus:border-[#06614B] outline-none flex justify-between items-center text-left transition-all">
                    <span id="dropdown-text" class="text-[#536961]">Pilih posisi...</span>
                    <i class="ph ph-caret-down text-[#536961]"></i>
                </button>

                <div id="dropdown-menu" class="hidden absolute z-50 w-full mt-1.5 bg-[#FFFDFA] border border-[#E3DDD1] rounded-xl shadow-xl max-h-48 overflow-y-auto">
                    <div class="p-1.5 space-y-0.5">
                        @forelse($positions as $position)
                            <button type="button"
                                    class="custom-option w-full text-left px-3 py-2 text-sm rounded-lg flex justify-between items-center text-[#0E271F] hover:bg-[#D2F0E4] hover:text-[#06614B] transition-colors"
                                    data-value="{{ $position->id }}"
                                    data-text="{{ $position->name }}"
                                    onclick="pilihPosisi(this)">
                                <span>{{ $position->name }}</span>
                                <i class="ph ph-check font-bold text-[#06614B] opacity-0 icon-centang"></i>
                            </button>
                        @empty
                            <p class="text-sm text-[#536961] text-center py-3">Tidak ada posisi tersedia.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-[#0E271F] mb-1.5">Jumlah orang (1–50)</label>
                <input type="number" name="quantity" required min="1" max="50" value="1"
                       class="w-full px-4 py-2.5 rounded-xl border border-[#E3DDD1] bg-white text-sm focus:ring-2 focus:ring-[#06614B]/20 focus:border-[#06614B] outline-none">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-[#0E271F] mb-1.5">Alasan kebutuhan</label>
                <textarea name="reason" required rows="3"
                          placeholder="Jelaskan mengapa divisi membutuhkan staf ini, mis. volume pasien, shift kosong, pengganti cuti."
                          class="w-full px-4 py-2.5 rounded-xl border border-[#E3DDD1] bg-white text-sm focus:ring-2 focus:ring-[#06614B]/20 focus:border-[#06614B] outline-none resize-none"></textarea>
                <p class="mt-1.5 text-[11px] text-[#536961]">Wajib diisi agar HRD memahami justifikasinya.</p>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="toggleModal('modalPengajuan')"
                        class="px-5 py-2.5 text-sm font-semibold text-[#536961] bg-white border border-[#E3DDD1] rounded-xl hover:bg-[#FAF6EE] transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="bg-[#06614B] text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-[#055040] transition-colors">
                    Kirim ke HRD
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Fungsi Modal
    function toggleModal(modalID) {
        document.getElementById(modalID).classList.toggle('hidden');
    }

    // Fungsi Custom Dropdown
    const dropdownContainer = document.getElementById('custom-dropdown-container');
    const dropdownTrigger  = document.getElementById('dropdown-trigger');
    const dropdownMenu     = document.getElementById('dropdown-menu');
    const inputPosisi      = document.getElementById('input_position_id');
    const dropdownText     = document.getElementById('dropdown-text');
    const semuaPilihan     = document.querySelectorAll('.custom-option');

    dropdownTrigger.addEventListener('click', function () {
        dropdownMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', function (event) {
        if (!dropdownContainer.contains(event.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });

    function pilihPosisi(elemen) {
        inputPosisi.value = elemen.getAttribute('data-value');
        dropdownText.textContent = elemen.getAttribute('data-text');
        dropdownText.classList.remove('text-[#536961]');
        dropdownText.classList.add('text-[#0E271F]');

        semuaPilihan.forEach(opt => {
            opt.classList.remove('bg-[#D2F0E4]', 'text-[#06614B]', 'font-semibold');
            opt.querySelector('.icon-centang').classList.add('opacity-0');
        });

        elemen.classList.add('bg-[#D2F0E4]', 'text-[#06614B]', 'font-semibold');
        elemen.querySelector('.icon-centang').classList.remove('opacity-0');
        dropdownMenu.classList.add('hidden');
    }
</script>
@endpush

@endsection

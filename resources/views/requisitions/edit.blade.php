@extends('layouts.app')
@section('title', 'Ubah Pengajuan')
@section('content')

<div class="p-8 max-w-2xl mx-auto">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-[#536961] mb-6">
        <a href="{{ route('requisitions.index') }}" class="hover:text-[#0E271F] transition-colors">Pengajuan Kebutuhan</a>
        <span>/</span>
        <span class="font-medium text-[#0E271F]">Ubah Pengajuan</span>
    </div>

    {{-- Header --}}
    <div class="mb-8">
        <p class="text-[11px] font-bold text-[#06614B] tracking-wider mb-1 uppercase">
            Divisi {{ auth()->user()->division->name ?? '' }}
        </p>
        <h1 class="text-3xl font-bold text-[#0E271F] mb-2 font-[Bricolage_Grotesque,sans-serif]">Ubah Pengajuan</h1>
        <p class="text-sm text-[#536961]">Perbarui detail pengajuan kebutuhan staf Anda.</p>
    </div>

    {{-- Error Validation --}}
    @if ($errors->any())
        <div class="mb-6 px-4 py-3 rounded-xl bg-[#FFE2DD] text-[#B32322] border border-[#F5C0BE] text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] shadow-sm p-8">
        <form action="{{ route('requisitions.update', $requisition->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Posisi --}}
            <div class="mb-5">
                <label class="block text-sm font-semibold text-[#0E271F] mb-1.5">Posisi</label>
                <select name="position_id" required
                        class="w-full px-4 py-2.5 rounded-xl border border-[#E3DDD1] bg-white text-sm focus:ring-2 focus:ring-[#06614B]/20 focus:border-[#06614B] outline-none">
                    @foreach($positions as $position)
                        <option value="{{ $position->id }}"
                            {{ (old('position_id', $requisition->position_id) == $position->id) ? 'selected' : '' }}>
                            {{ $position->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Jumlah --}}
            <div class="mb-5">
                <label class="block text-sm font-semibold text-[#0E271F] mb-1.5">Jumlah orang (1–50)</label>
                <input type="number" name="quantity" required min="1" max="50"
                       value="{{ old('quantity', $requisition->quantity) }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-[#E3DDD1] bg-white text-sm focus:ring-2 focus:ring-[#06614B]/20 focus:border-[#06614B] outline-none">
            </div>

            {{-- Alasan --}}
            <div class="mb-6">
                <label class="block text-sm font-semibold text-[#0E271F] mb-1.5">Alasan kebutuhan</label>
                <textarea name="reason" required rows="4"
                          class="w-full px-4 py-2.5 rounded-xl border border-[#E3DDD1] bg-white text-sm focus:ring-2 focus:ring-[#06614B]/20 focus:border-[#06614B] outline-none resize-none">{{ old('reason', $requisition->reason) }}</textarea>
                <p class="mt-1.5 text-[11px] text-[#536961]">Wajib diisi agar HRD memahami justifikasinya.</p>
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('requisitions.index') }}"
                   class="px-5 py-2.5 text-sm font-semibold text-[#536961] bg-white border border-[#E3DDD1] rounded-xl hover:bg-[#FAF6EE] transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="bg-[#06614B] text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-[#055040] transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>

@endsection

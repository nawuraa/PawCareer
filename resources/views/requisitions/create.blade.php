@extends('layouts.app')
@section('title', 'Buat Pengajuan Staf')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-[#536961] mb-6">
        <a href="{{ route('requisitions.index') }}" class="hover:text-[#06614B] transition-colors">Pengajuan Kebutuhan</a>
        <span>/</span>
        <span class="font-medium text-[#0E271F]">Buat Pengajuan Baru</span>
    </div>

    {{-- Header --}}
    <div class="mb-8">
        <p class="text-[11px] font-bold text-[#06614B] tracking-wider mb-1 uppercase">
            Divisi {{ auth()->user()->division->name ?? '' }}
        </p>
        <h1 class="text-3xl font-bold text-[#0E271F] mb-2 font-display">Buat Pengajuan Staf Baru</h1>
        <p class="text-sm text-[#536961]">Ajukan kebutuhan personel baru di divisi Anda untuk ditinjau oleh HRD.</p>
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
    <form action="{{ route('requisitions.store') }}" method="POST"
          class="bg-[#FFFDFA] rounded-2xl border border-[#E3DDD1] shadow-soft overflow-hidden">
        @csrf

        <div class="p-6 sm:p-8 space-y-5">
            {{-- Posisi --}}
            <div>
                <label for="position_id" class="block text-sm font-semibold text-[#0E271F] mb-1.5">
                    Pilih Posisi <span class="text-[#D33B36]">*</span>
                </label>
                <select name="position_id" id="position_id" required
                        class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('position_id') ? 'border-[#D33B36]' : 'border-[#DDD7C9]' }} bg-[#FFFDFA] text-sm text-[#0E271F] focus:ring-2 focus:ring-[#06614B]/20 focus:border-[#06614B] outline-none">
                    <option value="">-- Pilih Posisi --</option>
                    @foreach($positions as $position)
                        <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>
                            {{ $position->name }} — {{ $position->division->name }}
                        </option>
                    @endforeach
                </select>
                @error('position_id')
                    <p class="mt-1.5 text-xs font-medium text-[#B32322]">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jumlah --}}
            <div>
                <label for="quantity" class="block text-sm font-semibold text-[#0E271F] mb-1.5">
                    Jumlah Orang (1–50) <span class="text-[#D33B36]">*</span>
                </label>
                <input type="number" name="quantity" id="quantity" min="1" max="50"
                       value="{{ old('quantity') }}" required
                       class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('quantity') ? 'border-[#D33B36]' : 'border-[#DDD7C9]' }} bg-[#FFFDFA] text-sm text-[#0E271F] focus:ring-2 focus:ring-[#06614B]/20 focus:border-[#06614B] outline-none">
                @error('quantity')
                    <p class="mt-1.5 text-xs font-medium text-[#B32322]">{{ $message }}</p>
                @enderror
            </div>

            {{-- Alasan --}}
            <div>
                <label for="reason" class="block text-sm font-semibold text-[#0E271F] mb-1.5">
                    Alasan Pengajuan <span class="text-[#D33B36]">*</span>
                </label>
                <textarea name="reason" id="reason" rows="4" required
                          placeholder="Jelaskan kebutuhan operasional atau beban kerja yang mendasari pengajuan ini..."
                          class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('reason') ? 'border-[#D33B36]' : 'border-[#DDD7C9]' }} bg-[#FFFDFA] text-sm text-[#0E271F] placeholder-[#536961] focus:ring-2 focus:ring-[#06614B]/20 focus:border-[#06614B] outline-none">{{ old('reason') }}</textarea>
                @error('reason')
                    <p class="mt-1.5 text-xs font-medium text-[#B32322]">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="px-6 sm:px-8 py-4 bg-[#FAF6EE] border-t border-[#E3DDD1] flex items-center justify-end gap-3">
            <a href="{{ route('requisitions.index') }}"
               class="px-5 py-2.5 rounded-xl border border-[#E3DDD1] bg-[#FFFDFA] text-xs font-semibold text-[#536961] hover:bg-[#F2ECE1] transition-colors">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-[#F09A53] hover:bg-[#E08843] text-[#401F0A] font-semibold text-xs transition-colors cursor-pointer">
                Simpan Pengajuan
            </button>
        </div>
    </form>

</div>
@endsection

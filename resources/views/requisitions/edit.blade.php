<!-- resources/views/requisitions/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-warning">
            <h4 class="mb-0">Ubah Pengajuan Staf</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('requisitions.update', $requisition->id) }}" method="POST">
                @csrf
                @method('PUT') <!-- Wajib untuk proses update di Laravel -->
                
                <div class="mb-3">
                    <label for="position_id" class="form-label">Pilih Posisi</label>
                    <select name="position_id" id="position_id" class="form-select @error('position_id') is-invalid @enderror" required>
                        @foreach($positions as $position)
                            <option value="{{ $position->id }}" {{ (old('position_id', $requisition->position_id) == $position->id) ? 'selected' : '' }}>
                                {{ $position->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('position_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Jumlah Orang (1-50)</label>
                    <!-- Batas 1-50[cite: 1, 2] -->
                    <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" min="1" max="50" value="{{ old('quantity', $requisition->quantity) }}" required>
                    @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="reason" class="form-label">Alasan Pengajuan</label>
                    <!-- Alasan wajib diisi[cite: 2] -->
                    <textarea name="reason" id="reason" rows="4" class="form-control @error('reason') is-invalid @enderror" required>{{ old('reason', $requisition->reason) }}</textarea>
                    @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('requisitions.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-warning">Perbarui Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
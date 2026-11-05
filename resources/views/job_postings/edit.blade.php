@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Ubah Detail Lowongan</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('job-postings.update', $jobPosting->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">Judul Lowongan</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $jobPosting->title) }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi Pekerjaan</label>
                    <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $jobPosting->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="requirements" class="form-label">Persyaratan Kandidat</label>
                    <textarea class="form-control" id="requirements" name="requirements" rows="4">{{ old('requirements', $jobPosting->requirements) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="deadline" class="form-label">Batas Waktu Lamaran (Deadline)</label>
                    <!-- Zona waktu aplikasi diatur Asia/Jakarta dengan batas akhir pukul 23:59:59 -->
                    <input type="date" class="form-control" id="deadline" name="deadline" value="{{ old('deadline', optional($jobPosting->deadline)->format('Y-m-d')) }}">
                    <small class="text-muted">Deadline berlaku sampai akhir hari (23:59:59 WIB)[cite: 1].</small>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('job-postings.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
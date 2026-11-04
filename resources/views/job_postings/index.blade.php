@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Kelola Lowongan Pekerjaan</h2>
    <p class="text-muted">Daftar lowongan yang telah diterbitkan otomatis dari pengajuan yang disetujui.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Judul Lowongan</th>
                        <th>Kuota</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobPostings as $index => $job)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $job->title }}</strong><br>
                                <small class="text-muted">Divisi: {{ $job->requisition->division->name ?? '-' }}</small>
                            </td>
                            <td>{{ $job->quota }} Orang</td>
                            <td>{{ $job->deadline ? $job->deadline->format('d-m-Y') : 'Belum diset' }}</td>
                            <td>
                                <span class="badge bg-{{ $job->status == 'open' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </td>
                            <td>
                                @if($job->status === 'open')
                                    <!-- Tombol Ubah Detail -->
                                    <a href="{{ route('job-postings.edit', $job->id) }}" class="btn btn-sm btn-primary mb-1">Ubah</a>

                                    <!-- Tombol Tutup Manual -->
                                    <form action="{{ route('job-postings.update', $job->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menutup lowongan ini secara manual? Lowongan yang ditutup tidak dapat dibuka kembali[cite: 1, 2].');">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="close_job" value="1">
                                        <button type="submit" class="btn btn-sm btn-warning mb-1">Tutup</button>
                                    </form>
                                @endif

                                <!-- Tombol Hapus (Dibatasi aturan BR-14 di controller jika ada pelamar[cite: 1, 2]) -->
                                <form action="{{ route('job-postings.destroy', $job->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus lowongan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger mb-1">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada lowongan yang diterbitkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    
    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark">Kelola Lowongan Pekerjaan</h2>
            <p class="text-muted mb-0">Daftar lowongan aktif dan riwayat publikasi dari pengajuan manajer.</p>
        </div>
    </div>

    <!-- Notifikasi Sukses / Error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="ph-fill ph-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="ph-fill ph-warning-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Kartu Tabel -->
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3 text-secondary font-weight-bold">Posisi & Divisi</th>
                        <th class="py-3 text-secondary">Kuota</th>
                        <th class="py-3 text-secondary">Batas Waktu</th>
                        <th class="py-3 text-secondary">Status</th>
                        <th class="px-4 py-3 text-end text-secondary">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobPostings as $job)
                        <tr>
                            <!-- Kolom Judul & Divisi -->
                            <td class="px-4 py-3">
                                <div class="fw-bold text-dark fs-6">{{ $job->title }}</div>
                                <div class="text-muted small d-flex align-items-center gap-1 mt-1">
                                    <i class="ph ph-buildings"></i> 
                                    {{ $job->requisition->position->division->name ?? 'Divisi tidak ditemukan' }}
                                </div>
                            </td>
                            
                            <!-- Kolom Kuota -->
                            <td class="py-3">
                                <span class="badge bg-secondary rounded-pill px-3">{{ $job->quota }} Orang</span>
                            </td>
                            
                            <!-- Kolom Deadline -->
                            <td class="py-3">
                                @if($job->deadline)
                                    <div class="text-dark fw-medium">{{ $job->deadline->format('d M Y') }}</div>
                                    @if($job->deadline < now() && $job->status === 'open')
                                        <small class="text-danger fw-bold"><i class="ph ph-warning"></i> Kedaluwarsa</small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            
                            <!-- Kolom Status -->
                            <td class="py-3">
                                @if($job->status === 'open')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1">
                                        Dibuka
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-2 py-1">
                                        Ditutup
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Kolom Aksi -->
                            <td class="px-4 py-3 text-end">
                                <div class="d-inline-flex gap-2">
                                    @if($job->status === 'open')
                                        <!-- Tombol Edit (Hanya jika masih open) -->
                                        <a href="{{ route('job-postings.edit', $job->id) }}" class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Ubah Detail">
                                            <i class="ph ph-pencil-simple fs-5"></i>
                                        </a>

                                        <!-- Tombol Tutup Manual -->
                                        <form action="{{ route('job-postings.update', $job->id) }}" method="POST" class="m-0" onsubmit="return confirm('Tutup lowongan ini secara manual? Lowongan tidak dapat dibuka kembali.');">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="close_job" value="1">
                                            <button type="submit" class="btn btn-sm btn-outline-warning d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Tutup Lowongan">
                                                <i class="ph ph-lock-key fs-5"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Tombol Hapus (Dibatasi oleh controller jika ada pelamar) -->
                                    <form action="{{ route('job-postings.destroy', $job->id) }}" method="POST" class="m-0" onsubmit="return confirm('Yakin ingin menghapus lowongan ini ke Tempat Sampah?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Hapus Lowongan">
                                            <i class="ph ph-trash fs-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <!-- Jika Database Kosong -->
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                                    <i class="ph ph-folder-open text-black-50 mb-2" style="font-size: 48px;"></i>
                                    <h6 class="fw-bold text-dark mb-1">Belum ada lowongan</h6>
                                    <p class="small mb-0">Lowongan akan otomatis terbit saat pengajuan manajer disetujui.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
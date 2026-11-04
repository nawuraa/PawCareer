
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Review Pengajuan Kebutuhan Staf</h2>
    <p class="text-muted">Daftar pengajuan dari Manajer Divisi yang menunggu keputusan HRD.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Divisi & Manajer</th>
                        <th>Posisi</th>
                        <th>Jumlah</th>
                        <th>Alasan Pengajuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requisitions as $index => $req)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $req->manager->division->name ?? 'Divisi Tidak Diketahui' }}</strong><br>
                                <span class="text-muted small">Oleh: {{ $req->manager->name ?? 'User' }}</span>
                            </td>
                            <td>{{ $req->position->name ?? 'Posisi ID: ' . $req->position_id }}</td>
                            <td>{{ $req->quantity }} Orang</td>
                            <td>{{ $req->reason }}</td>
                            <td>
                                <!-- Tombol Setujui memicu modal atau form langsung -->
                                <form action="{{ route('job_postings.process_review', $req->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Setujui pengajuan ini dan terbitkan lowongan otomatis?');">
                                    @csrf
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="btn btn-sm btn-success mb-1">Setujui</button>
                                </form>

                                <!-- Tombol Tolak memicu Modal untuk mengisi catatan wajib -->
                                <button type="button" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $req->id }}">
                                    Tolak
                                </button>

                                <!-- Modal Penolakan (Aturan: Penolakan wajib bercatatan)[cite: 2] -->
                                <div class="modal fade" id="rejectModal{{ $req->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $req->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('job_postings.process_review', $req->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="action" value="reject">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" id="rejectModalLabel{{ $req->id }}">Tolak Pengajuan</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="hrd_notes{{ $req->id }}" class="form-label">Catatan Penolakan (Wajib)</label>
                                                        <textarea class="form-control" id="hrd_notes{{ $req->id }}" name="hrd_notes" rows="3" required></textarea>
                                                        <small class="text-danger">Pengajuan yang ditolak bersifat final dan tidak bisa diubah lagi oleh Manajer.[cite: 1, 2]</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger">Konfirmasi Tolak</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada pengajuan baru yang perlu di-review.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
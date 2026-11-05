<!-- resources/views/requisitions/index.blade.php -->
@extends('layouts.app') <!-- Asumsi Anggota 1 sudah membuat layout dasar ini[cite: 2] -->

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Pengajuan Staf</h2>
        <!-- Tombol untuk membuat pengajuan baru -->
        <a href="{{ route('requisitions.create') }}" class="btn btn-primary">Tambah Pengajuan</a>
    </div>

    <!-- Menampilkan pesan sukses dari Controller -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Divisi</th>
                        <th>Posisi</th>
                        <th>Jumlah</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requisitions as $index => $req)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <!-- Asumsi relasi ke model Position sudah ada untuk menampilkan nama posisi -->
                            <td>{{ $req->position->division->name ?? '-' }}</td>
                            <td>{{ $req->position->name ?? 'Position ID: ' . $req->position_id }}</td>
                            <td>{{ $req->quantity }} Orang</td>
                            <td>{{ $req->reason }}</td>
                            <td>
                                <!-- Label status konsisten -->
                                <span class="badge bg-{{ $req->status === 'submitted' ? 'warning' : ($req->status === 'approved' ? 'success' : 'danger') }}">
                                    {{ $req->status }}
                                </span>
                            </td>
                            <td>
                                <!-- Only the requester can edit or delete a pending requisition. -->
                                @if($req->requested_by === auth()->id() && $req->status === 'submitted')
                                    <a href="{{ route('requisitions.edit', $req->id) }}" class="btn btn-sm btn-outline-primary">Ubah</a>
                                    
                                    <form action="{{ route('requisitions.destroy', $req->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                @else
                                    <span class="text-muted small">Tidak ada aksi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada requisitions for this division.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
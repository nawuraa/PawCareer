
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Review Pengajuan Kebutuhan Staf</h2>
    <p class="text-muted">Daftar pengajuan dari Manajer Divisi yang menunggu keputusan HRD.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
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
                                <strong>{{ $req->position->division->name ?? 'Division unavailable' }}</strong><br>
                                <span class="text-muted small">By: {{ $req->requester->name ?? 'User' }}</span>
                            </td>
                            <td>{{ $req->position->name ?? 'Position ID: ' . $req->position_id }}</td>
                            <td>{{ $req->quantity }}</td>
                            <td>{{ $req->reason }}</td>
                            <td>
                                <details>
                                    <summary>Approve and publish</summary>
                                    <form action="{{ route('job_postings.process_review', $req) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <label for="title{{ $req->id }}">Posting title</label>
                                        <input id="title{{ $req->id }}" name="title" maxlength="150" value="{{ $req->position->name }}">
                                        <label for="description{{ $req->id }}">Job description</label>
                                        <textarea id="description{{ $req->id }}" name="description" required></textarea>
                                        <label for="requirements{{ $req->id }}">Requirements</label>
                                        <textarea id="requirements{{ $req->id }}" name="requirements"></textarea>
                                        <label for="deadline{{ $req->id }}">Application deadline</label>
                                        <input id="deadline{{ $req->id }}" type="date" name="deadline" min="{{ now()->toDateString() }}" required>
                                        <label for="approval_note{{ $req->id }}">Review note (optional)</label>
                                        <textarea id="approval_note{{ $req->id }}" name="review_note"></textarea>
                                        <button type="submit">Approve and publish</button>
                                    </form>
                                </details>
                                <details>
                                    <summary>Reject</summary>
                                    <form action="{{ route('job_postings.process_review', $req) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <label for="review_note{{ $req->id }}">Reason for rejection</label>
                                        <textarea id="review_note{{ $req->id }}" name="review_note" required></textarea>
                                        <button type="submit">Reject requisition</button>
                                    </form>
                                </details>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No requisitions are waiting for review.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
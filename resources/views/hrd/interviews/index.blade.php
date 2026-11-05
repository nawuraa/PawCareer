@extends('layouts.app')

@section('title', 'My interviews')

@section('content')
    <h1>My assigned interviews</h1>
    @if (session('success')) <p role="status">{{ session('success') }}</p> @endif
    @if ($errors->any())
        <ul role="alert">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    @endif

    <table>
        <thead><tr><th>Applicant</th><th>Job</th><th>Scheduled (Jakarta)</th><th>Status</th><th>Evaluation</th></tr></thead>
        <tbody>
            @forelse ($interviews as $interview)
                <tr>
                    <td>{{ $interview->application->applicant->name }} ({{ $interview->application->applicant->email }})</td>
                    <td>{{ $interview->application->jobPosting->title }}</td>
                    <td>{{ $interview->scheduled_at->timezone('Asia/Jakarta')->format('d-m-Y H:i') }}</td>
                    <td>{{ str($interview->status)->replace('_', ' ')->title() }}</td>
                    <td>
                        @if ($interview->status === 'scheduled')
                            <form action="{{ route('hrd.interviews.evaluation.update', $interview) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <label for="score{{ $interview->id }}">Score (0-100)</label>
                                <input id="score{{ $interview->id }}" name="score" type="number" min="0" max="100" required>
                                <label for="notes{{ $interview->id }}">Evaluation notes</label>
                                <textarea id="notes{{ $interview->id }}" name="evaluation_notes" maxlength="10000" required></textarea>
                                <button type="submit">Complete evaluation</button>
                            </form>
                        @else
                            <p>Score: {{ $interview->score }}</p>
                            <p>{{ $interview->evaluation_notes }}</p>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No interviews are assigned to you.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection

@extends('layouts.app')

@section('title', 'Applications')

@section('content')
    <h1>Applications</h1>
    @if (session('success')) <p role="status">{{ session('success') }}</p> @endif
    @if ($errors->any())
        <ul role="alert">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    @endif
    <table>
        <thead><tr><th>Applicant</th><th>Job</th><th>Division</th><th>Submitted</th><th>Status and history</th><th>CV</th><th>Screening</th></tr></thead>
        <tbody>
            @forelse ($applications as $application)
                <tr>
                    <td>{{ $application->applicant->name }} ({{ $application->applicant->email }})</td>
                    <td>{{ $application->jobPosting->title }}</td>
                    <td>{{ $application->jobPosting->requisition->position->division->name }}</td>
                    <td>{{ $application->submitted_at->timezone('Asia/Jakarta')->format('d-m-Y H:i') }}</td>
                    <td>
                        <strong>{{ str($application->status)->replace('_', ' ')->title() }}</strong>
                        <details>
                            <summary>View status history</summary>
                            <ol>
                                @foreach ($application->statusHistories as $history)
                                    <li>
                                        {{ str($history->new_status)->replace('_', ' ')->title() }}
                                        — {{ $history->created_at->timezone('Asia/Jakarta')->format('d-m-Y H:i') }}
                                        @if ($history->changer) by {{ $history->changer->name }} @endif
                                        @if ($history->reason) <p>{{ $history->reason }}</p> @endif
                                    </li>
                                @endforeach
                            </ol>
                        </details>
                    </td>
                    <td><a href="{{ route('applications.cv', $application) }}">Download CV</a></td>
                    <td>
                        @if ($application->status === 'submitted')
                            <form action="{{ route('hrd.applications.screening.update', $application) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <label for="status{{ $application->id }}">Decision</label>
                                <select id="status{{ $application->id }}" name="status" required>
                                    <option value="screening_passed">Pass screening</option>
                                    <option value="screening_failed">Fail screening</option>
                                </select>
                                <label for="reason{{ $application->id }}">Decision note</label>
                                <textarea id="reason{{ $application->id }}" name="reason" maxlength="5000" required>{{ old('reason') }}</textarea>
                                <button type="submit">Save screening decision</button>
                            </form>
                        @else
                            <span>Screening complete</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">No applications received.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection

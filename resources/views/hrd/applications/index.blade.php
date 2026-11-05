@extends('layouts.app')

@section('title', 'Applications')

@section('content')
    <h1>Applications</h1>
    @if (session('success')) <p role="status">{{ session('success') }}</p> @endif
    @if ($errors->any())
        <ul role="alert">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    @endif
    <table>
        <thead><tr><th>Applicant</th><th>Job</th><th>Division</th><th>Submitted</th><th>Status and history</th><th>CV</th><th>Screening</th><th>Interview</th></tr></thead>
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
                    <td>
                        @if (in_array($application->status, ['screening_passed', 'interview'], true))
                            @foreach ($application->interviews as $interview)
                                <p>{{ $interview->scheduled_at->timezone('Asia/Jakarta')->format('d-m-Y H:i') }} — {{ $interview->interviewer->name }} ({{ $interview->status }})</p>
                            @endforeach
                            <details>
                                <summary>Schedule interview</summary>
                                <form action="{{ route('hrd.applications.interviews.store', $application) }}" method="POST">
                                    @csrf
                                    <label for="interviewer{{ $application->id }}">HRD interviewer</label>
                                    <select id="interviewer{{ $application->id }}" name="interviewer_id" required>
                                        <option value="">Select an interviewer</option>
                                        @foreach ($interviewers as $hrdUser)
                                            <option value="{{ $hrdUser->id }}">{{ $hrdUser->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="scheduled_at{{ $application->id }}">Date and time (Jakarta)</label>
                                    <input id="scheduled_at{{ $application->id }}" type="datetime-local" name="scheduled_at" min="{{ now()->timezone('Asia/Jakarta')->format('Y-m-d\TH:i') }}" required>
                                    <button type="submit">Schedule</button>
                                </form>
                            </details>
                        @else
                            <span>Available after screening pass</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8">No applications received.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection

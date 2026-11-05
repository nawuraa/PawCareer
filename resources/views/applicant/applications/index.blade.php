@extends('layouts.app')

@section('title', 'My applications')

@section('content')
    <h1>My applications</h1>
    @if (session('success')) <p role="status">{{ session('success') }}</p> @endif
    <table>
        <thead><tr><th>Job</th><th>Division</th><th>Submitted</th><th>Status and history</th><th>Interviews</th><th>CV</th></tr></thead>
        <tbody>
            @forelse ($applications as $application)
                <tr>
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
                                        @if ($history->reason) <p>{{ $history->reason }}</p> @endif
                                    </li>
                                @endforeach
                            </ol>
                        </details>
                    </td>
                    <td>
                        @forelse ($application->interviews as $interview)
                            <p>{{ $interview->scheduled_at->timezone('Asia/Jakarta')->format('d-m-Y H:i') }} — {{ $interview->status }}</p>
                        @empty
                            <span>No interview scheduled</span>
                        @endforelse
                    </td>
                    <td>
                        @if ($application->hiringDecision)
                            <strong>Final outcome: {{ str($application->hiringDecision->result)->title() }}</strong>
                            @if ($application->hiringDecision->start_date)
                                <p>Start date: {{ $application->hiringDecision->start_date->format('d-m-Y') }}</p>
                            @endif
                        @else
                            <span>No final decision yet</span>
                        @endif
                    </td>
                    <td><a href="{{ route('applications.cv', $application) }}">Download my CV</a></td>
                </tr>
            @empty
                <tr><td colspan="7">You have not submitted any applications.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection

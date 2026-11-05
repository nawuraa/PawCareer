@extends('layouts.app')

@section('title', 'My applications')

@section('content')
    <h1>My applications</h1>
    @if (session('success')) <p role="status">{{ session('success') }}</p> @endif
    <table>
        <thead><tr><th>Job</th><th>Division</th><th>Submitted</th><th>Status and history</th><th>CV</th></tr></thead>
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
                    <td><a href="{{ route('applications.cv', $application) }}">Download my CV</a></td>
                </tr>
            @empty
                <tr><td colspan="5">You have not submitted any applications.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection

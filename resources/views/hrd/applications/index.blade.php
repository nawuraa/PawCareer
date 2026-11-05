@extends('layouts.app')

@section('title', 'Applications')

@section('content')
    <h1>Applications</h1>
    <table>
        <thead><tr><th>Applicant</th><th>Job</th><th>Division</th><th>Submitted</th><th>Status</th><th>CV</th></tr></thead>
        <tbody>
            @forelse ($applications as $application)
                <tr>
                    <td>{{ $application->applicant->name }} ({{ $application->applicant->email }})</td>
                    <td>{{ $application->jobPosting->title }}</td>
                    <td>{{ $application->jobPosting->requisition->position->division->name }}</td>
                    <td>{{ $application->submitted_at->timezone('Asia/Jakarta')->format('d-m-Y H:i') }}</td>
                    <td>{{ str($application->status)->replace('_', ' ')->title() }}</td>
                    <td><a href="{{ route('applications.cv', $application) }}">Download CV</a></td>
                </tr>
            @empty
                <tr><td colspan="6">No applications received.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection

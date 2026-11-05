@extends('layouts.app')

@section('title', 'Open jobs')

@section('content')
    <h1>Open jobs</h1>
    <ul>
        @forelse ($jobPostings as $jobPosting)
            <li>
                <h2><a href="{{ route('applicant.jobs.show', $jobPosting) }}">{{ $jobPosting->title }}</a></h2>
                <p>{{ $jobPosting->requisition->position->division->name }} · {{ $jobPosting->quota }} position(s)</p>
                <p>Apply by {{ $jobPosting->deadline->format('d-m-Y') }}</p>
            </li>
        @empty
            <li>No open jobs are accepting applications right now.</li>
        @endforelse
    </ul>
@endsection

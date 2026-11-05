@extends('layouts.app')

@section('title', $jobPosting->title)

@section('content')
    <h1>{{ $jobPosting->title }}</h1>
    <p>{{ $jobPosting->requisition->position->division->name }} · {{ $jobPosting->quota }} position(s)</p>
    <p>Apply by {{ $jobPosting->deadline->format('d-m-Y') }}</p>
    <p>{{ $jobPosting->description }}</p>

    @if ($jobPosting->requirements)
        <h2>Requirements</h2>
        <p>{{ $jobPosting->requirements }}</p>
    @endif

    @if ($errors->any())
        <ul role="alert">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    @endif

    @if ($hasApplied)
        <p>You have already applied for this job.</p>
    @else
        <form action="{{ route('applications.store', $jobPosting) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="cv">CV (PDF, DOC, or DOCX; max 5 MB)</label>
            <input id="cv" name="cv" type="file" accept=".pdf,.doc,.docx" required>
            <button type="submit">Submit application</button>
        </form>
    @endif
@endsection

@extends('layouts.app')

@section('title', 'Applicant profile')

@section('content')
    <h1>Applicant profile</h1>
    <p>Your phone number and education must be complete before you can apply for a job.</p>

    @if (session('success')) <p role="status">{{ session('success') }}</p> @endif
    @if ($errors->any())
        <ul role="alert">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    @endif

    <form action="{{ route('applicant.profile.update') }}" method="POST">
        @csrf
        @method('PUT')
        <label for="phone">Phone number</label>
        <input id="phone" name="phone" maxlength="20" required value="{{ old('phone', $profile?->phone) }}">

        <label for="education">Education</label>
        <input id="education" name="education" maxlength="150" required value="{{ old('education', $profile?->education) }}">

        <button type="submit">Save profile</button>
    </form>
@endsection

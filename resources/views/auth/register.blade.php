@extends('layouts.guest')

@section('title', 'Create an account | PawCareer')

@section('content')
    <span class="eyebrow">Start your journey</span>
    <h2>Create your account</h2>
    <p class="intro">Register as an applicant to explore veterinary-care opportunities.</p>

    @if ($errors->any())
        <p class="alert" role="alert">{{ $errors->first() }}</p>
    @endif

    <form action="{{ route('register.post') }}" method="POST">
        @csrf
        <div class="field">
            <label for="name">Full name</label>
            <input id="name" name="name" type="text" autocomplete="name" value="{{ old('name') }}" placeholder="Your full name" maxlength="255" required autofocus>
            @error('name') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div class="field">
            <label for="email">Email address</label>
            <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" placeholder="you@example.com" maxlength="255" required>
            @error('email') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" autocomplete="new-password" placeholder="At least 8 characters" minlength="8" required>
            <p class="hint">Use at least 8 characters.</p>
            @error('password') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div class="field">
            <label for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Enter your password again" minlength="8" required>
        </div>
        <button class="submit" type="submit">Create applicant account</button>
    </form>

    <p class="switch">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
@endsection

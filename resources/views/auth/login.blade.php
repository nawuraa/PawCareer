@extends('layouts.guest')

@section('title', 'Sign in | PawCareer')

@section('content')
    <span class="eyebrow">Welcome back</span>
    <h2>Sign in to PawCareer</h2>
    <p class="intro">Continue your journey in veterinary care.</p>

    @if (session('status'))
        <p class="alert" role="status">{{ session('status') }}</p>
    @endif
    @if ($errors->any())
        <p class="alert" role="alert">{{ $errors->first() }}</p>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="field">
            <label for="email">Email address</label>
            <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus aria-describedby="@error('email') email-error @enderror">
            @error('email') <p class="error" id="email-error">{{ $message }}</p> @enderror
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" placeholder="Enter your password" required>
        </div>
        <label class="remember">
            <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
            <span>Remember me</span>
        </label>
        <button class="submit" type="submit">Sign in</button>
    </form>

    <p class="switch">New to PawCareer? <a href="{{ route('register') }}">Create an applicant account</a></p>
@endsection

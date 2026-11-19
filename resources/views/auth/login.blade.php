@extends('layouts.guest')

@section('title', 'Masuk | PawCareer')

@section('content')
    {{-- HEADER FORM --}}
    <span class="eyebrow">Selamat datang kembali</span>
    <h2>Masuk ke PawCareer</h2>
    <p class="intro">Lanjutkan perjalanan karier Anda bersama Klinik Hewan Sahabat Satwa.</p>

    {{-- PESAN STATUS & ERROR --}}
    @if (session('status'))
        <p class="alert" role="status">{{ session('status') }}</p>
    @endif
    @if ($errors->any())
        <p class="alert" role="alert">{{ $errors->first() }}</p>
    @endif

    {{-- FORM LOGIN --}}
    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="field">
            <label for="email">Alamat email</label>
            <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus aria-describedby="@error('email') email-error @enderror">
            @error('email') <p class="error" id="email-error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="password">Kata sandi</label>
            <input id="password" name="password" type="password" autocomplete="current-password" placeholder="Masukkan kata sandi" required>
        </div>

        <label class="remember">
            <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
            <span>Ingat saya</span>
        </label>

        <button class="submit" type="submit">Masuk</button>
    </form>

    {{-- LINK DAFTAR --}}
    <p class="switch">Belum punya akun PawCareer? <a href="{{ route('register') }}">Buat akun pelamar</a></p>
@endsection
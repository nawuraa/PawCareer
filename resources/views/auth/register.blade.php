@extends('layouts.guest')

@section('title', 'Buat Akun | PawCareer')

@section('content')
    {{-- HEADER FORM --}}
    <span class="eyebrow">Mulai perjalanan Anda</span>
    <h2>Buat akun pelamar</h2>
    <p class="intro">Daftar sebagai pelamar untuk menjelajahi peluang karier di Klinik Hewan Sahabat Satwa.</p>

    {{-- PESAN ERROR --}}
    @if ($errors->any())
        <p class="alert" role="alert">{{ $errors->first() }}</p>
    @endif

    {{-- FORM REGISTRASI --}}
    <form action="{{ route('register.post') }}" method="POST">
        @csrf
        <div class="field">
            <label for="name">Nama lengkap</label>
            <input id="name" name="name" type="text" autocomplete="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap Anda" maxlength="255" required autofocus>
            @error('name') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="email">Alamat email</label>
            <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" placeholder="nama@email.com" maxlength="255" required>
            @error('email') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="password">Kata sandi</label>
            <input id="password" name="password" type="password" autocomplete="new-password" placeholder="Minimal 8 karakter" minlength="8" required>
            <p class="hint">Gunakan minimal 8 karakter.</p>
            @error('password') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Konfirmasi kata sandi</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Ulangi kata sandi Anda" minlength="8" required>
        </div>

        <button class="submit" type="submit">Buat akun pelamar</button>
    </form>

    {{-- LINK LOGIN --}}
    <p class="switch">Sudah punya akun? <a href="{{ route('login') }}">Masuk sekarang</a></p>
@endsection
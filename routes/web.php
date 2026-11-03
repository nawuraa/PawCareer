<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:hrd'])->get('/hrd', function () {
    return response()->json(['message' => 'hrd ok']);
});

Route::middleware(['auth'])->get('/dashboard', function () {
    return response()->json(['message' => 'dashboard ok']);
});

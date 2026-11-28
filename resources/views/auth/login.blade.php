@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="card">
    <h1>Masuk</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label for="email">Email</label>
        <input id="email" name="email" type="email" maxlength="100" required value="{{ old('email') }}">

        <label for="password">Password</label>
        <input id="password" name="password" type="password" required autocomplete="current-password">

        <p><button type="submit">Masuk</button></p>
    </form>
    <p class="muted">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
</div>
@endsection

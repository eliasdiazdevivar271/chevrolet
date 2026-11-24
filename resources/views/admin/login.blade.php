@extends('layouts.admin')
@section('title', 'Ingresar')
@section('content')
    <section class="card narrow">
        <h1>Ingresar al panel</h1>
        <form method="post" action="{{ route('login.store') }}">
            @csrf
            <label for="email">Correo electrónico</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
            <label for="password">Contraseña</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
            <button type="submit">Ingresar</button>
        </form>
    </section>
@endsection

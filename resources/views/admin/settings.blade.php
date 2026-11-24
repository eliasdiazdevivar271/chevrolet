@extends('layouts.admin')
@section('title', 'Datos de contacto')
@section('content')
    <h1>Datos de contacto</h1>
    <form class="card form" action="{{ route('admin.settings.update') }}" method="post">
        @csrf @method('PUT')
        <label for="dealer_name">Nombre del negocio</label><input id="dealer_name" name="dealer_name" value="{{ old('dealer_name', $settings?->dealer_name) }}" maxlength="255" required>
        <label for="phone">Teléfono</label><input id="phone" name="phone" type="tel" value="{{ old('phone', $settings?->phone) }}" maxlength="255">
        <label for="email">Correo electrónico</label><input id="email" name="email" type="email" value="{{ old('email', $settings?->email) }}" maxlength="255">
        <label for="address">Dirección</label><input id="address" name="address" value="{{ old('address', $settings?->address) }}" maxlength="255">
        <label for="whatsapp">WhatsApp (solo números, incluido el código de país)</label><input id="whatsapp" name="whatsapp" type="tel" inputmode="numeric" pattern="[0-9]{8,15}" value="{{ old('whatsapp', $settings?->whatsapp) }}" maxlength="15" placeholder="595...">
        <div class="form-actions"><button type="submit">Guardar</button></div>
    </form>
@endsection

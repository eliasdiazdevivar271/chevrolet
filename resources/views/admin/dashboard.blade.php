@extends('layouts.admin')
@section('title', 'Panel')
@section('content')
    <h1>Panel de administración</h1>
    <p>Gestione el contenido del sitio y consulte los mensajes recibidos.</p>
    <div class="cards">
        <a class="card" href="{{ route('admin.vehicles.index') }}"><strong>{{ $vehicleCount }}</strong><span>Vehículos</span></a>
        <a class="card" href="{{ route('admin.promotions.index') }}"><strong>{{ $promotionCount }}</strong><span>Promociones</span></a>
        <a class="card" href="{{ route('admin.messages.index') }}"><strong>{{ $messageCount }}</strong><span>Mensajes</span></a>
        <a class="card" href="{{ route('admin.content.edit') }}"><strong>Contenido</strong><span>Inicio, Nosotros e imagen</span></a>
        <a class="card" href="{{ route('admin.settings.edit') }}"><strong>Contacto</strong><span>Datos del negocio</span></a>
    </div>
@endsection

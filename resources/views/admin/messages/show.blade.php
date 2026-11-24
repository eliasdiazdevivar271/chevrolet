@extends('layouts.admin')
@section('title', 'Mensaje')
@section('content')
    <h1>Mensaje recibido</h1>
    <article class="card message">
        <dl>
            <dt>Fecha</dt><dd>{{ $message->created_at->format('d/m/Y H:i') }}</dd>
            <dt>Nombre</dt><dd>{{ $message->name }}</dd>
            <dt>Correo electrónico</dt><dd>{{ $message->email }}</dd>
            <dt>Teléfono</dt><dd>{{ $message->phone ?: 'No indicado' }}</dd>
            <dt>Asunto</dt><dd>{{ $message->subject ?: 'Sin asunto' }}</dd>
            <dt>Mensaje</dt><dd class="message-text">{{ $message->message }}</dd>
        </dl>
        <a href="{{ route('admin.messages.index') }}">Volver a mensajes</a>
    </article>
@endsection

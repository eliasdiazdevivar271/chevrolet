@extends('layouts.admin')
@section('title', 'Mensajes')
@section('content')
    <h1>Mensajes recibidos</h1>
    <div class="card table-wrap">
        <table>
            <thead><tr><th>Fecha</th><th>Nombre</th><th>Correo</th><th>Asunto</th><th></th></tr></thead>
            <tbody>
                @forelse ($messages as $message)
                    <tr><td>{{ $message->created_at->format('d/m/Y H:i') }}</td><td>{{ $message->name }}</td><td>{{ $message->email }}</td><td>{{ $message->subject ?: 'Sin asunto' }}</td><td><a href="{{ route('admin.messages.show', $message) }}">Ver</a></td></tr>
                @empty
                    <tr><td colspan="5">No hay mensajes todavía.</td></tr>
                @endforelse
            </tbody>
        </table>
        @include('admin.pagination', ['paginator' => $messages])
    </div>
@endsection

@extends('layouts.admin')
@section('title', 'Promociones')
@section('content')
    <div class="page-heading"><h1>Promociones</h1><a class="button" href="{{ route('admin.promotions.create') }}">Añadir promoción</a></div>
    <div class="card table-wrap">
        <table>
            <thead><tr><th>Título</th><th>Desde</th><th>Hasta</th><th>Estado</th><th>Acciones</th></tr></thead>
            <tbody>
                @forelse ($promotions as $promotion)
                    <tr>
                        <td>{{ $promotion->title }}</td><td>{{ $promotion->starts_on?->format('d/m/Y') ?? 'Sin fecha' }}</td><td>{{ $promotion->ends_on?->format('d/m/Y') ?? 'Sin fecha' }}</td>
                        <td>{{ $promotion->active ? 'Activa' : 'Oculta' }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.promotions.edit', $promotion) }}">Editar</a>
                            <form action="{{ route('admin.promotions.destroy', $promotion) }}" method="post" onsubmit="return confirm('¿Eliminar esta promoción?')">@csrf @method('DELETE')<button class="link-button danger" type="submit">Eliminar</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No hay promociones todavía.</td></tr>
                @endforelse
            </tbody>
        </table>
        @include('admin.pagination', ['paginator' => $promotions])
    </div>
@endsection

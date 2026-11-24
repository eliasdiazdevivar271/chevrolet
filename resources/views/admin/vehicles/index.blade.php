@extends('layouts.admin')
@section('title', 'Vehículos')
@section('content')
    <div class="page-heading"><h1>Vehículos</h1><a class="button" href="{{ route('admin.vehicles.create') }}">Añadir vehículo</a></div>
    <div class="card table-wrap">
        <table>
            <thead><tr><th>Nombre</th><th>Categoría</th><th>Estado</th><th>Destacado</th><th>Acciones</th></tr></thead>
            <tbody>
                @forelse ($vehicles as $vehicle)
                    <tr>
                        <td>{{ $vehicle->name }}</td><td>{{ $vehicle->category }}</td>
                        <td>{{ $vehicle->active ? 'Publicado' : 'Oculto' }}</td><td>{{ $vehicle->featured ? 'Sí' : 'No' }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.vehicles.edit', $vehicle) }}">Editar</a>
                            <form action="{{ route('admin.vehicles.destroy', $vehicle) }}" method="post" onsubmit="return confirm('¿Eliminar este vehículo?')">@csrf @method('DELETE')<button class="link-button danger" type="submit">Eliminar</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No hay vehículos todavía.</td></tr>
                @endforelse
            </tbody>
        </table>
        @include('admin.pagination', ['paginator' => $vehicles])
    </div>
@endsection

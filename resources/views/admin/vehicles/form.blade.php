@extends('layouts.admin')
@section('title', $vehicle->exists ? 'Editar vehículo' : 'Añadir vehículo')
@section('content')
    <h1>{{ $vehicle->exists ? 'Editar vehículo' : 'Añadir vehículo' }}</h1>
    <form class="card form" method="post" enctype="multipart/form-data" action="{{ $vehicle->exists ? route('admin.vehicles.update', $vehicle) : route('admin.vehicles.store') }}">
        @csrf
        @if ($vehicle->exists) @method('PUT') @endif
        <label for="name">Nombre</label><input id="name" name="name" value="{{ old('name', $vehicle->name) }}" maxlength="255" required>
        <label for="category">Categoría</label><input id="category" name="category" value="{{ old('category', $vehicle->category) }}" maxlength="255" required>
        <label for="description">Descripción</label><textarea id="description" name="description" rows="6" maxlength="10000" required>{{ old('description', $vehicle->description) }}</textarea>
        <label for="price">Precio (opcional)</label><input id="price" name="price" value="{{ old('price', $vehicle->price) }}" maxlength="255">
        <label for="image">Imagen (opcional: JPG, PNG o WebP, máximo 4 MB)</label><input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
        @if ($vehicle->image_path)<p>Imagen actual: <a href="{{ asset($vehicle->image_path) }}" target="_blank" rel="noopener">Ver imagen</a>. Deje el campo vacío para conservarla.</p>@endif
        <input type="hidden" name="featured" value="0"><label class="check"><input type="checkbox" name="featured" value="1" @checked(old('featured', $vehicle->featured))> Destacado en inicio</label>
        <input type="hidden" name="active" value="0"><label class="check"><input type="checkbox" name="active" value="1" @checked(old('active', $vehicle->exists ? $vehicle->active : true))> Publicado</label>
        <div class="form-actions"><button type="submit">Guardar</button><a href="{{ route('admin.vehicles.index') }}">Cancelar</a></div>
    </form>
@endsection

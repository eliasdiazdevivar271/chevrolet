@extends('layouts.admin')
@section('title', $promotion->exists ? 'Editar promoción' : 'Añadir promoción')
@section('content')
    <h1>{{ $promotion->exists ? 'Editar promoción' : 'Añadir promoción' }}</h1>
    <form class="card form" method="post" enctype="multipart/form-data" action="{{ $promotion->exists ? route('admin.promotions.update', $promotion) : route('admin.promotions.store') }}">
        @csrf
        @if ($promotion->exists) @method('PUT') @endif
        <label for="title">Título</label><input id="title" name="title" value="{{ old('title', $promotion->title) }}" maxlength="255" required>
        <label for="description">Descripción</label><textarea id="description" name="description" rows="6" maxlength="10000" required>{{ old('description', $promotion->description) }}</textarea>
        <label for="starts_on">Fecha inicial (opcional)</label><input id="starts_on" name="starts_on" type="date" value="{{ old('starts_on', $promotion->starts_on?->format('Y-m-d')) }}">
        <label for="ends_on">Fecha final (opcional)</label><input id="ends_on" name="ends_on" type="date" value="{{ old('ends_on', $promotion->ends_on?->format('Y-m-d')) }}">
        <label for="image">Imagen (opcional: JPG, PNG o WebP, máximo 4 MB)</label><input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
        @if ($promotion->image_path)<p>Imagen actual: <a href="{{ asset($promotion->image_path) }}" target="_blank" rel="noopener">Ver imagen</a>. Deje el campo vacío para conservarla.</p>@endif
        <input type="hidden" name="active" value="0"><label class="check"><input type="checkbox" name="active" value="1" @checked(old('active', $promotion->exists ? $promotion->active : true))> Publicada durante su vigencia</label>
        <div class="form-actions"><button type="submit">Guardar</button><a href="{{ route('admin.promotions.index') }}">Cancelar</a></div>
    </form>
@endsection

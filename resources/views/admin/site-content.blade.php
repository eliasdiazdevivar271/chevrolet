@extends('layouts.admin')
@section('title', 'Contenido del sitio')
@section('content')
    <h1>Contenido del sitio</h1>
    <p>Edite los textos principales y la imagen de portada. Los vehículos y promociones se administran en sus propias secciones.</p>
    <form class="card form" method="post" enctype="multipart/form-data" action="{{ route('admin.content.update') }}">
        @csrf @method('PUT')
        <label for="home_title">Título de inicio</label>
        <input id="home_title" name="home_title" value="{{ old('home_title', $settings?->home_title ?: 'Tu próximo Chevrolet.') }}" maxlength="120" required>
        <label for="home_description">Descripción de inicio</label>
        <textarea id="home_description" name="home_description" rows="3" maxlength="500" required>{{ old('home_description', $settings?->home_description ?: 'Conocé nuestros vehículos y promociones, y consultanos por el modelo que va con vos.') }}</textarea>
        <label for="about_title">Título de Nosotros</label>
        <input id="about_title" name="about_title" value="{{ old('about_title', $settings?->about_title ?: 'Te ayudamos a elegir tu Chevrolet.') }}" maxlength="120" required>
        <label for="about_description">Descripción de Nosotros</label>
        <textarea id="about_description" name="about_description" rows="5" maxlength="1500" required>{{ old('about_description', $settings?->about_description ?: 'Te acompañamos para conocer los modelos Chevrolet, comparar sus características y resolver tus consultas.') }}</textarea>
        <label for="hero_image">Imagen principal (opcional: JPG, PNG o WebP, máximo 4 MB)</label>
        <input id="hero_image" name="hero_image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
        @if($settings?->hero_image_path)
            <p>Imagen actual: <a href="{{ asset($settings->hero_image_path) }}" target="_blank" rel="noopener">Ver imagen</a>. Deje el campo vacío para conservarla.</p>
        @endif
        <div class="form-actions"><button type="submit">Guardar</button><a href="{{ route('admin.dashboard') }}">Cancelar</a></div>
    </form>
@endsection

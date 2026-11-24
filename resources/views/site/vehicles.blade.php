@extends('layouts.public')

@section('title', 'Vehículos')

@section('content')
<section class="page-intro">
    <div class="container"><div><p class="eyebrow">Catálogo</p><h1>Nuestros <em>vehículos.</em></h1><p>Explorá la gama Chevrolet y consultanos por el modelo que te interesa.</p></div></div>
</section>
<section class="section catalog-section">
    <div class="container">
        <div class="catalog-toolbar">
            <h2>Vehículos disponibles<span class="gold-dot">.</span></h2>
            @if($vehicles->isNotEmpty())
                <div class="category-filters" role="group" aria-label="Filtrar por categoría"><button class="filter-button is-active" type="button" data-filter="all" aria-pressed="true">Todos</button>@foreach($vehicles->pluck('category')->filter()->unique() as $category)<button class="filter-button" type="button" data-filter="{{ $category }}" aria-pressed="false">{{ $category }}</button>@endforeach</div>
            @endif
        </div>
        <div class="vehicle-grid" id="vehicle-list">
            @forelse($vehicles as $vehicle)
                @include('site.partials.vehicle-card', ['vehicle' => $vehicle])
            @empty
                <div class="empty-state">Nuestro catálogo estará disponible próximamente. <a href="{{ route('contact.show') }}">Contactanos para más información.</a></div>
            @endforelse
        </div>
        <p class="catalog-note">Las imágenes son ilustrativas. Consultá versiones, precios y disponibilidad.</p>
    </div>
</section>
@endsection

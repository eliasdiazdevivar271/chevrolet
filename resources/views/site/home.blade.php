@extends('layouts.public')

@section('title', 'Inicio')

@section('content')
<section class="hero">
    <div class="container hero-inner">
        <div class="hero-copy">
            <p class="eyebrow light">Concesionaria Chevrolet</p>
            <h1>{{ $siteSettings?->home_title ?: 'Tu próximo Chevrolet.' }}</h1>
            <p>{{ $siteSettings?->home_description ?: 'Conocé nuestros vehículos y promociones, y consultanos por el modelo que va con vos.' }}</p>
            <div class="actions"><a class="button button-gold" href="{{ route('vehicles.index') }}">Ver vehículos <span>→</span></a><a class="text-link light-link" href="{{ route('contact.show') }}">Hacer una consulta <span>→</span></a></div>
        </div>
        <div class="hero-image" role="img" aria-label="Vehículo Chevrolet ilustrativo" @if($siteSettings?->hero_image_path) style="background-image: url('{{ asset($siteSettings->hero_image_path) }}')" @endif></div>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="section-heading"><div><p class="eyebrow">Destacados</p><h2>Encontrá tu vehículo.</h2></div><a class="text-link" href="{{ route('vehicles.index') }}">Ver todos <span>→</span></a></div>
        <div class="vehicle-grid">
            @forelse($vehicles as $vehicle)
                @include('site.partials.vehicle-card', ['vehicle' => $vehicle])
            @empty
                <div class="empty-state">Estamos preparando nuestro catálogo. <a href="{{ route('contact.show') }}">Contactanos para más información.</a></div>
            @endforelse
        </div>
    </div>
</section>
<section class="section white">
    <div class="container">
        <div class="section-heading"><div><p class="eyebrow">Oportunidades</p><h2>Promociones disponibles.</h2></div><a class="text-link" href="{{ route('promotions.index') }}">Ver promociones <span>→</span></a></div>
        @if($promotions->isNotEmpty())
            <div class="promo-grid">
                @foreach($promotions as $promotion)
                    @include('site.partials.promotion-card', ['promotion' => $promotion])
                @endforeach
            </div>
        @else
            <div class="promo-empty"><div><h3>Consultanos por las oportunidades disponibles.</h3><p>Escribinos y te ayudamos a elegir tu próximo Chevrolet.</p></div><a class="button" href="{{ route('contact.show') }}">Contactar <span>→</span></a></div>
        @endif
    </div>
</section>
@endsection

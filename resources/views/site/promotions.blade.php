@extends('layouts.public')

@section('title', 'Promociones')

@section('content')
<section class="page-intro">
    <div class="container"><div><p class="eyebrow">Oportunidades</p><h1>Promociones <em>Chevrolet.</em></h1><p>Descubrí las oportunidades disponibles y consultanos por sus condiciones.</p></div></div>
</section>
<section class="section">
    <div class="container">
        <div class="section-heading"><div><p class="eyebrow">Vigentes</p><h2>Promociones disponibles.</h2></div><p>Consultá disponibilidad y condiciones con nuestro equipo.</p></div>
        @if($promotions->isNotEmpty())
            <div class="promo-grid">
                @foreach($promotions as $promotion)
                    @include('site.partials.promotion-card', ['promotion' => $promotion])
                @endforeach
            </div>
        @else
            <div class="promo-empty"><div><h3>Pronto habrá novedades.</h3><p>Mientras tanto, escribinos para conocer las oportunidades disponibles.</p></div><a class="button" href="{{ route('contact.show') }}">Contactanos <span>→</span></a></div>
        @endif
    </div>
</section>
@endsection

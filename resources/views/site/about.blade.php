@extends('layouts.public')

@section('title', 'Nosotros')

@section('content')
<section class="page-intro">
    <div class="container"><div><p class="eyebrow">Nosotros</p><h1>{{ $siteSettings?->about_title ?: 'Te ayudamos a elegir tu Chevrolet.' }}</h1><p>Conocé nuestras opciones y recibí asesoramiento para encontrar el vehículo que necesitás.</p></div></div>
</section>
<section class="section">
    <div class="container about-section">
        <div class="about-copy">
            <p class="eyebrow">Estamos para vos</p>
            <h2>Un vehículo para cada necesidad.</h2>
            <p>{{ $siteSettings?->about_description ?: 'Te acompañamos para conocer los modelos Chevrolet, comparar sus características y resolver tus consultas.' }}</p>
            <p>Acercate a nuestro equipo para recibir atención personalizada antes y después de tu compra.</p>
            <a class="button" href="{{ route('contact.show') }}">Hablemos <span>→</span></a>
        </div>
        <div class="about-image" @if($siteSettings?->hero_image_path) style="background-image: linear-gradient(0deg, #0009, transparent 70%), url('{{ asset($siteSettings->hero_image_path) }}')" @endif><div class="about-image-caption"><span>CHEVROLET</span><strong>Elegí tu próximo camino.</strong></div></div>
    </div>
</section>
<section class="section white">
    <div class="container">
        <div class="section-heading"><div><p class="eyebrow">Cómo trabajamos</p><h2>Atención simple y cercana.</h2></div></div>
        <div class="values-grid">
            <article><span>01</span><h3>Escuchamos</h3><p>Queremos conocer tus necesidades y el uso que le darás a tu vehículo.</p></article>
            <article><span>02</span><h3>Asesoramos</h3><p>Te ayudamos a comparar modelos, versiones y formas de pago.</p></article>
            <article><span>03</span><h3>Acompañamos</h3><p>Estamos disponibles para responder tus consultas y dudas.</p></article>
        </div>
    </div>
</section>
@endsection

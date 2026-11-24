<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Conocé vehículos Chevrolet, promociones y opciones de contacto.">
    <title>@yield('title', 'Inicio') | Chevrolet</title>
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    <script src="{{ asset('js/site.js') }}" defer></script>
</head>
<body>
    <a class="skip-link" href="#contenido">Ir al contenido</a>
    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="{{ route('home') }}" aria-label="Chevrolet, ir al inicio">
                <svg class="brand-mark" viewBox="0 0 100 38" aria-hidden="true"><path d="M29 4h42l4 10h19v10H75l-4 10H29l-4-10H6V14h19z" fill="#d7b871" stroke="#eee0ba" stroke-width="3"/><path d="M30 8h40l3 10h17v2H73l-3 10H30l-3-10H10v-2h17z" fill="#b89046"/></svg>
                <span class="brand-name">CHEVROLET <small>CONCESIONARIA</small></span>
            </a>
            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav">Menú</button>
            <nav class="main-nav" id="main-nav" aria-label="Navegación principal">
                <a class="{{ request()->routeIs('home') ? 'is-active' : '' }}" href="{{ route('home') }}">Inicio</a>
                <a class="{{ request()->routeIs('about') ? 'is-active' : '' }}" href="{{ route('about') }}">Nosotros</a>
                <a class="{{ request()->routeIs('vehicles.*') ? 'is-active' : '' }}" href="{{ route('vehicles.index') }}">Vehículos</a>
                <a class="{{ request()->routeIs('promotions.*') ? 'is-active' : '' }}" href="{{ route('promotions.index') }}">Promociones</a>
                <a class="{{ request()->routeIs('contact.*') ? 'is-active' : '' }}" href="{{ route('contact.show') }}">Contacto</a>
            </nav>
        </div>
    </header>

    <main id="contenido">
        @yield('content')
        <footer class="footer">
            <div class="container">
                <div class="footer-top">
                    <div><span class="eyebrow light">CHEVROLET</span><h2>Tu próximo camino empieza acá.</h2><p>Consultanos por vehículos y oportunidades disponibles.</p></div>
                    <a class="button button-gold" href="{{ route('contact.show') }}">Contactanos <span>→</span></a>
                </div>
                <div class="footer-bottom">
                    <div class="footer-links">
                        <a href="{{ route('home') }}">Inicio</a>
                        <a href="{{ route('about') }}">Nosotros</a>
                        <a href="{{ route('vehicles.index') }}">Vehículos</a>
                        <a href="{{ route('promotions.index') }}">Promociones</a>
                    </div>
                    <div class="footer-details">
                        @if($siteSettings?->phone)<a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings->phone) }}">{{ $siteSettings->phone }}</a>@endif
                        @if($siteSettings?->email)<a href="mailto:{{ $siteSettings->email }}">{{ $siteSettings->email }}</a>@endif
                        @if($siteSettings?->address)<span>{{ $siteSettings->address }}</span>@endif
                        <a href="{{ route('contact.show') }}">Contacto</a>
                        <a href="{{ route('login') }}">Administración</a>
                    </div>
                </div>
                <div class="footer-legal"><span>© {{ date('Y') }} {{ $siteSettings?->dealer_name ?: 'Chevrolet' }}</span><span>Imágenes ilustrativas. Consultá disponibilidad.</span></div>
            </div>
        </footer>
    </main>
</body>
</html>

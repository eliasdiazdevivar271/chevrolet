<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Administración') | Chevrolet</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <header class="header">
        <div class="container header-inner">
            <a class="brand" href="{{ route('admin.dashboard') }}">Chevrolet <span>Administración</span></a>
            @auth
                <nav aria-label="Menú de administración">
                    <a href="{{ route('admin.dashboard') }}">Inicio</a>
                    <a href="{{ route('admin.vehicles.index') }}">Vehículos</a>
                    <a href="{{ route('admin.promotions.index') }}">Promociones</a>
                    <a href="{{ route('admin.messages.index') }}">Mensajes</a>
                    <a href="{{ route('admin.content.edit') }}">Contenido</a>
                    <a href="{{ route('admin.settings.edit') }}">Contacto</a>
                    <a href="{{ route('home') }}">Ver sitio</a>
                    <form action="{{ route('logout') }}" method="post">@csrf <button class="link-button" type="submit">Salir</button></form>
                </nav>
            @endauth
        </div>
    </header>
    <main class="container main">
        @if (session('status')) <div class="notice" role="status">{{ session('status') }}</div> @endif
        @if ($errors->any())
            <div class="notice error" role="alert"><strong>Revise los datos:</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        @yield('content')
    </main>
</body>
</html>

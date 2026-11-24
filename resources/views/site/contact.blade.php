@extends('layouts.public')

@section('title', 'Contacto')

@section('content')
<section class="page-intro">
    <div class="container"><div><p class="eyebrow">Contacto</p><h1>Estamos para <em>ayudarte.</em></h1><p>Consultá por vehículos, promociones o cualquier información que necesites.</p></div></div>
</section>
<section class="section">
    <div class="container contact-section">
        <div class="contact-details">
            <p class="eyebrow">Nuestros datos</p>
            <h2>Hablemos.</h2>
            <p>Escribinos por el medio que prefieras o completá el formulario. Te responderemos a la brevedad.</p>
            <div class="contact-methods">
                @if($settings?->phone)<div><span>Teléfono</span><a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings->phone) }}">{{ $settings->phone }}</a></div>@endif
                @if($settings?->email)<div><span>Correo electrónico</span><a href="mailto:{{ $settings->email }}">{{ $settings->email }}</a></div>@endif
                @if($settings?->address)<div><span>Dirección</span><strong>{{ $settings->address }}</strong></div>@endif
                @if($settings?->whatsapp)<div><span>WhatsApp</span><a href="https://wa.me/{{ preg_replace('/\D/', '', $settings->whatsapp) }}" target="_blank" rel="noopener noreferrer">Escribinos por WhatsApp</a></div>@endif
                @if(!$settings?->phone && !$settings?->email && !$settings?->address && !$settings?->whatsapp)<div><span>Escribinos</span><strong>Dejanos tu consulta en el formulario.</strong></div>@endif
            </div>
        </div>
        <div class="contact-form-wrap">
            <div class="form-header"><h2>Dejanos un mensaje.</h2><p>Completá tus datos y contanos cómo podemos ayudarte.</p></div>
            @if(session('success'))<div class="form-success" role="status">{{ session('success') }}</div>@endif
            @if($errors->any())<div class="form-error" role="alert">Revisá los campos señalados e intentá nuevamente.</div>@endif
            <form action="{{ route('contact.store') }}" method="post" class="contact-form">
                @csrf
                <div class="form-row">
                    <div class="field"><label for="name">Nombre y apellido <span>*</span></label><input id="name" name="name" value="{{ old('name') }}" maxlength="120" required autocomplete="name" placeholder="Tu nombre">@error('name')<small>{{ $message }}</small>@enderror</div>
                    <div class="field"><label for="phone">Teléfono</label><input id="phone" name="phone" value="{{ old('phone') }}" maxlength="40" autocomplete="tel" placeholder="Tu número">@error('phone')<small>{{ $message }}</small>@enderror</div>
                </div>
                <div class="field"><label for="email">Correo electrónico <span>*</span></label><input id="email" name="email" type="email" value="{{ old('email') }}" maxlength="255" required autocomplete="email" placeholder="tu@email.com">@error('email')<small>{{ $message }}</small>@enderror</div>
                <div class="field"><label for="subject">Asunto</label><input id="subject" name="subject" value="{{ old('subject', $subject) }}" maxlength="150" placeholder="¿En qué podemos ayudarte?">@error('subject')<small>{{ $message }}</small>@enderror</div>
                <div class="field"><label for="message">Mensaje <span>*</span></label><textarea id="message" name="message" rows="5" maxlength="5000" required placeholder="Escribí tu consulta acá">{{ old('message') }}</textarea>@error('message')<small>{{ $message }}</small>@enderror</div>
                <div class="form-footer"><span>* Campos obligatorios</span><button type="submit" class="button">Enviar mensaje <span>→</span></button></div>
            </form>
        </div>
    </div>
</section>
@endsection

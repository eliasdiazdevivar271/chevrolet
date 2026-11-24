<article class="vehicle-card" data-category="{{ $vehicle->category }}">
    <div class="vehicle-image">
        <span class="card-index">{{ $vehicle->category }}</span>
        @if($vehicle->image_path)
            <img src="{{ asset($vehicle->image_path) }}" alt="{{ $vehicle->name }}" loading="lazy">
        @else
            <div class="image-placeholder">CHEVROLET</div>
        @endif
    </div>
    <div class="vehicle-info">
        <h3>{{ $vehicle->name }}</h3>
        <p>{{ $vehicle->description }}</p>
        <div class="card-bottom"><span>{{ $vehicle->price ?: 'Precio a consultar' }}</span><a href="{{ route('contact.show', ['vehiculo' => $vehicle->name]) }}" aria-label="Consultar por {{ $vehicle->name }}">Consultar <span>→</span></a></div>
    </div>
</article>

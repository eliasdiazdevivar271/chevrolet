<article class="promo-card">
    @if($promotion->image_path)<div class="promo-image"><img src="{{ asset($promotion->image_path) }}" alt="" loading="lazy"></div>@endif
    <div class="promo-content">
        <h3>{{ $promotion->title }}</h3>
        <p>{{ $promotion->description }}</p>
        @if($promotion->ends_on)<small>Válida hasta el {{ $promotion->ends_on->format('d/m/Y') }}.</small>@endif
        <a class="text-link" href="{{ route('contact.show', ['vehiculo' => $promotion->title]) }}">Consultar promoción <span>→</span></a>
    </div>
</article>

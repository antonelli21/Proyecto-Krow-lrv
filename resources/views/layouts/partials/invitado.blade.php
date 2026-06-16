<div class="panel-card cta-card">
    <p class="panel-card-title">Encontrá tu primer trabajo</p>
    <p style="margin-bottom:16px;">
        Registrate gratis y accedé a cientos de ofertas para estudiantes UTN.
    </p>
    <a href="{{ url('/registro') }}" class="btn-primary-sm" style="display:block;text-align:center;margin-bottom:8px;">
        Crear cuenta
    </a>
    <a href="{{ url('/login') }}" class="btn-ghost-sm" style="display:block;text-align:center;">
        Ya tengo cuenta
    </a>
</div>
<div class="panel-card featured-card">
    <div class="featured-badge"><i class="bi bi-star-fill"></i> Destacado</div>
    <p class="featured-title">Senior Backend Engineer</p>
    <p class="featured-company">MegaCorp Technologies</p>
    <button class="btn-quick-apply" onclick="location.href='{{ url('/login') }}'">
        Postularme rápido
    </button>
</div>
<div class="panel-card">
    <p class="panel-card-title">Panel Empresa</p>
    <div class="stat-row">
        <span class="stat-label">Ofertas activas</span>
        <span class="stat-value">7</span>
    </div>
    <div class="stat-row">
        <span class="stat-label">Postulantes recibidos</span>
        <span class="stat-value">143</span>
    </div>
    
    <button class="btn-new-offer" onclick="location.href='{{ route('empresa.crear-oferta') }}'">
        + Nueva Oferta
    </button>
</div>
<div class="panel-card">
    <p class="panel-card-title">Postulantes destacados</p>
    <div class="companies-grid">
        <div class="company-thumb"><span>MA</span></div>
        <div class="company-thumb"><span>LG</span></div>
        <div class="company-thumb"><span>RD</span></div>
        <div class="company-thumb"><span>SV</span></div>
    </div>
</div>
<div class="panel-card">
    <p class="panel-card-title">Tu perfil de empresa</p>
    <p style="font-size:0.85rem;color:var(--muted);margin-bottom:12px;">
        Mantené tu perfil actualizado para atraer mejores candidatos.
    </p>
    <a href="{{ url('/empresa/perfil') }}" class="btn-apply-filters" style="display:block;text-align:center;text-decoration:none;">
        <i class="bi bi-building"></i> Ver mi perfil
    </a>
</div>
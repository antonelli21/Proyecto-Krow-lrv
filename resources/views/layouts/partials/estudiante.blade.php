<div class="panel-card">
    <p class="panel-card-title">Mis Estadísticas</p>
    <div class="stat-row">
        <span class="stat-label">Postulaciones enviadas</span>
        <span class="stat-value">{{ $panelData['postulaciones'] ?? 0 }}</span>
    </div>
    <div class="stat-row">
        <span class="stat-label">En revisión</span>
        <span class="stat-value">{{ $panelData['en_revision'] ?? 0 }}</span>
    </div>
    <div class="stat-row">
        <span class="stat-label">Empresas que te contactaron</span>
        <span class="stat-value">{{ $panelData['contactado'] ?? 0 }}</span>
    </div>
</div>
<div class="panel-card">
    <p class="panel-card-title">Últimas ofertas</p>
    <div class="recent-offers" style="display:flex;flex-direction:column;gap:12px;">
        @if(isset($panelData['ultimas_ofertas']) && $panelData['ultimas_ofertas']->count() > 0)
        @foreach($panelData['ultimas_ofertas'] as $oferta)
        <a href="{{ route('ofertas.detalle', $oferta->id_oferta) }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
            <div class="company-thumb" style="width:32px;height:32px;font-size:0.7rem;">
                @if($oferta->empresa && $oferta->empresa->logo)
                <img src="{{ asset('storage/' . $oferta->empresa->logo) }}" alt="Logo" style="width:100%;height:100%;object-fit:cover;">
                @else
                <span>{{ strtoupper(substr($oferta->empresa->nombre_empresa ?? '?', 0, 2)) }}</span>
                @endif
            </div>
            <div>
                <div style="font-size:0.85rem;color:var(--text);font-weight:600;line-height:1.2;">{{ $oferta->titulo }}</div>
                <div style="font-size:0.75rem;color:var(--muted);">{{ $oferta->empresa->nombre_empresa ?? 'KROW' }}</div>
            </div>
        </a>
        @endforeach
        @else
        <p style="font-size:0.85rem;color:var(--muted);">No hay ofertas recientes.</p>
        @endif
    </div>
</div>
<div class="panel-card">
    <p class="panel-card-title">Tu perfil</p>
    <p style="font-size:0.85rem;color:var(--muted);margin-bottom:12px;">
        Completá tu perfil para que las empresas te encuentren.
    </p>
    <a href="{{ url('/estudiante/perfil') }}" class="btn-apply-filters" style="display:block;text-align:center;text-decoration:none;">
        <i class="bi bi-person-circle"></i> Ver mi perfil
    </a>
</div>
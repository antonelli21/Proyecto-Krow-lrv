<div class="panel-card">
    <p class="panel-card-title">Mis Estadísticas</p>
    <div class="stat-row">
        <span class="stat-label">Postulaciones enviadas</span>
        <span class="stat-value">{{ $panelData['postulaciones'] ?? 0 }}</span>
    </div>
    <div class="stat-row">
        <span class="stat-label">En revisión</span>
        <span class="stat-value">{{ $panelData['preseleccionado'] ?? 0 }}</span>
    </div>
    <div class="stat-row">
        <span class="stat-label">Empresas que te contactaron</span>
        <span class="stat-value">{{ $panelData['contactado'] ?? 0 }}</span>
    </div>
</div>
<div class="panel-card">
    <p class="panel-card-title">Últimas ofertas</p>
    <div style="display:flex;flex-direction:column;gap:4px;">
        @if(isset($panelData['ultimas_ofertas']) && $panelData['ultimas_ofertas']->count() > 0)
            @foreach($panelData['ultimas_ofertas'] as $oferta)
            <a href="{{ route('ofertas.detalle', $oferta->id_oferta) }}"
               class="panel-person-row">
                {{-- Logo empresa --}}
                <div class="panel-avatar" style="border-radius:var(--radius);">
                    @if($oferta->empresa && $oferta->empresa->logo)
                        <img src="{{ asset('storage/' . $oferta->empresa->logo) }}"
                             alt="Logo"
                             style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <span style="font-size:0.65rem;">{{ strtoupper(substr($oferta->empresa->nombre_empresa ?? '?', 0, 2)) }}</span>
                    @endif
                </div>
                {{-- Info --}}
                <div class="panel-person-info">
                    <span class="panel-person-name">{{ $oferta->titulo }}</span>
                    <span class="panel-person-sub">{{ $oferta->empresa->nombre_empresa ?? 'KROW' }}</span>
                </div>
                <i class="bi bi-chevron-right panel-chevron"></i>
            </a>
            @endforeach
        @else
            <p style="font-size:0.85rem;color:var(--muted);padding:4px 0;">No hay ofertas recientes.</p>
        @endif
    </div>
</div>
<div class="panel-card" style="padding:0;overflow:hidden;">

    {{-- Header con fondo accent-dim --}}
    <div style="
        padding:14px 16px 12px;
        background:var(--accent-dim);
        border-bottom:1px solid var(--border-accent);
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:8px;
    ">
        <p class="panel-card-title" style="margin:0;">Tu perfil</p>
        @php $completitud = $panelData['completitud'] ?? 0; @endphp
        <span style="
            font-family:var(--font-display);
            font-size:1.1rem;
            font-weight:800;
            color:{{ $completitud >= 80 ? 'var(--accent)' : ($completitud >= 50 ? '#f59e0b' : '#ef4444') }};
        ">{{ $completitud }}%</span>
    </div>

    {{-- Barra de progreso --}}
    <div style="height:4px;background:var(--border);width:100%;">
        <div style="
            height:100%;
            width:{{ $completitud }}%;
            background:{{ $completitud >= 80 ? 'var(--accent)' : ($completitud >= 50 ? '#f59e0b' : '#ef4444') }};
            transition:width .4s ease;
        "></div>
    </div>

    <div style="padding:14px 16px 16px;display:flex;flex-direction:column;gap:10px;">

        {{-- Advertencias --}}
        @if($panelData['sin_cv'] ?? false)
        <div style="
            display:flex;
            align-items:center;
            gap:8px;
            padding:8px 12px;
            background:rgba(239,68,68,.08);
            border:1px solid rgba(239,68,68,.25);
            border-radius:var(--radius);
            font-size:0.78rem;
            color:#ef4444;
        ">
            <i class="bi bi-exclamation-triangle-fill" style="flex-shrink:0;"></i>
            CV no cargado — requerido para postularte
        </div>
        @endif

        @if($completitud < 80)
        <div style="
            display:flex;
            align-items:center;
            gap:8px;
            padding:8px 12px;
            background:var(--bg-hover);
            border:1px solid var(--border);
            border-radius:var(--radius);
            font-size:0.78rem;
            color:var(--muted);
        ">
            <i class="bi bi-person-check" style="flex-shrink:0;color:var(--accent);"></i>
            Completá tu perfil para destacar ante las empresas
        </div>
        @endif

        {{-- Botón --}}
        <a href="{{ url('/estudiante/perfil') }}" class="btn-apply-filters" style="
            display:flex;
            align-items:center;
            justify-content:center;
            gap:6px;
            text-decoration:none;
            margin-top:2px;
        ">
            <i class="bi bi-person-circle"></i>
            {{ $completitud >= 80 ? 'Ver mi perfil' : 'Completar perfil' }}
        </a>

    </div>
</div>

<style>
.panel-person-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    border-radius: var(--radius);
    border: 1px solid transparent;
    text-decoration: none;
    transition: background .15s ease, border-color .15s ease;
}
.panel-person-row:hover {
    background: var(--bg-hover);
    border-color: var(--border);
}
.panel-person-row:hover .panel-chevron {
    color: var(--accent);
}
.panel-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    background: var(--surface);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--accent);
}
.panel-person-info {
    display: flex;
    flex-direction: column;
    min-width: 0;
}
.panel-person-name {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.3;
}
.panel-person-sub {
    font-size: 0.75rem;
    color: var(--muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.panel-chevron {
    margin-left: auto;
    font-size: 0.75rem;
    color: var(--muted);
    flex-shrink: 0;
    transition: color .15s ease;
}
</style>
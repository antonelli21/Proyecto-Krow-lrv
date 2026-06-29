{{-- ── STATS ─────────────────────────────────────────────── --}}
<div class="panel-card">
    <p class="panel-card-title">Panel Empresa</p>
    <div class="stat-row">
        <span class="stat-label">Ofertas activas</span>
        <span class="stat-value">{{ $panelData['ofertas_activas'] ?? 0 }}</span>
    </div>
    <div class="stat-row">
        <span class="stat-label">Postulantes recibidos</span>
        <span class="stat-value">{{ $panelData['postulantes_recibidos'] ?? 0 }}</span>
    </div>
    <button class="btn-new-offer" onclick="location.href='{{ route('empresa.crear-oferta') }}'">
        + Nueva Oferta
    </button>
</div>

{{-- ── ÚLTIMOS POSTULANTES ────────────────────────────────── --}}
<div class="panel-card">
    <p class="panel-card-title">Últimos postulantes</p>
    <div style="display:flex;flex-direction:column;gap:4px;">
        @if(isset($panelData['ultimos_postulantes']) && $panelData['ultimos_postulantes']->count() > 0)
            @foreach($panelData['ultimos_postulantes'] as $postulacion)
            <a href="{{ route('empresa.ofertas.postulantes', $postulacion->id_oferta) }}"
               class="panel-person-row">
                {{-- Avatar --}}
                <div class="panel-avatar">
                    @if(!empty($postulacion->estudiante->foto_perfil))
                        <img src="{{ asset('storage/' . $postulacion->estudiante->foto_perfil) }}"
                             alt="Foto"
                             style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <span>{{ strtoupper(substr($postulacion->estudiante->nombre ?? '?', 0, 1)) }}{{ strtoupper(substr($postulacion->estudiante->apellido ?? '', 0, 1)) }}</span>
                    @endif
                </div>
                {{-- Info --}}
                <div class="panel-person-info">
                    <span class="panel-person-name">
                        {{ trim(($postulacion->estudiante->nombre ?? '') . ' ' . ($postulacion->estudiante->apellido ?? '')) ?: 'Postulante' }}
                    </span>
                    <span class="panel-person-sub">
                        {{ \Illuminate\Support\Str::limit($postulacion->oferta->titulo ?? '', 26) }}
                    </span>
                </div>
                <i class="bi bi-chevron-right panel-chevron"></i>
            </a>
            @endforeach
        @else
            <p style="font-size:0.85rem;color:var(--muted);padding:4px 0;">Aún no hay postulantes.</p>
        @endif
    </div>
</div>

{{-- ── PERFIL DE EMPRESA ──────────────────────────────────── --}}
@php
    $emp = auth()->user()->empresa ?? null;
    $camposEmp = [
        'descripcion'  => 15,
        'logo'         => 15,
        'banner'       => 10,
        'sitio_web'    => 10,
        'linkedin'     => 10,
        'instagram'    => 10,
        'facebook'     => 10,
        'direccion'    => 10,
        'tamano_empresa' => 10,
    ];
    $completitudEmp = 0;
    if ($emp) {
        foreach ($camposEmp as $campo => $peso) {
            if (!empty($emp->$campo)) $completitudEmp += $peso;
        }
    }
@endphp
<div class="panel-card" style="padding:0;overflow:hidden;">
    {{-- Header --}}
    <div style="
        padding:14px 16px 12px;
        background:var(--accent-dim);
        border-bottom:1px solid var(--border-accent);
        display:flex;
        align-items:center;
        justify-content:space-between;
    ">
        <p class="panel-card-title" style="margin:0;">Tu perfil de empresa</p>
        <span style="
            font-family:var(--font-display);
            font-size:1.1rem;
            font-weight:800;
            color:{{ $completitudEmp >= 80 ? 'var(--accent)' : ($completitudEmp >= 50 ? '#f59e0b' : '#ef4444') }};
        ">{{ $completitudEmp }}%</span>
    </div>

    {{-- Barra de progreso --}}
    <div style="height:4px;background:var(--border);width:100%;">
        <div style="
            height:100%;
            width:{{ $completitudEmp }}%;
            background:{{ $completitudEmp >= 80 ? 'var(--accent)' : ($completitudEmp >= 50 ? '#f59e0b' : '#ef4444') }};
            transition:width .4s ease;
        "></div>
    </div>

    <div style="padding:14px 16px 16px;display:flex;flex-direction:column;gap:10px;">
        {{-- Aviso solo si está incompleto --}}
        @if($completitudEmp < 100)
        <div style="
            display:flex;
            align-items:flex-start;
            gap:8px;
            padding:8px 12px;
            background:var(--bg-hover);
            border:1px solid var(--border);
            border-radius:var(--radius);
            font-size:0.78rem;
            color:var(--muted);
            line-height:1.4;
        ">
            <i class="bi bi-info-circle" style="flex-shrink:0;color:var(--accent);margin-top:1px;"></i>
            Completá tu perfil para que los postulantes puedan conocer mejor tu empresa.
        </div>
        @endif

        <a href="{{ url('/empresa/perfil/editar') }}" class="btn-apply-filters" style="
            display:flex;
            align-items:center;
            justify-content:center;
            gap:6px;
            text-decoration:none;
        ">
            <i class="bi bi-pencil-square"></i>
            {{ $completitudEmp >= 80 ? 'Ver mi perfil' : 'Completar perfil' }}
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
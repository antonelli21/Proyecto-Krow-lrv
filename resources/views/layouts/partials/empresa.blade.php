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
<div class="panel-card">
    <p class="panel-card-title">Últimos postulantes</p>
    <div class="recent-offers" style="display:flex;flex-direction:column;gap:12px;">
        @if(isset($panelData['ultimos_postulantes']) && $panelData['ultimos_postulantes']->count() > 0)
        @foreach($panelData['ultimos_postulantes'] as $postulacion)
        <a href="#" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
            <div class="company-thumb" style="width:32px;height:32px;font-size:0.7rem;border-radius:50%;overflow:hidden;">
                @if($postulacion->estudiante->foto_perfil)
                <img src="{{ asset('storage/' . $postulacion->estudiante->foto_perfil) }}" alt="Foto" style="width:100%;height:100%;object-fit:cover;">
                @else
                <span>{{ strtoupper(substr($postulacion->estudiante->user->nombre ?? '?', 0, 1) . substr($postulacion->estudiante->user->apellido ?? '?', 0, 1)) }}</span>
                @endif
            </div>
            <div>
                <div style="font-size:0.85rem;color:var(--text);font-weight:600;line-height:1.2;">{{ $postulacion->estudiante->user->nombre ?? 'Postulante' }} {{ $postulacion->estudiante->user->apellido ?? '' }}</div>
                <div style="font-size:0.75rem;color:var(--muted);">{{ \Illuminate\Support\Str::limit($postulacion->oferta->titulo ?? '', 20) }}</div>
            </div>
        </a>
        @endforeach
        @else
        <p style="font-size:0.85rem;color:var(--muted);">Aún no hay postulantes recientes.</p>
        @endif
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
<div class="panel-card">
    <p class="panel-card-title">Administración</p>
    <div class="admin-alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        {{ $panelData['ofertas_pendientes'] ?? 0 }} ofertas pendientes de revisión
    </div>
    <div class="admin-alert" style="background:rgba(46,204,154,.08);border-color:rgba(46,204,154,.3);color:var(--accent);">
        <i class="bi bi-people-fill"></i>
        {{ $panelData['nuevos_registros'] ?? 0 }} nuevos registros hoy
    </div>
    <div class="stat-row">
        <span class="stat-label">Usuarios totales</span>
        <span class="stat-value">{{ $panelData['total_usuarios'] ?? 0 }}</span>
    </div>
    <div class="stat-row">
        <span class="stat-label">Empresas activas</span>
        <span class="stat-value">{{ $panelData['empresas_activas'] ?? 0 }}</span>
    </div>
    <div class="stat-row">
        <span class="stat-label">Ofertas publicadas</span>
        <span class="stat-value">{{ $panelData['ofertas_publicadas'] ?? 0 }}</span>
    </div>
</div>
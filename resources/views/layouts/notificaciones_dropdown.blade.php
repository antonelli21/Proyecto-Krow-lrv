@php
    $rol = auth()->check() ? auth()->user()->rol : 'invitado';
@endphp

{{-- ══ CAMPANA ══ --}}
<div class="notif-wrapper" id="notif-wrapper">
    <button class="action-btn notif-btn" id="notif-toggle" aria-label="Notificaciones" aria-expanded="false">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>
        <span class="notif-badge" id="notif-badge">4</span>
    </button>

    {{-- ══ DROPDOWN ══ --}}
    <div class="notif-dropdown" id="notif-dropdown" role="menu" aria-hidden="true">

        <div class="notif-header">
            <span class="notif-header-title">Notificaciones</span>
            <button class="notif-mark-all" id="notif-mark-all">Marcar todas como leídas</button>
        </div>

        <div class="notif-list">

            {{-- ── Alumno / Estudiante ── --}}
            @if($rol === 'estudiante')

                <a href="{{ route('estudiante.oferta', 4) }}" class="notif-item unread">
                    <div class="notif-icon notif-icon-info">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </div>
                    <div class="notif-body">
                        <p class="notif-text">Nueva búsqueda de <strong>Tech Solutions</strong> coincidente con tu perfil.</p>
                        <span class="notif-time">Hace 5 min</span>
                    </div>
                    <span class="notif-dot"></span>
                </a>

                <a href="{{ route('estudiante.home') }}" class="notif-item unread">
                    <div class="notif-icon notif-icon-success">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <div class="notif-body">
                        <p class="notif-text">Tu postulación fue <strong>seleccionada para entrevista</strong> en DevHouse.</p>
                        <span class="notif-time">Hace 1 hora</span>
                    </div>
                    <span class="notif-dot"></span>
                </a>

                <a href="{{ route('estudiante.mensajes') }}" class="notif-item unread">
                    <div class="notif-icon notif-icon-message">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                    </div>
                    <div class="notif-body">
                        <p class="notif-text">Nuevo mensaje privado de un reclutador de <strong>Softland</strong>.</p>
                        <span class="notif-time">Hace 3 horas</span>
                    </div>
                    <span class="notif-dot"></span>
                </a>

                <a href="{{ route('estudiante.perfil.editar') }}" class="notif-item unread">
                    <div class="notif-icon notif-icon-warning">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <div class="notif-body">
                        <p class="notif-text">Recordatorio: completá tu <strong>perfil y CV</strong> para destacarte.</p>
                        <span class="notif-time">Ayer</span>
                    </div>
                    <span class="notif-dot"></span>
                </a>

            {{-- ── Empresa ── --}}
            @elseif($rol === 'empresa')

                <a href="{{ route('empresa.empresa.ofertas.postulantes', 2) }}" class="notif-item unread">
                    <div class="notif-icon notif-icon-info">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <div class="notif-body">
                        <p class="notif-text">Nueva postulación en <strong>Junior SQL Developer</strong>.</p>
                        <span class="notif-time">Hace 10 min</span>
                    </div>
                    <span class="notif-dot"></span>
                </a>

                <a href="{{ route('empresa.mensajes') }}" class="notif-item unread">
                    <div class="notif-icon notif-icon-message">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                    </div>
                    <div class="notif-body">
                        <p class="notif-text">Mensaje nuevo del candidato <strong>Carlos Gómez</strong>.</p>
                        <span class="notif-time">Hace 45 min</span>
                    </div>
                    <span class="notif-dot"></span>
                </a>

                <a href="{{ route('empresa.home') }}" class="notif-item unread">
                    <div class="notif-icon notif-icon-success">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <div class="notif-body">
                        <p class="notif-text">Tu cuenta fue <strong>aprobada</strong> por el administrador.</p>
                        <span class="notif-time">Hace 2 horas</span>
                    </div>
                    <span class="notif-dot"></span>
                </a>

                <a href="{{ route('empresa.home') }}" class="notif-item unread">
                    <div class="notif-icon notif-icon-warning">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <div class="notif-body">
                        <p class="notif-text">La publicación <strong>Fullstack Trainee</strong> está por expirar.</p>
                        <span class="notif-time">Ayer</span>
                    </div>
                    <span class="notif-dot"></span>
                </a>

            {{-- ── Admin ── --}}
            @elseif($rol === 'admin')

                <a href="{{ route('admin.home') }}" class="notif-item unread">
                    <div class="notif-icon notif-icon-info">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                    </div>
                    <div class="notif-body">
                        <p class="notif-text">Nueva empresa <strong>Softland S.A.</strong> registrada, esperando aprobación.</p>
                        <span class="notif-time">Hace 15 min</span>
                    </div>
                    <span class="notif-dot"></span>
                </a>

                <a href="{{ route('admin.home') }}" class="notif-item unread">
                    <div class="notif-icon notif-icon-danger">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </div>
                    <div class="notif-body">
                        <p class="notif-text">Reporte de <strong>queja/spam</strong> en la oferta #12.</p>
                        <span class="notif-time">Hace 1 hora</span>
                    </div>
                    <span class="notif-dot"></span>
                </a>

                <a href="{{ route('admin.home') }}" class="notif-item unread">
                    <div class="notif-icon notif-icon-danger">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <div class="notif-body">
                        <p class="notif-text"><strong>Alerta:</strong> múltiples intentos de login fallidos detectados.</p>
                        <span class="notif-time">Hace 2 horas</span>
                    </div>
                    <span class="notif-dot"></span>
                </a>

                <a href="{{ route('admin.home') }}" class="notif-item unread">
                    <div class="notif-icon notif-icon-success">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                        </svg>
                    </div>
                    <div class="notif-body">
                        <p class="notif-text">El <strong>reporte semanal de métricas</strong> ya está disponible.</p>
                        <span class="notif-time">Hoy</span>
                    </div>
                    <span class="notif-dot"></span>
                </a>

            @endif

        </div>{{-- /notif-list --}}

        <div class="notif-footer">
            <a href="{{ route('notificaciones') }}" class="notif-ver-todas">Ver todas las notificaciones</a>
        </div>

    </div>{{-- /notif-dropdown --}}
</div>{{-- /notif-wrapper --}}

<script>
(function () {
    const wrapper  = document.getElementById('notif-wrapper');
    const toggle   = document.getElementById('notif-toggle');
    const dropdown = document.getElementById('notif-dropdown');
    const badge    = document.getElementById('notif-badge');
    const markAll  = document.getElementById('notif-mark-all');

    if (!toggle || !dropdown) return;

    /* ── Abrir / cerrar ── */
    toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        const isOpen = dropdown.classList.toggle('open');
        toggle.setAttribute('aria-expanded', isOpen);
        dropdown.setAttribute('aria-hidden', !isOpen);
    });

    /* ── Cerrar al click fuera ── */
    document.addEventListener('click', function (e) {
        if (!wrapper.contains(e.target)) {
            dropdown.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
            dropdown.setAttribute('aria-hidden', 'true');
        }
    });

    /* ── Marcar todas como leídas ── */
    markAll?.addEventListener('click', function (e) {
        e.stopPropagation();
        document.querySelectorAll('.notif-item.unread').forEach(item => {
            item.classList.remove('unread');
            item.querySelector('.notif-dot')?.remove();
        });
        if (badge) badge.style.display = 'none';
        this.style.display = 'none';
    });

    /* ── Click en notificación — marcar como leída ── */
    document.querySelectorAll('.notif-item').forEach(item => {
        item.addEventListener('click', function () {
            this.classList.remove('unread');
            this.querySelector('.notif-dot')?.remove();
            const unread = document.querySelectorAll('.notif-item.unread').length;
            if (badge) {
                if (unread === 0) {
                    badge.style.display = 'none';
                } else {
                    badge.textContent = unread;
                }
            }
        });
    });
})();
</script>
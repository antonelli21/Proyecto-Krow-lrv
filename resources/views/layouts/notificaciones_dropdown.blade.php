{{-- ═══════════════════════════════════════════════════════
     Dropdown de notificaciones — Krow
     Incluir en el navbar: @include('components.notificaciones-dropdown')
════════════════════════════════════════════════════════ --}}

<div class="notif-wrapper" id="notif-wrapper">

    {{-- Botón campana --}}
    <button class="action-btn" id="notif-toggle" aria-label="Notificaciones" aria-expanded="false">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>
        <span class="notif-badge" id="notif-badge" aria-live="polite" style="display:none;">0</span>
    </button>

    {{-- Dropdown --}}
    <div class="notif-dropdown" id="notif-dropdown" role="menu" aria-hidden="true">

        <div class="notif-header">
            <span class="notif-header-title">Notificaciones</span>
            <button class="notif-mark-all" id="notif-mark-all" title="Marcar todas como leídas">
                Marcar como leídas
            </button>
        </div>

        {{-- Lista --}}
        <div class="notif-list" id="notif-list" role="list">
            <div class="notif-item" style="justify-content:center; color:var(--muted); font-size:0.8rem; gap:8px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="animation: notif-spin 1s linear infinite; flex-shrink:0;" aria-hidden="true">
                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4"/>
                </svg>
                Cargando...
            </div>
        </div>

        <div class="notif-footer">
            <a href="{{ route('notificaciones.historial') }}" class="notif-ver-todas">
                Ver todas las notificaciones
            </a>
        </div>

    </div>
</div>


<script>
(function () {
    const toggle     = document.getElementById('notif-toggle');
    const dropdown   = document.getElementById('notif-dropdown');
    const list       = document.getElementById('notif-list');
    const badge      = document.getElementById('notif-badge');
    const btnMarkAll = document.getElementById('notif-mark-all');
    const CSRF       = '{{ csrf_token() }}';

    if (!toggle || !dropdown) return;

    const ICONOS = {
        info:    '&#9432;',
        success: '&#10003;',
        warning: '&#9888;',
        danger:  '&#10005;',
        message: '&#9993;',
    };

    // ── Fetch resumen ──────────────────────────────────────────
    async function cargarNotificaciones() {
        try {
            const res  = await fetch('/notificaciones/api/resumen');
            if (!res.ok) throw new Error(res.status);
            const data = await res.json();

            actualizarBadge(data.cantidad);
            renderLista(data.recientes);
        } catch (e) {
            console.error('Error cargando notificaciones:', e);
            list.innerHTML = '<div class="notif-item" style="justify-content:center;color:var(--muted);font-size:0.8rem;">No se pudieron cargar las notificaciones.</div>';
        }
    }

    // ── Badge ──────────────────────────────────────────────────
    function actualizarBadge(cantidad) {
        if (!badge) return;
        const n = parseInt(cantidad, 10) || 0;
        badge.textContent   = n > 99 ? '99+' : n;
        badge.style.display = n > 0 ? 'flex' : 'none';

        toggle.setAttribute('aria-label', n > 0
            ? `Notificaciones — ${n} sin leer`
            : 'Notificaciones'
        );
    }

    // ── Render lista ───────────────────────────────────────────
    function renderLista(recientes) {
        if (!recientes || recientes.length === 0) {
            list.innerHTML = '<div class="notif-item" style="justify-content:center;color:var(--muted);font-size:0.8rem;">Todo al día — no hay notificaciones.</div>';
            return;
        }

        list.innerHTML = recientes.map(n => `
            <a href="#"
               class="notif-item ${n.leida ? '' : 'unread'}"
               role="menuitem"
               data-id="${n.id}"
               data-url="${n.url || ''}"
               data-leida="${n.leida}">
                <div class="notif-icon notif-icon-${n.tipo}" aria-hidden="true">
                    ${ICONOS[n.tipo] || ICONOS.info}
                </div>
                <div class="notif-body">
                    <p class="notif-text"><strong>${escapeHtml(n.titulo)}</strong><br>${escapeHtml(n.mensaje)}</p>
                    <span class="notif-time">${n.fecha}</span>
                </div>
                ${!n.leida ? '<span class="notif-dot" aria-hidden="true"></span>' : ''}
            </a>
        `).join('');

        list.querySelectorAll('.notif-item').forEach(el => {
            el.addEventListener('click', onItemClick);
        });
    }

    // ── Click en ítem ──────────────────────────────────────────
    async function onItemClick(e) {
        e.preventDefault();
        const el  = e.currentTarget;
        const id  = el.dataset.id;
        const url = el.dataset.url;

        // Optimistic UI
        el.classList.remove('unread');
        el.querySelector('.notif-dot')?.remove();
        el.dataset.leida = 'true';

        const actual = parseInt(badge.textContent, 10) || 0;
        if (actual > 0) actualizarBadge(actual - 1);

        fetch('/notificaciones/api/marcar-leida', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
            },
            body: JSON.stringify({ id }),
        }).catch(err => console.error('Error marcando leída:', err));

        if (url && url !== '') window.location.href = url;
    }

    // ── Marcar todas como leídas ───────────────────────────────
    btnMarkAll?.addEventListener('click', async () => {
        try {
            await fetch('/notificaciones/api/marcar-todas', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF },
            });
            actualizarBadge(0);
            list.querySelectorAll('.notif-item.unread').forEach(el => {
                el.classList.remove('unread');
                el.querySelector('.notif-dot')?.remove();
            });
        } catch (e) {
            console.error('Error marcando todas como leídas:', e);
        }
    });

    // ── Toggle dropdown ────────────────────────────────────────
    toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdown.classList.contains('open') ? cerrarDropdown() : abrirDropdown();
    });

    function abrirDropdown() {
        dropdown.classList.add('open');
        toggle.setAttribute('aria-expanded', 'true');
        dropdown.setAttribute('aria-hidden', 'false');
        cargarNotificaciones();
    }

    function cerrarDropdown() {
        dropdown.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
        dropdown.setAttribute('aria-hidden', 'true');
    }

    document.addEventListener('click', function (e) {
        if (!dropdown.contains(e.target) && e.target !== toggle) cerrarDropdown();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && dropdown.classList.contains('open')) {
            cerrarDropdown();
            toggle.focus();
        }
    });

    // ── Helpers ────────────────────────────────────────────────
    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // ── Init ───────────────────────────────────────────────────
    (async () => {
        try {
            const res  = await fetch('/notificaciones/api/contador');
            const data = await res.json();
            actualizarBadge(data.cantidad);
        } catch (_) {}
    })();

    setInterval(async () => {
        try {
            const res  = await fetch('/notificaciones/api/contador');
            const data = await res.json();
            actualizarBadge(data.cantidad);
            if (dropdown.classList.contains('open')) cargarNotificaciones();
        } catch (_) {}
    }, 60000);

})();
</script>
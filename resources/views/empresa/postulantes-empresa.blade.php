@extends('layouts.app')

@section('title', 'Postulantes - Oferta #' . $oferta->id_oferta)

@section('content')
<style>
    .postulantes-container {
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
        padding: 32px 24px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--muted);
        text-decoration: none;
        margin-bottom: 24px;
        transition: color 0.2s;
    }
    .back-link:hover { color: var(--text); }

    .page-header { margin-bottom: 32px; }
    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 8px;
    }
    .page-subtitle { color: var(--muted); font-size: 14px; }

    /* Filtros */
    .filter-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .filter-btn {
        padding: 8px 16px;
        border-radius: 6px;
        transition: all 0.2s;
        cursor: pointer;
        border: 1px solid var(--border);
        background: var(--surface);
        color: var(--text);
        font-size: 14px;
    }
    .filter-btn:hover { background: var(--bg); }
    .filter-btn.active {
        background: var(--primary);
        color: #ffffff;
        border-color: var(--primary);
    }
    [data-theme="dark"] .filter-btn.active {
        background: var(--accent);
        color: #111118;
        border-color: var(--accent);
    }

    /* Lista */
    .applicants-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    /* Tarjeta */
    .applicant-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 24px;
        transition: box-shadow 0.2s;
    }
    .applicant-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .applicant-name {
        font-size: 18px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 4px;
    }
    .applicant-name a { color: var(--primary); text-decoration: none; }
    .applicant-name a:hover { text-decoration: underline; }
    .applicant-career { font-size: 13px; color: var(--muted); margin-bottom: 4px; }
    .applicant-date { font-size: 11px; color: var(--muted); }

    /* Badge */
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        border: 1px solid transparent;
        white-space: nowrap;
    }
    .status-pending {
        background: rgba(100,100,100,0.1);
        color: #9a9aaa;
        border-color: rgba(100,100,100,0.3);
    }
    .status-accepted {
        background: rgba(46,204,154,0.12);
        color: #2ECC9A;
        border-color: rgba(46,204,154,0.3);
    }
    .status-rejected {
        background: rgba(212,24,61,0.10);
        color: #e05577;
        border-color: rgba(212,24,61,0.3);
    }

    /* Layout dos columnas */
    .card-content { display: flex; gap: 24px; flex-wrap: wrap; }
    .card-left { flex: 2; min-width: 200px; }
    .card-right { flex: 1; min-width: 200px; display: flex; flex-direction: column; gap: 8px; }

    /* Contacto */
    .contact-info { display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px; }
    .contact-item { display: flex; align-items: center; gap: 8px; font-size: 14px; }
    .contact-icon { color: var(--muted); width: 18px; height: 18px; }
    .contact-item a { color: var(--primary); text-decoration: none; }
    .contact-item a:hover { text-decoration: underline; }

    /* Botones de acción */
    .action-buttons { display: flex; gap: 8px; flex-wrap: wrap; }

    .btn-accept {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 14px;
        background: #2ECC9A; color: white;
        border: none; border-radius: 6px;
        font-size: 13px; font-weight: 500;
        cursor: pointer; transition: opacity 0.2s;
    }
    .btn-accept:hover { opacity: 0.85; }

    .btn-reject {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 14px;
        background: #dc3545; color: white;
        border: none; border-radius: 6px;
        font-size: 13px; font-weight: 500;
        cursor: pointer; transition: opacity 0.2s;
    }
    .btn-reject:hover { opacity: 0.85; }

    .btn-unreject {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 14px;
        background: var(--bg); color: var(--text);
        border: 1px solid var(--border); border-radius: 6px;
        font-size: 13px; font-weight: 500;
        cursor: pointer; transition: all 0.2s;
    }
    .btn-unreject:hover { border-color: var(--primary); color: var(--primary); }

    /* Botones derecha */
    .btn-download {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        padding: 8px 16px;
        background: var(--primary); color: white;
        border: none; border-radius: 6px;
        font-size: 13px; font-weight: 500;
        cursor: pointer; text-decoration: none;
        transition: opacity 0.2s;
    }
    [data-theme="dark"] .btn-download { background: var(--accent); color: #111118; }
    .btn-download:hover { opacity: 0.85; }

    .social-links { display: flex; gap: 8px; }
    .social-btn {
        flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;
        padding: 8px; border-radius: 6px;
        font-size: 13px; font-weight: 500;
        text-decoration: none; transition: opacity 0.2s; color: white;
    }
    .social-linkedin { background: #0A66C2; }
    .social-github { background: #333; }
    .social-btn:hover { opacity: 0.85; }
    .social-btn.disabled {
        background: var(--border); color: var(--muted);
        cursor: not-allowed; pointer-events: none;
    }

    .btn-contact {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        padding: 8px 16px;
        background: var(--bg); border: 1px solid var(--border);
        color: var(--text); border-radius: 6px;
        font-size: 13px; font-weight: 500;
        cursor: pointer; text-decoration: none; transition: all 0.2s;
    }
    .btn-contact:hover { background: var(--surface); border-color: var(--accent); }

    /* Diálogo */
    .dialog-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.5);
        display: flex; align-items: center; justify-content: center;
        z-index: 1000;
    }
    .dialog-content {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 12px; padding: 24px;
        max-width: 400px; width: 90%;
    }
    .dialog-title { font-size: 20px; font-weight: 600; color: var(--text); margin-bottom: 12px; }
    .dialog-message { color: var(--muted); margin-bottom: 24px; }
    .dialog-buttons { display: flex; gap: 12px; justify-content: flex-end; }
    .dialog-cancel {
        padding: 8px 16px; background: var(--bg);
        border: 1px solid var(--border); color: var(--text);
        border-radius: 6px; cursor: pointer;
    }
    .dialog-confirm-accept { padding: 8px 16px; background: #2ECC9A; color: white; border: none; border-radius: 6px; cursor: pointer; }
    .dialog-confirm-reject { padding: 8px 16px; background: #dc3545; color: white; border: none; border-radius: 6px; cursor: pointer; }
    .dialog-confirm-neutral { padding: 8px 16px; background: var(--primary); color: white; border: none; border-radius: 6px; cursor: pointer; }

    .sin-resultados { text-align: center; padding: 60px 20px; color: var(--muted); }

    @media (max-width: 768px) {
        .postulantes-container { padding: 16px; }
        .card-content { flex-direction: column; }
        .card-header { flex-direction: column; }
        .action-buttons { flex-direction: column; }
        .btn-accept, .btn-reject, .btn-unreject { justify-content: center; }
    }
</style>

<div class="postulantes-container">
    <a href="{{ route('empresa.home') }}" class="back-link">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Volver al Dashboard
    </a>

    <div class="page-header">
        <h1 class="page-title">Postulantes — {{ $oferta->titulo }}</h1>
        <p class="page-subtitle">Oferta #{{ $oferta->id_oferta }}</p>
    </div>

    <!-- Filtros con contadores dinámicos -->
    <div class="filter-tabs">
        <button class="filter-btn active" data-estado="todos">
            Todos (<span class="counter" data-estado="todos">{{ $postulantes->count() }}</span>)
        </button>
        <button class="filter-btn" data-estado="Postulado">
            Postulado (<span class="counter" data-estado="Postulado">{{ $postulantes->where('estado', 'Postulado')->count() }}</span>)
        </button>
        <button class="filter-btn" data-estado="Preseleccionado">
            Preseleccionado (<span class="counter" data-estado="Preseleccionado">{{ $postulantes->where('estado', 'Preseleccionado')->count() }}</span>)
        </button>
        <button class="filter-btn" data-estado="En Contacto">
            En Contacto (<span class="counter" data-estado="En Contacto">{{ $postulantes->where('estado', 'En Contacto')->count() }}</span>)
        </button>
        <button class="filter-btn" data-estado="Rechazado">
            Rechazado (<span class="counter" data-estado="Rechazado">{{ $postulantes->where('estado', 'Rechazado')->count() }}</span>)
        </button>
    </div>

    <div class="applicants-list" id="applicantsList">
        @forelse($postulantes as $postulante)
        @php
            $badgeConfig = [
                'Postulado'       => ['class' => 'status-pending',  'text' => 'Postulado'],
                'Preseleccionado' => ['class' => 'status-accepted', 'text' => 'Preseleccionado'],
                'En Contacto'     => ['class' => 'status-accepted', 'text' => 'En Contacto'],
                'Rechazado'       => ['class' => 'status-rejected', 'text' => 'Rechazado'],
            ];
            $config = $badgeConfig[$postulante->estado] ?? $badgeConfig['Postulado'];
        @endphp
        <div class="applicant-card" data-estado="{{ $postulante->estado }}" data-id="{{ $postulante->id }}">
            <div class="card-header">
                <div>
                    <h3 class="applicant-name">
                        <a href="{{ route('empresa.estudiante.perfil', $postulante->id_estudiante) }}">
                            {{ $postulante->nombre ?? 'Nombre no disponible' }}
                        </a>
                    </h3>
                    <div class="applicant-career">{{ $postulante->carrera }}</div>
                    <div class="applicant-date">Postulado: {{ \Carbon\Carbon::parse($postulante->fecha_postulacion)->format('d/m/Y') }}</div>
                </div>
                <span class="status-badge {{ $config['class'] }}">{{ $config['text'] }}</span>
            </div>

            <div class="card-content">
                <div class="card-left">
                    <div class="contact-info">
                        <div class="contact-item">
                            <svg class="contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="4" width="20" height="16" rx="2"/>
                                <path d="M22 7l-10 7L2 7"/>
                            </svg>
                            <a href="mailto:{{ $postulante->email }}">{{ $postulante->email }}</a>
                        </div>
                        <div class="contact-item">
                            <svg class="contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                            <span>{{ $postulante->telefono }}</span>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="action-buttons">
                        @if($postulante->estado !== 'Rechazado')
                            @if($postulante->estado !== 'En Contacto')
                            <button class="btn-accept"
                                    onclick="openConfirmDialog({{ $postulante->id }}, 'accept', '{{ $postulante->estado }}')">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                {{ $postulante->estado === 'Postulado' ? 'Preseleccionar' : 'Marcar En Contacto' }}
                            </button>
                            @endif
                            <button class="btn-reject"
                                    onclick="openConfirmDialog({{ $postulante->id }}, 'reject', '{{ $postulante->estado }}')">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                </svg>
                                Rechazar
                            </button>
                        @else
                            <button class="btn-unreject"
                                    onclick="openConfirmDialog({{ $postulante->id }}, 'unreject', '{{ $postulante->estado }}')">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                                    <path d="M3 3v5h5"/>
                                </svg>
                                Volver a Postulado
                            </button>
                        @endif
                    </div>
                </div>

                <div class="card-right">
                    @if(!empty($postulante->cv))
                        <a href="{{ Storage::url($postulante->cv) }}" target="_blank" class="btn-download">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            Ver CV
                        </a>
                    @else
                        <span class="btn-download" style="opacity:0.4; cursor:not-allowed; pointer-events:none;">
                            Sin CV
                        </span>
                    @endif

                    <div class="social-links">
                        @if(!empty($postulante->linkedin))
                        <a href="{{ $postulante->linkedin }}" class="social-btn social-linkedin" target="_blank">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/>
                                <rect x="2" y="9" width="4" height="12"/>
                                <circle cx="4" cy="4" r="2"/>
                            </svg>
                            LinkedIn
                        </a>
                        @else
                        <span class="social-btn disabled">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/>
                                <rect x="2" y="9" width="4" height="12"/>
                                <circle cx="4" cy="4" r="2"/>
                            </svg>
                            LinkedIn
                        </span>
                        @endif

                        @if(!empty($postulante->github))
                        <a href="{{ $postulante->github }}" class="social-btn social-github" target="_blank">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/>
                            </svg>
                            GitHub
                        </a>
                        @else
                        <span class="social-btn disabled">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/>
                            </svg>
                            GitHub
                        </span>
                        @endif
                    </div>
                    <a href="{{ route('empresa.mensajes', ['postulante_id' => $postulante->id_usuario]) }}" class="btn-contact">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                        Contactar   
                        </a>
                </div>
            </div>
        </div>
        @empty
        <div class="sin-resultados">
            <p>📭 No hay postulantes para esta oferta.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Diálogo de confirmación -->
<div id="confirmDialog" class="dialog-overlay" style="display: none;">
    <div class="dialog-content">
        <h3 class="dialog-title" id="dialogTitle"></h3>
        <p class="dialog-message" id="dialogMessage"></p>
        <div class="dialog-buttons">
            <button class="dialog-cancel" onclick="closeDialog()">Cancelar</button>
            <button id="dialogConfirmBtn"></button>
        </div>
    </div>
</div>

<script>
let pendingAction = { applicantId: null, action: null, estadoActual: null };

const siguienteEstado = {
    'Postulado':       'Preseleccionado',
    'Preseleccionado': 'En Contacto',
    'En Contacto':     'En Contacto',
};

const badgeTexts = {
    'Postulado':       '📋 Postulado',
    'Preseleccionado': '⭐ Preseleccionado',
    'En Contacto':     '💬 En Contacto',
    'Rechazado':       '❌ Rechazado',
};

const badgeClasses = {
    'Postulado':       'status-pending',
    'Preseleccionado': 'status-accepted',
    'En Contacto':     'status-accepted',
    'Rechazado':       'status-rejected',
};

function openConfirmDialog(applicantId, action, estadoActual) {
    pendingAction = { applicantId, action, estadoActual };

    const title      = document.getElementById('dialogTitle');
    const message    = document.getElementById('dialogMessage');
    const confirmBtn = document.getElementById('dialogConfirmBtn');

    if (action === 'accept') {
        const next = siguienteEstado[estadoActual] || 'Preseleccionado';
        title.textContent      = `Mover a "${next}"`;
        message.textContent    = `¿Confirmás mover a este postulante a "${next}"?`;
        confirmBtn.className   = 'dialog-confirm-accept';
        confirmBtn.textContent = 'Sí, mover';
    } else if (action === 'reject') {
        title.textContent      = 'Rechazar postulante';
        message.textContent    = '¿Confirmás rechazar a este postulante?';
        confirmBtn.className   = 'dialog-confirm-reject';
        confirmBtn.textContent = 'Sí, rechazar';
    } else if (action === 'unreject') {
        title.textContent      = 'Volver a Postulado';
        message.textContent    = '¿Confirmás volver a poner a este postulante como Postulado?';
        confirmBtn.className   = 'dialog-confirm-neutral';
        confirmBtn.textContent = 'Sí, volver';
    }

    document.getElementById('confirmDialog').style.display = 'flex';
}

function closeDialog() {
    document.getElementById('confirmDialog').style.display = 'none';
    pendingAction = { applicantId: null, action: null, estadoActual: null };
}

document.getElementById('dialogConfirmBtn').addEventListener('click', function () {
    if (!pendingAction.applicantId) return;

    // Guardar antes de cerrar
    const applicantId  = pendingAction.applicantId;
    const action       = pendingAction.action;
    const estadoActual = pendingAction.estadoActual;

    let nuevoEstado;
    if (action === 'accept') {
        nuevoEstado = siguienteEstado[estadoActual] || 'Preseleccionado';
    } else if (action === 'reject') {
        nuevoEstado = 'Rechazado';
    } else if (action === 'unreject') {
        nuevoEstado = 'Postulado';
    }

    closeDialog();
    actualizarEstado(applicantId, nuevoEstado, estadoActual);
});

function actualizarEstado(applicantId, nuevoEstado, estadoAnterior) {
    const card = document.querySelector(`.applicant-card[data-id="${applicantId}"]`);
    if (!card) return;

    fetch(`/empresa/postulacion/${applicantId}/estado`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ estado: nuevoEstado })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            alert('Error al actualizar el estado.');
            return;
        }

        // Actualizar badge
        const badge = card.querySelector('.status-badge');
        badge.className   = `status-badge ${badgeClasses[nuevoEstado]}`;
        badge.textContent = badgeTexts[nuevoEstado];

        // Actualizar data-estado
        card.setAttribute('data-estado', nuevoEstado);

        // Reconstruir botones de acción
        const actionButtons = card.querySelector('.action-buttons');
        actionButtons.innerHTML = '';

        if (nuevoEstado === 'Rechazado') {
            // Solo botón para desrechazar
            actionButtons.innerHTML = `
                <button class="btn-unreject" onclick="openConfirmDialog(${applicantId}, 'unreject', 'Rechazado')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                        <path d="M3 3v5h5"/>
                    </svg>
                    Volver a Postulado
                </button>`;
        } else if (nuevoEstado === 'En Contacto') {
            // Solo rechazar, ya no puede avanzar
            actionButtons.innerHTML = `
                <button class="btn-reject" onclick="openConfirmDialog(${applicantId}, 'reject', 'En Contacto')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    Rechazar
                </button>`;
        } else if (nuevoEstado === 'Preseleccionado') {
            actionButtons.innerHTML = `
                <button class="btn-accept" onclick="openConfirmDialog(${applicantId}, 'accept', 'Preseleccionado')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Marcar En Contacto
                </button>
                <button class="btn-reject" onclick="openConfirmDialog(${applicantId}, 'reject', 'Preseleccionado')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    Rechazar
                </button>`;
        } else if (nuevoEstado === 'Postulado') {
            actionButtons.innerHTML = `
                <button class="btn-accept" onclick="openConfirmDialog(${applicantId}, 'accept', 'Postulado')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Preseleccionar
                </button>
                <button class="btn-reject" onclick="openConfirmDialog(${applicantId}, 'reject', 'Postulado')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    Rechazar
                </button>`;
        }

        // Actualizar contadores
        actualizarContadores(estadoAnterior, nuevoEstado);

        // Reaplicar filtro activo
        const filtroActivo = document.querySelector('.filter-btn.active');
        if (filtroActivo) {
            filtrarPostulantes(filtroActivo.getAttribute('data-estado'));
        }
    })
    .catch(() => alert('Error de red al actualizar el estado.'));
}

function actualizarContadores(estadoAnterior, estadoNuevo) {
    // Restar del estado anterior
    const spanAnterior = document.querySelector(`.counter[data-estado="${estadoAnterior}"]`);
    if (spanAnterior) {
        spanAnterior.textContent = Math.max(0, parseInt(spanAnterior.textContent) - 1);
    }

    // Sumar al estado nuevo
    const spanNuevo = document.querySelector(`.counter[data-estado="${estadoNuevo}"]`);
    if (spanNuevo) {
        spanNuevo.textContent = parseInt(spanNuevo.textContent) + 1;
    }
    // "Todos" no cambia
}

// Filtrado
const filtros = document.querySelectorAll('.filter-btn');

function filtrarPostulantes(estado) {
    const cards = document.querySelectorAll('.applicant-card');
    let visible = 0;

    cards.forEach(card => {
        const match = estado === 'todos' || card.getAttribute('data-estado') === estado;
        card.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    let sinResultados = document.querySelector('.sin-resultados');
    if (visible === 0) {
        if (!sinResultados) {
            const list = document.getElementById('applicantsList');
            const msg  = document.createElement('div');
            msg.className = 'sin-resultados';
            msg.innerHTML = '<p>📭 No hay postulantes con este estado.</p>';
            list.appendChild(msg);
        }
    } else {
        sinResultados?.remove();
    }
}

filtros.forEach(btn => {
    btn.addEventListener('click', function () {
        filtros.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        filtrarPostulantes(this.getAttribute('data-estado'));
    });
});
</script>
@endsection
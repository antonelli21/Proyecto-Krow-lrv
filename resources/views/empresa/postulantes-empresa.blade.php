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
    
    /* Volver */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--muted);
        text-decoration: none;
        margin-bottom: 24px;
        transition: color 0.2s;
    }
    
    .back-link:hover {
        color: var(--text);
    }
    
    /* Header */
    .page-header {
        margin-bottom: 32px;
    }
    
    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 8px;
    }
    
    .page-subtitle {
        color: var(--muted);
        font-size: 14px;
    }
    
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
    
    .filter-btn:hover {
        background: var(--bg);
    }
    
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
    
    /* Lista de postulantes */
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
    
    .applicant-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    /* Header de la tarjeta */
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
    
    .applicant-name a {
        color: var(--primary);
        text-decoration: none;
    }
    
    .applicant-name a:hover {
        text-decoration: underline;
    }
    
    .applicant-career {
        font-size: 13px;
        color: var(--muted);
        margin-bottom: 4px;
    }
    
    .applicant-date {
        font-size: 11px;
        color: var(--muted);
    }
    
    /* Badge de estado */
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        border: 1px solid transparent;
    }
    
    .status-pending {
        background: var(--bg);
        color: var(--text);
        border-color: var(--border);
    }
    
    .status-accepted {
        background: rgba(46, 204, 154, 0.12);
        color: #2ECC9A;
        border-color: rgba(46, 204, 154, 0.3);
    }
    
    .status-rejected {
        background: rgba(212, 24, 61, 0.10);
        color: #e05577;
        border-color: rgba(212, 24, 61, 0.3);
    }
    
    /* Layout de dos columnas */
    .card-content {
        display: flex;
        gap: 24px;
        flex-wrap: wrap;
    }
    
    .card-left {
        flex: 2;
        min-width: 200px;
    }
    
    .card-right {
        flex: 1;
        min-width: 200px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    /* Información de contacto */
    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 16px;
    }
    
    .contact-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }
    
    .contact-icon {
        color: var(--muted);
        width: 18px;
        height: 18px;
    }
    
    .contact-item a {
        color: var(--primary);
        text-decoration: none;
    }
    
    .contact-item a:hover {
        text-decoration: underline;
    }
    
    /* Botones de acción izquierda */
    .action-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    
    .btn-accept {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: #2ECC9A;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: opacity 0.2s;
    }
    
    .btn-accept:hover {
        opacity: 0.85;
    }
    
    .btn-reject {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: opacity 0.2s;
    }
    
    .btn-reject:hover {
        opacity: 0.85;
    }
    
    /* Botones lado derecho */
    .btn-download {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 8px 16px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: opacity 0.2s;
    }
    
    [data-theme="dark"] .btn-download {
        background: var(--accent);
        color: #111118;
    }
    
    .btn-download:hover {
        opacity: 0.85;
    }
    
    .social-links {
        display: flex;
        gap: 8px;
    }
    
    .social-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 8px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        transition: opacity 0.2s;
        color: white;
    }
    
    .social-linkedin {
        background: #0A66C2;
    }
    
    .social-github {
        background: #333;
    }
    
    .social-btn:hover {
        opacity: 0.85;
    }
    
    .btn-contact {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 8px 16px;
        background: var(--bg);
        border: 1px solid var(--border);
        color: var(--text);
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-contact:hover {
        background: var(--surface);
        border-color: var(--accent);
    }
    
    /* Estado mensaje */
    .status-message {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: var(--bg);
        border-radius: 6px;
        font-size: 13px;
        color: var(--muted);
    }
    
    /* Diálogo de confirmación */
    .dialog-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    
    .dialog-content {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 24px;
        max-width: 400px;
        width: 90%;
    }
    
    .dialog-title {
        font-size: 20px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 12px;
    }
    
    .dialog-message {
        color: var(--muted);
        margin-bottom: 24px;
    }
    
    .dialog-buttons {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }
    
    .dialog-cancel {
        padding: 8px 16px;
        background: var(--bg);
        border: 1px solid var(--border);
        color: var(--text);
        border-radius: 6px;
        cursor: pointer;
    }
    
    .dialog-confirm-accept {
        padding: 8px 16px;
        background: #2ECC9A;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }
    
    .dialog-confirm-reject {
        padding: 8px 16px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }
    
    .sin-resultados {
        text-align: center;
        padding: 60px 20px;
        color: var(--muted);
    }
    
    @media (max-width: 768px) {
        .postulantes-container {
            padding: 16px;
        }
        
        .card-content {
            flex-direction: column;
        }
        
        .card-header {
            flex-direction: column;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .btn-accept, .btn-reject {
            justify-content: center;
        }
    }
</style>

<div class="postulantes-container">
    <!-- Volver -->
    <a href="{{ route('empresa.home') }}" class="back-link">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Volver al Dashboard
    </a>

    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">Postulantes - Oferta #{{ $oferta->id_oferta }}</h1>
        <p class="page-subtitle">{{ $oferta->titulo }}</p>
    </div>

    <!-- Filtros -->
    <<div class="filter-tabs">
        <button class="filter-btn active" data-estado="todos">Todos ({{ $postulantes->count() }})</button>
        <button class="filter-btn" data-estado="postulado">Postulados ({{ $postulantes->where('estado', 'postulado')->count() }})</button>
        <button class="filter-btn" data-estado="en_revision">En Revisión ({{ $postulantes->where('estado', 'en_revision')->count() }})</button>
        <button class="filter-btn" data-estado="preseleccionado">Preseleccionados ({{ $postulantes->where('estado', 'preseleccionado')->count() }})</button>
        <button class="filter-btn" data-estado="en_contacto">En Contacto ({{ $postulantes->where('estado', 'en_contacto')->count() }})</button>
        <button class="filter-btn" data-estado="rechazado">Rechazados ({{ $postulantes->where('estado', 'rechazado')->count() }})</button>
    </div>

    <!-- Lista de postulantes -->
    <div class="applicants-list" id="applicantsList">
        @forelse($postulantes as $postulante)
        <div class="applicant-card" data-estado="{{ $postulante->estado }}" data-id="{{ $postulante->id }}">
            <div class="card-header">
                <div>
                    <h3 class="applicant-name">
                        <a href="{{ route('estudiante.perfil', $postulante->id) }}">{{ $postulante->nombre }}</a>
                    </h3>
                    <div class="applicant-career">{{ $postulante->carrera }}</div>
                    <div class="applicant-date">Postulado: {{ \Carbon\Carbon::parse($postulante->fecha_postulacion)->format('d/m/Y') }}</div>
                </div>
                <span class="status-badge status-{{ $postulante->estado }}">
                    @if($postulante->estado == 'pending') Pendiente
                    @elseif($postulante->estado == 'aceptado') Aceptado
                    @else Rechazado
                    @endif
                </span>
            </div>

            <div class="card-content">
                <!-- Lado izquierdo -->
                <div class="card-left">
                    <div class="contact-info">
                        <div class="contact-item">
                            <svg class="contact-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="4" width="20" height="16" rx="2"/>
                                <path d="M22 7l-10 7L2 7"/>
                            </svg>
                            <a href="mailto:{{ $postulante->email }}">{{ $postulante->email }}</a>
                        </div>
                        <div class="contact-item">
                            <svg class="contact-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                            <span>{{ $postulante->telefono }}</span>
                        </div>
                    </div>

                    <!-- Botones de acción izquierda -->
                    @if($postulante->estado_original != 'Rechazado')
                    <div class="action-buttons">
                        <button class="btn-accept" onclick="openConfirmDialog({{ $postulante->id }}, 'accept')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Aceptar / Avanzar
                        </button>
                        <button class="btn-reject" onclick="openConfirmDialog({{ $postulante->id }}, 'reject')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"/>
                                <line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                            Rechazar
                        </button>
                    </div>
                    @endif
                </div>

                <!-- Lado derecho -->
                <div class="card-right">
                    <a href="#" class="btn-download" onclick="mostrarCVModal({{ $postulante->id }})">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        Mirar CV
                    </a>

                    <div class="social-links">
                        <a href="#" class="social-btn social-linkedin" target="_blank">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/>
                                <rect x="2" y="9" width="4" height="12"/>
                                <circle cx="4" cy="4" r="2"/>
                            </svg>
                        </a>
                        <a href="#" class="social-btn social-github" target="_blank">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/>
                            </svg>
                        </a>
                    </div>

                    <a href="{{ route('empresa.mensajes') }}" class="btn-contact">
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
        <h3 class="dialog-title" id="dialogTitle">Confirmar Aceptación</h3>
        <p class="dialog-message" id="dialogMessage">¿Estás seguro que deseas aceptar a este postulante?</p>
        <div class="dialog-buttons">
            <button class="dialog-cancel" onclick="closeDialog()">Cancelar</button>
            <button id="dialogConfirmBtn" class="dialog-confirm-accept">Sí, Aceptar</button>
        </div>
    </div>
</div>

<script>
    let pendingAction = { applicantId: null, action: null };

    function openConfirmDialog(applicantId, action) {
        pendingAction = { applicantId, action };
        const dialog = document.getElementById('confirmDialog');
        const title = document.getElementById('dialogTitle');
        const message = document.getElementById('dialogMessage');
        const confirmBtn = document.getElementById('dialogConfirmBtn');
        
        // Obtener el estado actual del postulante
        const card = document.querySelector(`.applicant-card[data-id="${applicantId}"]`);
        const estadoActual = card.querySelector('.status-badge').textContent;
        
        // Definir el siguiente estado según el actual
        const estadosSiguientes = {
            'Postulado': 'En Revision',
            'En Revision': 'Preseleccionado',
            'Preseleccionado': 'En Contacto',
            'En Contacto': 'En Contacto' // Si ya está en contacto, se queda ahí
        };
        
        if (action === 'accept') {
            const siguienteEstado = estadosSiguientes[estadoActual] || 'En Revision';
            title.textContent = `Mover a "${siguienteEstado}"`;
            message.textContent = `¿Estás seguro que deseas mover a este postulante a "${siguienteEstado}"?`;
            confirmBtn.className = 'dialog-confirm-accept';
            confirmBtn.textContent = `Sí, mover a "${siguienteEstado}"`;
        } else {
            title.textContent = 'Confirmar Rechazo';
            message.textContent = '¿Estás seguro que deseas rechazar a este postulante?';
            confirmBtn.className = 'dialog-confirm-reject';
            confirmBtn.textContent = 'Sí, Rechazar';
        }
        
        dialog.style.display = 'flex';
    }

    function closeDialog() {
        document.getElementById('confirmDialog').style.display = 'none';
        pendingAction = { applicantId: null, action: null };
    }

    function actualizarEstado(applicantId, nuevoEstadoOriginal) {
        const card = document.querySelector(`.applicant-card[data-id="${applicantId}"]`);
        if (!card) return;
        
        // Mapeo de estados para clases CSS
        const estadoClaseMap = {
            'Postulado': 'status-pending',
            'En Revision': 'status-pending',
            'Preseleccionado': 'status-accepted',
            'En Contacto': 'status-accepted',
            'Rechazado': 'status-rejected'
        };
        
        const estadoFiltroMap = {
            'Postulado': 'postulado',
            'En Revision': 'en_revision',
            'Preseleccionado': 'preseleccionado',
            'En Contacto': 'en_contacto',
            'Rechazado': 'rechazado'
        };
        
        // Actualizar badge
        const badge = card.querySelector('.status-badge');
        badge.className = `status-badge ${estadoClaseMap[nuevoEstadoOriginal] || 'status-pending'}`;
        badge.textContent = nuevoEstadoOriginal;
        
        // Actualizar data-estado para filtros
        card.setAttribute('data-estado', estadoFiltroMap[nuevoEstadoOriginal] || 'postulado');
        
        // Si es rechazado, ocultar los botones
        if (nuevoEstadoOriginal === 'Rechazado') {
            const leftSide = card.querySelector('.card-left');
            const actionButtons = leftSide.querySelector('.action-buttons');
            if (actionButtons) {
                actionButtons.remove();
            }
        } else {
            // Actualizar el texto del botón para el próximo estado
            const actionButtons = card.querySelector('.action-buttons');
            if (actionButtons) {
                const acceptBtn = actionButtons.querySelector('.btn-accept');
                if (acceptBtn) {
                    // Opcional: cambiar el texto del botón según el nuevo estado
                    const nuevosEstados = {
                        'En Revision': 'Mover a Preseleccionado',
                        'Preseleccionado': 'Mover a En Contacto',
                        'En Contacto': 'En Contacto'
                    };
                    if (nuevosEstados[nuevoEstadoOriginal]) {
                        acceptBtn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                ${nuevosEstados[nuevoEstadoOriginal]}`;
                    }
                }
            }
        }
        
        // Enviar petición AJAX
        fetch(`/empresa/postulacion/${applicantId}/estado`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ estado: nuevoEstadoOriginal })
        });
        
        setTimeout(() => location.reload(), 500);
    }

    // Configurar el botón de confirmación
    document.getElementById('dialogConfirmBtn').addEventListener('click', function() {
        if (pendingAction.applicantId && pendingAction.action) {
            let nuevoEstado;
            
            if (pendingAction.action === 'accept') {
                // Obtener el estado actual para determinar el siguiente
                const card = document.querySelector(`.applicant-card[data-id="${pendingAction.applicantId}"]`);
                const estadoActual = card.querySelector('.status-badge').textContent;
                
                const estadosSiguientes = {
                    'Postulado': 'En Revision',
                    'En Revision': 'Preseleccionado',
                    'Preseleccionado': 'En Contacto',
                    'En Contacto': 'En Contacto'
                };
                nuevoEstado = estadosSiguientes[estadoActual] || 'En Revision';
            } else {
                nuevoEstado = 'Rechazado';
            }
            
            actualizarEstado(pendingAction.applicantId, nuevoEstado);
            closeDialog();
        }
    });

    // Filtrado dinámico
    const filtros = document.querySelectorAll('.filter-btn');
    const postulantesCards = document.querySelectorAll('.applicant-card');
    
    function filtrarPostulantes(estado) {
        let visibleCount = 0;
        postulantesCards.forEach(card => {
            const cardEstado = card.getAttribute('data-estado');
            if (estado === 'todos' || cardEstado === estado) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        let sinResultados = document.querySelector('.sin-resultados');
        if (visibleCount === 0) {
            if (!sinResultados) {
                const list = document.getElementById('applicantsList');
                const mensaje = document.createElement('div');
                mensaje.className = 'sin-resultados';
                mensaje.innerHTML = '<p>📭 No hay postulantes con este estado.</p>';
                list.appendChild(mensaje);
            }
        } else {
            if (sinResultados) sinResultados.remove();
        }
    }
    
    filtros.forEach(btn => {
        btn.addEventListener('click', function() {
            filtros.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const estado = this.getAttribute('data-estado');
            filtrarPostulantes(estado);
        });
    });
    
    function descargarCV(applicantId) {
        console.log('Descargar CV del postulante:', applicantId);
        alert('Funcionalidad: Descargar CV');
    }
</script>
@endsection
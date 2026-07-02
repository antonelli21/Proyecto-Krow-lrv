@extends('layouts.app')

@section('title', 'Mensajes — KROW')

{{-- Inyectamos el ID del usuario autenticado --}}
@auth
    <script>
        window.App = window.App || {};
        window.App.userId = {{ Auth::id() }};
    </script>
@endauth
@section('content')
<div id="mensajes-page" class="mensajes-page">

    {{-- ═══ SIDEBAR (lista de conversaciones) ═══ --}}
    <aside class="mensajes-sidebar">
        <div class="sidebar-header" id="header-container">
            <div class="header-top">
                <h2 class="sidebar-title">CONVERSACIONES</h2>
                <button id="btn-recarga" class="btn-recarga" title="Actualizar">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>

            <div class="search-wrapper">
                <i class="bi bi-search search-icon"></i>
                <input id="mensajes-search" type="text" placeholder="Buscar conversación…" class="search-input">
            </div>
        </div>
        <div id="chats-lista" class="chats-lista">
            <p class="loading-placeholder">Cargando…</p>
        </div>
    </aside>

    {{-- ═══ PANEL DERECHO (chat activo) ═══ --}}
    <section class="mensajes-panel">

        {{-- Cabecera del chat --}}
        <div id="panel-header" class="panel-header" style="display:none;">
            <div id="panel-avatar" class="panel-avatar">?</div>
            <div>
                <div id="panel-nombre" class="panel-nombre">—</div>
                <div id="panel-rol" class="panel-rol"></div>
            </div>
        </div>

        {{-- Historial de mensajes --}}
        <div id="mensajes-historial" class="mensajes-historial"></div>

        {{-- Estado vacío --}}
        <div id="mensajes-vacio" class="mensajes-vacio">
            <i class="bi bi-chat-dots vacio-icon"></i>
            <p>Seleccioná un chat para empezar a hablar.</p>
        </div>

        {{-- Formulario de envío --}}
        <form id="form-mensaje" class="form-mensaje" style="display:none;">
            @csrf

            {{-- Previsualización del PDF adjunto --}}
            <div id="pdf-preview-container" class="pdf-preview" style="display:none;">
                <i class="bi bi-file-earmark-pdf-fill pdf-icon"></i>
                <span id="pdf-preview-name" class="pdf-preview-name"></span>
                <i id="pdf-preview-remove" class="bi bi-x-circle-fill pdf-preview-remove"></i>
            </div>

            <div class="input-group">
                <label for="input-archivo" class="attach-btn" title="Adjuntar PDF">
                    <i class="bi bi-paperclip"></i>
                </label>
                <input id="input-archivo" type="file" name="archivo" accept="application/pdf" class="file-input">

                <textarea id="input-contenido" placeholder="Escribí un mensaje…" rows="1" class="message-input"></textarea>

                <button type="submit" class="send-btn" title="Enviar mensaje">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
        </form>

    </section>
</div>
{{-- ═══ ESTILOS ═══ --}}
<style>
    /* ── Variables globales (heredadas de layouts.app) ── */
        .mensajes-page {
            display: flex;
            height: calc(100vh - var(--header-h, 70px));
            min-height: 500px;
            overflow: hidden;
            background: var(--bg);
        }

        /* Ocultamos el footer completo en la página de mensajes para que no tape el chat */
        body:has(.mensajes-page) .site-footer,
        body:has(.mensajes-page) .footer-bottom {
            display: none !important;
        }

    /* ── Sidebar ── */
    .mensajes-sidebar {
        width: 300px;
        flex-shrink: 0;
        background: var(--surface);
        border-right: 0.5px solid var(--border);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .sidebar-header {
        padding: 18px 16px 12px;
        border-bottom: 0.5px solid var(--border);
    }

    .sidebar-title {
        font-family: var(--font-display);
        font-size: 15px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 12px;
    }

    .search-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-icon {
        position: absolute;
        left: 10px;
        color: var(--muted);
        font-size: 13px;
    }

    .search-input {
        width: 100%;
        padding: 8px 10px 8px 30px;
        background: var(--bg);
        border: 0.5px solid var(--border);
        border-radius: var(--radius);
        color: var(--text);
        font-size: 12.5px;
        outline: none;
    }

    .chats-lista {
        flex: 1;
        overflow-y: auto;
        scrollbar-width: thin;
    }

    .loading-placeholder {
        text-align: center;
        color: var(--muted);
        padding: 20px;
        font-size: 13px;
    }

    /* ── Elementos de la lista ── */
    .chat-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        border-bottom: 0.5px solid var(--border);
        cursor: pointer;
        background: transparent;
        transition: background 0.15s, border-right 0.15s;
    }

    .chat-item:hover {
        background: var(--bg-hover);
    }

    .chat-item.active {
        background: var(--accent-dim);
        border-right: 2px solid var(--accent);
    }

    .chat-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--accent);
        color: #0D1A13;
        font-family: var(--font-display);
        font-size: 15px;
        font-weight: 800;
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }

    .chat-avatar img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    .chat-info {
        flex: 1;
        min-width: 0;
    }

    .chat-nombre {
        font-family: var(--font-display);
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chat-preview {
        font-size: 12px;
        color: var(--muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 2px;
    }

    .chat-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 4px;
        flex-shrink: 0;
    }

    .chat-hora {
        font-size: 11px;
        color: var(--text-muted);
    }

    .chat-badge {
        background: var(--accent);
        color: #0D1A13;
        font-size: 10px;
        font-weight: 700;
        border-radius: 20px;
        padding: 1px 6px;
    }

    /* ── Panel derecho ── */
    .mensajes-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        background: var(--bg);
    }

    .panel-header {
        display: none;
        padding: 14px 20px;
        border-bottom: 0.5px solid var(--border);
        background: var(--surface);
        align-items: center;
        gap: 12px;
    }

    .panel-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--accent);
        color: #0D1A13;
        font-family: var(--font-display);
        font-size: 13px;
        font-weight: 800;
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }

    .panel-avatar img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    .panel-nombre {
        font-family: var(--font-display);
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
    }

    .panel-rol {
        font-size: 11.5px;
        color: var(--muted);
    }

    .mensajes-historial {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        scrollbar-width: thin;
    }

    .fecha-separador {
        text-align: center;
        font-size: 11px;
        color: var(--text-muted);
        font-family: var(--font-display);
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 8px 0;
    }

    .mensaje-burbuja {
        display: flex;
        flex-direction: column;
        max-width: 68%;
        align-items: flex-start;
    }

    .mensaje-burbuja.propio {
        align-self: flex-end;
        align-items: flex-end;
    }

    .mensaje-burbuja .burbuja {
        padding: 10px 14px;
        border-radius: 14px;
        font-size: 13.5px;
        line-height: 1.5;
        word-wrap: break-word;
        max-width: 100%;
    }

    .mensaje-burbuja.propio .burbuja {
        border-bottom-right-radius: 4px;
        background: var(--accent);
        color: var(--text_btn);
    }

    .mensaje-burbuja.ajeno .burbuja {
        border-bottom-left-radius: 4px;
        background: var(--surface);
        color: var(--text);
        border: 0.5px solid var(--border);
    }

    .mensaje-burbuja .hora {
        font-size: 10.5px;
        color: var(--text-muted);
        margin-top: 3px;
        padding: 0 4px;
    }

    .adjunto-pdf {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .adjunto-pdf .pdf-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border-radius: 8px;
        color: inherit;
        text-decoration: none;
        border: 0.5px solid var(--border);
        transition: background 0.2s;
    }

    .mensaje-burbuja.propio .pdf-link {
        background: rgba(0, 0, 0, 0.1);
        border-color: rgba(0, 0, 0, 0.15);
    }

    .mensaje-burbuja.ajeno .pdf-link {
        background: rgba(255, 255, 255, 0.05);
    }

    .pdf-link .pdf-icon {
        color: #ef4444;
        font-size: 22px;
    }

    .pdf-link .pdf-info {
        min-width: 0;
        flex: 1;
    }

    .pdf-link .pdf-nombre {
        font-size: 12.5px;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pdf-link .pdf-tamaño {
        font-size: 10px;
        opacity: 0.7;
    }

    .mensajes-vacio {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        gap: 12px;
    }

    .vacio-icon {
        font-size: 42px;
        color: var(--border);
    }

    /* ── Formulario ── */
    .form-mensaje {
        display: none;
        padding: 14px 16px;
        border-top: 0.5px solid var(--border);
        background: var(--surface);
        flex-direction: column;
        gap: 8px;
    }

    .pdf-preview {
        display: none;
        align-items: center;
        gap: 8px;
        background: var(--bg);
        padding: 6px 12px;
        border: 0.5px solid var(--border);
        border-radius: 8px;
        width: fit-content;
    }

    .pdf-preview .pdf-icon {
        color: #ef4444;
        font-size: 16px;
    }

    .pdf-preview-name {
        font-size: 12px;
        color: var(--text);
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .pdf-preview-remove {
        color: var(--muted);
        cursor: pointer;
        font-size: 14px;
    }

    .input-group {
        display: flex;
        width: 100%;
        align-items: center;
        gap: 10px;
    }

    .attach-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--bg);
        border: 0.5px solid var(--border);
        display: grid;
        place-items: center;
        cursor: pointer;
        color: var(--text);
        font-size: 16px;
        flex-shrink: 0;
        transition: background 0.15s;
    }

    .attach-btn:hover {
        background: var(--bg-hover);
    }

    .file-input {
        display: none;
    }

    .message-input {
        flex: 1;
        padding: 10px 14px;
        background: var(--bg);
        border: 0.5px solid var(--border);
        border-radius: 20px;
        color: var(--text);
        font-size: 13.5px;
        outline: none;
        resize: none;
        font-family: inherit;
    }

    .send-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background: var(--accent);
        color: #0D1A13;
        display: grid;
        place-items: center;
        cursor: pointer;
        font-size: 16px;
        flex-shrink: 0;
        transition: background 0.15s;
    }

    .send-btn:hover {
        background: var(--accent-hover);
    }

    /* ── Responsive: Móvil ── */
    @media (max-width: 640px) {
        .mensajes-page {
            flex-direction: column !important;
        }

        .mensajes-sidebar {
            width: 100% !important;
            border-right: none !important;
            max-height: 100%;
            transition: max-height 0.2s ease;
        }

        .mensajes-page.chat-abierto .mensajes-sidebar {
            max-height: 60px !important;
            overflow: hidden !important;
            border-bottom: 0.5px solid var(--border) !important;
        }

        .mensajes-page.chat-abierto .chats-lista {
            display: none !important;
        }

        .mensajes-page.chat-abierto .sidebar-header {
            display: flex !important;
            align-items: center !important;
            padding: 10px 16px !important;
        }

        .mensajes-page.chat-abierto .sidebar-title {
            display: none !important;
        }

        .mensajes-page.chat-abierto .search-wrapper {
            display: none !important;
        }



        .mensajes-page.chat-abierto #btn-volver-chats {
            display: flex !important;
        }

        .mensajes-panel {
            flex: 1 !important;
            min-height: 0 !important;
            overflow: hidden !important;
        }

        .panel-header {
            padding: 10px 14px !important;
            gap: 10px !important;
        }

        .panel-avatar {
            width: 30px !important;
            height: 30px !important;
            font-size: 11px !important;
        }

        .panel-nombre {
            font-size: 13px !important;
        }
        .panel-rol {
            font-size: 11px !important;
        }

        .mensajes-historial {
            padding: 12px 10px !important;
            gap: 8px !important;
        }

        .mensaje-burbuja {
            max-width: 82% !important;
        }

        .form-mensaje {
            padding: 10px 12px !important;
            gap: 8px !important;
        }

        .message-input {
            font-size: 14px !important;
            padding: 8px 12px !important;
        }

        .send-btn {
            width: 36px !important;
            height: 36px !important;
            font-size: 14px !important;
        }

        .vacio-icon {
            font-size: 32px !important;
        }
        .mensajes-vacio p {
            font-size: 13px !important;
            text-align: center !important;
            padding: 0 20px !important;
        }
    }

            .header-top {
            display: flex;
            align-items: center; /* Alinea verticalmente */
            gap: 10px;          /* Espacio entre título y botón */
            margin-bottom: 12px;
        }

    .btn-recarga {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border: 1px solid var(--border, #d0d0d0);
        border-radius: 8px;
        background: var(--accent);   
        color: var(--text_btn);              
        cursor: pointer;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-card, none);   
    }

    .btn-recarga:hover {
        background: var(--bg-hover, #e0e0e0);
        border-color: var(--accent, #077552);  /* Resalta con el color acento */
        color: var(--accent, #077552);
        transform: scale(1.05);
    }

    .btn-recarga:active {
        transform: scale(0.92);
    }

    /* ── Animación de giro ── */
    @keyframes spin {
        0%   { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .btn-recarga i.girar {
        animation: spin 0.6s linear;
    }

                    @keyframes pulse-gold {
                        0% {
                            box-shadow: 0 0 0 0 rgba(255, 215, 0, 0.4);
                        }
                        70% {
                            box-shadow: 0 0 0 8px rgba(255, 215, 0, 0);
                        }
                        100% {
                            box-shadow: 0 0 0 0 rgba(255, 215, 0, 0);
                        }
                    }
                /* ── Responsive: Tablet ── */
                @media (min-width: 641px) and (max-width: 900px) {
                    .mensajes-sidebar {
                        width: 220px !important;
                    }
                    .panel-header {
                        padding: 12px 16px !important;
                    }
                    .mensajes-historial {
                        padding: 16px 14px !important;
                    }
                    .mensaje-burbuja {
                        max-width: 75% !important;
                    }

                    /* Footer reducido en tablet */
                    body:has(.mensajes-page) footer,
                    body:has(.mensajes-page) .footer,
                    body:has(.mensajes-page) #main-footer {
                        padding: 2px 8px !important;
                        min-height: 24px !important;
                        font-size: 9px !important;
                        line-height: 1.2 !important;
                        border-top: 0.5px solid var(--border) !important;
                    }
                }

                /* ── Responsive: Móvil (ocultar footer completamente) ── */
                @media (max-width: 640px) {
                    body:has(.mensajes-page) footer,
                    body:has(.mensajes-page) .footer,
                    body:has(.mensajes-page) #main-footer {
                        display: none !important;
                    }
                }




</style>
@endsection

@section('scripts')
<script>
(function () {
    'use strict';

    // ── Inicialización ──────────────────────────────────────────────────────────
    const miId = window.App?.userId || null;
    if (!miId) {
        console.warn('[Mensajes] No se pudo obtener el ID del usuario autenticado.');
    }

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // ── Referencias DOM ─────────────────────────────────────────────────────────
    const listEl           = document.getElementById('chats-lista');
    const historialEl      = document.getElementById('mensajes-historial');
    const panelHeader      = document.getElementById('panel-header');
    const panelAvatar      = document.getElementById('panel-avatar');
    const panelNombre      = document.getElementById('panel-nombre');
    const panelRol         = document.getElementById('panel-rol');
    const vacioEl          = document.getElementById('mensajes-vacio');
    const formEl           = document.getElementById('form-mensaje');
    const inputEl          = document.getElementById('input-contenido');
    const searchEl         = document.getElementById('mensajes-search');
    const archivoEl        = document.getElementById('input-archivo');
    const pdfPreviewCont   = document.getElementById('pdf-preview-container');
    const pdfPreviewName   = document.getElementById('pdf-preview-name');
    const pdfPreviewRemove = document.getElementById('pdf-preview-remove');

    let chatActivoId = null;
    let intervalo    = null;

    // ── Utilidades ──────────────────────────────────────────────────────────────

    const escaparHTML = (texto) => {
        const div = document.createElement('div');
        div.textContent = texto;
        return div.innerHTML;
    };

    const inicial    = (nombre) => nombre ? nombre.charAt(0).toUpperCase() : '?';
    const formatHora = (iso)    => new Date(iso).toLocaleTimeString('es-AR', { hour: '2-digit', minute: '2-digit' });

    const formatFecha = (iso) => {
        const d   = new Date(iso);
        const hoy = new Date();
        if (d.toDateString() === hoy.toDateString()) return 'Hoy';
        const ayer = new Date();
        ayer.setDate(hoy.getDate() - 1);
        if (d.toDateString() === ayer.toDateString()) return 'Ayer';
        return d.toLocaleDateString('es-AR', { day: '2-digit', month: 'long' });
    };

    // ── Notificaciones ──────────────────────────────────────────────────────────

    const notificar = (mensaje, tipo = 'info') => {
        if (tipo === 'error') {
            alert('❌ ' + mensaje);
        } else {
            console.log('[Mensajes]', mensaje);
        }
    };

    // ── Limpieza de preview PDF ─────────────────────────────────────────────────

    const limpiarPreviewPDF = () => {
        if (archivoEl)      archivoEl.value = '';
        if (pdfPreviewCont) pdfPreviewCont.style.display = 'none';
        if (pdfPreviewName) pdfPreviewName.textContent   = '';
        if (inputEl)        inputEl.required = true;
    };

    // ── Cerrar chat activo ──────────────────────────────────────────────────────

    const cerrarChat = () => {
        chatActivoId = null;
        if (intervalo) {
            clearInterval(intervalo);
            intervalo = null;
        }
        if (panelHeader) panelHeader.style.display = 'none';
        if (vacioEl)     vacioEl.style.display     = 'flex';
        if (formEl)      formEl.style.display      = 'none';
        if (historialEl) historialEl.innerHTML     = '';
        document.querySelectorAll('.chat-item.active').forEach(el => el.classList.remove('active'));
    };

    // ── Cargar lista de chats ───────────────────────────────────────────────────

    const cargarChats = () => {
        if (!listEl) return;
        listEl.innerHTML = '<p class="loading-placeholder">Cargando…</p>';

        fetch('/api/chats')
            .then(r => {
                if (!r.ok) throw new Error('Error HTTP: ' + r.status);
                return r.json();
            })
            .then(chats => {
                const query = (searchEl?.value || '').toLowerCase();
                let html    = '';

                if (!chats?.length) {
                    listEl.innerHTML = '<p class="loading-placeholder">Sin conversaciones aún.</p>';
                    return;
                }

                chats.forEach(chat => {
                    const otro   = parseInt(chat.id_usuario_1) === miId ? chat.usuario2 : chat.usuario1;
                    const nombre = otro?.name || 'Usuario';

                    if (query && !nombre.toLowerCase().includes(query)) return;

                    const ultimo  = chat.ultimo_mensaje;
                    const activo  = chat.id_chat === chatActivoId;
                    const noLeidos = chat.no_leidos ?? 0;

                    let textoUltimo = ultimo ? escaparHTML(ultimo.contenido) : 'Sin mensajes aún';
                    if (ultimo?.ruta_archivo) {
                        textoUltimo = `<i class="bi bi-paperclip"></i> ${escaparHTML(ultimo.nombre_archivo ?? 'Archivo adjunto')}`;
                    }

                    const avatarHtml = otro?.avatar_ruta
                        ? `<div class="chat-avatar"><img src="/storage/${otro.avatar_ruta}" alt="Avatar de ${escaparHTML(nombre)}"></div>`
                        : `<div class="chat-avatar">${inicial(nombre)}</div>`;

                    const hora  = ultimo ? formatHora(ultimo.fecha_envio) : '';
                    const badge = noLeidos > 0 ? `<span class="chat-badge">${noLeidos}</span>` : '';

                    html += `
                        <div class="chat-item ${activo ? 'active' : ''}" data-chat-id="${chat.id_chat}">
                            ${avatarHtml}
                            <div class="chat-info">
                                <div class="chat-nombre">${escaparHTML(nombre)}</div>
                                <div class="chat-preview">${textoUltimo}</div>
                            </div>
                            <div class="chat-meta">
                                <span class="chat-hora">${hora}</span>
                                ${badge}
                            </div>
                        </div>
                    `;
                });

                if (!html) {
                    listEl.innerHTML = '<p class="loading-placeholder">No se encontraron conversaciones.</p>';
                    return;
                }

                listEl.innerHTML = html;

                document.querySelectorAll('.chat-item').forEach(item => {
                    item.addEventListener('click', function () {
                        const chatId = parseInt(this.dataset.chatId);
                        const chat   = chats.find(c => c.id_chat === chatId);
                        if (!chat) return;

                        const otro = parseInt(chat.id_usuario_1) === miId ? chat.usuario2 : chat.usuario1;
                        abrirChat(chat, otro);

                        if (window.innerWidth <= 640) {
                            document.getElementById('mensajes-page')?.classList.add('chat-abierto');
                        }
                    });
                });
            })
            .catch(err => {
                console.error('[Mensajes] Error cargando chats:', err);
                listEl.innerHTML = `<p class="loading-placeholder" style="color:var(--danger);">Error al cargar conversaciones.</p>`;
                notificar('No se pudieron cargar los chats.', 'error');
            });
    };

    // ── Abrir chat ──────────────────────────────────────────────────────────────

    const abrirChat = (chat, otro) => {
        if (!chat || !otro) return;
        chatActivoId = chat.id_chat;
        const nombre = otro.name || 'Usuario';
        console.log('perfil_id:', otro.perfil_id, 'rol:', otro.rol, 'otro completo:', JSON.stringify(otro));    
        if (panelAvatar) {
            if (otro.avatar_ruta) {
                panelAvatar.innerHTML = `<img src="/storage/${otro.avatar_ruta}" alt="Avatar">`;
            } else {
                panelAvatar.textContent      = inicial(nombre);
                panelAvatar.style.background = 'var(--accent)';
            }
        }
        // Armar URL según el rol del otro usuario
        const miRol = document.documentElement.dataset.role || '{{ auth()->user()->rol ?? "" }}';
        let perfilUrl = '#';

        if (otro.rol === 'estudiante' && otro.perfil_id) {
    if (miRol === 'empresa') {
        perfilUrl = `/empresa/estudiante/${otro.perfil_id}`;
    } else if (miRol === 'admin') {
        perfilUrl = `/admin/estudiante/${otro.perfil_id}`;
    }
} else if (otro.rol === 'empresa' && otro.perfil_id) {
    perfilUrl = `/empresas/${otro.perfil_id}`;
}

        if (panelNombre) {
            panelNombre.innerHTML = `<a href="${perfilUrl}" style="color:inherit; text-decoration:none; cursor:pointer;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">${escaparHTML(nombre)}</a>`;
        }
        if (panelRol)    panelRol.textContent    = otro.rol || '';
        if (panelHeader) panelHeader.style.display = 'flex';
        if (vacioEl)     vacioEl.style.display     = 'none';
        if (formEl)      formEl.style.display      = 'flex';

        limpiarPreviewPDF();

        document.querySelectorAll('.chat-item').forEach(el => el.classList.remove('active'));
        document.querySelector(`[data-chat-id="${chat.id_chat}"]`)?.classList.add('active');

        cargarMensajes();

        if (intervalo) clearInterval(intervalo);
        intervalo = setInterval(cargarMensajes, 5000);
    };

    // ── Cargar historial de mensajes ────────────────────────────────────────────

    const cargarMensajes = () => {
        if (!chatActivoId || !historialEl) return;

        fetch(`/api/chats/${chatActivoId}/mensajes`)
            .then(r => {
                if (!r.ok) throw new Error('Error HTTP: ' + r.status);
                return r.json();
            })
            .then(data => {
                const mensajes  = data.mensajes || [];
                let html        = '';
                let ultimaFecha = null;

                mensajes.forEach(msg => {
                    const fecha = formatFecha(msg.fecha_envio);
                    if (fecha !== ultimaFecha) {
                        html       += `<div class="fecha-separador">${fecha}</div>`;
                        ultimaFecha = fecha;
                    }

                    const propio = parseInt(msg.id_remitente) === miId;
                    const clase  = propio ? 'propio' : 'ajeno';

                    let cuerpoContenido = '';
                    if (msg.ruta_archivo) {
                        const nombreArchivo = escaparHTML(msg.nombre_archivo || 'Descargar PDF');
                        const contenido     = escaparHTML(msg.contenido || '');
                        cuerpoContenido = `
                            <div class="adjunto-pdf">
                                ${contenido ? `<span>${contenido}</span>` : ''}
                                <a href="/storage/${msg.ruta_archivo}" target="_blank" rel="noopener noreferrer" download class="pdf-link">
                                    <i class="bi bi-file-earmark-pdf-fill pdf-icon"></i>
                                    <div class="pdf-info">
                                        <div class="pdf-nombre">${nombreArchivo}</div>
                                        <div class="pdf-tamaño">Click para descargar</div>
                                    </div>
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        `;
                    } else {
                        cuerpoContenido = `<span>${escaparHTML(msg.contenido || '')}</span>`;
                    }

                    html += `
                        <div class="mensaje-burbuja ${clase}">
                            <div class="burbuja">${cuerpoContenido}</div>
                            <span class="hora">${formatHora(msg.fecha_envio)}</span>
                        </div>
                    `;
                });

                historialEl.innerHTML = html;
                historialEl.scrollTop = historialEl.scrollHeight;
            })
            .catch(err => {
                console.error('[Mensajes] Error cargando mensajes:', err);
            });
    };

    // ── Enviar mensaje ──────────────────────────────────────────────────────────

    const enviarMensaje = (e) => {
        e.preventDefault();

        const contenido    = inputEl?.value.trim() || '';
        const tieneArchivo = archivoEl?.files?.length > 0;

        if (!contenido && !tieneArchivo) {
            notificar('Escribí un mensaje o adjuntá un archivo.', 'info');
            return;
        }

        if (!chatActivoId) {
            notificar('No hay un chat activo.', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('id_chat', chatActivoId);
        if (contenido) formData.append('contenido', contenido);

        if (tieneArchivo) {
            const file = archivoEl.files[0];
            if (file.type !== 'application/pdf') {
                notificar('Solo se permiten archivos PDF.', 'error');
                return;
            }
            formData.append('archivo', file);
        }

        const submitBtn = formEl?.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        fetch('/api/mensajes', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf },
            body: formData,
        })
            .then(r => {
                if (!r.ok) throw new Error('Error HTTP: ' + r.status);
                return r.json();
            })
            .then(() => {
                if (inputEl) inputEl.value = '';
                limpiarPreviewPDF();
                cargarMensajes();
            })
            .catch(err => {
                console.error('[Mensajes] Error al enviar:', err);
                notificar('No se pudo enviar el mensaje. Intentá de nuevo.', 'error');
            })
            .finally(() => {
                if (submitBtn) submitBtn.disabled = false;
            });
    };

    // ── Event listeners ─────────────────────────────────────────────────────────

    formEl?.addEventListener('submit', enviarMensaje);

    inputEl?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            formEl?.dispatchEvent(new Event('submit'));
        }
    });

    archivoEl?.addEventListener('change', () => {
        if (!archivoEl.files.length) return;
        const file = archivoEl.files[0];
        if (file.type !== 'application/pdf') {
            notificar('Solo se permiten archivos PDF.', 'error');
            limpiarPreviewPDF();
            return;
        }
        if (pdfPreviewName) pdfPreviewName.textContent  = file.name;
        if (pdfPreviewCont) pdfPreviewCont.style.display = 'flex';
        if (inputEl)        inputEl.required = false;
    });

    pdfPreviewRemove?.addEventListener('click', limpiarPreviewPDF);

    searchEl?.addEventListener('input', cargarChats);

    // ── Botón de actualizar (el que ya existe en el HTML) ──────────────────────
        const btnRecarga = document.getElementById('btn-recarga');
        if (btnRecarga) {
            btnRecarga.addEventListener('click', function() {
                const icono = this.querySelector('i');
                icono.classList.add('girar');    // Inicia la animación de giro

                // Espera a que termine el giro (600ms) y recarga la página
                setTimeout(() => {
                    window.location.reload();    // Refresca TODO (como F5)
                }, 600);
            });
        }
    // ── Inicialización ───────────────────────────────────────────────────────────

    document.addEventListener('DOMContentLoaded', () => {
        cargarChats();

        const urlParams    = new URLSearchParams(window.location.search);
        const postulanteId = urlParams.get('postulante_id');

        if (postulanteId) {
            fetch(`/api/chats/buscar-o-crear?usuario_id=${postulanteId}`)
                .then(r => {
                    if (!r.ok) throw new Error('Error al buscar/crear chat');
                    return r.json();
                })
                .then(chat => {
                    const otro = parseInt(chat.id_usuario_1) === miId ? chat.usuario2 : chat.usuario1;
                    abrirChat(chat, otro);
                    if (window.innerWidth <= 640) {
                        document.getElementById('mensajes-page')?.classList.add('chat-abierto');
                    }
                })
                .catch(err => {
                    console.error('[Mensajes] Error abriendo chat desde URL:', err);
                    notificar('No se pudo abrir el chat con el postulante.', 'error');
                });
        }
    });

    window.addEventListener('beforeunload', () => {
        if (intervalo) clearInterval(intervalo);
    });

})();
</script>
@endsection
@extends('layouts.app')

@section('title', 'Panel Empresa — KROW')


@section('banner')
<div style="
    width:100%;
    height:clamp(140px, 22vw, 420px);
    position:relative;
    overflow:hidden;
">

    <img src="{{ asset('img/banner-estudiante.jpg') }}"
         alt="Banner"
         style="width:100%; height:100%; object-fit:cover; display:block;">

    <div style="
        position:absolute;
        inset:0;
        background:linear-gradient(to right, rgba(0,0,0,.8), rgba(0,0,0,.8));
    "></div>

</div>
@endsection

@section('content')

<div class="panel-page" style="margin-top: -260px; position: relative; z-index: 5; background-color:var(--bg); border-radius: 8px; border:1px solid var(--surface);">

    <h1 class="panel-page-title">Panel de Empresa</h1>
    <p class="panel-page-sub">Gestiona tus ofertas laborales y revisá los postulantes</p>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-card">
            <div>
                <p class="stat-card-label">Ofertas Activas</p>
                <span class="stat-card-value">{{ $ofertas->where('estado', 'Activa')->count() }}</span>
            </div>
            <i class="bi bi-briefcase stat-card-icon"></i>
        </div>
        <div class="stat-card">
            <div>
                <p class="stat-card-label">Total Postulantes</p>
                <span class="stat-card-value">{{ $totalPostulantes ?? 0 }}</span>
            </div>
            <i class="bi bi-people stat-card-icon"></i>
        </div>
        <div class="stat-card">
            <div>
                <p class="stat-card-label">Vistas Totales</p>
                <span class="stat-card-value">{{ $totalVistas ?? 0 }}</span>
            </div>
            <i class="bi bi-eye stat-card-icon"></i>
        </div>
    </div>

    <!-- Header sección -->
    <div class="section-header">
        <h2 class="section-title">Mis Ofertas</h2>
        <div class="section-actions">
            <a href="{{ route('mensajes') }}" class="btn-outline"><i class="bi bi-chat-dots"></i> Mensajes</a>
            <a href="{{ route('empresa.crear-oferta') }}" class="btn-accent"><i class="bi bi-plus-lg"></i> Nueva Oferta</a>
        </div>
    </div>

    @if(isset($ofertas) && count($ofertas) > 0)

    <!-- Desktop: tabla -->
    <div class="table-responsive-desktop">
        <table class="ofertas-table">
            <thead>
                <tr>
                    <th>Puesto</th>
                    <th>Ubicación</th>
                    <th>Tipo</th>
                    <th>Salario</th>
                    <th>Postulantes</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ofertas as $oferta)
                @php
                    $loc      = $oferta->localidad->nombre ?? null;
                    $prov     = $oferta->provincia->nombre ?? null;
                    $ubicacion = $loc && $prov ? "$loc, $prov" : ($loc ?? $prov ?? 'No especificada');
                    $salario  = ($oferta->salario_min && $oferta->salario_max)
                        ? '$' . number_format($oferta->salario_min, 0, ',', '.') . ' – $' . number_format($oferta->salario_max, 0, ',', '.')
                        : 'A convenir';
                    $estadoClase = match($oferta->estado) {
                        'Activa'  => 'estado-activa',
                        'Pausada' => 'estado-pausada',
                        'Cerrada' => 'estado-cerrada',
                        default   => 'estado-activa',
                    };
                @endphp
                <tr id="fila-oferta-{{ $oferta->id_oferta }}">
                    <td class="td-puesto td-clickable"
                        onclick="abrirModalOferta({{ $oferta->id_oferta }})">
                        {{ $oferta->titulo }}
                    </td>
                    <td class="td-ubicacion">{{ $ubicacion }}</td>
                    <td><span class="badge-tipo">{{ $oferta->tipo_oferta }}</span></td>
                    <td>{{ $salario }}</td>
                    <td>
                        <span class="td-postulantes">
                            <i class="bi bi-people-fill"></i> {{ $oferta->postulaciones_count ?? 0 }}
                        </span>
                        <a href="{{ route('empresa.ofertas.postulantes', $oferta->id_oferta) }}"
                               class="link-accion">
                                Postulantes →
                            </a>
                    </td>
                    <td>
                        <span class="badge-estado-oferta {{ $estadoClase }}"
                              id="badge-estado-{{ $oferta->id_oferta }}">
                            {{ $oferta->estado }}
                        </span>
                    </td>
                    <td>
                        <div class="acciones-oferta">
                        <button class="btn-toggle-estado"
                                    data-id="{{ $oferta->id_oferta }}"
                                    data-estado="{{ $oferta->estado }}"
                                    onclick="toggleEstadoOferta({{ $oferta->id_oferta }}, '{{ $oferta->estado }}')">
                                {{ $oferta->estado === 'Activa' ? 'Pausar' : 'Activar' }}
                            </button>
                            <button class="btn-eliminar-oferta"
                                    onclick="confirmarEliminar({{ $oferta->id_oferta }}, '{{ addslashes($oferta->titulo) }}')">
                                <i class="bi bi-trash"></i>
                            </button>    
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile: tarjetas -->
    <div class="ofertas-mobile-cards">
        @foreach($ofertas as $oferta)
        @php
            $loc      = $oferta->localidad->nombre ?? null;
            $prov     = $oferta->provincia->nombre ?? null;
            $ubicacion = $loc && $prov ? "$loc, $prov" : ($loc ?? $prov ?? 'No especificada');
            $salario  = ($oferta->salario_min && $oferta->salario_max)
                ? '$' . number_format($oferta->salario_min, 0, ',', '.') . ' – $' . number_format($oferta->salario_max, 0, ',', '.')
                : 'A convenir';
            $estadoClase = match($oferta->estado) {
                'Activa'  => 'estado-activa',
                'Pausada' => 'estado-pausada',
                'Cerrada' => 'estado-cerrada',
                default   => 'estado-activa',
            };
        @endphp
        <div class="oferta-mobile-card" id="card-oferta-{{ $oferta->id_oferta }}">
            <div class="oferta-mobile-header">
                <span class="oferta-mobile-titulo td-clickable"
                      onclick="abrirModalOferta({{ $oferta->id_oferta }})">
                    {{ $oferta->titulo }}
                </span>
                <span class="badge-estado-oferta {{ $estadoClase }}"
                      id="badge-estado-mob-{{ $oferta->id_oferta }}">
                    {{ $oferta->estado }}
                </span>
            </div>
            <div class="oferta-mobile-body">
                <div class="oferta-mobile-item">
                    <span class="item-label">Ubicación</span>
                    <span class="item-value">{{ $ubicacion }}</span>
                </div>
                <div class="oferta-mobile-item">
                    <span class="item-label">Tipo</span>
                    <span class="item-value">{{ $oferta->tipo_oferta }}</span>
                </div>
                <div class="oferta-mobile-item">
                    <span class="item-label">Salario</span>
                    <span class="item-value">{{ $salario }}</span>
                </div>
                <div class="oferta-mobile-item">
                    <span class="item-label">Postulantes</span>
                    <span class="item-value">{{ $oferta->postulaciones_count ?? 0 }}</span>
                </div>
            </div>
            <div class="oferta-mobile-footer">
                <a href="{{ route('empresa.ofertas.postulantes', $oferta->id_oferta) }}"
                   class="link-accion">Postulantes →</a>
                <button class="btn-toggle-estado"
                        data-id="{{ $oferta->id_oferta }}"
                        data-estado="{{ $oferta->estado }}"
                        onclick="toggleEstadoOferta({{ $oferta->id_oferta }}, '{{ $oferta->estado }}')">
                    {{ $oferta->estado === 'Activa' ? 'Pausar' : 'Activar' }}
                </button>
                <button class="btn-eliminar-oferta"
                        onclick="confirmarEliminar({{ $oferta->id_oferta }}, '{{ addslashes($oferta->titulo) }}')">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
        @endforeach
    </div>

    @else
    <div style="text-align:center; padding:3rem; background:var(--surface); border:1px solid var(--border); border-radius:var(--radius);">
        <p style="color:var(--muted);"> No tenés ofertas publicadas aún.</p>
        <a href="{{ route('empresa.crear-oferta') }}" class="btn-accent"
           style="display:inline-block; margin-top:1rem;">+ Crear primera oferta</a>
    </div>
    @endif

</div>

<!-- Modal oferta (iframe) — fuera del panel-page para evitar conflictos de layout -->
<dialog id="modalOferta" class="modal-oferta-dialog">
    <div class="modal-oferta-header">
        <button onclick="cerrarModalOferta()" class="modal-close-btn">&times;</button>
    </div>
    <iframe id="iframeOferta" src="" class="modal-oferta-iframe"></iframe>
</dialog>

<!-- Dialog confirmar eliminar -->
<dialog id="dialogEliminar" class="modal-confirmar">
    <div class="modal-confirmar-content">
        <h3 class="modal-confirmar-title">Eliminar oferta</h3>
        <p class="modal-confirmar-msg" id="msgEliminar"></p>
        <div class="modal-confirmar-btns">
            <button onclick="document.getElementById('dialogEliminar').close()"
                    class="btn-cancelar-dialog">Cancelar</button>
            <button id="btnConfirmarEliminar"
                    class="btn-confirmar-eliminar">Sí, eliminar</button>
        </div>
    </div>
</dialog>

<style>
    /* fix crítico: dialog cerrado no ocupa espacio */
    dialog:not([open]) { display: none !important; }

    /* ── td clickable ── */
    .td-clickable {
        cursor: pointer;
        color: var(--primary) !important;
        transition: opacity 0.15s;
    }
    .td-clickable:hover { opacity: 0.75; }

    /* ── Badges estado oferta ── */
    .badge-estado-oferta {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        border: 1px solid transparent;
        white-space: nowrap;
    }
    .estado-activa  { background: rgba(46,204,154,0.12);  color: #2ECC9A; border-color: rgba(46,204,154,0.3); }
    .estado-pausada { background: rgba(255,193,7,0.12);   color: #e6ac00; border-color: rgba(255,193,7,0.3); }
    .estado-cerrada { background: rgba(100,100,100,0.1);  color: #9a9aaa; border-color: rgba(100,100,100,0.3); }

    /* ── Acciones tabla ── */
    .acciones-oferta {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .btn-toggle-estado {
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 6px;
        border: 1px solid var(--border);
        background: var(--bg);
        color: var(--text);
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-toggle-estado:hover { border-color: var(--primary); color: var(--primary); }
    .btn-eliminar-oferta {
        background: none;
        border: none;
        color: #e05577;
        cursor: pointer;
        font-size: 15px;
        padding: 4px 6px;
        border-radius: 6px;
        transition: background 0.2s;
    }
    .btn-eliminar-oferta:hover { background: rgba(212,24,61,0.1); }

    /* ── Modal iframe ── */
    .modal-oferta-dialog {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--surface);
        padding: 0;
        width: min(920px, 95vw);
        height: 88vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        margin: 0;
    }
    .modal-oferta-dialog[open] {
        animation: modalIn 0.2s ease forwards;
    }
    .modal-oferta-dialog::backdrop {
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(3px);
    }
    .modal-oferta-header {
        display: flex;
        justify-content: flex-end;
        padding: 8px 12px;
        border-bottom: 1px solid var(--border);
        flex-shrink: 0;
    }
    .modal-close-btn {
        background: none;
        border: none;
        font-size: 24px;
        color: var(--muted);
        cursor: pointer;
        line-height: 1;
        padding: 0 4px;
    }
    .modal-close-btn:hover { color: var(--text); }
    .modal-oferta-iframe {
        flex: 1;
        width: 100%;
        border: none;
        background: var(--bg);
    }

    /* ── Dialog confirmar eliminar ── */
    .modal-confirmar {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--surface);
        padding: 0;
        width: min(400px, 92vw);
        margin: 0;
    }
    .modal-confirmar::backdrop {
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(3px);
    }
    .modal-confirmar-content { padding: 24px; }
    .modal-confirmar-title { font-size: 18px; font-weight: 600; color: var(--text); margin-bottom: 10px; }
    .modal-confirmar-msg   { color: var(--muted); margin-bottom: 24px; font-size: 14px; }
    .modal-confirmar-btns  { display: flex; gap: 10px; justify-content: flex-end; }
    .btn-cancelar-dialog {
        padding: 8px 16px;
        background: var(--bg);
        border: 1px solid var(--border);
        color: var(--text);
        border-radius: 6px;
        cursor: pointer;
    }
    .btn-confirmar-eliminar {
        padding: 8px 16px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    /* ── Mobile cards ── */
    .ofertas-mobile-cards { display: none; flex-direction: column; gap: 12px; }
    .oferta-mobile-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .oferta-mobile-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--border);
    }
    .oferta-mobile-titulo { font-size: 15px; font-weight: 700; }
    .oferta-mobile-body   { display: flex; flex-direction: column; gap: 6px; }
    .oferta-mobile-item   { display: flex; justify-content: space-between; font-size: 14px; padding: 4px 0; }
    .oferta-mobile-item .item-label { color: var(--muted); font-weight: 500; }
    .oferta-mobile-item .item-value { color: var(--text);  font-weight: 500; }
    .oferta-mobile-footer {
        padding-top: 10px;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-end;
    }

    @keyframes modalIn {
        from { opacity: 0; transform: translate(-50%, -48%) scale(0.97); }
        to   { opacity: 1; transform: translate(-50%, -50%) scale(1); }
    }

    @media (max-width: 768px) {
        .table-responsive-desktop { display: none; }
        .ofertas-mobile-cards     { display: flex; }
        .stats-row        { grid-template-columns: 1fr 1fr; }
        .section-header   { flex-direction: column; align-items: stretch; gap: 10px; }
        .section-actions  { display: flex; gap: 10px; }
        .section-actions .btn-outline,
        .section-actions .btn-accent { flex: 1; justify-content: center; text-align: center; }
    }
    @media (max-width: 480px) {
        .stats-row       { grid-template-columns: 1fr; }
        .section-actions { flex-direction: column; }
    }
</style>

<script>
    // ── Modal oferta ──────────────────────────────
    function abrirModalOferta(idOferta) {
        const iframe = document.getElementById('iframeOferta');
        const dialog = document.getElementById('modalOferta');
        iframe.src = '/empresa/oferta/' + idOferta + '/preview';

        dialog.showModal();
    }

    function cerrarModalOferta() {
        const dialog = document.getElementById('modalOferta');
        const iframe = document.getElementById('iframeOferta');
        dialog.close();
        setTimeout(() => { iframe.src = ''; }, 200);
    }

    // Cerrar clickeando el backdrop
    document.getElementById('modalOferta').addEventListener('click', function(e) {
        const rect = this.getBoundingClientRect();
        if (e.clientX < rect.left || e.clientX > rect.right ||
            e.clientY < rect.top  || e.clientY > rect.bottom) {
            cerrarModalOferta();
        }
    });

    // ── Pausar / Activar ──────────────────────────
    function toggleEstadoOferta(idOferta, estadoActual) {
        const nuevoEstado = estadoActual === 'Activa' ? 'Pausada' : 'Activa';

        fetch(`/empresa/oferta/${idOferta}/estado`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ estado: nuevoEstado })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) { alert('Error al cambiar estado.'); return; }

            const claseMap = {
                'Activa':  'estado-activa',
                'Pausada': 'estado-pausada',
                'Cerrada': 'estado-cerrada'
            };

            // Actualizar badges desktop y mobile
            [`badge-estado-${idOferta}`, `badge-estado-mob-${idOferta}`].forEach(id => {
                const badge = document.getElementById(id);
                if (!badge) return;
                badge.className   = `badge-estado-oferta ${claseMap[nuevoEstado]}`;
                badge.textContent = nuevoEstado;
            });

            // Después de actualizar badges, agregar esto:
const statActivas = document.querySelectorAll('.stat-card')[0]?.querySelector('.stat-card-value');
if (statActivas) {
    // Dividir por 2 porque hay badge desktop y mobile por cada oferta
    statActivas.textContent = document.querySelectorAll('.badge-estado-oferta.estado-activa').length / 2;
}

            // Actualizar botones toggle
            document.querySelectorAll(`.btn-toggle-estado[data-id="${idOferta}"]`).forEach(btn => {
                btn.setAttribute('data-estado', nuevoEstado);
                btn.setAttribute('onclick', `toggleEstadoOferta(${idOferta}, '${nuevoEstado}')`);
                btn.textContent = nuevoEstado === 'Activa' ? 'Pausar' : 'Activar';
            });
        })
        .catch(() => alert('Error de red.'));
    }

    // ── Eliminar ──────────────────────────────────
    let ofertaAEliminar = null;

    function confirmarEliminar(idOferta, titulo) {
        ofertaAEliminar = idOferta;
        document.getElementById('msgEliminar').textContent =
            `¿Confirmás eliminar "${titulo}"? Se borrarán todas las postulaciones asociadas y no se puede deshacer.`;
        document.getElementById('dialogEliminar').showModal();
    }

    document.getElementById('btnConfirmarEliminar').addEventListener('click', function () {
        if (!ofertaAEliminar) return;
        const id = ofertaAEliminar;
        document.getElementById('dialogEliminar').close();
        ofertaAEliminar = null;

        fetch(`/empresa/oferta/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) { alert('Error al eliminar.'); return; }
            document.getElementById(`fila-oferta-${id}`)?.remove();
            document.getElementById(`card-oferta-${id}`)?.remove();

            // Actualizar contador activas tras eliminar
const statActivas = document.querySelectorAll('.stat-card')[0]?.querySelector('.stat-card-value');
if (statActivas) {
    // Dividir por 2 porque hay badge desktop y mobile por cada oferta
    statActivas.textContent = document.querySelectorAll('.badge-estado-oferta.estado-activa').length / 2;
}
        })
        .catch(() => alert('Error de red.'));
    });

    document.getElementById('dialogEliminar').addEventListener('click', function (e) {
        const rect = this.getBoundingClientRect();
        if (e.clientX < rect.left || e.clientX > rect.right ||
            e.clientY < rect.top  || e.clientY > rect.bottom) {
            this.close();
        }
    });
</script>

@endsection
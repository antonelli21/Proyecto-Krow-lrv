@extends('layouts.app')

@section('title', 'Panel Empresa — KROW')


@section('banner')
<div id="banner-index" style="
    width:100%;
    height: 600px;
    position:relative;
    overflow:hidden;
    background-image: url('{{ asset('img/banner.jpg') }}');
    background-size: cover;
    background-position: top;
    background-repeat: no-repeat;
    margin:0;
">

    <div  style="
        position:absolute;
        inset:0;
        background:linear-gradient(to right, rgba(0,0,0,.6), rgba(0,0,0,.4));
    "></div>

    <div id="pad" style="
        position:absolute;
        inset:0;
        display:flex;
        flex-direction:column;
        justify-content:start;
        align-items:center;
        text-align:center;
        z-index:2;
        padding-top: 1.5rem;
    ">
</div>
</div>
@endsection

@section('content')
<!-- Dialog confirmar — genérico -->
<dialog id="dialogConfirmar" class="modal-confirmar">
    <div class="modal-confirmar-content">
        <h3 class="modal-confirmar-title" id="dialogConfirmarTitle">Confirmar acción</h3>
        <p class="modal-confirmar-msg" id="dialogConfirmarMsg"></p>
        <div class="modal-confirmar-btns">
            <button onclick="document.getElementById('dialogConfirmar').close()"
                    class="btn-cancelar-dialog">Cancelar</button>
            <button id="btnConfirmarAccion"
                    class="btn-confirmar-eliminar">Confirmar</button>
        </div>
    </div>
</dialog>

<!-- Toast de confirmación -->
<div id="toast-msg" class="toast-msg" role="status" aria-live="polite"></div>

<div class="panel-page">

    <h1 class="panel-page-title">Panel de Empresa</h1>
    <p class="panel-page-sub">Gestiona tus ofertas laborales y revisá los postulantes</p>

    @if(session('success'))
        <div style="margin-bottom:16px;padding:13px 16px;border:1px solid rgba(46,204,154,.35);background:rgba(14,24,22,.96);color:#2ECC9A;font-size:13px;font-weight:700;display:flex;align-items:center;gap:8px;">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

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
                <p class="stat-card-label">Ofertas Pausadas</p>
                <span class="stat-card-value">{{ $ofertas->where('estado', 'Pausada')->count() }}</span>
            </div>
            <i class="bi bi-eye stat-card-icon"></i>
        </div>
    </div>

    <!-- Header sección -->
    <div class="section-header">
        <h2 class="section-title">Mis Ofertas</h2>
        <div class="section-actions">
            <a href="{{ route('empresa.mensajes') }}" class="btn-outline" style="position:relative;">
                <i class="bi bi-chat-dots"></i> Mensajes
                @if(isset($mensajesSinLeer) && $mensajesSinLeer > 0)
                    <span style="
                        position:absolute;
                        top:-8px;
                        right:-8px;
                        background:#e05577;
                        color:#fff;
                        font-size:0.7rem;
                        font-weight:700;
                        min-width:18px;
                        height:18px;
                        border-radius:50%;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        padding:0 4px;
                        line-height:1;
                        border:2px solid var(--bg);
                    ">{{ $mensajesSinLeer > 99 ? '99+' : $mensajesSinLeer }}</span>
                @endif
            </a>
            <a href="{{ route('empresa.crear-oferta') }}" class="btn-accent"><i class="bi bi-plus-lg"></i> Nueva Oferta</a>
        </div>
    </div>

    @if(isset($ofertas) && count($ofertas) > 0)

    <!-- Desktop: tabla -->
    <div id="bulk-bar-emp" style="display:none; align-items:center; gap:8px; padding:10px 14px; background:var(--surface); border:1px solid var(--accent); border-radius:var(--radius); margin-bottom:12px; flex-wrap:wrap;">
  <span id="bulk-count-emp" style="font-size:12.5px; font-weight:700; color:var(--accent); margin-right:4px;"></span>

  <button onclick="bulkEmpEstado('Activa')"
          style="display:inline-flex; align-items:center; gap:5px; padding:5px 11px; font-size:12px; font-weight:700; border-radius:6px; cursor:pointer; border:1px solid var(--border); background:var(--bg); color:var(--text); transition:border-color 0.15s, color 0.15s;"
          onmouseover="this.style.borderColor='var(--accent)';this.style.color='var(--accent)'"
          onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text)'">
    <i class="bi bi-check-circle"></i> Activar
  </button>

  <button onclick="bulkEmpEstado('Pausada')"
          style="display:inline-flex; align-items:center; gap:5px; padding:5px 11px; font-size:12px; font-weight:700; border-radius:6px; cursor:pointer; border:1px solid var(--border); background:var(--bg); color:var(--text); transition:border-color 0.15s, color 0.15s;"
          onmouseover="this.style.borderColor='var(--accent)';this.style.color='var(--accent)'"
          onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text)'">
    <i class="bi bi-pause-circle"></i> Pausar
  </button>

  <button onclick="bulkEmpEliminar()"
          style="display:inline-flex; align-items:center; gap:5px; padding:5px 11px; font-size:12px; font-weight:700; border-radius:6px; cursor:pointer; border:1px solid rgba(212,24,61,.4); background:transparent; color:#e05577; transition:background 0.15s;"
          onmouseover="this.style.background='rgba(212,24,61,0.08)'"
          onmouseout="this.style.background='transparent'">
    <i class="bi bi-trash"></i> Eliminar
  </button>

  <button onclick="clearBulkEmp()"
          style="display:inline-flex; align-items:center; gap:5px; padding:5px 11px; font-size:12px; font-weight:700; border-radius:6px; cursor:pointer; border:1px solid var(--border); background:transparent; color:var(--muted); margin-left:auto; transition:color 0.15s;"
          onmouseover="this.style.color='var(--text)'"
          onmouseout="this.style.color='var(--muted)'">
    Cancelar
  </button>
</div>
    <div class="table-responsive-desktop tabla-wrap">
        <table class="ofertas-table">
            <colgroup>
                <col style="width:36px;">
                <col style="width:24%;">
                <col style="width:19%;">
                <col style="width:10%;">
                <col style="width:17%;">
                <col style="width:13%;">
                <col style="width:11%;">
                <col style="width:150px;">
            </colgroup>
            <thead>
                <tr>
                    <th style="width:36px;"><input type="checkbox" id="check-all-emp" class="check-all-emp"></th>
                    <th class="th-left">Puesto</th>
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
                        default   => 'estado-activa',
                    };
                    $bloqueadaPorAdmin = $oferta->pausada_por_admin && $oferta->estado === 'Pausada';
                    $delayFila = min($loop->index, 8) * 0.035;
                @endphp
                <tr id="fila-oferta-{{ $oferta->id_oferta }}" class="fade-in-row" style="animation-delay: {{ $delayFila }}s;">
                    <td><input type="checkbox" class="check-emp" data-id="{{ $oferta->id_oferta }}" data-pausada-admin="{{ $bloqueadaPorAdmin ? '1' : '0' }}"></td>
                    <td class="td-puesto td-clickable td-left"
                        onclick="abrirModalOferta({{ $oferta->id_oferta }})">
                        {{ $oferta->titulo }}
                    </td>
                    <td class="td-ubicacion">{{ $ubicacion }}</td>
                    <td><span class="badge-tipo">{{ $oferta->tipo_oferta }}</span></td>
                    <td class="td-salario">{{ $salario }}</td>
                    <td>
                        <div class="td-postulantes-wrap">
                            <span class="td-postulantes">
                                <i class="bi bi-people-fill"></i> {{ $oferta->postulaciones_count ?? 0 }}
                            </span>
                            <a href="{{ route('empresa.ofertas.postulantes', $oferta->id_oferta) }}"
                                   class="link-accion">
                                    Postulantes →
                                </a>
                        </div>
                    </td>
                    <td>
                        <span class="badge-estado-oferta {{ $estadoClase }}"
                              id="badge-estado-{{ $oferta->id_oferta }}">
                            {{ $oferta->estado }}
                        </span>
                    </td>
                    <td class="td-acciones-cell">
                        <div class="acciones-oferta">
                        @if($bloqueadaPorAdmin)
                            <button class="btn-toggle-estado"
                                    style="opacity:0.5; cursor:not-allowed;"
                                    disabled
                                    title="Pausada por el administrador.">
                                Pausada por Admin
                            </button>
                        @else
                            <button class="btn-toggle-estado"
                                    data-id="{{ $oferta->id_oferta }}"
                                    data-estado="{{ $oferta->estado }}"
                                    onclick="toggleEstadoOferta({{ $oferta->id_oferta }}, '{{ $oferta->estado }}')">
                                {{ $oferta->estado === 'Activa' ? 'Pausar' : 'Activar' }}
                            </button>
                        @endif
                        @if($bloqueadaPorAdmin)
                            <div style="margin-top:6px;">
                                @if($oferta->motivo_pausa_admin)
                                    <small style="color:var(--muted); display:block; margin-bottom:6px; line-height:1.4;">

                                        <a href="{{ route('empresa.mensajes') }}" class="link-accion" style="font-size:.8rem;">
                                            Ver motivo →
                                        </a>
                                    </small>
                                @endif
                            </div>
                        @endif
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
                default   => 'estado-activa',
            };
            $bloqueadaPorAdmin = $oferta->pausada_por_admin && $oferta->estado === 'Pausada';
            $delayCard = min($loop->index, 8) * 0.035;
        @endphp
        <div class="oferta-mobile-card fade-in-row" id="card-oferta-{{ $oferta->id_oferta }}" style="animation-delay: {{ $delayCard }}s;">
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
                @if($bloqueadaPorAdmin)
                    <button class="btn-toggle-estado"
                            style="opacity:0.5; cursor:not-allowed;"
                            disabled
                            title="Pausada por el administrador.">
                        Pausada por Admin
                    </button>
                    <a href="{{ route('ayuda') }}#contacto"
                       class="link-accion"
                       style="font-size:0.8rem;">
                        <i class="bi bi-ticket"></i> Ticket
                    </a>
                @else
                    <button class="btn-toggle-estado"
                            data-id="{{ $oferta->id_oferta }}"
                            data-estado="{{ $oferta->estado }}"
                            onclick="toggleEstadoOferta({{ $oferta->id_oferta }}, '{{ $oferta->estado }}')">
                        {{ $oferta->estado === 'Activa' ? 'Pausar' : 'Activar' }}
                    </button>
                @endif
                <button class="btn-eliminar-oferta"
                        onclick="confirmarEliminar({{ $oferta->id_oferta }}, '{{ addslashes($oferta->titulo) }}')">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <div id="ofertas-empty-state" style="text-align:center; padding:3rem; background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); {{ (isset($ofertas) && count($ofertas) > 0) ? 'display:none;' : '' }}">
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

<!-- Dialog aviso — solo informativo -->
<dialog id="dialogAviso" class="modal-confirmar">
    <div class="modal-confirmar-content">
        <h3 class="modal-confirmar-title" id="dialogAvisoTitle">Atención</h3>
        <p class="modal-confirmar-msg" id="dialogAvisoMsg"></p>
        <div class="modal-confirmar-btns">
            <button id="btnCerrarAviso" class="btn-confirmar-eliminar" style="background:var(--primary, #3d7cf0);">
                Entendido
            </button>
        </div>
    </div>
</dialog>

<style>
    /* fix crítico: dialog cerrado no ocupa espacio */
    dialog:not([open]) { display: none !important; }

    /* ── Contenedor principal ── */
    .panel-page {
        position: relative;
        z-index: 5;
        margin-top: -530px !important;
        margin-bottom: 80px;
        background-color: var(--bg);
        opacity: 0.95;
        border-radius: 8px;
        border: 1px solid var(--accent);
        box-shadow: 0 20px 50px var(--shadow-color), 0 0px 30px var(--shadow-glow);
        max-width: 1320px;
        width: calc(100% - 32px);
        margin-left: auto;
        margin-right: auto;
        box-sizing: border-box;
    }

    /* ── Animación de entrada — misma sensación que el skeleton de estudiante,
         acá los datos ya vienen renderizados por Blade así que en vez de
         mostrar un loader falso, hacemos que el contenido real aparezca
         prolijo y escalonado apenas carga la página. ── */
    @keyframes fadeInUpPanel {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .stat-card {
        animation: fadeInUpPanel .4s ease both;
    }
    .stat-card:nth-child(1) { animation-delay: .04s; }
    .stat-card:nth-child(2) { animation-delay: .09s; }
    .stat-card:nth-child(3) { animation-delay: .14s; }

    .fade-in-row {
        animation: fadeInUpPanel .35s ease both;
    }

    /* ── Tabla: ancho estable, columnas centradas salvo Puesto ── */
    .tabla-wrap {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .ofertas-table {
        table-layout: fixed;
        width: 100%;
        min-width: 760px;
        border-collapse: collapse;
    }
    .ofertas-table th,
    .ofertas-table td {
        text-align: center;
        vertical-align: middle;
        padding: 14px 10px;
    }
    .ofertas-table th.th-left,
    .ofertas-table td.td-left {
        text-align: left;
    }
    .ofertas-table td.td-ubicacion,
    .ofertas-table td.td-salario {
        white-space: normal;
        word-break: break-word;
        line-height: 1.35;
    }
    .td-postulantes-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
    }

    /* ── Toast de confirmación ── */
    .toast-msg {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%) translateY(16px);
        background: var(--surface);
        border: 1px solid var(--border);
        color: var(--text);
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 600;
        box-shadow: 0 12px 30px rgba(0,0,0,.35);
        opacity: 0;
        pointer-events: none;
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: opacity 0.25s ease, transform 0.25s ease;
        max-width: 90vw;
    }
    .toast-msg.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
    .toast-msg .toast-icon {
        font-size: 15px;
        line-height: 1;
        flex-shrink: 0;
    }
    .toast-msg.toast-success { border-color: rgba(46,204,154,.5); }
    .toast-msg.toast-success .toast-icon { color: #2ECC9A; }
    .toast-msg.toast-error { border-color: rgba(212,24,61,.5); }
    .toast-msg.toast-error .toast-icon { color: #e05577; }

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
      padding: 6px 16px;
      border-radius: 11.5px;
      font-size: 11.5px;
      font-weight: 600;
      white-space: nowrap;
    }
    .estado-activa { 
    /* Mezcla tu variable con transparente al 50% */
      background: var(--badge-contacto-bg);
      color: var(--badge-contacto-text);
      border: 1px solid var(--badge-contacto-border);
    }

    /* Estado: Pausada */
    .estado-pausada { 
      background: var(--badge-postulado-bg);
      color: var(--badge-postulado-text);
      border: 1px solid var(--badge-postulado-border);
    }


    /* ── Acciones tabla ── */
    .acciones-oferta {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .btn-toggle-estado {
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 6px;
        border: 1px solid var(--pausar);
        background: var(--pausar);
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
    .oferta-mobile-item   { display: flex; justify-content: space-between; gap: 12px; font-size: 14px; padding: 4px 0; }
    .oferta-mobile-item .item-label { color: var(--muted); font-weight: 500; flex-shrink: 0; }
    .oferta-mobile-item .item-value { color: var(--text);  font-weight: 500; text-align: right; word-break: break-word; }
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

    /* ── Responsive ── */

    /* Tablet ancho: aliviana un poco antes de pasar a cards */
    @media (max-width: 1100px) {
        .ofertas-table th,
        .ofertas-table td { padding: 12px 8px; font-size: 13px; }
    }

    /* Tablet: la columna Ubicación es la que menos se extraña si hay que
       liberar espacio; se puede seguir viendo en el detalle de la oferta. */
    @media (max-width: 1024px) {
        .panel-page { width: calc(100% - 24px); }
        .ofertas-table th:nth-child(3),
        .ofertas-table td:nth-child(3) { display: none; }
    }

    @media (max-width: 768px) {
        .panel-page { width: calc(100% - 16px); padding: 20px 14px 48px; }
        .panel-page-title { font-size: 22px; }
        .panel-page-sub { font-size: 13px; margin-bottom: 22px; }
        .table-responsive-desktop { display: none; }
        .ofertas-mobile-cards     { display: flex; }
        .stats-row        { grid-template-columns: 1fr 1fr; gap: 10px; }
        .stat-card         { padding: 16px 18px; }
        .stat-card-value   { font-size: 32px; }
        .section-header   { flex-direction: column; align-items: stretch; gap: 10px; }
        .section-actions  { display: flex; gap: 10px; }
        .section-actions .btn-outline,
        .section-actions .btn-accent { flex: 1; justify-content: center; text-align: center; padding: 10px 14px; font-size: 13.5px; }
        .toast-msg { bottom: 16px; font-size: 13px; padding: 10px 16px; width: calc(100% - 32px); justify-content: center; }
    }
    @media (max-width: 480px) {
        .stats-row       { grid-template-columns: 1fr; }
        .section-actions { flex-direction: column; }
        .oferta-mobile-card { padding: 14px; }
        .oferta-mobile-titulo { font-size: 14px; }
    }
    @media (min-width: 1025px) and (max-width: 1360px) {
        .panel-page { width: calc(100% - 40px); }
    }
   
</style>

<script>
    // ── Toast ──────────────────────────────────────
    let toastTimeout;
    function mostrarToast(mensaje, tipo = 'success') {
        const toast = document.getElementById('toast-msg');
        if (!toast) return;
        clearTimeout(toastTimeout);
        const icono = tipo === 'success' ? '&#10003;' : '&#10005;';
        toast.innerHTML = `<span class="toast-icon">${icono}</span><span>${mensaje}</span>`;
        toast.className = `toast-msg toast-${tipo} show`;
        toastTimeout = setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

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

    // ── Helpers de estadísticas / DOM ──────────────
    function actualizarStatsCards() {
        document.querySelectorAll('.stat-card')[0]
            .querySelector('.stat-card-value').textContent =
            document.querySelectorAll('.badge-estado-oferta.estado-activa').length / 2;

        document.querySelectorAll('.stat-card')[2]
            .querySelector('.stat-card-value').textContent =
            document.querySelectorAll('.badge-estado-oferta.estado-pausada').length / 2;
    }

    function actualizarEstadoDOM(idOferta, nuevoEstado) {
        const claseMap = {
            Activa: 'estado-activa',
            Pausada: 'estado-pausada',
        };

        [`badge-estado-${idOferta}`, `badge-estado-mob-${idOferta}`].forEach(id => {
            const badge = document.getElementById(id);
            if (!badge) return;

            badge.className = `badge-estado-oferta ${claseMap[nuevoEstado]}`;
            badge.textContent = nuevoEstado;
        });

        document.querySelectorAll(`.btn-toggle-estado[data-id="${idOferta}"]`)
            .forEach(btn => {
                btn.dataset.estado = nuevoEstado;
                btn.setAttribute(
                    'onclick',
                    `toggleEstadoOferta(${idOferta}, '${nuevoEstado}')`
                );
                btn.textContent = nuevoEstado === 'Activa'
                    ? 'Pausar'
                    : 'Activar';
            });

        actualizarStatsCards();
    }

    function eliminarOfertaDOM(idOferta) {
    document.getElementById(`fila-oferta-${idOferta}`)?.remove();
    document.getElementById(`card-oferta-${idOferta}`)?.remove();
    actualizarStatsCards();
    revisarEstadoVacio();
}

function revisarEstadoVacio() {
    const filasTabla = document.querySelectorAll('.ofertas-table tbody tr').length;
    const emptyState = document.getElementById('ofertas-empty-state');
    const tablaWrap = document.querySelector('.table-responsive-desktop');
    const cardsWrap = document.querySelector('.ofertas-mobile-cards');
    const bulkBar = document.getElementById('bulk-bar-emp');

    if (filasTabla === 0) {
        if (tablaWrap) tablaWrap.style.display = 'none';
        if (cardsWrap) cardsWrap.style.display = 'none';
        if (bulkBar) bulkBar.style.display = 'none';
        if (emptyState) emptyState.style.display = 'block';
    }
}

    // ── Pausar / Activar (individual) ──────────────
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
        if (!data.success) {
            mostrarToast('Error al cambiar estado.', 'error');
            return;
        }

        actualizarEstadoDOM(idOferta, nuevoEstado);
        mostrarToast(nuevoEstado === 'Activa' ? 'Oferta activada' : 'Oferta pausada');
    })
    .catch(() => mostrarToast('Error de red.', 'error'));
}

    // ── Eliminar (individual) ───────────────────────
    let ofertaAEliminar = null;

    async function confirmarEliminar(idOferta, titulo) {
        const ok = await modalConfirm(
            'Eliminar oferta',
            `¿Confirmás eliminar "${titulo}"? Se borrarán todas las postulaciones asociadas y no se puede deshacer.`,
            'Sí, eliminar'
        );

        if (!ok) return;

        fetch(`/empresa/oferta/${idOferta}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(r => r.json())
        .then(data => {

            if (!data.success) {
                mostrarToast('Error al eliminar.', 'error');
                return;
            }

            eliminarOfertaDOM(idOferta);

            // Total postulantes: solo lo pisamos si el backend efectivamente lo mandó
            if (typeof data.totalPostulantes !== 'undefined') {
                document.querySelectorAll('.stat-card')[1]
                    .querySelector('.stat-card-value').textContent = data.totalPostulantes;
            }

            mostrarToast('Oferta eliminada correctamente');
        })
        .catch(() => mostrarToast('Error de red.', 'error'));
    }
    

    document.getElementById('dialogEliminar').addEventListener('click', function (e) {
        const rect = this.getBoundingClientRect();
        if (e.clientX < rect.left || e.clientX > rect.right ||
            e.clientY < rect.top  || e.clientY > rect.bottom) {
            this.close();
        }
    });
    /* ── Empresa bulk ── */
document.getElementById('check-all-emp')?.addEventListener('change', function() {
  document.querySelectorAll('.check-emp').forEach(c => c.checked = this.checked);
  updateBulkEmp();
});
document.addEventListener('change', e => {
  if (!e.target.classList.contains('check-emp')) return;
  const all = document.querySelectorAll('.check-emp');
  const checkAll = document.getElementById('check-all-emp');
  if (checkAll) checkAll.checked = [...all].every(c => c.checked);
  updateBulkEmp();
});

function getSelectedEmp() {
  return [...document.querySelectorAll('.check-emp:checked')].map(c => c.dataset.id);
}

// Ids seleccionados que están pausados por el admin (no se pueden reactivar desde acá)
function getSelectedBloqueadasAdmin() {
  return [...document.querySelectorAll('.check-emp:checked')]
    .filter(c => c.dataset.pausadaAdmin === '1')
    .map(c => c.dataset.id);
}

function updateBulkEmp() {
  const ids = getSelectedEmp();
  const bar = document.getElementById('bulk-bar-emp');
  if (!bar) return;
  bar.style.display = ids.length > 0 ? 'flex' : 'none';
  document.getElementById('bulk-count-emp').textContent =
    `${ids.length} seleccionada${ids.length !== 1 ? 's' : ''}`;
}
function clearBulkEmp() {
  document.querySelectorAll('.check-emp, #check-all-emp').forEach(c => c.checked = false);
  updateBulkEmp();
}

// ── Cambiar estado en lote — sin recargar la página ──
async function bulkEmpEstado(estado) {
  let ids = getSelectedEmp();
  if (!ids.length) return;

  if (estado === 'Activa') {
    const bloqueadas = getSelectedBloqueadasAdmin();
    if (bloqueadas.length) {
      ids = ids.filter(id => !bloqueadas.includes(id));
      await modalAviso(
        bloqueadas.length === 1
          ? 'Una de las ofertas seleccionadas fue pausada por el administrador y no se puede reactivar desde acá.'
          : `${bloqueadas.length} ofertas seleccionadas fueron pausadas por el administrador y no se pueden reactivar desde acá.`
      );
    }
    if (!ids.length) return;
  }

  const resultados = await Promise.all(ids.map(id =>
    fetch(`/empresa/oferta/${id}/estado`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({ estado })
    }).then(r => r.json()).then(data => ({ id, data })).catch(() => ({ id, data: { success: false } }))
  ));

  let exitosas = 0;
  resultados.forEach(({ id, data }) => {
    if (data.success) {
      actualizarEstadoDOM(id, estado);
      exitosas++;
    }
  });

  clearBulkEmp();

  if (exitosas === ids.length) {
    mostrarToast(`${exitosas} oferta${exitosas !== 1 ? 's' : ''} ${estado === 'Activa' ? 'activada' : 'pausada'}${exitosas !== 1 ? 's' : ''}`);
  } else if (exitosas > 0) {
    mostrarToast(`${exitosas} de ${ids.length} ofertas actualizadas`, 'error');
  } else {
    mostrarToast('Error al actualizar las ofertas.', 'error');
  }
}

// ── Eliminar en lote — sin recargar la página ──
async function bulkEmpEliminar() {
    const ids = getSelectedEmp();
    if (!ids.length) return;

    const ok = await modalConfirm(
        'Eliminar ofertas',
        `¿Eliminar ${ids.length} oferta(s) seleccionada(s)? No se puede deshacer.`,
        'Sí, eliminar'
    );
    if (!ok) return;

    const resultados = await Promise.all(ids.map(id =>
        fetch(`/empresa/oferta/${id}`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(r => r.json()).then(data => ({ id, data })).catch(() => ({ id, data: { success: false } }))
    ));

    let exitosas = 0;
    let ultimoTotalPostulantes;
    resultados.forEach(({ id, data }) => {
        if (data.success) {
            eliminarOfertaDOM(id);
            exitosas++;
            if (typeof data.totalPostulantes !== 'undefined') {
                ultimoTotalPostulantes = data.totalPostulantes;
            }
        }
    });

    if (typeof ultimoTotalPostulantes !== 'undefined') {
        document.querySelectorAll('.stat-card')[1]
            .querySelector('.stat-card-value').textContent = ultimoTotalPostulantes;
    }

    clearBulkEmp();

    if (exitosas === ids.length) {
        mostrarToast(`${exitosas} oferta${exitosas !== 1 ? 's' : ''} eliminada${exitosas !== 1 ? 's' : ''}`);
    } else if (exitosas > 0) {
        mostrarToast(`${exitosas} de ${ids.length} ofertas eliminadas`, 'error');
    } else {
        mostrarToast('Error al eliminar las ofertas.', 'error');
    }
}
// Devuelve una Promise<boolean>, igual que el adminConfirm del admin
function modalConfirm(titulo, mensaje, labelBoton = 'Confirmar') {
    return new Promise(resolve => {
        const dialog = document.getElementById('dialogConfirmar');
        document.getElementById('dialogConfirmarTitle').textContent = titulo;
        document.getElementById('dialogConfirmarMsg').textContent   = mensaje;

        const btn = document.getElementById('btnConfirmarAccion');
        btn.textContent = labelBoton;

        // Limpiar listener anterior para evitar doble-disparo
        const nuevo = btn.cloneNode(true);
        btn.parentNode.replaceChild(nuevo, btn);

        nuevo.addEventListener('click', () => {
            dialog.close();
            resolve(true);
        });

        dialog.addEventListener('close', () => resolve(false), { once: true });

        // Cerrar clickeando backdrop
        dialog.addEventListener('click', function handler(e) {
            const rect = dialog.getBoundingClientRect();
            if (e.clientX < rect.left || e.clientX > rect.right ||
                e.clientY < rect.top  || e.clientY > rect.bottom) {
                dialog.close();
                dialog.removeEventListener('click', handler);
            }
        });

        dialog.showModal();
    });
}
function modalAviso(mensaje, titulo = 'Atención') {
    return new Promise(resolve => {
        const dialog = document.getElementById('dialogAviso');
        document.getElementById('dialogAvisoTitle').textContent = titulo;
        document.getElementById('dialogAvisoMsg').textContent = mensaje;

        const btn = document.getElementById('btnCerrarAviso');
        // Limpiar listener anterior para evitar doble-disparo
        const nuevoBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(nuevoBtn, btn);

        nuevoBtn.addEventListener('click', () => dialog.close());

        dialog.addEventListener('close', () => resolve(), { once: true });

        dialog.addEventListener('click', function handler(e) {
            const rect = dialog.getBoundingClientRect();
            if (e.clientX < rect.left || e.clientX > rect.right ||
                e.clientY < rect.top  || e.clientY > rect.bottom) {
                dialog.close();
                dialog.removeEventListener('click', handler);
            }
        });

        dialog.showModal();
    });
}

// Si el backend redirigió con un mensaje de sesión, también lo mostramos como toast
@if(session('success'))
    mostrarToast(@json(session('success')));
@endif
</script>

@endsection
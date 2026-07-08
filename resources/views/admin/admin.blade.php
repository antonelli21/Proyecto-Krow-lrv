@extends('layouts.app')

@section('title', 'Administración — KROW')

@section('banner')
<style>
#banner-index::before{
    content: "";
    position: absolute;
    inset: 0;

    background: url("{{ asset('img/banner.jpg') }}") center top / cover no-repeat;

    filter: blur(8px);
    transform: scale(1.08);

    z-index: 0;
}

#banner-index > *{
    position: relative;
    z-index: 1;
}
</style>
<div id="banner-index" style="
    width:100%;
    height:600px;
    position:relative;
    overflow:hidden;
    margin:0;
">
  <div style="
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
  "></div>
</div>
@endsection

@section('content')

<!-- Toast de confirmación -->
<div id="toast-msg" class="toast-msg" role="status" aria-live="polite"></div>

<div class="admin-page">

  <h1 class="admin-page-title">
    <i class="bi bi-shield-check"></i> Administración
  </h1>
  <p class="admin-page-sub">Gestión completa de estudiantes, empresas y ofertas de la plataforma.</p>

  {{-- ── Mensajes flash (fallback si el navegador no soporta fetch / primera carga) ── --}}
  @if($errors->any())
    <div style="margin-bottom:16px;padding:13px 16px;border:1px solid rgba(212,24,61,.35);background:rgba(14,24,22,.96);color:#e05577;font-size:13px;font-weight:700;display:flex;align-items:center;gap:8px;">
      <i class="bi bi-exclamation-circle"></i> {{ $errors->first() }}
    </div>
  @endif

  {{-- ═══ TABS ═══ --}}
  <div class="admin-tabs">
    <a href="{{ route('admin.estudiantes') }}" class="admin-tab {{ $seccion === 'estudiantes' ? 'active' : '' }}">
      <i class="bi bi-mortarboard"></i> Alumnos
    </a>
    <a href="{{ route('admin.empresas') }}" class="admin-tab {{ $seccion === 'empresas' ? 'active' : '' }}">
      <i class="bi bi-building"></i> Empresas
    </a>
    <a href="{{ route('admin.ofertas') }}" class="admin-tab {{ $seccion === 'ofertas' ? 'active' : '' }}">
      <i class="bi bi-briefcase"></i> Ofertas
    </a>
    <a href="{{ route('admin.reportes') }}" class="admin-tab {{ $seccion === 'reportes' ? 'active' : '' }}">
      <i class="bi bi-ticket-perforated"></i> Reportes
    </a>
    <a href="{{ route('admin.papelera') }}" class="admin-tab {{ $seccion === 'papelera' ? 'active' : '' }}">
      <i class="bi bi-trash2"></i> Papelera
    </a>
  </div>

  {{-- ════════════════════════════════════════
       TAB — ALUMNOS
  ════════════════════════════════════════ --}}
  @if($seccion === 'estudiantes')
  <div class="admin-tab-panel active" id="panel-alumnos">

    <div class="admin-stats">
      <div class="admin-stat">
        <div class="admin-stat-label label-total"><i class="bi bi-people"></i> Total</div>
        <div class="admin-stat-value" id="stat-alumnos-total">{{ $totalAlumnos }}</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-activo"><i class="bi bi-person-check"></i> Activos</div>
        <div class="admin-stat-value" id="stat-alumnos-activos">{{ $alumnosActivos }}</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-suspendido"><i class="bi bi-person-x"></i> Suspendidos</div>
        <div class="admin-stat-value" id="stat-alumnos-suspendidos">{{ $alumnosSuspendidos }}</div>
      </div>
    </div>

    <div class="admin-toolbar">
      <div class="admin-search">
        <i class="bi bi-search"></i>
        <input type="text" placeholder="Buscar por nombre, legajo o email...">
      </div>
      <select class="admin-filter-select" data-filter="estado">
        <option value="">Todos los estados</option>
        <option value="activo">Activo</option>
        <option value="suspendido">Suspendido</option>
        <option value="pendiente">Pendiente</option>
      </select>
      <select class="admin-filter-select" data-filter="carrera">
        <option value="">Todas las carreras</option>
        @foreach($estudiantes->unique('carrera.nombre') as $est)
          @if($est->carrera)
            <option value="{{ Str::slug($est->carrera->nombre) }}">{{ $est->carrera->nombre }}</option>
          @endif
        @endforeach
      </select>
    </div>

    <div class="bulk-bar" id="bulk-bar-alumnos" style="display:none;">
      <span class="bulk-count"></span>
      <button onclick="bulkAccion('panel-alumnos','estado','activo')">
        <i class="bi bi-check-circle"></i> Activar
      </button>
      <button onclick="bulkAccion('panel-alumnos','estado','suspendido')">
        <i class="bi bi-slash-circle"></i> Suspender
      </button>
      <button class="bulk-btn-danger" onclick="bulkAccion('panel-alumnos','delete')">
        <i class="bi bi-trash"></i> Eliminar
      </button>
      <button class="bulk-btn-cancel" onclick="clearBulk('panel-alumnos')">Cancelar</button>
    </div>

    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th class="th-check"><input type="checkbox" class="check-all"></th>
            <th>Legajo</th>
            <th>Nombre</th>
            <th>Carrera</th>
            <th>Estado</th>
            <th>Postulaciones</th>
            <th>Registro</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($estudiantes as $i => $a)
            @php
              $badgeEst = match($a->estado ?? 'pendiente') {
                'activo'     => 'activo',
                'suspendido' => 'suspendido',
                'pendiente'  => 'pendiente',
                default      => 'pendiente'
              };
              $delay = min($i, 8) * 0.035;
            @endphp
            <tr data-id="{{ $a->id_estudiante }}"
                data-search="{{ strtolower(($a->nombre ?? '').' '.($a->apellido ?? '').' '.($a->legajo ?? '').' '.($a->user->email ?? '')) }}"
                data-estado="{{ $a->estado ?? 'pendiente' }}"
                data-carrera="{{ $a->carrera ? Str::slug($a->carrera->nombre) : '' }}"
                class="fade-in-row" style="animation-delay: {{ $delay }}s;">
              <td data-label=""><input type="checkbox" class="check-row"></td>
              <td data-label="Legajo">{{ $a->legajo ?? '—' }}</td>
              <td class="td-nombre" data-label="Nombre">
                <a href="{{ route('admin.estudiante.perfil', $a->id_estudiante) }}" class="admin-name-link">
                  {{ $a->apellido }}, {{ $a->nombre }}
                </a>
                <br><span class="td-id">{{ $a->user->email ?? '—' }}</span>
              </td>
              <td class="td-carrera" data-label="Carrera">{{ $a->carrera->nombre ?? '—' }}</td>
              <td data-label="Estado">
                <span class="badge-admin badge-{{ $badgeEst }}" id="badge-estudiante-{{ $a->id_estudiante }}">
                  {{ ucfirst($a->estado ?? 'pendiente') }}
                </span>
              </td>
              <td data-label="Postulaciones">{{ $a->postulaciones_count }}</td>
              <td class="td-fecha" data-label="Registro">
                {{ $a->fecha_creacion ? \Carbon\Carbon::parse($a->fecha_creacion)->format('d/m/Y') : '—' }}
              </td>
              <td data-label="Acciones">
                <div class="td-acciones">
                  <button class="btn-icon btn-ver" title="Ver perfil"
                          onclick="toggleAdminDetalle('{{ $a->id_estudiante }}', this)">
                    <i class="bi bi-eye"></i>
                  </button>
                  <button class="btn-icon btn-aprobar" title="Activar"
                          onclick="submitEstado('estudiante', {{ $a->id_estudiante }}, '{{ route('admin.estudiantes.estado', $a->id_estudiante) }}', 'activo')">
                    <i class="bi bi-check-circle"></i>
                  </button>
                  <button class="btn-icon btn-suspender" title="Suspender"
                          onclick="submitEstado('estudiante', {{ $a->id_estudiante }}, '{{ route('admin.estudiantes.estado', $a->id_estudiante) }}', 'suspendido')">
                    <i class="bi bi-slash-circle"></i>
                  </button>
                  <button class="btn-icon btn-eliminar" title="Eliminar"
                          data-delete-url="{{ route('admin.estudiantes.destroy', $a->id_estudiante) }}"
                          data-delete-name="{{ $a->nombre }} {{ $a->apellido }}"
                          data-delete-tipo="estudiante"
                          data-delete-id="{{ $a->id_estudiante }}">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
            {{-- DETALLE EXPANDIBLE --}}
            <tr class="admin-detalle-row" id="admin-det-{{ $a->id_estudiante }}">
              <td colspan="8">
                <div class="admin-detalle-inner">
                  <div>
                    <p class="admin-detalle-block-title">Datos personales</p>
                    <p class="admin-detalle-value">Legajo: {{ $a->legajo ?? '—' }}</p>
                    <p class="admin-detalle-value">Email: {{ $a->user->email ?? '—' }}</p>
                    <p class="admin-detalle-value">Teléfono: {{ $a->telefono ?? '—' }}</p>
                    <p class="admin-detalle-value">DNI: {{ $a->dni ?? '—' }}</p>
                    <p class="admin-detalle-value">
                      Registro: {{ $a->fecha_creacion ? \Carbon\Carbon::parse($a->fecha_creacion)->format('d/m/Y') : '—' }}
                    </p>
                  </div>
                  <div>
                    <p class="admin-detalle-block-title">Académico</p>
                    <p class="admin-detalle-value">Carrera: {{ $a->carrera->nombre ?? '—' }}</p>
                    <p class="admin-detalle-value">Postulaciones: {{ $a->postulaciones_count }}</p>
                    <p class="admin-detalle-value">Modalidad: {{ $a->modalidad_deseada ?? '—' }}</p>
                    <p class="admin-detalle-value">Disponibilidad: {{ $a->disponibilidad_horaria ?? '—' }}</p>
                  </div>
                  <div>
                    <p class="admin-detalle-block-title">Acciones</p>
                    <div class="admin-detalle-actions">
                      <button class="btn-admin-aprobar"
                              onclick="submitEstado('estudiante', {{ $a->id_estudiante }}, '{{ route('admin.estudiantes.estado', $a->id_estudiante) }}', 'activo')">
                        <i class="bi bi-check-circle"></i> Activar
                      </button>
                      <button class="btn-admin-suspender"
                              onclick="submitEstado('estudiante', {{ $a->id_estudiante }}, '{{ route('admin.estudiantes.estado', $a->id_estudiante) }}', 'suspendido')">
                        <i class="bi bi-slash-circle"></i> Suspender
                      </button>
                      <button class="btn-admin-rechazar"
                              data-delete-url="{{ route('admin.estudiantes.destroy', $a->id_estudiante) }}"
                              data-delete-name="{{ $a->nombre }} {{ $a->apellido }}"
                              data-delete-tipo="estudiante"
                              data-delete-id="{{ $a->id_estudiante }}">
                        <i class="bi bi-trash"></i> Eliminar
                      </button>
                      @if($a->id_usuario)
                        <a href="{{ route('admin.mensajes', ['postulante_id' => $a->id_usuario]) }}"
                           class="btn-admin-contactar">
                          <i class="bi bi-chat-dots"></i> Contactar
                        </a>
                      @endif
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" style="text-align:center;padding:2rem;color:var(--muted);">
                No hay estudiantes registrados.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>

      @if($estudiantes->hasPages())
        <div style="padding:16px;">{{ $estudiantes->links() }}</div>
      @endif

      <div class="admin-empty" style="display:none">
        <i class="bi bi-search"></i>
        <p>No se encontraron estudiantes con esos filtros.</p>
      </div>
    </div>
  </div>
  @endif

  {{-- ════════════════════════════════════════
       TAB — EMPRESAS
  ════════════════════════════════════════ --}}
  @if($seccion === 'empresas')
  <div class="admin-tab-panel active" id="panel-empresas">

    <div class="admin-stats">
      <div class="admin-stat">
        <div class="admin-stat-label label-total"><i class="bi bi-building"></i> Total</div>
        <div class="admin-stat-value" id="stat-empresas-total">{{ $totalEmpresas }}</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-aprobado"><i class="bi bi-check-circle"></i> Aprobadas</div>
        <div class="admin-stat-value" id="stat-empresas-aprobadas">{{ $empresasAprobadas }}</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-suspendido"><i class="bi bi-slash-circle"></i> Suspendidas</div>
        <div class="admin-stat-value" id="stat-empresas-suspendidas">{{ $empresasSuspendidas }}</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-pendiente"><i class="bi bi-hourglass-split"></i> Pendientes</div>
        <div class="admin-stat-value" id="stat-empresas-pendientes">{{ $empresasPendientes }}</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-rechazado"><i class="bi bi-x-circle"></i> Rechazadas</div>
        <div class="admin-stat-value" id="stat-empresas-rechazadas">{{ $empresasRechazadas }}</div>
      </div>
    </div>

    <div class="admin-toolbar">
      <div class="admin-search">
        <i class="bi bi-search"></i>
        <input type="text" placeholder="Buscar por nombre, rubro o ubicación...">
      </div>
      <select class="admin-filter-select" data-filter="estado">
        <option value="">Todos los estados</option>
        <option value="aprobada">Aprobada</option>
        <option value="suspendida">Suspendida</option>
        <option value="pendiente">Pendiente</option>
        <option value="rechazada">Rechazada</option>
      </select>
    </div>

    <div class="bulk-bar" id="bulk-bar-empresas" style="display:none;">
      <span class="bulk-count"></span>
      <button onclick="bulkAccion('panel-empresas','estado','aprobada')">
        <i class="bi bi-check-circle"></i> Aprobar
      </button>
      <button onclick="bulkAccion('panel-empresas','estado','suspendida')">
        <i class="bi bi-slash-circle"></i> Suspender
      </button>
      <button class="bulk-btn-danger" onclick="bulkAccion('panel-empresas','delete')">
        <i class="bi bi-trash"></i> Eliminar
      </button>
      <button class="bulk-btn-cancel" onclick="clearBulk('panel-empresas')">Cancelar</button>
    </div>

    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th class="th-check"><input type="checkbox" class="check-all"></th>
            <th>Empresa</th>
            <th>Rubro</th>
            <th>Ubicación</th>
            <th>Ofertas activas</th>
            <th>Estado</th>
            <th>Registro</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($empresas as $i => $e)
            @php
              $badgeEmp = match($e->estado ?? 'pendiente') {
                'aprobada'   => 'aprobado',
                'rechazada'  => 'rechazado',
                'suspendida' => 'suspendido',
                'pendiente'  => 'pendiente',
                default      => 'pendiente'
              };
              $delay = min($i, 8) * 0.035;
            @endphp
            <tr data-id="{{ $e->id_empresa }}"
                data-search="{{ strtolower(($e->nombre_empresa ?? '').' '.($e->rubro ?? '').' '.($e->user->email ?? '')) }}"
                data-estado="{{ $e->estado ?? 'pendiente' }}"
                class="fade-in-row" style="animation-delay: {{ $delay }}s;">
              <td data-label=""><input type="checkbox" class="check-row"></td>
              <td class="td-nombre" data-label="Empresa">
                <a href="{{ route('empresas.perfil', $e->id_empresa) }}" class="admin-name-link">
                  {{ $e->nombre_empresa }}
                </a>
                <br><span class="td-id">{{ $e->user->email ?? '—' }}</span>
              </td>
              <td class="td-carrera" data-label="Rubro">{{ $e->rubro ?? '—' }}</td>
              <td class="td-ubicacion" data-label="Ubicación">{{ $e->direccion ?? '—' }}</td>
              <td data-label="Ofertas activas">{{ $e->ofertas_activas_count }}</td>
              <td data-label="Estado">
                <span class="badge-admin badge-{{ $badgeEmp }}" id="badge-empresa-{{ $e->id_empresa }}">
                  {{ ucfirst($e->estado ?? 'pendiente') }}
                </span>
              </td>
              <td class="td-fecha" data-label="Registro">
                {{ $e->fecha_creacion ? \Carbon\Carbon::parse($e->fecha_creacion)->format('d/m/Y') : '—' }}
              </td>
              <td data-label="Acciones">
                <div class="td-acciones">
                  <button class="btn-icon btn-ver" title="Ver detalle"
                          onclick="toggleAdminDetalle('e{{ $e->id_empresa }}', this)">
                    <i class="bi bi-eye"></i>
                  </button>
                  <button class="btn-icon btn-aprobar" title="Aprobar"
                          onclick="submitEstado('empresa', {{ $e->id_empresa }}, '{{ route('admin.empresas.estado', $e->id_empresa) }}', 'aprobada')">
                    <i class="bi bi-check-circle"></i>
                  </button>
                  <button class="btn-icon btn-suspender" title="Suspender"
                          onclick="submitEstado('empresa', {{ $e->id_empresa }}, '{{ route('admin.empresas.estado', $e->id_empresa) }}', 'suspendida')">
                    <i class="bi bi-slash-circle"></i>
                  </button>
                  <button class="btn-icon btn-eliminar" title="Eliminar"
                          data-delete-url="{{ route('admin.empresas.destroy', $e->id_empresa) }}"
                          data-delete-name="{{ $e->nombre_empresa }}"
                          data-delete-tipo="empresa"
                          data-delete-id="{{ $e->id_empresa }}">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </td>
            </tr>

            {{-- DETALLE EXPANDIBLE --}}
            <tr class="admin-detalle-row" id="admin-det-e{{ $e->id_empresa }}">
              <td colspan="8">
                <div class="admin-detalle-inner">
                  <div>
                    <p class="admin-detalle-block-title">Descripción</p>
                    <p class="admin-detalle-value" style="word-break:break-word; overflow-wrap:break-word;">
                      {{ \Illuminate\Support\Str::limit($e->descripcion ?? '—', 100) }}
                    </p>
                    <p class="admin-detalle-value" style="margin-top:8px;">
                      Registro: {{ $e->fecha_creacion ? \Carbon\Carbon::parse($e->fecha_creacion)->format('d/m/Y') : '—' }}
                    </p>
                  </div>
                  <div>
                    <p class="admin-detalle-block-title">Actividad</p>
                    <p class="admin-detalle-value">Rubro: {{ $e->rubro ?? '—' }}</p>
                    <p class="admin-detalle-value">Ubicación: {{ $e->direccion ?? '—' }}</p>
                    <p class="admin-detalle-value">CUIT: {{ $e->cuit ?? '—' }}</p>
                    <p class="admin-detalle-value">Tamaño: {{ $e->tamano_empresa ?? '—' }}</p>
                    <p class="admin-detalle-value">Ofertas activas: {{ $e->ofertas_activas_count }}</p>
                  </div>
                  <div>
                    <p class="admin-detalle-block-title">Acciones</p>
                    <div class="admin-detalle-actions">
                      <button class="btn-admin-aprobar"
                              onclick="submitEstado('empresa', {{ $e->id_empresa }}, '{{ route('admin.empresas.estado', $e->id_empresa) }}', 'aprobada')">
                        <i class="bi bi-check-circle"></i> Aprobar
                      </button>
                      <button class="btn-admin-rechazar"
                              onclick="submitEstado('empresa', {{ $e->id_empresa }}, '{{ route('admin.empresas.estado', $e->id_empresa) }}', 'rechazada')">
                        <i class="bi bi-x-circle"></i> Rechazar
                      </button>
                      <button class="btn-admin-suspender"
                              onclick="submitEstado('empresa', {{ $e->id_empresa }}, '{{ route('admin.empresas.estado', $e->id_empresa) }}', 'suspendida')">
                        <i class="bi bi-slash-circle"></i> Suspender
                      </button>
                      <button class="btn-admin-rechazar"
                              data-delete-url="{{ route('admin.empresas.destroy', $e->id_empresa) }}"
                              data-delete-name="{{ $e->nombre_empresa }}"
                              data-delete-tipo="empresa"
                              data-delete-id="{{ $e->id_empresa }}">
                        <i class="bi bi-trash"></i> Eliminar
                      </button>
                      @if($e->id_usuario)
                        <a href="{{ route('admin.mensajes', ['postulante_id' => $e->id_usuario]) }}"
                           class="btn-admin-contactar">
                          <i class="bi bi-chat-dots"></i> Contactar
                        </a>
                      @endif
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" style="text-align:center;padding:2rem;color:var(--muted);">
                No hay empresas registradas.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>

      @if($empresas->hasPages())
        <div style="padding:16px;">{{ $empresas->links() }}</div>
      @endif

      <div class="admin-empty" style="display:none">
        <i class="bi bi-search"></i>
        <p>No se encontraron empresas con esos filtros.</p>
      </div>
    </div>
  </div>
  @endif

  {{-- ════════════════════════════════════════
       TAB — OFERTAS
  ════════════════════════════════════════ --}}
  @if($seccion === 'ofertas')
  <div class="admin-tab-panel active" id="panel-ofertas">

    <div class="admin-stats">
      <div class="admin-stat">
        <div class="admin-stat-label label-total"><i class="bi bi-briefcase"></i> Total</div>
        <div class="admin-stat-value" id="stat-ofertas-total">{{ $totalOfertas }}</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-publicada"><i class="bi bi-megaphone"></i> Publicadas</div>
        <div class="admin-stat-value" id="stat-ofertas-publicadas">{{ $ofertasPublicadas }}</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-pendiente"><i class="bi bi-hourglass-split"></i> Pendientes</div>
        <div class="admin-stat-value" id="stat-ofertas-pendientes">{{ $ofertasPendientes }}</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-pausada"><i class="bi bi-pause-circle"></i> Pausadas</div>
        <div class="admin-stat-value" id="stat-ofertas-pausadas">{{ $ofertasPausadas }}</div>
      </div>
    </div>

    <div class="admin-toolbar">
      <div class="admin-search">
        <i class="bi bi-search"></i>
        <input type="text" placeholder="Buscar por título o empresa...">
      </div>
      <select class="admin-filter-select" data-filter="estado">
        <option value="">Todos los estados</option>
        <option value="activa">Activa</option>
        <option value="pendiente">Pendiente</option>
        <option value="pausada">Pausada</option>
      </select>
      <select class="admin-filter-select" data-filter="modalidad">
        <option value="">Todas las modalidades</option>
        <option value="presencial">Presencial</option>
        <option value="remoto">Remoto</option>
        <option value="hibrido">Híbrido</option>
      </select>
      <select class="admin-filter-select" data-filter="tipo">
        <option value="">Todos los tipos</option>
        <option value="pasantia">Pasantía</option>
        <option value="part-time">Part-Time</option>
        <option value="full-time">Full-Time</option>
      </select>
    </div>

    <div class="bulk-bar" id="bulk-bar-ofertas" style="display:none;">
      <span class="bulk-count"></span>
      <button onclick="bulkAccion('panel-ofertas','estado','Activa')">
        <i class="bi bi-check-circle"></i> Activar
      </button>
      <button onclick="bulkAccion('panel-ofertas','estado','Pausada')">
        <i class="bi bi-pause-circle"></i> Pausar
      </button>
      <button class="bulk-btn-danger" onclick="bulkAccion('panel-ofertas','delete')">
        <i class="bi bi-trash"></i> Eliminar
      </button>
      <button class="bulk-btn-cancel" onclick="clearBulk('panel-ofertas')">Cancelar</button>
    </div>

    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th class="th-check"><input type="checkbox" class="check-all"></th>
            <th>Título</th>
            <th>Empresa</th>
            <th>Modalidad</th>
            <th>Tipo</th>
            <th>Postulantes</th>
            <th>Estado</th>
            <th>Publicación</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($ofertas as $i => $o)
            @php
              $estado = strtolower($o->estado ?? '');
              $badgeOfe = match($estado) {
                'activa'  => 'publicada',
                'pausada' => 'pausada',
                default   => 'pendiente'
              };
              $labelOfe = ucfirst($estado);
              $delay = min($i, 8) * 0.035;
            @endphp
            <tr data-id="{{ $o->id_oferta }}"
                data-search="{{ strtolower(($o->titulo ?? '').' '.($o->empresa->nombre_empresa ?? '')) }}"
                data-estado="{{ $o->estado ?? '' }}"
                data-modalidad="{{ strtolower($o->modalidad ?? '') }}"
                data-tipo="{{ strtolower(str_replace(' ', '-', $o->tipo_oferta ?? '')) }}"
                class="fade-in-row" style="animation-delay: {{ $delay }}s;">
              <td data-label=""><input type="checkbox" class="check-row"></td>
              <td class="td-nombre" data-label="Título">
                <a href="{{ route('ofertas.detalle', $o->id_oferta) }}" class="admin-name-link">
                  {{ $o->titulo }}
                </a>
              </td>
              <td class="td-carrera" data-label="Empresa">{{ $o->empresa->nombre_empresa ?? '—' }}</td>
              <td data-label="Modalidad">{{ ucfirst($o->modalidad ?? '—') }}</td>
              <td data-label="Tipo"><span class="badge-tipo">{{ ucfirst($o->tipo_oferta ?? '—') }}</span></td>
              <td data-label="Postulantes">{{ $o->postulaciones_count }}</td>
              <td data-label="Estado">
                <span class="badge-admin badge-{{ $badgeOfe }}" id="badge-oferta-{{ $o->id_oferta }}">{{ $labelOfe }}</span>
              </td>
              <td class="td-fecha" data-label="Publicación">
                {{ $o->fecha_publicacion ? \Carbon\Carbon::parse($o->fecha_publicacion)->format('d/m/Y') : '—' }}
              </td>
              <td data-label="Acciones">
                <div class="td-acciones">
                  <button class="btn-icon btn-ver" title="Ver detalle"
                          onclick="toggleAdminDetalle('o{{ $o->id_oferta }}', this)">
                    <i class="bi bi-eye"></i>
                  </button>
                  <button class="btn-icon btn-aprobar" title="Activar"
                          onclick="submitEstado('oferta', {{ $o->id_oferta }}, '{{ route('admin.ofertas.estado', $o->id_oferta) }}', 'Activa')">
                    <i class="bi bi-check-circle"></i>
                  </button>
                  @if($estado !== 'pausada')
                    <button class="btn-icon btn-suspender" title="Pausar"
                            onclick="submitEstadoConMotivo({{ $o->id_oferta }}, '{{ route('admin.ofertas.estado', $o->id_oferta) }}', 'Pausada')">
                      <i class="bi bi-pause-circle"></i>
                    </button>
                  @else
                    <button class="btn-icon" style="opacity:.3; cursor:not-allowed;" disabled title="Ya está pausada">
                      <i class="bi bi-pause-circle"></i>
                    </button>
                  @endif
                  <button class="btn-icon btn-eliminar" title="Eliminar"
                          data-delete-url="{{ route('admin.ofertas.destroy', $o->id_oferta) }}"
                          data-delete-name="{{ $o->titulo }}"
                          data-delete-tipo="oferta"
                          data-delete-id="{{ $o->id_oferta }}">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
            {{-- DETALLE EXPANDIBLE --}}
            <tr class="admin-detalle-row" id="admin-det-o{{ $o->id_oferta }}">
              <td colspan="9">
                <div class="admin-detalle-inner">
                  <div>
                    <p class="admin-detalle-block-title">Descripción</p>
                    <p class="admin-detalle-value" style="word-break:break-word; overflow-wrap:break-word;">{{ Str::limit($o->descripcion ?? '—', 100) }}</p>
                    @if($o->requisitos)
                      <p class="admin-detalle-block-title" style="margin-top:10px;">Requisitos</p>
                      <p class="admin-detalle-value" style="word-break:break-word; overflow-wrap:break-word;">{{ Str::limit($o->requisitos, 100) }}</p>
                    @endif
                  </div>
                  <div>
                    <p class="admin-detalle-block-title">Detalles</p>
                    <p class="admin-detalle-value">Empresa: {{ $o->empresa->nombre_empresa ?? '—' }}</p>
                    <p class="admin-detalle-value">Modalidad: {{ ucfirst($o->modalidad ?? '—') }}</p>
                    <p class="admin-detalle-value">Tipo: {{ ucfirst($o->tipo_oferta ?? '—') }}</p>
                    <p class="admin-detalle-value">Experiencia: {{ $o->experiencia_requerida ?? '—' }}</p>
                    <p class="admin-detalle-value">Postulantes: {{ $o->postulaciones_count }}</p>
                    @if($o->salario_min)
                      <p class="admin-detalle-value">Salario: ${{ number_format($o->salario_min) }} – ${{ number_format($o->salario_max) }}</p>
                    @endif
                  </div>
                  <div>
                    <p class="admin-detalle-block-title">Acciones</p>
                    <div class="admin-detalle-actions">
                      <button class="btn-admin-aprobar"
                              onclick="submitEstado('oferta', {{ $o->id_oferta }}, '{{ route('admin.ofertas.estado', $o->id_oferta) }}', 'Activa')">
                        <i class="bi bi-check-circle"></i> Activar
                      </button>
                      @if($estado !== 'pausada')
                        <button class="btn-admin-suspender"
                                onclick="submitEstadoConMotivo({{ $o->id_oferta }}, '{{ route('admin.ofertas.estado', $o->id_oferta) }}', 'Pausada')">
                          <i class="bi bi-pause-circle"></i> Pausar
                        </button>
                      @else
                        <span class="btn-admin-suspender" style="opacity:.4; cursor:not-allowed;">
                          <i class="bi bi-pause-circle"></i> Ya pausada
                        </span>
                      @endif
                      <button class="btn-admin-rechazar"
                              data-delete-url="{{ route('admin.ofertas.destroy', $o->id_oferta) }}"
                              data-delete-name="{{ $o->titulo }}"
                              data-delete-tipo="oferta"
                              data-delete-id="{{ $o->id_oferta }}">
                        <i class="bi bi-trash"></i> Eliminar
                      </button>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" style="text-align:center;padding:2rem;color:var(--muted);">
                No hay ofertas registradas.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>

      @if($ofertas->hasPages())
        <div style="padding:16px;">{{ $ofertas->links() }}</div>
      @endif

      <div class="admin-empty" style="display:none">
        <i class="bi bi-search"></i>
        <p>No se encontraron ofertas con esos filtros.</p>
      </div>
    </div>
  </div>
  @endif

  {{-- ════════════════════════════════════════
       TAB — REPORTES
  ════════════════════════════════════════ --}}
  @if($seccion === 'reportes')
  <div class="admin-tab-panel active" id="panel-reportes">

    <div class="admin-stats">
      <div class="admin-stat">
        <div class="admin-stat-label label-total"><i class="bi bi-ticket-perforated"></i> Total</div>
        <div class="admin-stat-value" id="stat-reportes-total">{{ $totalReportes }}</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-pendiente"><i class="bi bi-envelope"></i> Abiertos</div>
        <div class="admin-stat-value" id="stat-reportes-abiertos">{{ $reportesAbiertos }}</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-suspendido"><i class="bi bi-arrow-repeat"></i> En Proceso</div>
        <div class="admin-stat-value" id="stat-reportes-proceso">{{ $reportesEnProceso }}</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-activo"><i class="bi bi-check2-all"></i> Resueltos</div>
        <div class="admin-stat-value" id="stat-reportes-resueltos">{{ $reportesResueltos }}</div>
      </div>
    </div>

    <div class="admin-toolbar">
      <div class="admin-search">
        <i class="bi bi-search"></i>
        <input type="text" placeholder="Buscar por nombre, email o asunto...">
      </div>
      <select class="admin-filter-select" data-filter="estado">
        <option value="">Todos los estados</option>
        <option value="abierto">Abierto</option>
        <option value="en proceso">En Proceso</option>
        <option value="resuelto">Resuelto</option>
      </select>
    </div>

    <div class="bulk-bar" id="bulk-bar-reportes" style="display:none;">
      <span class="bulk-count"></span>
      <button onclick="bulkAccionReportes('estado','Abierto')">
        <i class="bi bi-envelope"></i> Marcar Abierto
      </button>
      <button onclick="bulkAccionReportes('estado','En Proceso')">
        <i class="bi bi-arrow-repeat"></i> En Proceso
      </button>
      <button onclick="bulkAccionReportes('estado','Resuelto')">
        <i class="bi bi-check2-all"></i> Resuelto
      </button>
      <button class="bulk-btn-danger" onclick="bulkAccionReportes('delete')">
        <i class="bi bi-trash"></i> Eliminar
      </button>
      <button class="bulk-btn-cancel" onclick="clearBulk('panel-reportes')">Cancelar</button>
    </div>

    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th class="th-check"><input type="checkbox" class="check-all"></th>
            <th>Remitente</th>
            <th>Asunto</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($reportes as $i => $r)
            @php
              $badgeRep = match($r->estado) {
                'Abierto'    => 'pendiente',
                'En Proceso' => 'suspendido',
                'Resuelto'   => 'activo',
                default      => 'pendiente'
              };
              $nombreMostrar = $r->user_name  ?? $r->nombre_remitente ?? '—';
              $emailMostrar  = $r->user_email ?? $r->email_remitente  ?? '—';
              $asuntoReal    = $r->asunto;
              $delay = min($i, 8) * 0.035;
            @endphp
            <tr data-id="{{ $r->id_ticket }}"
                data-search="{{ strtolower($nombreMostrar . ' ' . $emailMostrar . ' ' . $asuntoReal) }}"
                data-estado="{{ strtolower($r->estado) }}"
                id="fila-reporte-{{ $r->id_ticket }}"
                class="fade-in-row" style="animation-delay: {{ $delay }}s; {{ $r->estado === 'Abierto' ? 'font-weight:600;' : '' }}">
              <td data-label=""><input type="checkbox" class="check-row"></td>
              <td class="td-nombre" data-label="Remitente">
                {{ $nombreMostrar }}
                <br><span class="td-id">{{ $emailMostrar }}</span>
              </td>
              <td data-label="Asunto" style="max-width:260px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                {{ $asuntoReal }}
              </td>
              <td class="td-fecha" data-label="Fecha">
                {{ \Carbon\Carbon::parse($r->fecha_creacion)->format('d/m/Y H:i') }}
              </td>
              <td data-label="Estado">
                <span class="badge-admin badge-{{ $badgeRep }}" id="badge-rep-{{ $r->id_ticket }}">
                  {{ $r->estado }}
                </span>
              </td>
              <td data-label="Acciones">
                <div class="td-acciones">
                  <button class="btn-icon btn-ver" title="Ver ticket"
                          onclick="toggleAdminDetalle('rep{{ $r->id_ticket }}', this)">
                    <i class="bi bi-eye"></i>
                  </button>
                  <button class="btn-icon btn-eliminar" title="Eliminar ticket"
                          data-delete-url="{{ route('admin.reportes.destroy', $r->id_ticket) }}"
                          data-delete-name="el ticket &quot;{{ $asuntoReal }}&quot;"
                          data-delete-tipo="reporte"
                          data-delete-id="{{ $r->id_ticket }}">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </td>
            </tr>

            {{-- DETALLE EXPANDIBLE --}}
            <tr class="admin-detalle-row" id="admin-det-rep{{ $r->id_ticket }}">
              <td colspan="6" style="padding: 12px 14px !important; overflow-x: hidden;">
                <div class="admin-detalle-inner" style="
                    display: grid;
                    grid-template-columns: 1fr 1.8fr 0.8fr;
                    gap: 12px;
                    max-width: 100%;
                    overflow-x: hidden;
                ">
                  {{-- Columna 1: Información --}}
                  <div>
                    <p class="admin-detalle-block-title">Información</p>
                    <p class="admin-detalle-value" style="font-weight:600;">{{ $nombreMostrar }}</p>
                    <p class="admin-detalle-value">{{ $emailMostrar }}</p>
                    <p class="admin-detalle-value" style="margin-top:8px; font-size:11.5px; color:var(--muted);">
                      {{ \Carbon\Carbon::parse($r->fecha_creacion)->format('d/m/Y H:i') }}
                    </p>
                    @if($r->id_usuario)
                      <p class="admin-detalle-value" style="margin-top:4px; font-size:11.5px; color:var(--accent);">
                        <i class="bi bi-person-check"></i> Usuario registrado
                      </p>
                    @else
                      <p class="admin-detalle-value" style="margin-top:4px; font-size:11.5px; color:var(--muted);">
                        <i class="bi bi-person-x"></i> No registrado
                      </p>
                    @endif
                  </div>

                  {{-- Columna 2: Contenido --}}
                  <div>
                    <p class="admin-detalle-block-title">Contenido</p>
                    <p class="admin-detalle-value" style="font-weight:600; margin-bottom:4px;">{{ $asuntoReal }}</p>
                    <p class="admin-detalle-value" style="white-space:pre-line; line-height:1.7; word-break:break-word; overflow-wrap:break-word;">{{ $r->descripcion }}</p>
                  </div>

                  {{-- Columna 3: Gestión --}}
                  <div>
                    <p class="admin-detalle-block-title">Gestión</p>
                    <div class="admin-detalle-actions" style="display:flex; flex-direction:column; gap:8px; align-items:flex-start;">

                      {{-- Cambiar estado — ahora vía AJAX, sin recargar --}}
                      <select class="admin-filter-select select-estado-reporte"
                              style="font-size:12px; padding:6px 10px;"
                              data-id="{{ $r->id_ticket }}"
                              data-url="{{ route('admin.reportes.estado', $r->id_ticket) }}"
                              onchange="cambiarEstadoReporte(this)">
                        <option value="Abierto"    {{ $r->estado === 'Abierto'    ? 'selected' : '' }}>Abierto</option>
                        <option value="En Proceso" {{ $r->estado === 'En Proceso' ? 'selected' : '' }}>En Proceso</option>
                        <option value="Resuelto"   {{ $r->estado === 'Resuelto'   ? 'selected' : '' }}>Resuelto</option>
                      </select>

                      {{-- Contactar / Email --}}
                      @if($r->id_usuario)
                        <a href="{{ route('admin.mensajes', ['postulante_id' => $r->id_usuario]) }}"
                           class="btn-admin-contactar">
                          <i class="bi bi-chat-dots"></i> Contactar
                        </a>
                      @else
                        <a href="mailto:{{ $emailMostrar }}" class="btn-admin-contactar">
                          <i class="bi bi-envelope"></i> Enviar email
                        </a>
                      @endif

                      {{-- Eliminar --}}
                      <button class="btn-admin-rechazar"
                              data-delete-url="{{ route('admin.reportes.destroy', $r->id_ticket) }}"
                              data-delete-name="el ticket &quot;{{ $asuntoReal }}&quot;"
                              data-delete-tipo="reporte"
                              data-delete-id="{{ $r->id_ticket }}">
                        <i class="bi bi-trash"></i> Eliminar
                      </button>

                    </div>
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align:center; padding:30px; color:var(--muted);">
                <i class="bi bi-inbox" style="font-size:24px; display:block; margin-bottom:8px;"></i>
                No hay reportes
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>

      <div class="admin-empty" style="display:none">
        <i class="bi bi-search"></i>
        <p>No se encontraron reportes con esos filtros.</p>
      </div>
    </div>
  </div>
  @endif

  {{-- ════════════════════════════════════════
       TAB — PAPELERA
  ════════════════════════════════════════ --}}
  @if($seccion === 'papelera')
  <div class="admin-tab-panel active" id="panel-papelera">

    {{-- ── OFERTAS ELIMINADAS ── --}}
    <div class="admin-toolbar admin-toolbar-papelera">
      <h3 class="admin-toolbar-papelera-title">
        <i class="bi bi-briefcase"></i> Ofertas eliminadas
      </h3>
      <div class="admin-search">
        <i class="bi bi-search"></i>
        <input type="text" placeholder="Buscar por título o empresa...">
      </div>
    </div>

    <div class="bulk-bar" id="bulk-bar-papelera-ofertas" style="display:none;">
      <span class="bulk-count"></span>
      <button onclick="bulkAccionPapelera('ofertas','restaurar')">
        <i class="bi bi-arrow-counterclockwise"></i> Restaurar
      </button>
      <button class="bulk-btn-danger" onclick="bulkAccionPapelera('ofertas','destroy')">
        <i class="bi bi-trash"></i> Eliminar definitivamente
      </button>
      <button class="bulk-btn-cancel" onclick="clearBulkPapelera('ofertas')">Cancelar</button>
    </div>

    <div class="admin-table-wrap" style="margin-bottom:32px;" id="panel-papelera-ofertas">
      <table class="admin-table">
        <thead>
          <tr>
            <th class="th-check"><input type="checkbox" class="check-all-papelera" data-grupo="ofertas"></th>
            <th>Título</th>
            <th>Empresa</th>
            <th>Eliminada</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($ofertasEliminadas as $i => $o)
            @php $delay = min($i, 8) * 0.035; @endphp
            <tr data-id="{{ $o->id_oferta }}"
                data-search="{{ strtolower(($o->titulo ?? '').' '.($o->empresa->nombre_empresa ?? '')) }}"
                class="fade-in-row papelera-row" data-grupo="ofertas" style="animation-delay: {{ $delay }}s;">
              <td data-label=""><input type="checkbox" class="check-row-papelera" data-grupo="ofertas"></td>
              <td class="td-nombre" data-label="Título">{{ $o->titulo }}</td>
              <td class="td-carrera" data-label="Empresa">{{ $o->empresa->nombre_empresa ?? '—' }}</td>
              <td class="td-fecha" data-label="Eliminada">{{ \Carbon\Carbon::parse($o->deleted_at)->format('d/m/Y H:i') }}</td>
              <td data-label="Acciones">
                <div class="td-acciones">
                  <button class="btn-icon btn-aprobar" title="Restaurar"
                          onclick="restaurarPapelera('ofertas', {{ $o->id_oferta }}, '{{ route('admin.papelera.oferta.restaurar', $o->id_oferta) }}')">
                    <i class="bi bi-arrow-counterclockwise"></i>
                  </button>
                  <button class="btn-icon btn-eliminar" title="Eliminar definitivamente"
                          data-delete-url="{{ route('admin.papelera.oferta.destroy', $o->id_oferta) }}"
                          data-delete-name="{{ $o->titulo }} (permanente)"
                          data-delete-tipo="papelera-oferta"
                          data-delete-id="{{ $o->id_oferta }}">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="text-align:center;padding:2rem;color:var(--muted);">
                No hay ofertas eliminadas.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
      @if($ofertasEliminadas->hasPages())
        <div style="padding:16px;">{{ $ofertasEliminadas->links() }}</div>
      @endif
      <div class="admin-empty" style="display:none">
        <i class="bi bi-search"></i>
        <p>No se encontraron ofertas eliminadas con esa búsqueda.</p>
      </div>
    </div>

    {{-- ── POSTULACIONES ELIMINADAS ── --}}
    <div class="admin-toolbar admin-toolbar-papelera">
      <h3 class="admin-toolbar-papelera-title">
        <i class="bi bi-send"></i> Postulaciones eliminadas
      </h3>
      <div class="admin-search">
        <i class="bi bi-search"></i>
        <input type="text" placeholder="Buscar por estudiante, oferta o empresa...">
      </div>
    </div>

    <div class="bulk-bar" id="bulk-bar-papelera-postulaciones" style="display:none;">
      <span class="bulk-count"></span>
      <button onclick="bulkAccionPapelera('postulaciones','restaurar')">
        <i class="bi bi-arrow-counterclockwise"></i> Restaurar
      </button>
      <button class="bulk-btn-danger" onclick="bulkAccionPapelera('postulaciones','destroy')">
        <i class="bi bi-trash"></i> Eliminar definitivamente
      </button>
      <button class="bulk-btn-cancel" onclick="clearBulkPapelera('postulaciones')">Cancelar</button>
    </div>

    <div class="admin-table-wrap" id="panel-papelera-postulaciones">
      <table class="admin-table">
        <thead>
          <tr>
            <th class="th-check"><input type="checkbox" class="check-all-papelera" data-grupo="postulaciones"></th>
            <th>Estudiante</th>
            <th>Oferta</th>
            <th>Empresa</th>
            <th>Eliminada</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($postulacionesEliminadas as $i => $p)
            @php $delay = min($i, 8) * 0.035; @endphp
            <tr data-id="{{ $p->id_postulacion }}"
                data-search="{{ strtolower((($p->estudiante->nombre ?? '').' '.($p->estudiante->apellido ?? '').' '.($p->oferta->titulo ?? '').' '.($p->oferta->empresa->nombre_empresa ?? ''))) }}"
                class="fade-in-row papelera-row" data-grupo="postulaciones" style="animation-delay: {{ $delay }}s;">
              <td data-label=""><input type="checkbox" class="check-row-papelera" data-grupo="postulaciones"></td>
              <td class="td-nombre" data-label="Estudiante">
                {{ $p->estudiante->nombre ?? '—' }} {{ $p->estudiante->apellido ?? '' }}
                <br><span class="td-id">{{ $p->estudiante->user->email ?? '—' }}</span>
              </td>
              <td class="td-carrera" data-label="Oferta">{{ $p->oferta->titulo ?? '—' }}</td>
              <td class="td-carrera" data-label="Empresa">{{ $p->oferta->empresa->nombre_empresa ?? '—' }}</td>
              <td class="td-fecha" data-label="Eliminada">{{ \Carbon\Carbon::parse($p->deleted_at)->format('d/m/Y H:i') }}</td>
              <td data-label="Acciones">
                <div class="td-acciones">
                  <button class="btn-icon btn-aprobar" title="Restaurar"
                          onclick="restaurarPapelera('postulaciones', {{ $p->id_postulacion }}, '{{ route('admin.papelera.postulacion.restaurar', $p->id_postulacion) }}')">
                    <i class="bi bi-arrow-counterclockwise"></i>
                  </button>
                  <button class="btn-icon btn-eliminar" title="Eliminar definitivamente"
                          data-delete-url="{{ route('admin.papelera.postulacion.destroy', $p->id_postulacion) }}"
                          data-delete-name="postulación de {{ $p->estudiante->nombre ?? '' }} (permanente)"
                          data-delete-tipo="papelera-postulacion"
                          data-delete-id="{{ $p->id_postulacion }}">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align:center;padding:2rem;color:var(--muted);">
                No hay postulaciones eliminadas.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
      @if($postulacionesEliminadas->hasPages())
        <div style="padding:16px;">{{ $postulacionesEliminadas->links() }}</div>
      @endif
      <div class="admin-empty" style="display:none">
        <i class="bi bi-search"></i>
        <p>No se encontraron postulaciones eliminadas con esa búsqueda.</p>
      </div>
    </div>

  </div>
  @endif

</div>

{{-- Modal confirmación genérico (usado por bulk y eliminaciones) --}}
<dialog id="dialogConfirmar" class="modal-confirmar">
  <div class="modal-confirmar-content">
    <h3 class="modal-confirmar-title" id="dialogConfirmarTitle">Confirmar acción</h3>
    <p class="modal-confirmar-msg" id="dialogConfirmarMsg"></p>
    <div class="modal-confirmar-btns">
      <button onclick="document.getElementById('dialogConfirmar').close()" class="btn-cancelar-dialog">Cancelar</button>
      <button id="btnConfirmarAccion" class="btn-confirmar-eliminar">Confirmar</button>
    </div>
  </div>
</dialog>

{{-- Modal para motivo de pausa (reemplaza prompt() nativo) --}}
<dialog id="dialogMotivo" class="modal-motivo">
  <div class="modal-confirmar-content">
    <h3 class="modal-confirmar-title" id="dialogMotivoTitle">Motivo de la pausa</h3>
    <p class="modal-confirmar-msg" id="dialogMotivoMsg">Se mostrará a la empresa (opcional).</p>
    <textarea id="dialogMotivoInput"
              rows="8"
              oninput="actualizarContadorMotivo()"
              placeholder="Ej: la oferta no cumple con los requisitos de la plataforma..."
              style="
                width:100%;
                box-sizing:border-box;
                padding:12px 14px;
                border-radius:6px;
                border:1px solid var(--border);
                background:var(--bg);
                color:var(--text);
                font-family:var(--font-body);
                font-size:14px;
                line-height:1.5;
                resize:vertical;
              "></textarea>
    <div id="dialogMotivoContador" style="text-align:right; font-size:12px; color:var(--muted); margin-top:4px; margin-bottom:16px;">
      0 / 5000
    </div>
    <div class="modal-confirmar-btns">
      <button onclick="document.getElementById('dialogMotivo').close()" class="btn-cancelar-dialog">Cancelar</button>
      <button id="btnConfirmarMotivo" class="btn-confirmar-eliminar" style="background:var(--pausada);">
        Confirmar pausa
      </button>
    </div>
  </div>
</dialog>

@endsection

@section('scripts')
<script>

const csrfToken = document.querySelector('meta[name=csrf-token]')?.content ?? '{{ csrf_token() }}';

let toastTimeout;
function mostrarToast(mensaje, tipo = 'success') {
  const toast = document.getElementById('toast-msg');
  if (!toast) return;
  clearTimeout(toastTimeout);
  const icono = tipo === 'success' ? '&#10003;' : '&#10005;';
  toast.innerHTML = `<span class="toast-icon">${icono}</span><span>${mensaje}</span>`;
  toast.className = `toast-msg toast-${tipo} show`;
  toastTimeout = setTimeout(() => toast.classList.remove('show'), 3200);
}

@if(session('success'))
  document.addEventListener('DOMContentLoaded', () => mostrarToast(@json(session('success'))));
@endif
@if(session('error'))
  document.addEventListener('DOMContentLoaded', () => mostrarToast(@json(session('error')), 'error'));
@endif

async function ajaxPost(url, body = {}) {
  try {
    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
      body: JSON.stringify(body)
    });
    return await res.json();
  } catch (e) {
    return { success: false, message: 'Error de red.' };
  }
}

async function ajaxDelete(url) {
  try {
    const res = await fetch(url, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      }
    });
    return await res.json();
  } catch (e) {
    return { success: false, message: 'Error de red.' };
  }
}

/* Modal confirm (reemplaza confirm() nativo) */
function modalConfirm(titulo, mensaje, labelBoton = 'Confirmar') {
  return new Promise(resolve => {
    const dialog = document.getElementById('dialogConfirmar');
    document.getElementById('dialogConfirmarTitle').textContent = titulo;
    document.getElementById('dialogConfirmarMsg').textContent   = mensaje;

    const btnViejo = document.getElementById('btnConfirmarAccion');
    const btn = btnViejo.cloneNode(true);
    btn.textContent = labelBoton;
    btnViejo.parentNode.replaceChild(btn, btnViejo);

    btn.addEventListener('click', () => { dialog.close(); resolve(true); });
    dialog.addEventListener('close', () => resolve(false), { once: true });

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

/* Contador de caracteres — motivo de pausa */
const MOTIVO_MAX = 5000;

function actualizarContadorMotivo() {
  const input    = document.getElementById('dialogMotivoInput');
  const contador = document.getElementById('dialogMotivoContador');
  const btn      = document.getElementById('btnConfirmarMotivo');
  const len      = input.value.length;

  contador.textContent = `${len} / ${MOTIVO_MAX}`;

  if (len > MOTIVO_MAX) {
    contador.style.color = '#e05577';
    contador.style.fontWeight = '700';
    btn.disabled = true;
    btn.style.opacity = '.5';
    btn.style.cursor = 'not-allowed';
  } else {
    contador.style.color = 'var(--muted)';
    contador.style.fontWeight = 'normal';
    btn.disabled = false;
    btn.style.opacity = '1';
    btn.style.cursor = 'pointer';
  }
}

function modalMotivo(titulo = 'Motivo de la pausa', mensaje = 'Se mostrará a la empresa (opcional).') {
  return new Promise(resolve => {
    const dialog = document.getElementById('dialogMotivo');
    document.getElementById('dialogMotivoTitle').textContent = titulo;
    document.getElementById('dialogMotivoMsg').textContent   = mensaje;
    const input = document.getElementById('dialogMotivoInput');
    input.value = '';
    actualizarContadorMotivo();

    const btnViejo = document.getElementById('btnConfirmarMotivo');
    const btn = btnViejo.cloneNode(true);
    btnViejo.parentNode.replaceChild(btn, btnViejo);

    let confirmado = false;

    btn.addEventListener('click', () => {
      if (input.value.length > MOTIVO_MAX) return;
      confirmado = true;
      dialog.close();
      resolve(input.value.trim());
    });

    dialog.addEventListener('close', () => {
      if (!confirmado) resolve(null);
    }, { once: true });

    dialog.addEventListener('click', function handler(e) {
      const rect = dialog.getBoundingClientRect();
      if (e.clientX < rect.left || e.clientX > rect.right ||
          e.clientY < rect.top  || e.clientY > rect.bottom) {
        dialog.close();
        dialog.removeEventListener('click', handler);
      }
    });

    dialog.showModal();
    input.focus();
  });
}

/* Badges — clases según tipo + estado */
const BADGE_CLASS = {
  estudiante: { activo: 'activo', suspendido: 'suspendido', pendiente: 'pendiente' },
  empresa:    { aprobada: 'aprobado', rechazada: 'rechazado', suspendida: 'suspendido', pendiente: 'pendiente' },
  oferta:     { Activa: 'publicada', Pausada: 'pausada', Cerrada: 'pendiente' },
  reporte:    { 'Abierto': 'pendiente', 'En Proceso': 'suspendido', 'Resuelto': 'activo' },
};

function actualizarBadge(tipo, id, estado) {
  const idMap = { estudiante: 'badge-estudiante-', empresa: 'badge-empresa-', oferta: 'badge-oferta-', reporte: 'badge-rep-' };
  const el = document.getElementById(idMap[tipo] + id);
  if (!el) return;
  const clase = BADGE_CLASS[tipo]?.[estado] ?? 'pendiente';
  el.className = `badge-admin badge-${clase}`;
  el.textContent = tipo === 'reporte' ? estado : (estado.charAt(0).toUpperCase() + estado.slice(1).toLowerCase());
  el.dataset.estado = estado;

  const row = el.closest('tr');
  if (row) row.dataset.estado = tipo === 'oferta' ? estado : estado.toLowerCase();
}

function recalcularStats(panelId) {
  const map = {
    'panel-alumnos': {
      total: 'stat-alumnos-total',
      counts: { activo: 'stat-alumnos-activos', suspendido: 'stat-alumnos-suspendidos' }
    },
    'panel-empresas': {
      total: 'stat-empresas-total',
      counts: { aprobado: 'stat-empresas-aprobadas', suspendido: 'stat-empresas-suspendidas', pendiente: 'stat-empresas-pendientes', rechazado: 'stat-empresas-rechazadas' }
    },
    'panel-ofertas': {
      total: 'stat-ofertas-total',
      counts: { publicada: 'stat-ofertas-publicadas', pendiente: 'stat-ofertas-pendientes', pausada: 'stat-ofertas-pausadas' }
    },
    'panel-reportes': {
      total: 'stat-reportes-total',
      counts: { pendiente: 'stat-reportes-abiertos', suspendido: 'stat-reportes-proceso', activo: 'stat-reportes-resueltos' }
    },
  };
  const cfg = map[panelId];
  if (!cfg) return;
  const panel = document.getElementById(panelId);
  if (!panel) return;
  const badges = [...panel.querySelectorAll('tbody tr:not(.admin-detalle-row) .badge-admin')];

  const totalEl = document.getElementById(cfg.total);
  if (totalEl) totalEl.textContent = badges.length;

  Object.entries(cfg.counts).forEach(([clase, elId]) => {
    const el = document.getElementById(elId);
    if (!el) return;
    el.textContent = badges.filter(b => b.classList.contains(`badge-${clase}`)).length;
  });
}

/* Mensaje "no hay registros" dinámico: se inyecta cuando una tabla
   queda vacía tras un borrado/restauración por AJAX, sin esperar reload */
function chequearVacio(tbody, colspan, mensaje) {
  if (!tbody) return;
  const filaVacia = tbody.querySelector('tr.fila-vacia-dinamica');

  if (!tbody.querySelector('tr[data-id]')) {
    if (!filaVacia) {
      const tr = document.createElement('tr');
      tr.className = 'fila-vacia-dinamica';
      tr.innerHTML = `<td colspan="${colspan}" style="text-align:center;padding:2rem;color:var(--muted);">${mensaje}</td>`;
      tbody.appendChild(tr);
    }
  } else if (filaVacia) {
    filaVacia.remove();
  }
}

const MENSAJES_VACIO = {
  estudiante: [8, 'No hay estudiantes registrados.'],
  empresa:    [8, 'No hay empresas registradas.'],
  oferta:     [9, 'No hay ofertas registradas.'],
  reporte:    [6, 'No hay reportes'],
};
const MENSAJES_VACIO_PAPELERA = {
  ofertas:        [5, 'No hay ofertas eliminadas.'],
  postulaciones:  [6, 'No hay postulaciones eliminadas.'],
};

async function submitEstado(tipo, id, url, estado) {
  const data = await ajaxPost(url, { estado });
  if (!data.success) {
    mostrarToast(data.message || 'Error al actualizar.', 'error');
    return;
  }
  actualizarBadge(tipo, id, data.estado || estado);
  const panelId = { estudiante: 'panel-alumnos', empresa: 'panel-empresas', oferta: 'panel-ofertas' }[tipo];
  if (panelId) recalcularStats(panelId);
  mostrarToast(data.message || 'Actualizado correctamente.');
}

async function submitEstadoConMotivo(id, url, estado) {
  const motivo = await modalMotivo('Motivo de la pausa', 'Se mostrará a la empresa (opcional).');
  if (motivo === null) return;

  const data = await ajaxPost(url, { estado, motivo });
  if (!data.success) {
    mostrarToast(data.message || 'Error al actualizar.', 'error');
    return;
  }
  actualizarBadge('oferta', id, data.estado || estado);
  recalcularStats('panel-ofertas');
  mostrarToast(data.message || 'Oferta pausada.');

  document.querySelectorAll(`[onclick*="submitEstadoConMotivo(${id},"]`).forEach(btn => {
    btn.setAttribute('disabled', 'disabled');
    btn.style.opacity = '.3';
    btn.style.cursor = 'not-allowed';
    btn.removeAttribute('onclick');
  });
}

async function cambiarEstadoReporte(select) {
  const id  = select.dataset.id;
  const url = select.dataset.url;
  const estado = select.value;

  const data = await ajaxPost(url, { estado });
  if (!data.success) {
    mostrarToast(data.message || 'Error al actualizar.', 'error');
    return;
  }
  actualizarBadge('reporte', id, data.estado || estado);
  recalcularStats('panel-reportes');
  mostrarToast(data.message || 'Ticket actualizado.');
}

/* Detalle expandible — acordeón animado por altura real */
function inicializarDetalles() {
  document.querySelectorAll('.admin-detalle-row').forEach(row => {
    const td = row.querySelector('td');
    const inner = row.querySelector('.admin-detalle-inner');
    if (!td || !inner) return;

    if (td.dataset.origPadding === undefined) {
      td.dataset.origPadding = td.style.padding || getComputedStyle(td).padding;
    }

    row.style.display = 'table-row';
    inner.style.overflow = 'hidden';

    if (row.classList.contains('open')) {
      td.style.padding = td.dataset.origPadding;
      inner.style.maxHeight = 'none';
      inner.style.opacity = '1';
    } else {
      td.style.padding = '0px';
      inner.style.maxHeight = '0px';
      inner.style.opacity = '0';
    }
  });
}

window.toggleAdminDetalle = function (id) {
  const row = document.getElementById('admin-det-' + id);
  if (!row) return;

  const td = row.querySelector('td');
  const inner = row.querySelector('.admin-detalle-inner');
  if (!td || !inner) { row.classList.toggle('open'); return; }

  if (td.dataset.origPadding === undefined) {
    td.dataset.origPadding = td.style.padding || getComputedStyle(td).padding;
  }

  const yaAbierta = row.classList.contains('open');

  if (!yaAbierta) {
    row.classList.add('open');
    row.style.display = 'table-row';

    td.style.transition = 'none';
    td.style.padding = td.dataset.origPadding;

    inner.style.transition = 'none';
    inner.style.overflow = 'hidden';
    inner.style.maxHeight = '0px';
    inner.style.opacity = '0';

    void inner.offsetHeight;

    const alturaFinal = inner.scrollHeight;
    td.style.transition = 'padding .3s ease';
    inner.style.transition = 'max-height .32s ease, opacity .25s ease';
    inner.style.maxHeight = alturaFinal + 'px';
    inner.style.opacity = '1';

    const onAbrir = (ev) => {
      if (ev.propertyName !== 'max-height') return;
      if (row.classList.contains('open')) inner.style.maxHeight = 'none';
      inner.removeEventListener('transitionend', onAbrir);
    };
    inner.addEventListener('transitionend', onAbrir);

  } else {
    const alturaActual = inner.scrollHeight;
    inner.style.transition = 'none';
    inner.style.maxHeight = alturaActual + 'px';
    void inner.offsetHeight;

    inner.style.transition = 'max-height .28s ease, opacity .2s ease';
    td.style.transition = 'padding .28s ease';
    inner.style.maxHeight = '0px';
    inner.style.opacity = '0';
    td.style.padding = '0px';

    row.classList.remove('open');
  }
};

/* Eliminar individual — AJAX, delegado en document */
document.addEventListener('click', async e => {
  const btn = e.target.closest('[data-delete-url]');
  if (!btn) return;

  const name = btn.dataset.deleteName || 'este registro';
  const url  = btn.dataset.deleteUrl;
  const tipo = btn.dataset.deleteTipo;
  const id   = btn.dataset.deleteId;
  if (!url) return;

  const ok = await modalConfirm(
    'Eliminar registro',
    `¿Confirmás eliminar "${name}"? Esta acción no se puede deshacer.`,
    'Sí, eliminar'
  );
  if (!ok) return;

  const data = await ajaxDelete(url);
  if (!data.success) {
    mostrarToast(data.message || 'Error al eliminar.', 'error');
    return;
  }

  quitarFilaDelDOM(tipo, id);
  mostrarToast(data.message || 'Eliminado correctamente.');
});

function quitarFilaDelDOM(tipo, id) {
  const panelMap = {
    estudiante: 'panel-alumnos',
    empresa: 'panel-empresas',
    oferta: 'panel-ofertas',
    reporte: 'panel-reportes',
  };

  if (tipo === 'papelera-oferta' || tipo === 'papelera-postulacion') {
    const grupo = tipo === 'papelera-oferta' ? 'ofertas' : 'postulaciones';
    document.querySelector(`tr[data-id="${id}"].papelera-row[data-grupo="${grupo}"]`)?.remove();
    const tbody = document.querySelector(`#panel-papelera-${grupo} tbody`);
    const [cs, msg] = MENSAJES_VACIO_PAPELERA[grupo];
    chequearVacio(tbody, cs, msg);
    return;
  }

  document.querySelector(`#${panelMap[tipo]} tbody tr[data-id="${id}"]`)?.remove();
  document.getElementById(`admin-det-${tipo === 'estudiante' ? '' : (tipo === 'empresa' ? 'e' : (tipo === 'oferta' ? 'o' : 'rep'))}${id}`)?.remove();

  if (panelMap[tipo]) {
    recalcularStats(panelMap[tipo]);
    const tbody = document.querySelector(`#${panelMap[tipo]} tbody`);
    const [cs, msg] = MENSAJES_VACIO[tipo];
    chequearVacio(tbody, cs, msg);
  }
}

/* Búsqueda + filtros genéricos */
function inicializarFiltros(panelSelector) {
  document.querySelectorAll(panelSelector).forEach(panel => {
    const searchInput = panel.querySelector('.admin-search input');
    const filterSelects = panel.querySelectorAll('.admin-filter-select:not(.select-estado-reporte)');
    const emptyEl = panel.querySelector('.admin-empty');

    function applyFilters() {
      const term = (searchInput?.value || '').toLowerCase().trim();
      const filters = {};
      filterSelects.forEach(sel => { if (sel.value) filters[sel.dataset.filter] = sel.value.toLowerCase(); });

      let visibleCount = 0;
      panel.querySelectorAll('tbody tr:not(.admin-detalle-row)').forEach(row => {
        if (!row.dataset.id) { return; }
        const searchOk = !term || (row.dataset.search || '').includes(term);
        let filterOk = true;
        Object.entries(filters).forEach(([key, val]) => {
          if ((row.dataset[key] || '').toLowerCase() !== val) filterOk = false;
        });
        const show = searchOk && filterOk;
        row.style.display = show ? '' : 'none';
        const detalle = document.getElementById('admin-det-' + row.dataset.id) ||
                         document.getElementById('admin-det-e' + row.dataset.id) ||
                         document.getElementById('admin-det-o' + row.dataset.id) ||
                         document.getElementById('admin-det-rep' + row.dataset.id);
        if (detalle && !show) detalle.classList.remove('open');
        if (show) visibleCount++;
      });
      if (emptyEl) emptyEl.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    searchInput?.addEventListener('input', applyFilters);
    filterSelects.forEach(sel => sel.addEventListener('change', applyFilters));
  });
}

function inicializarFiltrosPapelera() {
  document.querySelectorAll('.admin-tab-panel#panel-papelera .admin-toolbar').forEach(toolbar => {
    const searchInput = toolbar.querySelector('.admin-search input');
    const tableWrap = toolbar.nextElementSibling?.nextElementSibling;
    if (!searchInput || !tableWrap || !tableWrap.classList.contains('admin-table-wrap')) return;

    const emptyEl = tableWrap.querySelector('.admin-empty');

    function applyFilters() {
      const term = searchInput.value.toLowerCase().trim();
      let visibleCount = 0;
      tableWrap.querySelectorAll('tbody tr').forEach(row => {
        if (!row.dataset.id) return;
        const show = !term || (row.dataset.search || '').includes(term);
        row.style.display = show ? '' : 'none';
        if (show) visibleCount++;
      });
      if (emptyEl) emptyEl.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    searchInput.addEventListener('input', applyFilters);
  });
}

/* Checkboxes */
function inicializarCheckboxes() {
  document.querySelectorAll('.check-all').forEach(chkAll => {
    chkAll.addEventListener('change', () => {
      const panel = chkAll.closest('.admin-tab-panel');
      panel.querySelectorAll('tbody tr:not(.admin-detalle-row)').forEach(row => {
        if (row.style.display !== 'none') {
          const c = row.querySelector('.check-row');
          if (c) c.checked = chkAll.checked;
        }
      });
      updateBulkBar(panel);
    });
  });

  document.querySelectorAll('.check-all-papelera').forEach(chkAll => {
    chkAll.addEventListener('change', () => {
      const grupo = chkAll.dataset.grupo;
      document.querySelectorAll(`.check-row-papelera[data-grupo="${grupo}"]`).forEach(c => {
        const row = c.closest('tr');
        if (row.style.display !== 'none') c.checked = chkAll.checked;
      });
      updateBulkBarPapelera(grupo);
    });
  });
}

document.addEventListener('change', e => {
  if (e.target.classList.contains('check-row')) {
    const panel = e.target.closest('.admin-tab-panel');
    if (!panel) return;
    const rows   = [...panel.querySelectorAll('.check-row')];
    const chkAll = panel.querySelector('.check-all');
    if (chkAll) chkAll.checked = rows.length > 0 && rows.every(c => c.checked);
    updateBulkBar(panel);
  }
  if (e.target.classList.contains('check-row-papelera')) {
    const grupo = e.target.dataset.grupo;
    const all = [...document.querySelectorAll(`.check-row-papelera[data-grupo="${grupo}"]`)];
    const chkAll = document.querySelector(`.check-all-papelera[data-grupo="${grupo}"]`);
    if (chkAll) chkAll.checked = all.length > 0 && all.every(c => c.checked);
    updateBulkBarPapelera(grupo);
  }
});

function getSelectedIds(panel) {
  return [...panel.querySelectorAll('.check-row:checked')]
    .map(c => c.closest('tr')?.dataset.id)
    .filter(Boolean);
}

function updateBulkBar(panel) {
  const ids = getSelectedIds(panel);
  const bar = panel.querySelector('.bulk-bar');
  if (!bar) return;
  bar.querySelector('.bulk-count').textContent =
    `${ids.length} seleccionado${ids.length !== 1 ? 's' : ''}`;
  bar.style.display = ids.length > 0 ? 'flex' : 'none';
}

function clearBulk(panelId) {
  const panel = document.getElementById(panelId);
  panel.querySelectorAll('.check-row, .check-all').forEach(c => c.checked = false);
  updateBulkBar(panel);
}

const bulkUrlMap = {
  'panel-alumnos':  'estudiantes',
  'panel-empresas': 'empresas',
  'panel-ofertas':  'ofertas',
};
const bulkTipoMap = { 'panel-alumnos': 'estudiante', 'panel-empresas': 'empresa', 'panel-ofertas': 'oferta' };

async function bulkAccion(panelId, accion, estado) {
  const panel = document.getElementById(panelId);
  const ids   = getSelectedIds(panel);
  if (!ids.length) return;

  const segmento = bulkUrlMap[panelId];
  const count    = ids.length;

  if (accion === 'delete') {
    const ok = await modalConfirm(
      'Eliminar registros',
      `¿Confirmás eliminar ${count} registro${count !== 1 ? 's' : ''}? Esta acción no se puede deshacer.`,
      'Sí, eliminar'
    );
    if (!ok) return;

    const data = await ajaxPost(`/admin/${segmento}/bulk-destroy`, { ids });
    if (!data.success) { mostrarToast(data.message || 'Error al eliminar.', 'error'); return; }

    ids.forEach(id => panel.querySelector(`tbody tr[data-id="${id}"]`)?.remove());
    recalcularStats(panelId);
    const [cs, msg] = MENSAJES_VACIO[bulkTipoMap[panelId]];
    chequearVacio(panel.querySelector('tbody'), cs, msg);
    clearBulk(panelId);
    mostrarToast(data.message);
  } else {
    const data = await ajaxPost(`/admin/${segmento}/bulk-estado`, { ids, estado });
    if (!data.success) { mostrarToast(data.message || 'Error al actualizar.', 'error'); return; }

    ids.forEach(id => actualizarBadge(bulkTipoMap[panelId], id, estado));
    recalcularStats(panelId);
    clearBulk(panelId);
    mostrarToast(data.message);
  }
}

async function bulkAccionReportes(accion, estado) {
  const panel = document.getElementById('panel-reportes');
  const ids = getSelectedIds(panel);
  if (!ids.length) return;

  if (accion === 'delete') {
    const ok = await modalConfirm(
      'Eliminar tickets',
      `¿Confirmás eliminar ${ids.length} ticket(s)? Esta acción no se puede deshacer.`,
      'Sí, eliminar'
    );
    if (!ok) return;

    const data = await ajaxPost('/admin/reportes/bulk-destroy', { ids });
    if (!data.success) { mostrarToast(data.message || 'Error al eliminar.', 'error'); return; }

    ids.forEach(id => {
      panel.querySelector(`tbody tr[data-id="${id}"]`)?.remove();
      document.getElementById(`admin-det-rep${id}`)?.remove();
    });
    recalcularStats('panel-reportes');
    const [cs, msg] = MENSAJES_VACIO.reporte;
    chequearVacio(panel.querySelector('tbody'), cs, msg);
    clearBulk('panel-reportes');
    mostrarToast(data.message);
  } else {
    const data = await ajaxPost('/admin/reportes/bulk-estado', { ids, estado });
    if (!data.success) { mostrarToast(data.message || 'Error al actualizar.', 'error'); return; }

    ids.forEach(id => {
      actualizarBadge('reporte', id, estado);
      const sel = document.querySelector(`.select-estado-reporte[data-id="${id}"]`);
      if (sel) sel.value = estado;
    });
    recalcularStats('panel-reportes');
    clearBulk('panel-reportes');
    mostrarToast(data.message);
  }
}

/* Papelera — restaurar / eliminar individual y bulk */
async function restaurarPapelera(grupo, id, url) {
  const data = await ajaxPost(url, {});
  if (!data.success) { mostrarToast(data.message || 'Error al restaurar.', 'error'); return; }

  document.querySelector(`tr[data-id="${id}"].papelera-row[data-grupo="${grupo}"]`)?.remove();
  const tbody = document.querySelector(`#panel-papelera-${grupo} tbody`);
  const [cs, msg] = MENSAJES_VACIO_PAPELERA[grupo];
  chequearVacio(tbody, cs, msg);
  quitarPostulacionesRestauradas(data.postulaciones_restauradas);

  mostrarToast(data.message || 'Restaurado correctamente.');
}

function quitarPostulacionesRestauradas(ids) {
  if (!Array.isArray(ids) || !ids.length) return;
  ids.forEach(pid => {
    document.querySelector(`tr[data-id="${pid}"].papelera-row[data-grupo="postulaciones"]`)?.remove();
  });
  const [cs, msg] = MENSAJES_VACIO_PAPELERA.postulaciones;
  chequearVacio(document.querySelector('#panel-papelera-postulaciones tbody'), cs, msg);
}

function getSelectedPapelera(grupo) {
  return [...document.querySelectorAll(`.check-row-papelera[data-grupo="${grupo}"]:checked`)]
    .map(c => c.closest('tr')?.dataset.id)
    .filter(Boolean);
}

function updateBulkBarPapelera(grupo) {
  const ids = getSelectedPapelera(grupo);
  const bar = document.getElementById(`bulk-bar-papelera-${grupo}`);
  if (!bar) return;
  bar.querySelector('.bulk-count').textContent = `${ids.length} seleccionado${ids.length !== 1 ? 's' : ''}`;
  bar.style.display = ids.length > 0 ? 'flex' : 'none';
}

function clearBulkPapelera(grupo) {
  document.querySelectorAll(`.check-row-papelera[data-grupo="${grupo}"], .check-all-papelera[data-grupo="${grupo}"]`)
    .forEach(c => c.checked = false);
  updateBulkBarPapelera(grupo);
}

async function bulkAccionPapelera(grupo, accion) {
  const ids = getSelectedPapelera(grupo);
  if (!ids.length) return;

  const nombreGrupo = grupo === 'ofertas' ? 'ofertas' : 'postulaciones';

  if (accion === 'destroy') {
    const ok = await modalConfirm(
      'Eliminar definitivamente',
      `¿Confirmás eliminar definitivamente ${ids.length} ${nombreGrupo}? Esta acción no se puede deshacer.`,
      'Sí, eliminar'
    );
    if (!ok) return;

    const data = await ajaxPost(`/admin/papelera/${grupo}/bulk-destroy`, { ids });
    if (!data.success) { mostrarToast(data.message || 'Error al eliminar.', 'error'); return; }

    ids.forEach(id => document.querySelector(`tr[data-id="${id}"].papelera-row[data-grupo="${grupo}"]`)?.remove());
    const [cs, msg] = MENSAJES_VACIO_PAPELERA[grupo];
    chequearVacio(document.querySelector(`#panel-papelera-${grupo} tbody`), cs, msg);
    clearBulkPapelera(grupo);
    mostrarToast(data.message);
  } else {
    const data = await ajaxPost(`/admin/papelera/${grupo}/bulk-restaurar`, { ids });
    if (!data.success) { mostrarToast(data.message || 'Error al restaurar.', 'error'); return; }

    ids.forEach(id => document.querySelector(`tr[data-id="${id}"].papelera-row[data-grupo="${grupo}"]`)?.remove());
    const [cs, msg] = MENSAJES_VACIO_PAPELERA[grupo];
    chequearVacio(document.querySelector(`#panel-papelera-${grupo} tbody`), cs, msg);

    quitarPostulacionesRestauradas(data.postulaciones_restauradas);

    clearBulkPapelera(grupo);
    mostrarToast(data.message);
  }
}

function inicializarPanel() {
  inicializarFiltros('.admin-tab-panel');
  inicializarFiltrosPapelera();
  inicializarCheckboxes();
  inicializarDetalles();
}

document.addEventListener('DOMContentLoaded', inicializarPanel);
</script>

<style>
/* ── Toast (mismo patrón que empresa/estudiante) ── */
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
.toast-msg.show { opacity: 1; transform: translateX(-50%) translateY(0); }
.toast-msg .toast-icon { font-size: 15px; line-height: 1; flex-shrink: 0; }
.toast-msg.toast-success { border-color: rgba(46,204,154,.5); }
.toast-msg.toast-success .toast-icon { color: #2ECC9A; }
.toast-msg.toast-error { border-color: rgba(212,24,61,.5); }
.toast-msg.toast-error .toast-icon { color: #e05577; }

/* ── Ancho / efectos consistentes con empresa y estudiante ── */
@keyframes fadeInUpPanel {
  from { opacity: 0; transform: translateY(8px); }
  to   { opacity: 1; transform: translateY(0); }
}
.admin-page {
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
  transition: opacity .18s ease;
}
/* La transición de opacidad al navegar entre tabs por AJAX se controla
   directamente desde JS (estilo inline) sobre este mismo elemento. */

.admin-stat { animation: fadeInUpPanel .4s ease both; }
.admin-stat:nth-child(1) { animation-delay: .04s; }
.admin-stat:nth-child(2) { animation-delay: .09s; }
.admin-stat:nth-child(3) { animation-delay: .14s; }
.admin-stat:nth-child(4) { animation-delay: .19s; }
.admin-stat:nth-child(5) { animation-delay: .24s; }
.fade-in-row { animation: fadeInUpPanel .35s ease both; }

/* ── Espaciado de celdas: evita que el texto en varias líneas choque ── */
.admin-table td {
  vertical-align: top;
  line-height: 1.45;
  padding-top: 16px;
  padding-bottom: 16px;
}
.admin-table td.td-carrera,
.admin-table td.td-ubicacion {
  white-space: normal;
  word-break: break-word;
  padding-right: 16px;
}
.admin-table td.td-nombre {
  line-height: 1.4;
  white-space: normal;
  word-break: break-word;
  padding-right: 16px;
}
.admin-table td.td-nombre .td-id { display: inline-block; margin-top: 2px; }
.admin-table tbody tr:not(.admin-detalle-row) { border-bottom: 1px solid var(--border); }

/* ── Solo el contador (número) se centra ── */
.admin-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); }
.admin-stat-value { text-align: left; }

/* ── Toolbar de Papelera: título arriba, buscador abajo, SIEMPRE en
   columna (no cambia de layout según el ancho de pantalla), para que
   no salte de golpe la altura al pasar de escritorio a mobile. ── */
.admin-toolbar-papelera {
  flex-direction: column;
  align-items: stretch;
  gap: 10px;
}
.admin-toolbar-papelera-title {
  font-size: 15px;
  font-weight: 700;
  color: var(--text);
  margin: 0;
  display: flex;
  align-items: center;
  gap: 8px;
}
.admin-toolbar-papelera .admin-search {
  width: 100%;
  min-width: 100%;
}

/* ── Modal confirmar ── */
dialog:not([open]) { display: none !important; }

.modal-motivo {
  position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
  border: 1px solid var(--border); border-radius: 12px; background: var(--surface);
  padding: 0; width: min(600px, 92vw); margin: 0; z-index: 9999;
}
.modal-motivo::backdrop { background: rgba(0,0,0,0.5); backdrop-filter: blur(3px); }

.modal-confirmar {
  position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
  border: 1px solid var(--border); border-radius: 12px; background: var(--surface);
  padding: 0; width: min(400px, 92vw); margin: 0; z-index: 9999;
}
.modal-confirmar::backdrop { background: rgba(0,0,0,0.5); backdrop-filter: blur(3px); }
.modal-confirmar-content { padding: 24px; }
.modal-confirmar-title   { font-size: 18px; font-weight: 600; color: var(--text); margin-bottom: 10px; }
.modal-confirmar-msg     { color: var(--muted); margin-bottom: 24px; font-size: 14px; line-height: 1.5; }
.modal-confirmar-btns    { display: flex; gap: 10px; justify-content: flex-end; }
.btn-cancelar-dialog     { padding: 8px 16px; background: var(--bg); border: 1px solid var(--border); color: var(--text); border-radius: 6px; cursor: pointer; font-size: 13px; }
.btn-cancelar-dialog:hover { border-color: var(--text); }
.btn-confirmar-eliminar  { padding: 8px 16px; background: #dc3545; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 700; }
.btn-confirmar-eliminar:hover { background: #b02a37; }

/* ── Bulk bar ── */
.bulk-bar {
  display: flex; align-items: center; gap: 8px; padding: 10px 14px;
  background: var(--surface); border: 1px solid var(--accent); border-radius: var(--radius);
  margin-bottom: 12px; flex-wrap: wrap;
}
.bulk-count { font-size: 12.5px; font-weight: 700; color: var(--accent); margin-right: 4px; }
.bulk-bar button {
  display: inline-flex; align-items: center; gap: 5px; padding: 5px 11px; font-size: 12px;
  font-weight: 700; font-family: var(--font-display); border-radius: var(--radius); cursor: pointer;
  border: 1px solid var(--border); background: var(--bg); color: var(--text);
  transition: border-color 0.15s, color 0.15s, background 0.15s;
}
.bulk-bar button:hover           { border-color: var(--accent); color: var(--accent); }
.bulk-bar .bulk-btn-danger       { border-color: rgba(212,24,61,.4); color: #e05577; background: transparent; }
.bulk-bar .bulk-btn-danger:hover { background: rgba(212,24,61,.08); border-color: #e05577; }
.bulk-bar .bulk-btn-cancel       { color: var(--muted); margin-left: auto; }
.bulk-bar .bulk-btn-cancel:hover { color: var(--text); border-color: var(--border); }

/* ── Botón contactar ── */
.btn-admin-contactar {
  display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 6px;
  background: #077552; border: none; color: #ffffff; font-size: 12.5px; font-weight: 700;
  font-family: var(--font-display); cursor: pointer; text-decoration: none; transition: filter var(--trans);
}
.btn-admin-contactar:hover { filter: brightness(1.1); }

/* ════════════════════════════════════════
   PAGINACIÓN LARAVEL
════════════════════════════════════════ */
.pagination {
  display: flex; align-items: center; gap: 4px; list-style: none; padding: 12px 0;
  margin: 0; justify-content: center; flex-wrap: wrap;
}
.page-item .page-link, .page-item span {
  display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px;
  padding: 0 8px; border-radius: 6px; border: 1px solid var(--border); background: var(--surface);
  color: var(--text); font-size: 13px; font-weight: 500; text-decoration: none;
  transition: background var(--trans), border-color var(--trans), color var(--trans);
}
.page-item .page-link:hover { background: var(--accent-dim); border-color: var(--accent); color: var(--accent); }
.page-item.active .page-link, .page-item.active span { background: var(--accent); border-color: var(--accent); color: #fff; font-weight: 700; }
[data-theme="dark"] .page-item.active .page-link, [data-theme="dark"] .page-item.active span { color: #111118; }
.page-item.disabled .page-link, .page-item.disabled span { opacity: 0.35; cursor: not-allowed; pointer-events: none; }

/* ── Tablet ancho (900–1200px) ── */
@media (max-width: 1200px) {
  .admin-page { padding: 28px 16px 56px; }
  .admin-table td:nth-child(4), .admin-table th:nth-child(4) { display: none; }
}

/* ── Tablet (≤ 1024px) ── */
@media (max-width: 1024px) {
  .admin-page { width: calc(100% - 24px); }
}

/* ── Tablet (≤ 900px) ── */
@media (max-width: 900px) {
  .admin-page { padding: 24px 16px 52px; }
  .admin-page-title { font-size: 21px; }
  .admin-page-sub { font-size: 13px; margin-bottom: 20px; }
  .admin-stats { grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 20px; }
  .admin-stat { padding: 14px 16px; }
  .admin-stat-value { font-size: 26px; }
  .admin-toolbar { flex-direction: column; align-items: stretch; gap: 8px; }
  .admin-search { min-width: 100%; }
  .admin-filter-select { width: 100%; }
  .admin-detalle-inner { grid-template-columns: 1fr 1fr; gap: 16px; }
  .admin-table th:nth-child(4), .admin-table td:nth-child(4),
  .admin-table th:nth-child(6), .admin-table td:nth-child(6) { display: none; }
}

@media (min-width: 1025px) and (max-width: 1360px) {
  .admin-page { width: calc(100% - 40px); }
}

/* ── Mobile (≤ 640px) ── */
@media (max-width: 640px) {
  *, *::before, *::after { box-sizing: border-box; }
  html, body { overflow-x: hidden; max-width: 100vw; }
  .admin-page { padding: 16px 10px 48px; overflow-x: hidden; max-width: 100%; width: calc(100% - 16px); }
  .admin-page-title { font-size: 19px; gap: 7px; }
  .admin-page-sub { font-size: 12.5px; margin-bottom: 18px; }
  .admin-tabs { gap: 0; overflow-x: auto; -webkit-overflow-scrolling: touch; scrollbar-width: none; margin-bottom: 20px; }
  .admin-tabs::-webkit-scrollbar { display: none; }
  .admin-tab { padding: 10px 16px; font-size: 12.5px; white-space: nowrap; flex-shrink: 0; }
  .admin-stats { grid-template-columns: repeat(2, 1fr); gap: 8px; margin-bottom: 16px; }
  .admin-stat { padding: 12px 14px; }
  .admin-stat-label { font-size: 9.5px; gap: 4px; }
  .admin-stat-value { font-size: 28px; }
  .admin-toolbar { flex-direction: column; gap: 8px; margin-bottom: 12px; }
  .admin-search input { font-size: 14px; padding: 10px 12px 10px 34px; }
  .admin-filter-select { width: 100%; font-size: 14px; padding: 10px 28px 10px 12px; }
  .admin-table-wrap { border: none; background: transparent; overflow: hidden; max-width: 100%; }
  .admin-table, .admin-table thead, .admin-table tbody, .admin-table th, .admin-table td, .admin-table tr {
    display: block; width: 100%; max-width: 100%; box-sizing: border-box;
  }
  .admin-table thead { display: none; }
  .admin-table tbody tr { background: var(--surface); border: 1px solid var(--border); border-radius: 6px; margin-bottom: 6px; padding: 8px 10px; position: relative; box-sizing: border-box; width: 100%; }
  .admin-table tbody tr:hover { background: var(--surface); }
  .btn-icon.btn-ver { display: none; }
  .admin-detalle-row { background: transparent !important; border: none !important; padding: 0 !important; margin-bottom: 10px; }
  .admin-detalle-row td { border: 1px solid var(--border); border-radius: 6px; padding: 14px !important; background: var(--bg) !important; }
  .admin-table td:first-child { display: none; }
  .admin-table td { border-bottom: none; padding: 2px 0; font-size: 12px; display: flex; align-items: flex-start; gap: 6px; }
  .admin-table td::before { content: attr(data-label); font-size: 9.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); min-width: 62px; max-width: 62px; padding-top: 1px; flex-shrink: 0; }
  .admin-table td.td-nombre { flex-direction: column; gap: 1px; font-size: 13px; font-weight: 700; margin-bottom: 6px; padding-bottom: 7px; border-bottom: 1px solid var(--border); }
  .admin-table td.td-nombre::before { display: none; }
  .admin-table td:last-child { margin-top: 7px; padding-top: 7px; border-top: 1px solid var(--border); justify-content: flex-end; }
  .admin-table td:last-child::before { display: none; }
  .td-acciones { gap: 2px; }
  .btn-icon { width: 30px; height: 30px; font-size: 14px; }
  .badge-admin { font-size: 11px; padding: 3px 8px; }
  .badge-tipo { font-size: 11px; }
  .admin-detalle-inner { grid-template-columns: 1fr; gap: 14px; }
  .admin-detalle-actions { flex-direction: column; margin-top: 12px; gap: 6px; }
  .btn-admin-aprobar, .btn-admin-rechazar, .btn-admin-suspender { width: 100%; justify-content: center; padding: 10px 16px; font-size: 13px; }
  .admin-empty { padding: 36px 16px; }
  .admin-empty i { font-size: 28px; }
  .bulk-bar { flex-direction: column; align-items: stretch; }
  .bulk-bar button { justify-content: center; }
  .bulk-bar .bulk-btn-cancel { margin-left: 0; }
  .btn-admin-contactar { width: 100%; justify-content: center; padding: 10px 16px; font-size: 13px; }
}

.admin-table-wrap .pagination { display: flex; justify-content: center; flex-wrap: wrap; }
</style>

@endsection
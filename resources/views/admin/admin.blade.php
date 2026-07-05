@extends('layouts.app')

@section('title', 'Administración — KROW')



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

<div class="admin-page" style="position: relative; z-index: 5; margin-top: -510px !important; margin-bottom:80px;background-color:var(--bg) ;opacity: 0.95; border-radius: 8px; border:1px solid var(--accent); justify-content:start;box-shadow:
0 20px 50px var(--shadow-color),
0 0px 30px var(--shadow-glow);">

  <h1 class="admin-page-title">
    <i class="bi bi-shield-check"></i> Administración
  </h1>
  <p class="admin-page-sub">Gestión completa de estudiantes, empresas y ofertas de la plataforma.</p>

  {{-- ── Mensajes flash ── --}}
  @if(session('success'))
    <div style="margin-bottom:16px;padding:13px 16px;border:1px solid rgba(46,204,154,.35);background:rgba(14,24,22,.96);color:#2ECC9A;font-size:13px;font-weight:700;display:flex;align-items:center;gap:8px;">
      <i class="bi bi-check-circle"></i> {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div style="margin-bottom:16px;padding:13px 16px;border:1px solid rgba(212,24,61,.35);background:rgba(14,24,22,.96);color:#e05577;font-size:13px;font-weight:700;display:flex;align-items:center;gap:8px;">
      <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
    </div>
  @endif
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
        <div class="admin-stat-value">{{ $totalAlumnos }}</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-activo"><i class="bi bi-person-check"></i> Activos</div>
        <div class="admin-stat-value">{{ $alumnosActivos }}</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-suspendido"><i class="bi bi-person-x"></i> Suspendidos</div>
        <div class="admin-stat-value">{{ $alumnosSuspendidos }}</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-pendiente"><i class="bi bi-funnel"></i> Pendientes</div>
        <div class="admin-stat-value">{{ $alumnosPendientes }}</div>
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
          @forelse($estudiantes as $a)
          @php
            $badgeEst = match($a->estado ?? 'pendiente') {
              'activo'     => 'activo',
              'suspendido' => 'suspendido',
              'pendiente'  => 'pendiente',
              default      => 'pendiente'
            };
          @endphp
          <tr data-id="{{ $a->id_estudiante }}"
              data-search="{{ strtolower(($a->nombre ?? '').' '.($a->apellido ?? '').' '.($a->legajo ?? '').' '.($a->user->email ?? '')) }}"
              data-estado="{{ $a->estado ?? 'pendiente' }}"
              data-carrera="{{ $a->carrera ? Str::slug($a->carrera->nombre) : '' }}">
            <td><input type="checkbox" class="check-row"></td>
            <td>{{ $a->legajo ?? '—' }}</td>
            <td class="td-nombre">
              <a href="{{ route('admin.estudiante.perfil', $a->id_estudiante) }}" class="admin-name-link">
                {{ $a->apellido }}, {{ $a->nombre }}
              </a>
              <br><span class="td-id">{{ $a->user->email ?? '—' }}</span>
            </td>
            <td class="td-carrera">{{ $a->carrera->nombre ?? '—' }}</td>
            <td>
              <span class="badge-admin badge-{{ $badgeEst }}">
                {{ ucfirst($a->estado ?? 'pendiente') }}
              </span>
            </td>
            <td>{{ $a->postulaciones_count }}</td>
            <td class="td-fecha">
              {{ $a->fecha_creacion ? \Carbon\Carbon::parse($a->fecha_creacion)->format('d/m/Y') : '—' }}
            </td>
            <td>
              <div class="td-acciones">
                <button class="btn-icon btn-ver" title="Ver perfil"
                        onclick="toggleAdminDetalle('{{ $a->id_estudiante }}', this)">
                  <i class="bi bi-eye"></i>
                </button>
                <button class="btn-icon btn-aprobar" title="Activar"
                        onclick="submitEstado('{{ route('admin.estudiantes.estado', $a->id_estudiante) }}', 'activo')">
                  <i class="bi bi-check-circle"></i>
                </button>
                <button class="btn-icon btn-suspender" title="Suspender"
                        onclick="submitEstado('{{ route('admin.estudiantes.estado', $a->id_estudiante) }}', 'suspendido')">
                  <i class="bi bi-slash-circle"></i>
                </button>
                <button class="btn-icon btn-eliminar" title="Eliminar"
                        data-delete-url="{{ route('admin.estudiantes.destroy', $a->id_estudiante) }}"
                        data-delete-name="{{ $a->nombre }} {{ $a->apellido }}">
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
                            onclick="submitEstado('{{ route('admin.estudiantes.estado', $a->id_estudiante) }}', 'activo')">
                      <i class="bi bi-check-circle"></i> Activar
                    </button>
                    <button class="btn-admin-suspender"
                            onclick="submitEstado('{{ route('admin.estudiantes.estado', $a->id_estudiante) }}', 'suspendido')">
                      <i class="bi bi-slash-circle"></i> Suspender
                    </button>
                    <button class="btn-admin-rechazar"
                            data-delete-url="{{ route('admin.estudiantes.destroy', $a->id_estudiante) }}"
                            data-delete-name="{{ $a->nombre }} {{ $a->apellido }}">
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
        <div class="admin-stat-value">{{ $totalEmpresas }}</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-aprobado"><i class="bi bi-check-circle"></i> Aprobadas</div>
        <div class="admin-stat-value">{{ $empresasAprobadas }}</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-pendiente"><i class="bi bi-hourglass-split"></i> Pendientes</div>
        <div class="admin-stat-value">{{ $empresasPendientes }}</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-rechazado"><i class="bi bi-x-circle"></i> Rechazadas</div>
        <div class="admin-stat-value">{{ $empresasRechazadas }}</div>
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
        <option value="pendiente">Pendiente</option>
        <option value="rechazada">Rechazada</option>
        <option value="suspendida">Suspendida</option>
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
          @forelse($empresas as $e)
          @php
            $badgeEmp = match($e->estado ?? 'pendiente') {
              'aprobada'   => 'aprobado',
              'rechazada'  => 'rechazado',
              'suspendida' => 'suspendido',
              'pendiente'  => 'pendiente',
              default      => 'pendiente'
            };
          @endphp
          <tr data-id="{{ $e->id_empresa }}"
              data-search="{{ strtolower(($e->nombre_empresa ?? '').' '.($e->rubro ?? '').' '.($e->user->email ?? '')) }}"
              data-estado="{{ $e->estado ?? 'pendiente' }}">
            <td><input type="checkbox" class="check-row"></td>
            <td class="td-nombre">
              <a href="{{ route('empresas.perfil', $e->id_empresa) }}" class="admin-name-link">
                {{ $e->nombre_empresa }}
              </a>
              <br><span class="td-id">{{ $e->user->email ?? '—' }}</span>
            </td>
            <td class="td-carrera">{{ $e->rubro ?? '—' }}</td>
            <td class="td-ubicacion">{{ $e->direccion ?? '—' }}</td>
            <td>{{ $e->ofertas_activas_count }}</td>
            <td>
              <span class="badge-admin badge-{{ $badgeEmp }}">
                {{ ucfirst($e->estado ?? 'pendiente') }}
              </span>
            </td>
            <td class="td-fecha">
              {{ $e->fecha_creacion ? \Carbon\Carbon::parse($e->fecha_creacion)->format('d/m/Y') : '—' }}
            </td>
            <td>
              <div class="td-acciones">
                <button class="btn-icon btn-ver" title="Ver detalle"
                        onclick="toggleAdminDetalle('e{{ $e->id_empresa }}', this)">
                  <i class="bi bi-eye"></i>
                </button>
                <button class="btn-icon btn-aprobar" title="Aprobar"
                        onclick="submitEstado('{{ route('admin.empresas.estado', $e->id_empresa) }}', 'aprobada')">
                  <i class="bi bi-check-circle"></i>
                </button>
                <button class="btn-icon btn-suspender" title="Suspender"
                        onclick="submitEstado('{{ route('admin.empresas.estado', $e->id_empresa) }}', 'suspendida')">
                  <i class="bi bi-slash-circle"></i>
                </button>
                <button class="btn-icon btn-eliminar" title="Eliminar"
                        data-delete-url="{{ route('admin.empresas.destroy', $e->id_empresa) }}"
                        data-delete-name="{{ $e->nombre_empresa }}">
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
                            onclick="submitEstado('{{ route('admin.empresas.estado', $e->id_empresa) }}', 'aprobada')">
                      <i class="bi bi-check-circle"></i> Aprobar
                    </button>
                    <button class="btn-admin-rechazar"
                            onclick="submitEstado('{{ route('admin.empresas.estado', $e->id_empresa) }}', 'rechazada')">
                      <i class="bi bi-x-circle"></i> Rechazar
                    </button>
                    <button class="btn-admin-suspender"
                            onclick="submitEstado('{{ route('admin.empresas.estado', $e->id_empresa) }}', 'suspendida')">
                      <i class="bi bi-slash-circle"></i> Suspender
                    </button>
                    <button class="btn-admin-rechazar"
                            data-delete-url="{{ route('admin.empresas.destroy', $e->id_empresa) }}"
                            data-delete-name="{{ $e->nombre_empresa }}">
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
        <div class="admin-stat-value">{{ $totalOfertas }}</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-publicada"><i class="bi bi-megaphone"></i> Publicadas</div>
        <div class="admin-stat-value">{{ $ofertasPublicadas }}</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-pendiente"><i class="bi bi-hourglass-split"></i> Pendientes</div>
        <div class="admin-stat-value">{{ $ofertasPendientes }}</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-pausada"><i class="bi bi-pause-circle"></i> Pausadas</div>
        <div class="admin-stat-value">{{ $ofertasPausadas }}</div>
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
        <option value="cerrada">Cerrada</option>
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
      <button onclick="bulkAccion('panel-ofertas','estado','Cerrada')">
        <i class="bi bi-x-circle"></i> Cerrar
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
          @forelse($ofertas as $o)
          @php
            $estado = strtolower($o->estado ?? '');
            $badgeOfe = match($estado) {
              'activa'  => 'publicada',
              'pausada' => 'pausada',
              'cerrada' => 'cerrada',
              default   => 'pendiente'
            };
            $labelOfe = ucfirst($estado);
          @endphp
          <tr data-id="{{ $o->id_oferta }}"
              data-search="{{ strtolower(($o->titulo ?? '').' '.($o->empresa->nombre_empresa ?? '')) }}"
              data-estado="{{ $o->estado ?? '' }}"
              data-modalidad="{{ strtolower($o->modalidad ?? '') }}"
              data-tipo="{{ strtolower(str_replace(' ', '-', $o->tipo_oferta ?? '')) }}">
            <td><input type="checkbox" class="check-row"></td>
            <td class="td-nombre">
              <a href="{{ route('ofertas.detalle', $o->id_oferta) }}" class="admin-name-link">
                {{ $o->titulo }}
              </a>
            </td>
            <td class="td-carrera">{{ $o->empresa->nombre_empresa ?? '—' }}</td>
            <td>{{ ucfirst($o->modalidad ?? '—') }}</td>
            <td><span class="badge-tipo">{{ ucfirst($o->tipo_oferta ?? '—') }}</span></td>
            <td>{{ $o->postulaciones_count }}</td>
            <td>
              <span class="badge-admin badge-{{ $badgeOfe }}">{{ $labelOfe }}</span>
            </td>
            <td class="td-fecha">
              {{ $o->fecha_publicacion ? \Carbon\Carbon::parse($o->fecha_publicacion)->format('d/m/Y') : '—' }}
            </td>
            <td>
              <div class="td-acciones">
                <button class="btn-icon btn-ver" title="Ver detalle"
                        onclick="toggleAdminDetalle('o{{ $o->id_oferta }}', this)">
                  <i class="bi bi-eye"></i>
                </button>
                <button class="btn-icon btn-aprobar" title="Activar"
                        onclick="submitEstado('{{ route('admin.ofertas.estado', $o->id_oferta) }}', 'Activa')">
                  <i class="bi bi-check-circle"></i>
                </button>
                @if($estado !== 'pausada')
                  <button class="btn-icon btn-suspender" title="Pausar"
                          onclick="submitEstadoConMotivo('{{ route('admin.ofertas.estado', $o->id_oferta) }}', 'Pausada')">
                    <i class="bi bi-pause-circle"></i>
                  </button>
                @else
                  <button class="btn-icon" style="opacity:.3; cursor:not-allowed;" disabled title="Ya está pausada">
                    <i class="bi bi-pause-circle"></i>
                  </button>
                @endif
                <button class="btn-icon btn-eliminar" title="Eliminar"
                        data-delete-url="{{ route('admin.ofertas.destroy', $o->id_oferta) }}"
                        data-delete-name="{{ $o->titulo }}">
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
                            onclick="submitEstado('{{ route('admin.ofertas.estado', $o->id_oferta) }}', 'Activa')">
                      <i class="bi bi-check-circle"></i> Activar
                    </button>
                    @if($estado !== 'pausada')
                      <button class="btn-admin-suspender"
                              onclick="submitEstadoConMotivo('{{ route('admin.ofertas.estado', $o->id_oferta) }}', 'Pausada')">
                        <i class="bi bi-pause-circle"></i> Pausar
                      </button>
                    @else
                      <span class="btn-admin-suspender" style="opacity:.4; cursor:not-allowed;">
                        <i class="bi bi-pause-circle"></i> Ya pausada
                      </span>
                    @endif
                    <button class="btn-admin-rechazar"
                            onclick="submitEstado('{{ route('admin.ofertas.estado', $o->id_oferta) }}', 'Cerrada')">
                      <i class="bi bi-x-circle"></i> Cerrar
                    </button>
                    <button class="btn-admin-rechazar"
                            data-delete-url="{{ route('admin.ofertas.destroy', $o->id_oferta) }}"
                            data-delete-name="{{ $o->titulo }}">
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
@if($seccion === 'reportes')
<div class="admin-tab-panel active" id="panel-reportes">

  <div class="admin-stats">
    <div class="admin-stat">
      <div class="admin-stat-label label-total"><i class="bi bi-ticket-perforated"></i> Total</div>
      <div class="admin-stat-value">{{ $totalReportes }}</div>
    </div>
    <div class="admin-stat">
      <div class="admin-stat-label label-pendiente"><i class="bi bi-envelope"></i> Abiertos</div>
      <div class="admin-stat-value">{{ $reportesAbiertos }}</div>
    </div>
    <div class="admin-stat">
      <div class="admin-stat-label label-suspendido"><i class="bi bi-arrow-repeat"></i> En Proceso</div>
      <div class="admin-stat-value">{{ $reportesEnProceso }}</div>
    </div>
    <div class="admin-stat">
      <div class="admin-stat-label label-activo"><i class="bi bi-check2-all"></i> Resueltos</div>
      <div class="admin-stat-value">{{ $reportesResueltos }}</div>
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

  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Remitente</th>
          <th>Asunto</th>
          <th>Fecha</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($reportes as $r)
        @php
          $badgeRep = match($r->estado) {
            'Abierto'    => 'pendiente',
            'En Proceso' => 'suspendido',
            'Resuelto'   => 'activo',
            default      => 'pendiente'
          };
          $nombreMostrar = $r->user_name      ?? $r->nombre_remitente ?? '—';
          $emailMostrar  = $r->user_email     ?? $r->email_remitente  ?? '—';
          $asuntoReal    = $r->asunto;
        @endphp
        <tr data-id="rep{{ $r->id_ticket }}"
            data-search="{{ strtolower($nombreMostrar . ' ' . $emailMostrar . ' ' . $asuntoReal) }}"
            data-estado="{{ strtolower($r->estado) }}"
            id="fila-reporte-{{ $r->id_ticket }}"
            style="{{ $r->estado === 'Abierto' ? 'font-weight:600;' : '' }}">
          <td class="td-nombre">
            {{ $nombreMostrar }}
            <br><span class="td-id">{{ $emailMostrar }}</span>
          </td>
          <td style="max-width:260px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
            {{ $asuntoReal }}
          </td>
          <td class="td-fecha">
            {{ \Carbon\Carbon::parse($r->fecha_creacion)->format('d/m/Y H:i') }}
          </td>
          <td>
            <span class="badge-admin badge-{{ $badgeRep }}" id="badge-rep-{{ $r->id_ticket }}">
              {{ $r->estado }}
            </span>
          </td>
          <td>
            <div class="td-acciones">
              <button class="btn-icon btn-ver" title="Ver ticket"
                      onclick="toggleAdminDetalle('rep{{ $r->id_ticket }}', this)">
                <i class="bi bi-eye"></i>
              </button>
              <button class="btn-icon btn-eliminar" title="Eliminar ticket"
                      data-delete-url="{{ route('admin.reportes.destroy', $r->id_ticket) }}"
                      data-delete-name="el ticket \"{{ $asuntoReal }}\"">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </td>
        </tr>

        {{-- DETALLE EXPANDIBLE --}}
        <tr class="admin-detalle-row" id="admin-det-rep{{ $r->id_ticket }}">
          <td colspan="5">
            <div class="admin-detalle-inner" style="grid-template-columns: 180px minmax(0,1fr) 200px;">

              {{-- Columna 1: Información --}}
              <div>
                <p class="admin-detalle-block-title">Información</p>
                <p class="admin-detalle-value" style="font-weight:600;">{{ $nombreMostrar }}</p>
                <p class="admin-detalle-value">{{ $emailMostrar }}</p>
                <p class="admin-detalle-value">
                  <span class="badge-admin badge-{{ $badgeRep }}">{{ $r->estado }}</span>
                </p>
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
                <div class="admin-detalle-actions">

                  {{-- Cambiar estado --}}
                  <form method="POST" action="{{ route('admin.reportes.estado', $r->id_ticket) }}"
                        style="display:flex; flex-direction:column; gap:6px;">
                    @csrf
                    <select name="estado" class="admin-filter-select"
                            style="font-size:12px; padding:6px 10px;"
                            onchange="this.form.submit()">
                      <option value="Abierto"    {{ $r->estado === 'Abierto'    ? 'selected' : '' }}>Abierto</option>
                      <option value="En Proceso" {{ $r->estado === 'En Proceso' ? 'selected' : '' }}>En Proceso</option>
                      <option value="Resuelto"   {{ $r->estado === 'Resuelto'   ? 'selected' : '' }}>Resuelto</option>
                    </select>
                  </form>

                  {{-- Contactar --}}
                  @if($r->id_usuario)
                    <a href="{{ route('admin.mensajes', ['postulante_id' => $r->id_usuario]) }}"
                       class="btn-admin-contactar" style="margin-top:6px;">
                      <i class="bi bi-chat-dots"></i> Contactar
                    </a>
                  @else
                    <a href="mailto:{{ $emailMostrar }}"
                       class="btn-admin-contactar" style="margin-top:6px;">
                      <i class="bi bi-envelope"></i> Enviar email
                    </a>
                  @endif

                  <button class="btn-admin-rechazar" style="margin-top:6px;"
                          data-delete-url="{{ route('admin.reportes.destroy', $r->id_ticket) }}"
                          data-delete-name="el ticket \"{{ $asuntoReal }}\"">
                    <i class="bi bi-trash"></i> Eliminar
                  </button>

                </div>
              </div>

            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" style="text-align:center; padding:30px; color:var(--muted);">
            <i class="bi bi-inbox" style="font-size:24px; display:block; margin-bottom:8px;"></i>
            No hay reportes
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endif
@if($seccion === 'papelera')
<div class="admin-tab-panel active" id="panel-papelera">

  {{-- ── OFERTAS ELIMINADAS ── --}}
  <h3 style="font-size:15px; font-weight:700; color:var(--text); margin-bottom:12px;">
    <i class="bi bi-briefcase"></i> Ofertas eliminadas
  </h3>

  <div class="admin-table-wrap" style="margin-bottom:32px;">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Título</th>
          <th>Empresa</th>
          <th>Eliminada</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($ofertasEliminadas as $o)
        <tr>
          <td class="td-nombre">{{ $o->titulo }}</td>
          <td class="td-carrera">{{ $o->empresa->nombre_empresa ?? '—' }}</td>
          <td class="td-fecha">{{ \Carbon\Carbon::parse($o->deleted_at)->format('d/m/Y H:i') }}</td>
          <td>
            <div class="td-acciones">
              {{-- Restaurar --}}
              <form method="POST" action="{{ route('admin.papelera.oferta.restaurar', $o->id_oferta) }}" style="display:inline;">
                @csrf
                <button class="btn-icon btn-aprobar" title="Restaurar">
                  <i class="bi bi-arrow-counterclockwise"></i>
                </button>
              </form>
              {{-- Eliminar definitivo --}}
              <button class="btn-icon btn-eliminar" title="Eliminar definitivamente"
                      data-delete-url="{{ route('admin.papelera.oferta.destroy', $o->id_oferta) }}"
                      data-delete-name="{{ $o->titulo }} (permanente)">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="4" style="text-align:center;padding:2rem;color:var(--muted);">
            No hay ofertas eliminadas.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
    @if($ofertasEliminadas->hasPages())
      <div style="padding:16px;">{{ $ofertasEliminadas->links() }}</div>
    @endif
  </div>

  {{-- ── POSTULACIONES ELIMINADAS ── --}}
  <h3 style="font-size:15px; font-weight:700; color:var(--text); margin-bottom:12px;">
    <i class="bi bi-send"></i> Postulaciones eliminadas
  </h3>

  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Estudiante</th>
          <th>Oferta</th>
          <th>Empresa</th>
          <th>Eliminada</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($postulacionesEliminadas as $p)
        <tr>
          <td class="td-nombre">
            {{ $p->estudiante->nombre ?? '—' }} {{ $p->estudiante->apellido ?? '' }}
            <br><span class="td-id">{{ $p->estudiante->user->email ?? '—' }}</span>
          </td>
          <td class="td-carrera">{{ $p->oferta->titulo ?? '—' }}</td>
          <td class="td-carrera">{{ $p->oferta->empresa->nombre_empresa ?? '—' }}</td>
          <td class="td-fecha">{{ \Carbon\Carbon::parse($p->deleted_at)->format('d/m/Y H:i') }}</td>
          <td>
            <div class="td-acciones">
              {{-- Restaurar --}}
              <form method="POST" action="{{ route('admin.papelera.postulacion.restaurar', $p->id_postulacion) }}" style="display:inline;">
                @csrf
                <button class="btn-icon btn-aprobar" title="Restaurar">
                  <i class="bi bi-arrow-counterclockwise"></i>
                </button>
              </form>
              {{-- Eliminar definitivo --}}
              <button class="btn-icon btn-eliminar" title="Eliminar definitivamente"
                      data-delete-url="{{ route('admin.papelera.postulacion.destroy', $p->id_postulacion) }}"
                      data-delete-name="postulación de {{ $p->estudiante->nombre ?? '' }} (permanente)">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" style="text-align:center;padding:2rem;color:var(--muted);">
            No hay postulaciones eliminadas.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
    @if($postulacionesEliminadas->hasPages())
      <div style="padding:16px;">{{ $postulacionesEliminadas->links() }}</div>
    @endif
  </div>

</div>
@endif
</div>
{{-- Formulario oculto para cambios de estado --}}
<form id="form-estado" method="POST" style="display:none;">
  @csrf
  <input type="hidden" name="estado" id="form-estado-valor">
  <input type="hidden" name="motivo" id="form-estado-motivo">
</form>

{{-- Formulario oculto para eliminaciones individuales --}}
<form id="form-delete" method="POST" style="display:none;">
  @csrf
  @method('DELETE')
</form>

{{-- Modal confirmación genérico (usado por bulk y eliminaciones) --}}
<dialog id="dialogConfirmar" class="modal-confirmar">
  <div class="modal-confirmar-content">
    <h3 class="modal-confirmar-title" id="dialogConfirmarTitle">Confirmar acción</h3>
    <p class="modal-confirmar-msg" id="dialogConfirmarMsg"></p>
    <div class="modal-confirmar-btns">
      <button onclick="document.getElementById('dialogConfirmar').close()"
              class="btn-cancelar-dialog">Cancelar</button>
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
      <button onclick="document.getElementById('dialogMotivo').close()"
              class="btn-cancelar-dialog">Cancelar</button>
      <button id="btnConfirmarMotivo" class="btn-confirmar-eliminar" style="background:var(--pausada);">
        Confirmar pausa
      </button>
    </div>
  </div>
</dialog>

@endsection

@section('scripts')
<script>

/* ════════════════════════════════════════
   MODAL CONFIRM — reemplaza confirm() nativo
════════════════════════════════════════ */
function modalConfirm(titulo, mensaje, labelBoton = 'Confirmar') {
  return new Promise(resolve => {
    const dialog = document.getElementById('dialogConfirmar');
    document.getElementById('dialogConfirmarTitle').textContent = titulo;
    document.getElementById('dialogConfirmarMsg').textContent   = mensaje;

    // Clonar botón para limpiar listeners anteriores
    const btnViejo = document.getElementById('btnConfirmarAccion');
    const btn = btnViejo.cloneNode(true);
    btn.textContent = labelBoton;
    btnViejo.parentNode.replaceChild(btn, btnViejo);

    btn.addEventListener('click', () => { dialog.close(); resolve(true); });
    dialog.addEventListener('close', () => resolve(false), { once: true });

    // Cerrar clickeando el backdrop
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

/* ════════════════════════════════════════
   CONTADOR DE CARACTERES — motivo de pausa
════════════════════════════════════════ */
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

/* ════════════════════════════════════════
   MODAL MOTIVO — reemplaza prompt() nativo
════════════════════════════════════════ */
function modalMotivo(titulo = 'Motivo de la pausa', mensaje = 'Se mostrará a la empresa (opcional).') {
  return new Promise(resolve => {
    const dialog = document.getElementById('dialogMotivo');
    document.getElementById('dialogMotivoTitle').textContent = titulo;
    document.getElementById('dialogMotivoMsg').textContent   = mensaje;
    const input = document.getElementById('dialogMotivoInput');
    input.value = '';
    actualizarContadorMotivo();

    // Clonar botón para limpiar listeners anteriores
    const btnViejo = document.getElementById('btnConfirmarMotivo');
    const btn = btnViejo.cloneNode(true);
    btnViejo.parentNode.replaceChild(btn, btnViejo);

    let confirmado = false;

    btn.addEventListener('click', () => {
      if (input.value.length > MOTIVO_MAX) return; // seguridad extra, el botón ya está disabled en este caso
      confirmado = true;
      dialog.close();
      resolve(input.value.trim());
    });

    dialog.addEventListener('close', () => {
      if (!confirmado) resolve(null); // el admin canceló
    }, { once: true });

    // Cerrar clickeando el backdrop
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

/* ════════════════════════════════════════
   CAMBIO DE ESTADO INDIVIDUAL
════════════════════════════════════════ */
function submitEstado(url, estado) {
  const form = document.getElementById('form-estado');
  document.getElementById('form-estado-valor').value = estado;
  document.getElementById('form-estado-motivo').value = '';
  form.action = url;
  form.submit();
}

async function submitEstadoConMotivo(url, estado) {
  const motivo = await modalMotivo('Motivo de la pausa', 'Se mostrará a la empresa (opcional).');
  if (motivo === null) return; // el admin canceló

  const form = document.getElementById('form-estado');
  document.getElementById('form-estado-valor').value = estado;
  document.getElementById('form-estado-motivo').value = motivo;
  form.action = url;
  form.submit();
}

/* ════════════════════════════════════════
   DETALLE EXPANDIBLE
════════════════════════════════════════ */
window.toggleAdminDetalle = function(id) {
  document.getElementById('admin-det-' + id)?.classList.toggle('open');
};

/* ════════════════════════════════════════
   ELIMINAR INDIVIDUAL — usa modal
════════════════════════════════════════ */
document.addEventListener('click', async e => {
  const btn = e.target.closest('[data-delete-url]');
  if (!btn) return;

  const name = btn.dataset.deleteName || 'este registro';
  const url  = btn.dataset.deleteUrl;
  if (!url) return;

  const ok = await modalConfirm(
    'Eliminar registro',
    `¿Confirmás eliminar "${name}"? Esta acción no se puede deshacer.`,
    'Sí, eliminar'
  );
  if (!ok) return;

  const form = document.getElementById('form-delete');
  form.action = url;
  form.submit();
});

/* ════════════════════════════════════════
   CHECKBOXES
════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {

  // check-all → marca/desmarca solo filas visibles (no detalle-rows)
  document.querySelectorAll('.check-all').forEach(chkAll => {
    const panel = chkAll.closest('.admin-tab-panel');
    chkAll.addEventListener('change', () => {
      panel.querySelectorAll('tbody tr:not(.admin-detalle-row)').forEach(row => {
        if (row.style.display !== 'none') {
          const c = row.querySelector('.check-row');
          if (c) c.checked = chkAll.checked;
        }
      });
      updateBulkBar(panel);
    });
  });

  // check-row individual
  document.addEventListener('change', e => {
    if (!e.target.classList.contains('check-row')) return;
    const panel = e.target.closest('.admin-tab-panel');
    if (!panel) return;
    const rows   = [...panel.querySelectorAll('.check-row')];
    const chkAll = panel.querySelector('.check-all');
    if (chkAll) chkAll.checked = rows.length > 0 && rows.every(c => c.checked);
    updateBulkBar(panel);
  });
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

/* ════════════════════════════════════════
   RUTAS BULK — mapa correcto panel → segmento URL
════════════════════════════════════════ */
const bulkUrlMap = {
  'panel-alumnos':   'estudiantes',
  'panel-empresas':  'empresas',
  'panel-ofertas':   'ofertas',
};

async function bulkAccion(panelId, accion, estado) {
  const panel    = document.getElementById(panelId);
  const ids      = getSelectedIds(panel);
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
    submitBulkForm(`/admin/${segmento}/bulk-destroy`, ids);
  } else {
    // Para estado no hace falta confirmación, va directo
    submitBulkForm(`/admin/${segmento}/bulk-estado`, ids, { estado });
  }
}

function submitBulkForm(url, ids, extras = {}) {
  const csrf = document.querySelector('meta[name=csrf-token]')?.content ?? '{{ csrf_token() }}';
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = url;
  form.style.display = 'none';

  const addHidden = (n, v) => {
    const i = document.createElement('input');
    i.type = 'hidden';
    i.name = n;
    i.value = v;
    form.appendChild(i);
  };

  addHidden('_token', csrf);
  ids.forEach(id => addHidden('ids[]', id));
  Object.entries(extras).forEach(([k, v]) => addHidden(k, v));

  document.body.appendChild(form);
  form.submit();
}

</script>

<style>
/* ── Modal confirmar ── */
dialog:not([open]) { display: none !important; }

.modal-motivo {
  position: fixed;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  border: 1px solid var(--border);
  border-radius: 12px;
  background: var(--surface);
  padding: 0;
  width: min(600px, 92vw);
  margin: 0;
  z-index: 9999;
}
.modal-motivo::backdrop {
  background: rgba(0,0,0,0.5);
  backdrop-filter: blur(3px);
}

.modal-confirmar {
  position: fixed;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  border: 1px solid var(--border);
  border-radius: 12px;
  background: var(--surface);
  padding: 0;
  width: min(400px, 92vw);
  margin: 0;
  z-index: 9999;
}
.modal-confirmar::backdrop {
  background: rgba(0,0,0,0.5);
  backdrop-filter: blur(3px);
}
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
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 14px;
  background: var(--surface);
  border: 1px solid var(--accent);
  border-radius: var(--radius);
  margin-bottom: 12px;
  flex-wrap: wrap;
}
.bulk-count {
  font-size: 12.5px;
  font-weight: 700;
  color: var(--accent);
  margin-right: 4px;
}
.bulk-bar button {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 5px 11px;
  font-size: 12px;
  font-weight: 700;
  font-family: var(--font-display);
  border-radius: var(--radius);
  cursor: pointer;
  border: 1px solid var(--border);
  background: var(--bg);
  color: var(--text);
  transition: border-color 0.15s, color 0.15s, background 0.15s;
}
.bulk-bar button:hover        { border-color: var(--accent); color: var(--accent); }
.bulk-bar .bulk-btn-danger    { border-color: rgba(212,24,61,.4); color: #e05577; background: transparent; }
.bulk-bar .bulk-btn-danger:hover { background: rgba(212,24,61,.08); border-color: #e05577; }
.bulk-bar .bulk-btn-cancel    { color: var(--muted); margin-left: auto; }
.bulk-bar .bulk-btn-cancel:hover { color: var(--text); border-color: var(--border); }

/* ── Botón contactar ── */
.btn-admin-contactar {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 14px;
  border-radius: 6px;
  background: #077552;
  border: none;
  color: #ffffff;
  font-size: 12.5px;
  font-weight: 700;
  font-family: var(--font-display);
  cursor: pointer;
  text-decoration: none;
  transition: filter var(--trans);
}
.btn-admin-contactar:hover { filter: brightness(1.1); }

/* ════════════════════════════════════════
   PAGINACIÓN LARAVEL
════════════════════════════════════════ */
.pagination {
    display: flex;
    align-items: center;
    gap: 4px;
    list-style: none;
    padding: 12px 0;
    margin: 0;
    justify-content: center;
    flex-wrap: wrap;
}
.page-item .page-link,
.page-item span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 8px;
    border-radius: 6px;
    border: 1px solid var(--border);
    background: var(--surface);
    color: var(--text);
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: background var(--trans), border-color var(--trans), color var(--trans);
}
.page-item .page-link:hover {
    background: var(--accent-dim);
    border-color: var(--accent);
    color: var(--accent);
}
.page-item.active .page-link,
.page-item.active span {
    background: var(--accent);
    border-color: var(--accent);
    color: #fff;
    font-weight: 700;
}
[data-theme="dark"] .page-item.active .page-link,
[data-theme="dark"] .page-item.active span {
    color: #111118;
}
.page-item.disabled .page-link,
.page-item.disabled span {
    opacity: 0.35;
    cursor: not-allowed;
    pointer-events: none;
}

/* ── Tablet ancho (900–1200px) ── */
@media (max-width: 1200px) {
  .admin-page { padding: 28px 16px 56px; }
  .admin-table td:nth-child(4),
  .admin-table th:nth-child(4) { display: none; }
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
  .admin-table th:nth-child(4),
  .admin-table td:nth-child(4),
  .admin-table th:nth-child(6),
  .admin-table td:nth-child(6) { display: none; }
}

/* ── Mobile (≤ 640px) ── */
@media (max-width: 640px) {
  *, *::before, *::after { box-sizing: border-box; }
  html, body { overflow-x: hidden; max-width: 100vw; }
  .admin-page { padding: 16px 10px 48px; overflow-x: hidden; max-width: 100%; width: 100%; }
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
  .admin-table,
  .admin-table thead,
  .admin-table tbody,
  .admin-table th,
  .admin-table td,
  .admin-table tr { display: block; width: 100%; max-width: 100%; box-sizing: border-box; }
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
  .btn-admin-aprobar,
  .btn-admin-rechazar,
  .btn-admin-suspender { width: 100%; justify-content: center; padding: 10px 16px; font-size: 13px; }
  .admin-action-notice { position: fixed; right: 24px; bottom: 24px; max-width: 100%; z-index: 99; }
  .admin-empty { padding: 36px 16px; }
  .admin-empty i { font-size: 28px; }
  .bulk-bar { flex-direction: column; align-items: stretch; }
  .bulk-bar button { justify-content: center; }
  .bulk-bar .bulk-btn-cancel { margin-left: 0; }
  .btn-admin-contactar { width: 100%; justify-content: center; padding: 10px 16px; font-size: 13px; }
}
</style>

@endsection
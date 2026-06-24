@extends('layouts.app')

@section('title', 'Administración — KROW')



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

<div class="admin-page" style="margin-top: -260px; position: relative; z-index: 5; background-color:var(--bg); border-radius: 8px; border:1px solid var(--surface);">

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
              {{ $a->apellido }}, {{ $a->nombre }}
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
                    {{-- ✅ Contactar estudiante --}}
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
          <tr data-id="e{{ $e->id_empresa }}"
              data-search="{{ strtolower(($e->nombre_empresa ?? '').' '.($e->rubro ?? '').' '.($e->user->email ?? '')) }}"
              data-estado="{{ $e->estado ?? 'pendiente' }}">
            <td><input type="checkbox" class="check-row"></td>
            <td class="td-nombre">
              {{ $e->nombre_empresa }}
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
                  <p class="admin-detalle-value">{{ $e->descripcion ?? '—' }}</p>
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
                    {{-- ✅ Contactar empresa --}}
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
          <tr data-id="o{{ $o->id_oferta }}"
              data-search="{{ strtolower(($o->titulo ?? '').' '.($o->empresa->nombre_empresa ?? '')) }}"
              data-estado="{{ $o->estado ?? '' }}"
              data-modalidad="{{ strtolower($o->modalidad ?? '') }}"
              data-tipo="{{ strtolower(str_replace(' ', '-', $o->tipo_oferta ?? '')) }}">
            <td><input type="checkbox" class="check-row"></td>
            <td class="td-nombre">{{ $o->titulo }}</td>
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
                        onclick="submitEstado('{{ route('admin.ofertas.estado', $o->id_oferta) }}', 'activa')">
                  <i class="bi bi-check-circle"></i>
                </button>
                <button class="btn-icon btn-suspender" title="Pausar"
                        onclick="submitEstado('{{ route('admin.ofertas.estado', $o->id_oferta) }}', 'pausada')">
                  <i class="bi bi-pause-circle"></i>
                </button>
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
                  <p class="admin-detalle-value">{{ Str::limit($o->descripcion ?? '—', 200) }}</p>
                  @if($o->requisitos)
                    <p class="admin-detalle-block-title" style="margin-top:10px;">Requisitos</p>
                    <p class="admin-detalle-value">{{ Str::limit($o->requisitos, 150) }}</p>
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
                            onclick="submitEstado('{{ route('admin.ofertas.estado', $o->id_oferta) }}', 'activa')">
                      <i class="bi bi-check-circle"></i> Activar
                    </button>
                    <button class="btn-admin-suspender"
                            onclick="submitEstado('{{ route('admin.ofertas.estado', $o->id_oferta) }}', 'pausada')">
                      <i class="bi bi-pause-circle"></i> Pausar
                    </button>
                    <button class="btn-admin-rechazar"
                            onclick="submitEstado('{{ route('admin.ofertas.estado', $o->id_oferta) }}', 'cerrada')">
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

</div>

{{-- Formulario oculto para cambios de estado --}}
<form id="form-estado" method="POST" style="display:none;">
  @csrf
  <input type="hidden" name="estado" id="form-estado-valor">
</form>

{{-- Formulario oculto para eliminaciones --}}
<form id="form-delete" method="POST" style="display:none;">
  @csrf
  @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
/* ── Cambio de estado ── */
function submitEstado(url, estado) {
  const form = document.getElementById('form-estado');
  document.getElementById('form-estado-valor').value = estado;
  form.action = url;
  form.submit();
}

/* ── Detalle expandible ── */
window.toggleAdminDetalle = function(id, btn) {
  const row = document.getElementById('admin-det-' + id);
  if (!row) return;
  row.classList.toggle('open');
};

/* ── Eliminar con modal ── */
document.addEventListener('click', async e => {
  const btn = e.target.closest('[data-delete-url]');
  if (!btn) return;
  const name = btn.dataset.deleteName || 'este registro';
  const url  = btn.dataset.deleteUrl;
  if (!url) return;
  const confirmed = await adminConfirm(name);
  if (!confirmed) return;
  const form = document.getElementById('form-delete');
  form.action = url;
  form.submit();
});
</script>

<style>
/* ── Botón contactar ── */
.btn-admin-contactar {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 14px;
  border-radius: var(--radius);
  background: transparent;
  border: 1px solid var(--accent);
  color: var(--accent);
  font-size: 12.5px;
  font-weight: 700;
  font-family: var(--font-display);
  cursor: pointer;
  text-decoration: none;
  transition: background 0.15s, color 0.15s;
}

.btn-admin-contactar:hover {
  background: var(--accent);
  color: #0D1A13;
}

/* ── Fix global: evita desborde horizontal ── */
@media (max-width: 640px) {
  *,
  *::before,
  *::after {
    box-sizing: border-box;
  }

  html, body {
    overflow-x: hidden;
    max-width: 100vw;
  }

  .admin-page {
    overflow-x: hidden;
    max-width: 100%;
  }

  .btn-admin-contactar {
    width: 100%;
    justify-content: center;
    padding: 10px 16px;
    font-size: 13px;
  }
}

/* ── Tablet ancho (900–1200px) ── */
@media (max-width: 1200px) {
  .admin-page {
    padding: 28px 16px 56px;
  }
  .admin-table td:nth-child(4),
  .admin-table th:nth-child(4) {
    display: none;
  }
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
  .admin-page { padding: 16px 10px 48px; max-width: 100%; width: 100%; }
  .admin-page-title { font-size: 19px; gap: 7px; }
  .admin-page-sub { font-size: 12.5px; margin-bottom: 18px; }
  .admin-tabs { gap: 0; overflow-x: auto; -webkit-overflow-scrolling: touch; scrollbar-width: none; margin-bottom: 20px; }
  .admin-tabs::-webkit-scrollbar { display: none; }
  .admin-tab { padding: 10px 16px; font-size: 12.5px; white-space: nowrap; flex-shrink: 0; }
  .tab-count { font-size: 10px; padding: 1px 5px; }
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
}
</style>

@endsection
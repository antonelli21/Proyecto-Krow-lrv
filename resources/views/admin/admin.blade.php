
@extends('layouts.app')
 
@section('title', 'Administración — KROW')
 
@section('content')
 
<div class="admin-page">
 
  <h1 class="admin-page-title">
    <i class="bi bi-shield-check"></i> Administración
  </h1>
  <p class="admin-page-sub">Gestión completa de estudiantes, empresas y ofertas de la plataforma.</p>
 
  {{-- ═══ TABS ═══ --}}
  <div class="admin-tabs">
    <button class="admin-tab active" data-tab="alumnos">
      <i class="bi bi-mortarboard"></i> Alumnos
      <span class="tab-count">12</span>
    </button>
    <button class="admin-tab" data-tab="empresas">
      <i class="bi bi-building"></i> Empresas
      <span class="tab-count">8</span>
    </button>
    <button class="admin-tab" data-tab="ofertas">
      <i class="bi bi-briefcase"></i> Ofertas
      <span class="tab-count">24</span>
    </button>
  </div>
 
  {{-- ════════════════════════════════════════
       TAB — ALUMNOS
  ════════════════════════════════════════ --}}
  <div class="admin-tab-panel active" id="panel-alumnos">
 
    <div class="admin-stats">
      <div class="admin-stat">
        <div class="admin-stat-label label-total"><i class="bi bi-people"></i> Total</div>
        <div class="admin-stat-value">12</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-activo"><i class="bi bi-person-check"></i> Activos</div>
        <div class="admin-stat-value">7</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-suspendido"><i class="bi bi-person-x"></i> Suspendidos</div>
        <div class="admin-stat-value">2</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-pendiente"><i class="bi bi-funnel"></i> Pendientes</div>
        <div class="admin-stat-value">3</div>
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
        <option value="ing-sistemas">Ingeniería en Sistemas</option>
        <option value="ing-electronica">Ingeniería Electrónica</option>
        <option value="ing-mecanica">Ingeniería Mecánica</option>
        <option value="ing-industrial">Ingeniería Industrial</option>
        <option value="ing-civil">Ingeniería Civil</option>
        <option value="tup">Tec. Universitaria en Programación</option>
      </select>
    </div>
 
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th class="th-check"><input type="checkbox" class="check-all"></th>
            <th class="sortable">Legajo ↑</th>
            <th>Nombre</th>
            <th>ID Cuenta</th>
            <th>Carrera</th>
            <th>Año</th>
            <th>Estado</th>
            <th>Postulaciones</th>
            <th>Registro</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @php
          $alumnos = [
            ['id'=>1,'legajo'=>'165430','nombre'=>'López, Diego',      'uid'=>'u004','carrera'=>'Ingeniería Eléctrica',          'carrera_key'=>'ing-electronica','anio'=>'5°','estado'=>'suspendido','posts'=>12,'fecha'=>'2023-08-20'],
            ['id'=>2,'legajo'=>'166340','nombre'=>'Peralta, Gonzalo',   'uid'=>'u008','carrera'=>'Ingeniería Química',            'carrera_key'=>'ing-quimica',    'anio'=>'5°','estado'=>'suspendido','posts'=>4, 'fecha'=>'2022-10-05'],
            ['id'=>3,'legajo'=>'167899','nombre'=>'Molina, Agustina',   'uid'=>'u011','carrera'=>'Ingeniería Eléctrica',          'carrera_key'=>'ing-electronica','anio'=>'4°','estado'=>'activo',    'posts'=>2, 'fecha'=>'2023-07-30'],
            ['id'=>4,'legajo'=>'168750','nombre'=>'Méndez, Sebastián',  'uid'=>'u006','carrera'=>'Ingeniería Mecánica',           'carrera_key'=>'ing-mecanica',   'anio'=>'4°','estado'=>'activo',    'posts'=>9, 'fecha'=>'2023-09-15'],
            ['id'=>5,'legajo'=>'169812','nombre'=>'Fernández, Lucas',   'uid'=>'u002','carrera'=>'Ingeniería Civil',              'carrera_key'=>'ing-civil',      'anio'=>'4°','estado'=>'activo',    'posts'=>3, 'fecha'=>'2024-02-28'],
            ['id'=>6,'legajo'=>'170023','nombre'=>'Vega, Nicolás',      'uid'=>'u010','carrera'=>'Lic. en Administración',        'carrera_key'=>'adm',            'anio'=>'3°','estado'=>'activo',    'posts'=>6, 'fecha'=>'2024-01-18'],
            ['id'=>7,'legajo'=>'171200','nombre'=>'Rodríguez, Camila',  'uid'=>'u005','carrera'=>'Ingeniería Industrial',         'carrera_key'=>'ing-industrial', 'anio'=>'3°','estado'=>'activo',    'posts'=>5, 'fecha'=>'2024-03-01'],
            ['id'=>8,'legajo'=>'172345','nombre'=>'García, Martina',    'uid'=>'u001','carrera'=>'Ingeniería en Sistemas',        'carrera_key'=>'ing-sistemas',   'anio'=>'3°','estado'=>'activo',    'posts'=>7, 'fecha'=>'2024-03-12'],
            ['id'=>9,'legajo'=>'173100','nombre'=>'Torres, Iván',       'uid'=>'u009','carrera'=>'Tec. Univ. en Programación',   'carrera_key'=>'tup',            'anio'=>'2°','estado'=>'pendiente', 'posts'=>0, 'fecha'=>'2024-05-01'],
            ['id'=>10,'legajo'=>'173456','nombre'=>'Suárez, Valentina', 'uid'=>'u003','carrera'=>'Ingeniería en Sistemas',       'carrera_key'=>'ing-sistemas',   'anio'=>'2°','estado'=>'pendiente', 'posts'=>0, 'fecha'=>'2024-05-10'],
          ];
          @endphp
 
          @foreach($alumnos as $a)
          <tr data-id="{{ $a['id'] }}"
              data-search="{{ strtolower($a['nombre'].' '.$a['legajo'].' '.$a['uid']) }}"
              data-estado="{{ $a['estado'] }}"
              data-carrera="{{ $a['carrera_key'] }}">
            <td><input type="checkbox" class="check-row"></td>
            <td>{{ $a['legajo'] }}</td>
            <td class="td-nombre">{{ $a['nombre'] }}<br><span class="td-id">{{ $a['uid'] }}</span></td>
            <td class="td-id">{{ $a['uid'] }}</td>
            <td class="td-carrera">{{ $a['carrera'] }}</td>
            <td>{{ $a['anio'] }}</td>
            <td><span class="badge-admin badge-{{ $a['estado'] }}">{{ ucfirst($a['estado']) }}</span></td>
            <td>{{ $a['posts'] }}</td>
            <td class="td-fecha">{{ $a['fecha'] }}</td>
            <td>
              <div class="td-acciones">
                <button class="btn-icon btn-ver" title="Ver perfil"
                        onclick="toggleAdminDetalle({{ $a['id'] }}, this)">
                  <i class="bi bi-eye"></i>
                </button>
                <button class="btn-icon btn-aprobar" title="Activar"
                        data-action="activar" data-id="{{ $a['id'] }}">
                  <i class="bi bi-check-circle"></i>
                </button>
                <button class="btn-icon btn-suspender" title="Suspender"
                        data-action="suspender" data-id="{{ $a['id'] }}">
                  <i class="bi bi-slash-circle"></i>
                </button>
                <button class="btn-icon btn-mensaje" title="Enviar mensaje">
                  <i class="bi bi-envelope"></i>
                </button>
                <button class="btn-icon btn-eliminar" title="Eliminar"
                        data-delete-type="estudiantes" data-delete-id="{{ $a['id'] }}"
                        data-delete-row-id="{{ $a['id'] }}" data-delete-name="{{ $a['nombre'] }}">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </td>
          </tr>
          <tr class="admin-detalle-row" id="admin-det-{{ $a['id'] }}">
            <td colspan="10">
              <div class="admin-detalle-inner">
                <div>
                  <p class="admin-detalle-block-title">Datos personales</p>
                  <p class="admin-detalle-value">Legajo: {{ $a['legajo'] }}</p>
                  <p class="admin-detalle-value">Cuenta: {{ $a['uid'] }}</p>
                  <p class="admin-detalle-value">Registro: {{ $a['fecha'] }}</p>
                </div>
                <div>
                  <p class="admin-detalle-block-title">Académico</p>
                  <p class="admin-detalle-value">{{ $a['carrera'] }}</p>
                  <p class="admin-detalle-value">Año: {{ $a['anio'] }}</p>
                  <p class="admin-detalle-value">Postulaciones: {{ $a['posts'] }}</p>
                </div>
                <div>
                  <p class="admin-detalle-block-title">Acciones</p>
                  <div class="admin-detalle-actions">
                    <button class="btn-admin-aprobar" data-action="activar" data-id="{{ $a['id'] }}">
                      <i class="bi bi-check-circle"></i> Activar
                    </button>
                    <button class="btn-admin-suspender" data-action="suspender" data-id="{{ $a['id'] }}">
                      <i class="bi bi-slash-circle"></i> Suspender
                    </button>
                    <button class="btn-admin-rechazar"
                            data-delete-type="estudiantes" data-delete-id="{{ $a['id'] }}"
                            data-delete-row-id="{{ $a['id'] }}" data-delete-name="{{ $a['nombre'] }}">
                      <i class="bi bi-trash"></i> Eliminar
                    </button>
                  </div>
                </div>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div class="admin-empty" style="display:none">
        <i class="bi bi-search"></i>
        <p>No se encontraron estudiantes con esos filtros.</p>
      </div>
    </div>
  </div>
 
  {{-- ════════════════════════════════════════
       TAB — EMPRESAS
  ════════════════════════════════════════ --}}
  <div class="admin-tab-panel" id="panel-empresas">
 
    <div class="admin-stats">
      <div class="admin-stat">
        <div class="admin-stat-label label-total"><i class="bi bi-building"></i> Total</div>
        <div class="admin-stat-value">8</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-aprobado"><i class="bi bi-check-circle"></i> Aprobadas</div>
        <div class="admin-stat-value">5</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-pendiente"><i class="bi bi-hourglass-split"></i> Pendientes</div>
        <div class="admin-stat-value">2</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-rechazado"><i class="bi bi-x-circle"></i> Rechazadas</div>
        <div class="admin-stat-value">1</div>
      </div>
    </div>
 
    <div class="admin-toolbar">
      <div class="admin-search">
        <i class="bi bi-search"></i>
        <input type="text" placeholder="Buscar por nombre, rubro o ubicación...">
      </div>
      <select class="admin-filter-select" data-filter="estado">
        <option value="">Todos los estados</option>
        <option value="aprobado">Aprobada</option>
        <option value="pendiente">Pendiente</option>
        <option value="rechazado">Rechazada</option>
        <option value="suspendido">Suspendida</option>
      </select>
      <select class="admin-filter-select" data-filter="rubro">
        <option value="">Todos los rubros</option>
        <option value="tecnologia">Tecnología</option>
        <option value="fintech">Fintech</option>
        <option value="diseno">Diseño & UX</option>
        <option value="datos">Análisis de datos</option>
        <option value="cloud">Infraestructura Cloud</option>
      </select>
    </div>
 
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th class="th-check"><input type="checkbox" class="check-all"></th>
            <th class="sortable">Empresa ↑</th>
            <th>Rubro</th>
            <th>Ubicación</th>
            <th>Ofertas activas</th>
            <th>Estado</th>
            <th>Registro</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @php
          $empresas = [
            ['id'=>1,'nombre'=>'TechCorp',            'rubro'=>'Tecnología',          'rubro_key'=>'tecnologia','ubicacion'=>'CABA',             'ofertas'=>5, 'estado'=>'aprobado', 'fecha'=>'2023-05-10','desc'=>'Empresa líder en desarrollo de software para el sector financiero y retail.'],
            ['id'=>2,'nombre'=>'DataCorp',             'rubro'=>'Análisis de datos',   'rubro_key'=>'datos',     'ubicacion'=>'Buenos Aires',     'ofertas'=>3, 'estado'=>'aprobado', 'fecha'=>'2023-08-22','desc'=>'Consultora en Business Intelligence y ciencia de datos.'],
            ['id'=>3,'nombre'=>'DesignStudio',         'rubro'=>'Diseño & UX',         'rubro_key'=>'diseno',    'ubicacion'=>'Remoto',           'ofertas'=>2, 'estado'=>'aprobado', 'fecha'=>'2023-11-14','desc'=>'Agencia de diseño de producto con clientes en toda LATAM.'],
            ['id'=>4,'nombre'=>'CloudNet',             'rubro'=>'Infraestructura Cloud','rubro_key'=>'cloud',    'ubicacion'=>'Buenos Aires',     'ofertas'=>4, 'estado'=>'aprobado', 'fecha'=>'2022-09-01','desc'=>'Proveedor líder de soluciones cloud y DevOps.'],
            ['id'=>5,'nombre'=>'StartupXYZ',           'rubro'=>'Fintech',             'rubro_key'=>'fintech',   'ubicacion'=>'CABA',             'ofertas'=>6, 'estado'=>'aprobado', 'fecha'=>'2024-01-05','desc'=>'Startup de pagos digitales y wallets crypto.'],
            ['id'=>6,'nombre'=>'NuvaCloud',            'rubro'=>'Tecnología',          'rubro_key'=>'tecnologia','ubicacion'=>'Córdoba',          'ofertas'=>0, 'estado'=>'pendiente','fecha'=>'2024-05-20','desc'=>'Nueva empresa de software para el sector educativo.'],
            ['id'=>7,'nombre'=>'FinancePro',           'rubro'=>'Fintech',             'rubro_key'=>'fintech',   'ubicacion'=>'CABA',             'ofertas'=>0, 'estado'=>'pendiente','fecha'=>'2024-05-28','desc'=>'Plataforma de créditos digitales para PyMEs.'],
            ['id'=>8,'nombre'=>'OldSystems S.A.',      'rubro'=>'Tecnología',          'rubro_key'=>'tecnologia','ubicacion'=>'Rosario',          'ofertas'=>0, 'estado'=>'rechazado','fecha'=>'2023-03-10','desc'=>'Empresa rechazada por incumplimiento de términos.'],
          ];
          @endphp
 
          @foreach($empresas as $e)
          <tr data-id="e{{ $e['id'] }}"
              data-search="{{ strtolower($e['nombre'].' '.$e['rubro'].' '.$e['ubicacion']) }}"
              data-estado="{{ $e['estado'] }}"
              data-rubro="{{ $e['rubro_key'] }}">
            <td><input type="checkbox" class="check-row"></td>
            <td class="td-nombre">{{ $e['nombre'] }}</td>
            <td class="td-carrera">{{ $e['rubro'] }}</td>
            <td class="td-ubicacion">{{ $e['ubicacion'] }}</td>
            <td>{{ $e['ofertas'] }}</td>
            <td><span class="badge-admin badge-{{ $e['estado'] }}">{{ ucfirst($e['estado']) }}</span></td>
            <td class="td-fecha">{{ $e['fecha'] }}</td>
            <td>
              <div class="td-acciones">
                <button class="btn-icon btn-ver" title="Ver detalle"
                        onclick="toggleAdminDetalle('e{{ $e['id'] }}', this)">
                  <i class="bi bi-eye"></i>
                </button>
                <button class="btn-icon btn-aprobar" title="Aprobar"
                        data-action="aprobar" data-id="e{{ $e['id'] }}">
                  <i class="bi bi-check-circle"></i>
                </button>
                <button class="btn-icon btn-suspender" title="Suspender"
                        data-action="suspender" data-id="e{{ $e['id'] }}">
                  <i class="bi bi-slash-circle"></i>
                </button>
                <button class="btn-icon btn-mensaje" title="Enviar mensaje">
                  <i class="bi bi-envelope"></i>
                </button>
                <button class="btn-icon btn-eliminar" title="Eliminar"
                        data-delete-type="empresas" data-delete-id="{{ $e['id'] }}"
                        data-delete-row-id="e{{ $e['id'] }}" data-delete-name="{{ $e['nombre'] }}">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </td>
          </tr>
          <tr class="admin-detalle-row" id="admin-det-e{{ $e['id'] }}">
            <td colspan="8">
              <div class="admin-detalle-inner">
                <div>
                  <p class="admin-detalle-block-title">Descripción</p>
                  <p class="admin-detalle-value">{{ $e['desc'] }}</p>
                  <p class="admin-detalle-value" style="margin-top:8px;">Registro: {{ $e['fecha'] }}</p>
                </div>
                <div>
                  <p class="admin-detalle-block-title">Actividad</p>
                  <p class="admin-detalle-value">Rubro: {{ $e['rubro'] }}</p>
                  <p class="admin-detalle-value">Ubicación: {{ $e['ubicacion'] }}</p>
                  <p class="admin-detalle-value">Ofertas activas: {{ $e['ofertas'] }}</p>
                </div>
                <div>
                  <p class="admin-detalle-block-title">Acciones</p>
                  <div class="admin-detalle-actions">
                    <button class="btn-admin-aprobar" data-action="aprobar" data-id="e{{ $e['id'] }}">
                      <i class="bi bi-check-circle"></i> Aprobar
                    </button>
                    <button class="btn-admin-rechazar" data-action="rechazar" data-id="e{{ $e['id'] }}">
                      <i class="bi bi-x-circle"></i> Rechazar
                    </button>
                    <button class="btn-admin-suspender" data-action="suspender" data-id="e{{ $e['id'] }}">
                      <i class="bi bi-slash-circle"></i> Suspender
                    </button>
                    <button class="btn-admin-rechazar"
                            data-delete-type="empresas" data-delete-id="{{ $e['id'] }}"
                            data-delete-row-id="e{{ $e['id'] }}" data-delete-name="{{ $e['nombre'] }}">
                      <i class="bi bi-trash"></i> Eliminar
                    </button>
                  </div>
                </div>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div class="admin-empty" style="display:none">
        <i class="bi bi-search"></i>
        <p>No se encontraron empresas con esos filtros.</p>
      </div>
    </div>
  </div>
 
  {{-- ════════════════════════════════════════
       TAB — OFERTAS
  ════════════════════════════════════════ --}}
  <div class="admin-tab-panel" id="panel-ofertas">
 
    <div class="admin-stats">
      <div class="admin-stat">
        <div class="admin-stat-label label-total"><i class="bi bi-briefcase"></i> Total</div>
        <div class="admin-stat-value">24</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-publicada"><i class="bi bi-megaphone"></i> Publicadas</div>
        <div class="admin-stat-value">18</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-pendiente"><i class="bi bi-hourglass-split"></i> Pendientes</div>
        <div class="admin-stat-value">4</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-label label-pausada"><i class="bi bi-pause-circle"></i> Pausadas</div>
        <div class="admin-stat-value">2</div>
      </div>
    </div>
 
    <div class="admin-toolbar">
      <div class="admin-search">
        <i class="bi bi-search"></i>
        <input type="text" placeholder="Buscar por título, empresa o tecnología...">
      </div>
      <select class="admin-filter-select" data-filter="estado">
        <option value="">Todos los estados</option>
        <option value="publicada">Publicada</option>
        <option value="pendiente">Pendiente</option>
        <option value="pausada">Pausada</option>
        <option value="rechazado">Rechazada</option>
      </select>
      <select class="admin-filter-select" data-filter="modalidad">
        <option value="">Todas las modalidades</option>
        <option value="remoto">Remoto</option>
        <option value="presencial">Presencial</option>
        <option value="mixto">Mixto</option>
      </select>
      <select class="admin-filter-select" data-filter="tipo">
        <option value="">Todos los tipos</option>
        <option value="full-time">Full-time</option>
        <option value="part-time">Part-time</option>
        <option value="pasantia">Pasantía</option>
      </select>
    </div>
 
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th class="th-check"><input type="checkbox" class="check-all"></th>
            <th class="sortable">Título ↑</th>
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
          @php
          $ofertas = [
            ['id'=>1,'titulo'=>'Fullstack Dev Node/React',    'empresa'=>'MegaCorp',     'modalidad'=>'remoto',    'tipo'=>'full-time','posts'=>24,'estado'=>'publicada','fecha'=>'2024-01-14','desc'=>'Desarrollador para equipo de core-banking. Experiencia en Node.js y React.'],
            ['id'=>2,'titulo'=>'Diseñador UX/UI Senior',      'empresa'=>'DesignStudio', 'modalidad'=>'remoto',    'tipo'=>'full-time','posts'=>18,'estado'=>'publicada','fecha'=>'2024-01-09','desc'=>'Buscamos diseñador con experiencia en Figma y sistemas de diseño.'],
            ['id'=>3,'titulo'=>'Data Analyst',                'empresa'=>'DataCorp',     'modalidad'=>'mixto',     'tipo'=>'full-time','posts'=>12,'estado'=>'publicada','fecha'=>'2024-01-04','desc'=>'Analista de datos con experiencia en Python y Power BI.'],
            ['id'=>4,'titulo'=>'DevOps Engineer',             'empresa'=>'CloudNet',     'modalidad'=>'remoto',    'tipo'=>'full-time','posts'=>8, 'estado'=>'publicada','fecha'=>'2024-02-01','desc'=>'Ingeniero DevOps con experiencia en AWS y Terraform.'],
            ['id'=>5,'titulo'=>'Pasantía Backend PHP',        'empresa'=>'TechCorp',     'modalidad'=>'presencial','tipo'=>'pasantia', 'posts'=>31,'estado'=>'publicada','fecha'=>'2024-02-15','desc'=>'Pasantía para estudiantes avanzados de sistemas o TUP.'],
            ['id'=>6,'titulo'=>'Analista Financiero',         'empresa'=>'StartupXYZ',   'modalidad'=>'presencial','tipo'=>'full-time','posts'=>5, 'estado'=>'pendiente','fecha'=>'2024-05-20','desc'=>'Analista para área de riesgo crediticio en fintech.'],
            ['id'=>7,'titulo'=>'Frontend Vue.js',             'empresa'=>'NuvaCloud',    'modalidad'=>'remoto',    'tipo'=>'part-time','posts'=>0, 'estado'=>'pendiente','fecha'=>'2024-05-25','desc'=>'Desarrollador frontend con experiencia en Vue 3 y Tailwind.'],
            ['id'=>8,'titulo'=>'Soporte IT',                  'empresa'=>'TechCorp',     'modalidad'=>'presencial','tipo'=>'part-time','posts'=>3, 'estado'=>'pausada',  'fecha'=>'2024-03-10','desc'=>'Soporte técnico nivel 1 para usuarios internos.'],
          ];
          @endphp
 
          @foreach($ofertas as $o)
          <tr data-id="o{{ $o['id'] }}"
              data-search="{{ strtolower($o['titulo'].' '.$o['empresa']) }}"
              data-estado="{{ $o['estado'] }}"
              data-modalidad="{{ $o['modalidad'] }}"
              data-tipo="{{ $o['tipo'] }}">
            <td><input type="checkbox" class="check-row"></td>
            <td class="td-nombre">{{ $o['titulo'] }}</td>
            <td class="td-carrera">{{ $o['empresa'] }}</td>
            <td>{{ ucfirst($o['modalidad']) }}</td>
            <td><span class="badge-tipo">{{ ucfirst($o['tipo']) }}</span></td>
            <td>{{ $o['posts'] }}</td>
            <td><span class="badge-admin badge-{{ $o['estado'] }}">{{ ucfirst($o['estado']) }}</span></td>
            <td class="td-fecha">{{ $o['fecha'] }}</td>
            <td>
              <div class="td-acciones">
                <button class="btn-icon btn-ver" title="Ver detalle"
                        onclick="toggleAdminDetalle('o{{ $o['id'] }}', this)">
                  <i class="bi bi-eye"></i>
                </button>
                <button class="btn-icon btn-aprobar" title="Publicar"
                        data-action="publicar" data-id="o{{ $o['id'] }}">
                  <i class="bi bi-check-circle"></i>
                </button>
                <button class="btn-icon btn-suspender" title="Pausar"
                        data-action="pausar" data-id="o{{ $o['id'] }}">
                  <i class="bi bi-pause-circle"></i>
                </button>
                <button class="btn-icon btn-eliminar" title="Eliminar"
                        data-delete-type="ofertas" data-delete-id="{{ $o['id'] }}"
                        data-delete-row-id="o{{ $o['id'] }}" data-delete-name="{{ $o['titulo'] }}">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </td>
          </tr>
          <tr class="admin-detalle-row" id="admin-det-o{{ $o['id'] }}">
            <td colspan="9">
              <div class="admin-detalle-inner">
                <div>
                  <p class="admin-detalle-block-title">Descripción</p>
                  <p class="admin-detalle-value">{{ $o['desc'] }}</p>
                </div>
                <div>
                  <p class="admin-detalle-block-title">Detalles</p>
                  <p class="admin-detalle-value">Empresa: {{ $o['empresa'] }}</p>
                  <p class="admin-detalle-value">Modalidad: {{ ucfirst($o['modalidad']) }}</p>
                  <p class="admin-detalle-value">Tipo: {{ ucfirst($o['tipo']) }}</p>
                  <p class="admin-detalle-value">Postulantes: {{ $o['posts'] }}</p>
                </div>
                <div>
                  <p class="admin-detalle-block-title">Acciones</p>
                  <div class="admin-detalle-actions">
                    <button class="btn-admin-aprobar" data-action="publicar" data-id="o{{ $o['id'] }}">
                      <i class="bi bi-check-circle"></i> Publicar
                    </button>
                    <button class="btn-admin-suspender" data-action="pausar" data-id="o{{ $o['id'] }}">
                      <i class="bi bi-pause-circle"></i> Pausar
                    </button>
                    <button class="btn-admin-rechazar"
                            data-delete-type="ofertas" data-delete-id="{{ $o['id'] }}"
                            data-delete-row-id="o{{ $o['id'] }}" data-delete-name="{{ $o['titulo'] }}">
                      <i class="bi bi-trash"></i> Eliminar
                    </button>
                  </div>
                </div>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div class="admin-empty" style="display:none">
        <i class="bi bi-search"></i>
        <p>No se encontraron ofertas con esos filtros.</p>
      </div>
    </div>
  </div>
 
</div>

<style>
/* ════════════════════════════════════════
   RESPONSIVE — Admin Panel KROW
   Pegar al pie del CSS de admin (reemplaza
   el bloque @media existente al final)
════════════════════════════════════════ */

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
}

/* ── Tablet ancho (900–1200px) ── */
@media (max-width: 1200px) {
  .admin-page {
    padding: 28px 16px 56px;
  }

  /* Ocultar columnas menos prioritarias en tabla */
  .admin-table td:nth-child(4),
  .admin-table th:nth-child(4) {
    display: none;
  }
}

/* ── Tablet (≤ 900px) ── */
@media (max-width: 900px) {

  .admin-page {
    padding: 24px 16px 52px;
  }

  .admin-page-title {
    font-size: 21px;
  }

  .admin-page-sub {
    font-size: 13px;
    margin-bottom: 20px;
  }

  /* Stats: 2 columnas */
  .admin-stats {
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    margin-bottom: 20px;
  }

  .admin-stat {
    padding: 14px 16px;
  }

  .admin-stat-value {
    font-size: 26px;
  }

  /* Toolbar: wrap con buscador full-width */
  .admin-toolbar {
    flex-direction: column;
    align-items: stretch;
    gap: 8px;
  }

  .admin-search {
    min-width: 100%;
  }

  .admin-filter-select {
    width: 100%;
  }

  /* Detalle expandible: 2 columnas */
  .admin-detalle-inner {
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }

  /* Tabla: ocultar columnas de menor prioridad */
  .admin-table th:nth-child(4),
  .admin-table td:nth-child(4),
  .admin-table th:nth-child(6),
  .admin-table td:nth-child(6) {
    display: none;
  }
}

/* ── Mobile (≤ 640px) ── */
@media (max-width: 640px) {

  .admin-page {
    padding: 16px 10px 48px;
    max-width: 100%;
    width: 100%;
  }

  .admin-page-title {
    font-size: 19px;
    gap: 7px;
  }

  .admin-page-sub {
    font-size: 12.5px;
    margin-bottom: 18px;
  }

  /* ── Tabs: scroll horizontal ── */
  .admin-tabs {
    gap: 0;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    margin-bottom: 20px;
  }
  .admin-tabs::-webkit-scrollbar { display: none; }

  .admin-tab {
    padding: 10px 16px;
    font-size: 12.5px;
    white-space: nowrap;
    flex-shrink: 0;
  }

  .tab-count {
    font-size: 10px;
    padding: 1px 5px;
  }

  /* ── Stats: 2 columnas compactas ── */
  .admin-stats {
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
    margin-bottom: 16px;
  }

  .admin-stat {
    padding: 12px 14px;
  }

  .admin-stat-label {
    font-size: 9.5px;
    gap: 4px;
  }

  .admin-stat-value {
    font-size: 28px;
  }

  /* ── Toolbar ── */
  .admin-toolbar {
    flex-direction: column;
    gap: 8px;
    margin-bottom: 12px;
  }

  .admin-search input {
    font-size: 14px; /* evita zoom en iOS */
    padding: 10px 12px 10px 34px;
  }

  .admin-filter-select {
    width: 100%;
    font-size: 14px; /* evita zoom en iOS */
    padding: 10px 28px 10px 12px;
  }

  /* ── Tabla → tarjetas ── */
  .admin-table-wrap {
    border: none;
    background: transparent;
    overflow: hidden;
    max-width: 100%;
  }

  .admin-table,
  .admin-table thead,
  .admin-table tbody,
  .admin-table th,
  .admin-table td,
  .admin-table tr {
    display: block;
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
  }

  .admin-table thead {
    display: none;
  }

  .admin-table tbody tr {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 6px;
    margin-bottom: 6px;
    padding: 8px 10px;
    position: relative;
    box-sizing: border-box;
    width: 100%;
  }

  .admin-table tbody tr:hover {
    background: var(--surface);
  }

  /* Ocultar botón ojo en mobile (el detalle se ve en el panel expandible) */
  .btn-icon.btn-ver {
    display: none;
  }

  /* Fila de detalle expandible: no es tarjeta */
  .admin-detalle-row {
    background: transparent !important;
    border: none !important;
    padding: 0 !important;
    margin-bottom: 10px;
  }

  .admin-detalle-row td {
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 14px !important;
    background: var(--bg) !important;
  }

  /* Celda checkbox: ocultar en mobile */
  .admin-table td:first-child {
    display: none;
  }

  /* Celdas normales: flex con etiqueta */
  .admin-table td {
    border-bottom: none;
    padding: 2px 0;
    font-size: 12px;
    display: flex;
    align-items: flex-start;
    gap: 6px;
  }

  .admin-table td::before {
    content: attr(data-label);
    font-size: 9.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    min-width: 62px;
    max-width: 62px;
    padding-top: 1px;
    flex-shrink: 0;
  }

  /* Celda nombre: sin etiqueta, destacada */
  .admin-table td.td-nombre {
    flex-direction: column;
    gap: 1px;
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 6px;
    padding-bottom: 7px;
    border-bottom: 1px solid var(--border);
  }

  .admin-table td.td-nombre::before {
    display: none;
  }

  /* Celda acciones: sin etiqueta, al final */
  .admin-table td:last-child {
    margin-top: 7px;
    padding-top: 7px;
    border-top: 1px solid var(--border);
    justify-content: flex-end;
  }

  .admin-table td:last-child::before {
    display: none;
  }

  /* Botones de acción más compactos */
  .td-acciones {
    gap: 2px;
  }

  .btn-icon {
    width: 30px;
    height: 30px;
    font-size: 14px;
  }

  /* Badges */
  .badge-admin {
    font-size: 11px;
    padding: 3px 8px;
  }

  .badge-tipo {
    font-size: 11px;
  }

  /* ── Detalle expandible: columna única ── */
  .admin-detalle-inner {
    grid-template-columns: 1fr;
    gap: 14px;
  }

  .admin-detalle-actions {
    flex-direction: column;
    margin-top: 12px;
    gap: 6px;
  }

  .btn-admin-aprobar,
  .btn-admin-rechazar,
  .btn-admin-suspender {
    width: 100%;
    justify-content: center;
    padding: 10px 16px;
    font-size: 13px;
  }

  /* ── Toast de acción ── */
  .admin-action-notice {
    right: 12px;
    bottom: 12px;
    left: 12px;
    max-width: 100%;
  }

  /* ── Empty state ── */
  .admin-empty {
    padding: 36px 16px;
  }

  .admin-empty i {
    font-size: 28px;
  }
}
</style>



@endsection
 

@extends('layouts.app')

@section('title', 'Base de Empresas — KROW')

@section('content')

@php
$empresas = [
    ['id'=>1,'nombre'=>'TechCorp','slug'=>'techcorp','rubro'=>'Tecnología','ubicacion'=>'CABA, Argentina','tamaño'=>'50-200 empleados','modalidades'=>['Presencial','Mixto'],'ofertas'=>5,'desc_corta'=>'Empresa líder en desarrollo de software y soluciones tecnológicas para el sector financiero y retail. Más de 10 años en el mercado...','desc_larga'=>'Empresa líder en desarrollo de software y soluciones tecnológicas para el sector financiero y retail. Más de 10 años en el mercado argentino.','web'=>'techcorp.com.ar','linkedin'=>'techcorp-ar'],
    ['id'=>2,'nombre'=>'DataCorp','slug'=>'datacorp','rubro'=>'Análisis de datos','ubicacion'=>'Buenos Aires, Argentina','tamaño'=>'10-50 empleados','modalidades'=>['Virtual','Mixto'],'ofertas'=>3,'desc_corta'=>'Consultora especializada en Business Intelligence, ciencia de datos y analítica avanzada para empresas de todos los rubros.','desc_larga'=>'Consultora especializada en Business Intelligence, ciencia de datos y analítica avanzada para empresas de todos los rubros. Equipo multidisciplinario con certificaciones internacionales.','web'=>'datacorp.com','linkedin'=>'datacorp'],
    ['id'=>3,'nombre'=>'DesignStudio','slug'=>'designstudio','rubro'=>'Diseño & UX','ubicacion'=>'Remoto','tamaño'=>'10-50 empleados','modalidades'=>['Virtual'],'ofertas'=>2,'desc_corta'=>'Agencia de diseño de producto enfocada en experiencias digitales. Trabajo 100% remoto con clientes en toda Latinoamérica.','desc_larga'=>'Agencia de diseño de producto enfocada en experiencias digitales. Trabajo 100% remoto con clientes en toda Latinoamérica y España. Especialistas en UX Research, UI Design y Design Systems.','web'=>'designstudio.ar','linkedin'=>'designstudio-ar'],
    ['id'=>4,'nombre'=>'CloudNet','slug'=>'cloudnet','rubro'=>'Infraestructura Cloud','ubicacion'=>'Buenos Aires, Argentina','tamaño'=>'50-200 empleados','modalidades'=>['Virtual','Mixto'],'ofertas'=>4,'desc_corta'=>'Proveedor líder de soluciones cloud, DevOps y ciberseguridad para empresas enterprise en el Cono Sur.','desc_larga'=>'Proveedor líder de soluciones cloud, DevOps y ciberseguridad para empresas enterprise en el Cono Sur. Partners oficiales de AWS, Azure y Google Cloud.','web'=>'cloudnet.io','linkedin'=>'cloudnet-io'],
    ['id'=>5,'nombre'=>'StartupXYZ','slug'=>'startupxyz','rubro'=>'Fintech','ubicacion'=>'CABA, Argentina','tamaño'=>'10-50 empleados','modalidades'=>['Presencial'],'ofertas'=>6,'desc_corta'=>'Startup en crecimiento que desarrolla soluciones de pagos digitales y wallets crypto para el mercado latinoamericano.','desc_larga'=>'Startup en crecimiento que desarrolla soluciones de pagos digitales y wallets crypto para el mercado latinoamericano. Recaudó USD 8M en Serie A.','web'=>'startupxyz.finance','linkedin'=>'startupxyz'],
    ['id'=>6,'nombre'=>'MegaCorp Technologies','slug'=>'megacorp-technologies','rubro'=>'Tecnología','ubicacion'=>'Buenos Aires, Argentina','tamaño'=>'500+ empleados','modalidades'=>['Presencial','Mixto','Virtual'],'ofertas'=>12,'desc_corta'=>'Multinacional con sede central en Buenos Aires. Desarrolla productos de software a gran escala para clientes Fortune 500.','desc_larga'=>'Multinacional con sede central en Buenos Aires. Desarrolla productos de software a gran escala para clientes Fortune 500. Programas de rotación internacional y beneficios premium.','web'=>'megacorp.tech','linkedin'=>'megacorp-tech'],
    ['id'=>7,'nombre'=>'GreenEnergy','slug'=>'greenenergy','rubro'=>'Energía renovable','ubicacion'=>'Córdoba, Argentina','tamaño'=>'200-500 empleados','modalidades'=>['Presencial'],'ofertas'=>3,'desc_corta'=>'Empresa dedicada al desarrollo de infraestructura de energía solar y eólica en toda la región pampeana.','desc_larga'=>'Empresa dedicada al desarrollo de infraestructura de energía solar y eólica en toda la región pampeana. Certificada B-Corp y con fuerte compromiso ESG.','web'=>'greenenergy.com.ar','linkedin'=>'greenenergy-ar'],
    ['id'=>8,'nombre'=>'HealthPlus','slug'=>'healthplus','rubro'=>'Salud / HealthTech','ubicacion'=>'Rosario, Argentina','tamaño'=>'50-200 empleados','modalidades'=>['Mixto','Virtual'],'ofertas'=>7,'desc_corta'=>'Plataforma de telemedicina y gestión de historias clínicas digitales. Conecta a pacientes con especialistas en tiempo real.','desc_larga'=>'Plataforma de telemedicina y gestión de historias clínicas digitales. Conecta a pacientes con especialistas en tiempo real. Más de 2 millones de consultas realizadas.','web'=>'healthplus.app','linkedin'=>'healthplus-app'],
];

$rubros = array_unique(array_column($empresas, 'rubro'));
$modalidades = [];
foreach ($empresas as $e) {
    foreach ($e['modalidades'] as $m) { $modalidades[$m] = true; }
}
$modalidades = array_keys($modalidades);
@endphp

<main class="empresas-page">

  <div class="empresas-header">
    <h1>Base de Empresas</h1>
    <p>Explorá las empresas registradas en la plataforma y sus ofertas laborales activas.</p>
  </div>

  <div class="empresas-toolbar">
    <div class="toolbar-search">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
      </svg>
      <input type="text" id="buscador" placeholder="Buscar empresa, rubro, ubicación..." autocomplete="off">
    </div>
    <div class="toolbar-select">
      <select id="filtro-rubro">
        <option value="">Todos los rubros</option>
        @foreach($rubros as $r)
          <option value="{{ $r }}">{{ $r }}</option>
        @endforeach
      </select>
    </div>
    <div class="toolbar-select">
      <select id="filtro-modalidad">
        <option value="">Todas las modalidades</option>
        @foreach($modalidades as $m)
          <option value="{{ $m }}">{{ $m }}</option>
        @endforeach
      </select>
    </div>
    <div class="toolbar-count" id="contador">{{ count($empresas) }} empresas</div>
  </div>

  <div class="empresas-grid" id="grid-empresas">

    @foreach($empresas as $emp)
    <article class="empresa-card"
      data-nombre="{{ strtolower($emp['nombre']) }}"
      data-rubro="{{ strtolower($emp['rubro']) }}"
      data-ubicacion="{{ strtolower($emp['ubicacion']) }}"
      data-modalidades="{{ strtolower(implode(',', $emp['modalidades'])) }}">

      <div class="empresa-card-header">
        <div class="empresa-info-title">
          <div class="empresa-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <path d="M3 21h18M5 21V7l8-4 8 4v14M9 21v-6h6v6"/>
            </svg>
          </div>
          <div class="empresa-info-text">
            <div class="empresa-nombre">{{ $emp['nombre'] }}</div>
            <div class="empresa-meta">
              <span>{{ $emp['rubro'] }}</span>
              <span>·</span>
              <span>{{ $emp['ubicacion'] }}</span>
              <span>·</span>
              <span>{{ $emp['tamaño'] }}</span>
            </div>
          </div>
        </div>
        <div class="empresa-badge-ofertas">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
          </svg>
          {{ $emp['ofertas'] }} oferta{{ $emp['ofertas'] > 1 ? 's' : '' }}
        </div>
      </div>

      <div class="empresa-tags">
        @foreach($emp['modalidades'] as $mod)
          <span class="empresa-tag">{{ $mod }}</span>
        @endforeach
      </div>

      <div class="empresa-desc">
        {{ $emp['desc_corta'] }}
      </div>

      <button class="empresa-toggle" id="toggle-{{ $emp['id'] }}"
          onclick="togglePerfil('{{ $emp['id'] }}')" aria-expanded="false">
          <span>Ver perfil completo</span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="6 9 12 15 18 9"/>
          </svg>
      </button>

      {{-- ESTE div es el que se muestra/oculta con JS --}}
      <div class="empresa-expanded" id="expanded-{{ $emp['id'] }}" style="display:none;">

          <div class="empresa-desc-full">{{ $emp['desc_larga'] }}</div>

          <div class="empresa-links">
              <a href="https://{{ $emp['web'] }}" target="_blank" rel="noopener">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <circle cx="12" cy="12" r="10"/>
                      <path d="M2 12h20"/>
                      <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                  </svg>
                  {{ $emp['web'] }}
              </a>
              <a href="https://linkedin.com/company/{{ $emp['linkedin'] }}" target="_blank" rel="noopener">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/>
                      <rect x="2" y="9" width="4" height="12"/>
                      <circle cx="4" cy="4" r="2"/>
                  </svg>
                  {{ $emp['linkedin'] }}
              </a>
          </div>

          <a href="{{ route('inicio') }}?empresa={{ urlencode($emp['slug']) }}" class="empresa-btn-ofertas">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                  <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
              </svg>
              Ver {{ $emp['ofertas'] }} oferta{{ $emp['ofertas'] > 1 ? 's' : '' }} activas
          </a>

      </div>{{-- fin empresa-expanded --}}

    </article>
    @endforeach

    <div class="empresas-empty" id="empty-state" style="display:none">
      <h3>No se encontraron empresas</h3>
      <p>Probá ajustando los filtros o el término de búsqueda.</p>
    </div>

  </div>

</main>

@endsection
<aside class="sidebar-filtros">
  <button class="sidebar-toggle-btn" id="sidebar-toggle" aria-label="Colapsar filtros">
    <span>Filtros</span>
    <span class="sidebar-toggle-icon" id="sidebar-toggle-symbol" style="font-size:1.3rem; font-weight:700; line-height:1;">−</span>
  </button>

  <form action="{{ url()->current() }}" method="GET" class="filters-form">

    <div class="filter-group text-search-group">
      <label for="buscar">Buscar</label>
      <div class="search-input-wrapper">
        <i class="bi bi-search search-icon"></i>
        <input type="text" id="buscar" name="buscar"
               placeholder="Buscar trabajo, empresa..."
               class="filter-input-text"
               value="{{ request('buscar') }}">
      </div>
    </div>

    <div class="filter-group">
      <label for="provincia">Provincia</label>
      <div class="select-wrapper">
        <select id="provincia" name="provincia" class="filter-select">
          <option value="" disabled {{ !request('provincia') ? 'selected' : '' }}>Seleccioná una provincia</option>
          @foreach(['Buenos Aires','CABA','Córdoba','Santa Fe','Mendoza'] as $prov)
            <option value="{{ $prov }}" {{ request('provincia') === $prov ? 'selected' : '' }}>{{ $prov }}</option>
          @endforeach
        </select>
        <i class="bi bi-chevron-down select-chevron"></i>
      </div>
    </div>

    <div class="filter-group">
      <label for="localidad">Localidad</label>
      <div class="select-wrapper">
        <select id="localidad" name="localidad" class="filter-select" {{ !request('provincia') ? 'disabled' : '' }}>
          <option value="" disabled {{ !request('localidad') ? 'selected' : '' }}>Seleccioná primero una provincia</option>
        </select>
        <i class="bi bi-chevron-down select-chevron"></i>
      </div>
    </div>

    <div class="filter-group">
      <label for="categoria">Categoría</label>
      <div class="select-wrapper">
        <select id="categoria" name="categoria" class="filter-select">
          @foreach([
            'Ingenieria'           => 'Ingeniería',
            'Tecnología'           => 'Tecnología',
            'Industria y produccion' => 'Industria y producción',
            'Marketing'            => 'Marketing',
            'Ventas'               => 'Ventas',
            'Recursos Humanos'     => 'Recursos Humanos',
            'Diseño'               => 'Diseño',
            'Administración'       => 'Administración',
            'Finanzas'             => 'Finanzas',
          ] as $val => $label)
            <option value="{{ $val }}" {{ request('categoria') === $val ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
        <i class="bi bi-chevron-down select-chevron"></i>
      </div>
    </div>

    {{-- Tipo de contrato --}}
    <div class="filter-accordion open">
      <div class="accordion-header">
        <span>Tipo de contrato</span>
        <span class="accordion-chevron">−</span>
      </div>
      <div class="accordion-content">
        @foreach(['pasantia' => 'Pasantía', 'part-time' => 'Part-time', 'full-time' => 'Full-time', 'practica profesional' => 'Práctica profesional'] as $val => $label)
          <label class="filter-checkbox-label">
            <input type="checkbox" name="contrato[]" value="{{ $val }}"
                   {{ in_array($val, request('contrato', [])) ? 'checked' : '' }}>
            <span class="custom-checkbox"></span> {{ $label }}
          </label>
        @endforeach
      </div>
    </div>

    {{-- Modalidad --}}
    <div class="filter-accordion open">
      <div class="accordion-header">
        <span>Modalidad</span>
        <span class="accordion-chevron">−</span>
      </div>
      <div class="accordion-content">
        @foreach(['presencial' => 'Presencial', 'remoto' => 'Remoto', 'mixto' => 'Mixto'] as $val => $label)
          <label class="filter-checkbox-label">
            <input type="checkbox" name="modalidad[]" value="{{ $val }}"
                   {{ in_array($val, request('modalidad', [])) ? 'checked' : '' }}>
            <span class="custom-checkbox"></span> {{ $label }}
          </label>
        @endforeach
      </div>
    </div>

    {{-- Carreras UTN FRH --}}
    <div class="filter-accordion open">
      <div class="accordion-header">
        <span>Carreras UTN FRH</span>
        <span class="accordion-chevron">−</span>
      </div>
      <div class="accordion-content">
        @foreach([
          'aeronautica-aeroespacial' => 'Ingeniería Aeronáutica/Aeroespacial',
          'electronica'              => 'Ingeniería Electrónica',
          'ferroviaria'              => 'Ingeniería Ferroviaria',
          'industrial'               => 'Ingeniería Industrial',
          'mecanica'                 => 'Ingeniería Mecánica',
          'bio'                      => 'Bioingeniería',
          'tec-tup'                  => 'Tecnicatura en Programación',
          'tec-tuoa'                 => 'Tecnicatura en Operación de Aeronaves',
          'tec-tumrf'                => 'Tecnicatura en Material Rodante Ferroviario',
          'tec-tudpv'                => 'Tecnicatura en Desarrollo y Producción de Videojuegos',
          'tec-tuhst'                => 'Tecnicatura en Higiene y Seguridad en el Trabajo',
          'tec-tucem'                => 'Tecnicatura en Comercio Electrónico y Marketing Digital',
          'tec-tul'                  => 'Tecnicatura en Logística',
        ] as $val => $label)
          <label class="filter-checkbox-label">
            <input type="checkbox" name="carrera[]" value="{{ $val }}"
                   {{ in_array($val, request('carrera', [])) ? 'checked' : '' }}>
            <span class="custom-checkbox"></span> {{ $label }}
          </label>
        @endforeach
      </div>
    </div>

    {{-- Tecnologías --}}
    <div class="filter-accordion open">
      <div class="accordion-header">
        <span>Tecnologías / Herramientas</span>
        <span class="accordion-chevron">−</span>
      </div>
      <div class="accordion-content">
        <div class="tag-input-wrapper">
          <input type="text" id="tecnologia-input" name="tecnologia"
                 placeholder="Ej: C#, Oracle, Git..."
                 class="filter-input-text"
                 value="{{ request('tecnologia') }}">
          <button type="button" id="btn-add-tag" class="btn-input-append">+</button>
        </div>
        <div id="tags-container" class="tags-flex-container"></div>
      </div>
    </div>

    {{-- Fecha de publicación --}}
    <div class="filter-accordion">
      <div class="accordion-header">
        <span>Fecha de publicación</span>
        <span class="accordion-chevron">+</span>
      </div>
      <div class="accordion-content">
        @foreach(['hoy' => 'Hoy', 'ultima-semana' => 'Última semana', 'ultimo-mes' => 'Último mes', 'total' => 'Cualquier fecha'] as $val => $label)
          <label class="filter-checkbox-label">
            <input type="radio" name="fecha" value="{{ $val }}"
                   {{ request('fecha', 'total') === $val ? 'checked' : '' }}>
            <span class="custom-radio"></span> {{ $label }}
          </label>
        @endforeach
      </div>
    </div>

    <div class="filters-actions">
      <button type="submit" class="btn-aplicar-filtros">Aplicar filtros</button>
    </div>

  </form>
</aside>

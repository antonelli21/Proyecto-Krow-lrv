<button class="sidebar-toggle" id="sidebar-toggle" aria-label="Colapsar filtros">
  <span>Filtros</span>
</button>

@if(isset($localidadesMap))
<script>
  window.localidadesDB = @json($localidadesMap);
</script>
@endif

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
        <option value="" {{ !request('provincia') ? 'selected' : '' }}>Todas las provincias</option>
        @if(isset($provinciasFiltro))
        @foreach($provinciasFiltro as $prov)
        <option value="{{ $prov->nombre }}" data-id="{{ $prov->id_provincia }}" {{ request('provincia') === $prov->nombre ? 'selected' : '' }}>{{ $prov->nombre }}</option>
        @endforeach
        @endif
      </select>
      <i class="bi bi-chevron-down select-chevron"></i>
    </div>
  </div>

  <div class="filter-group">
    <label for="localidad">Localidad</label>
    <div class="select-wrapper">
      <select id="localidad" name="localidad" class="filter-select" {{ !request('provincia') ? 'disabled' : '' }}>
        <option value="" disabled {{ !request('localidad') ? 'selected' : '' }}>Seleccioná primero una provincia</option>
        @if(request('localidad'))
        <option value="{{ request('localidad') }}" selected>{{ request('localidad') }}</option>
        @endif
      </select>
      <i class="bi bi-chevron-down select-chevron"></i>
    </div>
  </div>

  <div class="filter-group">
    <label for="categoria">Categoría</label>
    <div class="select-wrapper">
      <select id="categoria" name="categoria" class="filter-select">
        <option value="">Todas</option>
        @foreach(['Ingeniería', 'Tecnología', 'Industria y producción', 'Marketing', 'Ventas', 'Recursos Humanos', 'Diseño', 'Administración', 'Finanzas'] as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
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
      @if(isset($contratosFiltro))
      @foreach($contratosFiltro as $val)
      <label class="filter-checkbox-label">
        <input type="checkbox" name="contrato[]" value="{{ $val }}"
          {{ in_array($val, request('contrato', [])) ? 'checked' : '' }}>
        <span class="custom-checkbox"></span> {{ ucfirst(str_replace('_', ' ', $val)) }}
      </label>
      @endforeach
      @endif
    </div>
  </div>

  {{-- Modalidad --}}
  <div class="filter-accordion open">
    <div class="accordion-header">
      <span>Modalidad</span>
      <span class="accordion-chevron">−</span>
    </div>
    <div class="accordion-content">
      @if(isset($modalidadesFiltro))
      @foreach($modalidadesFiltro as $val)
      <label class="filter-checkbox-label">
        <input type="checkbox" name="modalidad[]" value="{{ $val }}"
          {{ in_array($val, request('modalidad', [])) ? 'checked' : '' }}>
        <span class="custom-checkbox"></span> {{ ucfirst($val) }}
      </label>
      @endforeach
      @endif
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
      'electronica' => 'Ingeniería Electrónica',
      'ferroviaria' => 'Ingeniería Ferroviaria',
      'industrial' => 'Ingeniería Industrial',
      'mecanica' => 'Ingeniería Mecánica',
      'bio' => 'Bioingeniería',
      'tec-tup' => 'Tecnicatura en Programación',
      'tec-tuoa' => 'Tecnicatura en Operación de Aeronaves',
      'tec-tumrf' => 'Tecnicatura en Material Rodante Ferroviario',
      'tec-tudpv' => 'Tecnicatura en Desarrollo y Producción de Videojuegos',
      'tec-tuhst' => 'Tecnicatura en Higiene y Seguridad en el Trabajo',
      'tec-tucem' => 'Tecnicatura en Comercio Electrónico y Marketing Digital',
      'tec-tul' => 'Tecnicatura en Logística',
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
            <input type="text" id="tecnologia-input"
                placeholder="Ej: C#, Oracle, Git..."
                class="filter-input-text">
            <button type="button" id="btn-add-tag" class="btn-input-append">+</button>
        </div>
        <div id="tags-container" class="tags-flex-container">
            {{-- Restaurar tags desde request --}}
            @foreach(request('tecnologias', []) as $tec)
                <div class="tech-tag" data-tech="{{ $tec }}">
                    <span>{{ $tec }}</span>
                    <input type="hidden" name="tecnologias[]" value="{{ $tec }}">
                    <button type="button" class="btn-remove-tag">&times;</button>
                </div>
            @endforeach
        </div>
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

  <div class="filters-actions" style="margin-top: 20px; width: 100%;">
    <input type="hidden" name="orden" id="orden-hidden" value="{{ request('orden', 'recientes') }}">
    <button type="submit" class="btn-aplicar-filtros" style="width: 100%; padding: 10px; background: var(--accent, #FFC107); color: #0D1A13; font-family: var(--font-display); font-weight: 800; border: none; border-radius: var(--radius, 6px); cursor: pointer; transition: background 0.2s;">
      Aplicar filtros
    </button>
  </div>

</form>
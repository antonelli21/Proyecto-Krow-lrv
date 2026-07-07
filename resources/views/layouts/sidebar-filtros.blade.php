<button class="sidebar-toggle" aria-label="Colapsar filtros" style="cursor: default;">
  <span>Filtros</span>
</button>

@if(isset($localidadesMap))
<script>
  window.localidadesDB = @json($localidadesMap);
</script>
@endif

<form action="{{ url()->current() }}" method="GET" class="filters-form" id="filtros-form">

  {{-- LIMPIAR TODOS — JS lo muestra/oculta según haya filtros activos --}}
  <a href="{{ url()->current() }}" class="btn-limpiar-filtros" id="btn-limpiar-todos" style="display:none;">
    <i class="bi bi-x-circle"></i> Limpiar todos los filtros
  </a>

  {{-- PROVINCIA --}}
  <div class="filter-group">
    <label for="provincia">Provincia</label>
    <div class="select-wrapper">
      <select id="provincia" name="provincia" class="filter-select">
        <option value="">Todas las provincias</option>
        @if(isset($provinciasFiltro))
          @foreach($provinciasFiltro as $prov)
            <option value="{{ $prov->nombre }}" data-id="{{ $prov->id_provincia }}"
              {{ request('provincia') === $prov->nombre ? 'selected' : '' }}>
              {{ $prov->nombre }}
            </option>
          @endforeach
        @endif
      </select>
      <i class="bi bi-chevron-down select-chevron"></i>
    </div>
  </div>

  {{-- LOCALIDAD --}}
  <div class="filter-group">
    <label for="localidad">Localidad</label>
    <div class="select-wrapper">
      <select id="localidad" name="localidad" class="filter-select"
              {{ !request('provincia') ? 'disabled' : '' }}>
        <option value="">Todas las localidades</option>
        @if(request('localidad'))
          <option value="{{ request('localidad') }}" selected>{{ request('localidad') }}</option>
        @endif
      </select>
      <i class="bi bi-chevron-down select-chevron"></i>
    </div>
  </div>

  {{-- CATEGORÍA --}}
  <div class="filter-group">
    <label for="categoria">Categoría</label>
    <div class="select-wrapper">
      <select id="categoria" name="categoria" class="filter-select">
        <option value="">Todas</option>
        @foreach(['Ingeniería','Tecnología','Industria y producción','Marketing','Ventas','Recursos Humanos','Diseño','Administración','Finanzas'] as $cat)
          <option value="{{ $cat }}" {{ request('categoria') === $cat ? 'selected' : '' }}>
            {{ $cat }}
          </option>
        @endforeach
      </select>
      <i class="bi bi-chevron-down select-chevron"></i>
    </div>
  </div>

  {{-- TIPO DE CONTRATO --}}
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
            <span class="custom-checkbox"></span>
            {{ ucfirst(str_replace('_', ' ', $val)) }}
          </label>
        @endforeach
      @endif
    </div>
  </div>

  {{-- MODALIDAD --}}
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
            <span class="custom-checkbox"></span>
            {{ ucfirst($val) }}
          </label>
        @endforeach
      @endif
    </div>
  </div>

  {{-- CARRERAS --}}
  <div class="filter-accordion open">
    <div class="accordion-header">
      <span>Carreras UTN FRH</span>
      <span class="accordion-chevron">−</span>
    </div>
    <div class="accordion-content">
      @foreach($carrerasFiltro as $carrera)
        <label class="filter-checkbox-label">
          <input type="checkbox" name="carrera[]" value="{{ $carrera->id_carrera }}"
            {{ in_array($carrera->id_carrera, request('carrera', [])) ? 'checked' : '' }}>
          <span class="custom-checkbox"></span>
          {{ $carrera->nombre }}
        </label>
      @endforeach
    </div>
  </div>

  {{-- TECNOLOGÍAS --}}
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

  {{-- FECHA DE PUBLICACIÓN --}}
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
          <span class="custom-radio"></span>
          {{ $label }}
        </label>
      @endforeach
    </div>
  </div>

  {{-- ACCIONES --}}
  <div class="filters-actions">
    <input type="hidden" name="orden" id="orden-hidden" value="{{ request('orden', 'recientes') }}">
    <button type="submit" class="btn-aplicar-filtros">
      <i class="bi bi-funnel-fill"></i> Aplicar filtros
    </button>
  </div>

</form>
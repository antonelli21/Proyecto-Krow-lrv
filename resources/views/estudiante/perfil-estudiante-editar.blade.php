@extends('layouts.app')

@section('title', 'Editar Perfil — KROW')

@section('banner')
{{-- Banner: en flujo normal, NO fixed, NO sticky. Scrollea como el resto de la página. --}}
<div style="width:100%; max-width:1600px; margin:0 auto;">
    <div style="width:100%; height:260px; overflow:hidden;
                background-image:url('{{ asset('img/banner.jpg') }}'); background-size:cover; background-position:top;">
    </div>
</div>
@endsection

@section('content')

@php
    $usuario = auth()->user();
    $misHabilidadesNombres = old('habilidades', $estudiante->habilidades->pluck('nombre')->toArray());
@endphp

{{-- panel-page con margin-top negativo: sube el header de perfil para que solape el borde inferior del banner --}}
<div class="panel-page" style="margin-top: -200px !important; margin-bottom:80px;background-color:var(--bg) ;opacity: 0.95; border-radius: 8px; border:1px solid var(--accent); justify-content:start;box-shadow:
0 20px 50px var(--shadow-color),
0 0px 30px var(--shadow-glow);">
<div class="panel-page" >

    {{-- ══ HEADER ══ --}}
    <div class="perfil-header-card" style="width:1100px; max-width: 1400px;">
        <div class="perfil-header-inner">
            <div class="perfil-avatar" id="avatarPreview"
                 style="cursor:pointer; position:relative; {{ $estudiante->foto_perfil ? 'background-image:url(\'' . \Illuminate\Support\Facades\Storage::url($estudiante->foto_perfil) . '\'); background-size:cover; background-position:center;' : '' }}"
                 onclick="document.getElementById('foto_perfil').click()"
                 title="Tocar para cambiar la foto de perfil">
                <span id="avatarInitial">
                    @if(!$estudiante->foto_perfil)
                        {{ strtoupper(substr($estudiante->nombre ?? 'E', 0, 1)) }}
                    @endif
                </span>
                <div class="avatar-overlay">
                    <i class="bi bi-camera-fill"></i>
                </div>
            </div>
            <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*"
                   form="formEditarPerfil"
                   style="display:none;" onchange="previewAvatar(this)">
            @error('foto_perfil')
                <span class="config-error" style="display:block; margin-top:4px;">{{ $message }}</span>
            @enderror
            <div class="perfil-header-info" style="flex: 1;">
                <h1 class="panel-page-title">Editar perfil</h1>
                <p class="panel-page-sub">{{ $estudiante->nombre ?? '' }} {{ $estudiante->apellido ?? '' }}</p>
            </div>
            <a href="{{ route('estudiante.perfil') }}" class="btn-outline">
                <i class="bi bi-arrow-left"></i> Volver al perfil
            </a>
        </div>
    </div>

    {{-- ══ FORMULARIO ══ --}}
    <div style="width:1100px; max-width: 1400px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem;">

        <form id="formEditarPerfil" action="{{ route('estudiante.perfil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if (session('perfil_ok'))
                <div class="config-alert config-alert-success" style="margin-bottom:16px;">
                    <i class="bi bi-check-circle"></i> Perfil actualizado correctamente.
                </div>
            @endif

            {{-- ── Datos Personales ── --}}
            <div class="perfil-card">
                <div class="perfil-card-header">
                    <i class="bi bi-person-circle"></i> Datos Personales
                </div>
                <div class="perfil-card-body" style="display: flex; flex-direction: column; gap: 16px;">

                    {{-- Fila 1 --}}
                    <div style="display: flex; gap: 16px; width: 100%;">
                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre"
                                   class="filter-input-text {{ $errors->has('nombre') ? 'input-error' : '' }}"
                                   value="{{ old('nombre', $estudiante->nombre ?? '') }}" required>
                            @error('nombre') <span class="config-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="apellido">Apellido</label>
                            <input type="text" id="apellido" name="apellido"
                                   class="filter-input-text {{ $errors->has('apellido') ? 'input-error' : '' }}"
                                   value="{{ old('apellido', $estudiante->apellido ?? '') }}" required>
                            @error('apellido') <span class="config-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Fila 2 --}}
                    <div style="display: flex; gap: 16px; width: 100%;">
                        {{-- Fecha de nacimiento — datepicker propio (igual al de Crear Cuenta) --}}
                        <div class="info-item" style="flex: 1; position:relative;">
                            <label class="info-label" for="fecha_nacimiento_input">
                                Fecha de nacimiento
                            </label>

                            <div class="input-wrap date-wrap" id="fecha_nacimiento_wrap">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>

                                <input
                                    type="text"
                                    id="fecha_nacimiento_input"
                                    class="filter-input-text {{ $errors->has('fecha_nacimiento') ? 'input-error' : '' }}"
                                    placeholder="dd/mm/aaaa"
                                    autocomplete="off"
                                    readonly
                                    value="{{ old('fecha_nacimiento', optional($estudiante->fecha_nacimiento)->format('d/m/Y')) }}">

                                <input
                                    type="hidden"
                                    id="fecha_nacimiento"
                                    name="fecha_nacimiento"
                                    value="{{ old('fecha_nacimiento', optional($estudiante->fecha_nacimiento)->format('Y-m-d')) }}">

                                <button
                                    type="button"
                                    class="date-toggle-btn"
                                    id="fecha_nacimiento_toggle"
                                    aria-label="Abrir calendario">

                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="6 9 12 15 18 9"/>
                                    </svg>
                                </button>
                            </div>

                            <div
                                class="datepicker-panel"
                                id="fecha_nacimiento_panel"
                                hidden
                                data-hoy="{{ now()->format('Y-m-d') }}">

                                <div class="dp-header">
                                    <button type="button" class="dp-nav" data-nav="prev-year" aria-label="Año anterior">«</button>
                                    <button type="button" class="dp-nav" data-nav="prev-month" aria-label="Mes anterior">‹</button>

                                    <div class="dp-title" id="fecha_nacimiento_title"></div>

                                    <button type="button" class="dp-nav" data-nav="next-month" aria-label="Mes siguiente">›</button>
                                    <button type="button" class="dp-nav" data-nav="next-year" aria-label="Año siguiente">»</button>
                                </div>

                                <div class="dp-weekdays">
                                    <span>DO</span>
                                    <span>LU</span>
                                    <span>MA</span>
                                    <span>MI</span>
                                    <span>JU</span>
                                    <span>VI</span>
                                    <span>SA</span>
                                </div>

                                <div class="dp-days" id="fecha_nacimiento_days"></div>

                                <div class="dp-footer">
                                    <button type="button" class="dp-link" data-action="clear">
                                        Borrar
                                    </button>

                                    <button type="button" class="dp-link" data-action="today">
                                        Hoy
                                    </button>
                                </div>
                            </div>

                            @error('fecha_nacimiento')
                                <span class="config-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="dni">DNI</label>
                            <input type="text" id="dni" name="dni"
                                   class="filter-input-text {{ $errors->has('dni') ? 'input-error' : '' }}"
                                   value="{{ old('dni', $estudiante->dni ?? '') }}" required>
                            @error('dni') <span class="config-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Fila 3 --}}
                    <div style="display: flex; gap: 16px; width: 100%;">
                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="email">Correo electrónico</label>
                            <input type="email" id="email" name="email"
                                   class="filter-input-text {{ $errors->has('email') ? 'input-error' : '' }}"
                                   value="{{ old('email', $usuario->email ?? '') }}" required>
                            @error('email') <span class="config-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="telefono">Teléfono</label>
                            <input type="text" id="telefono" name="telefono"
                                   class="filter-input-text {{ $errors->has('telefono') ? 'input-error' : '' }}"
                                   value="{{ old('telefono', $estudiante->telefono ?? '') }}">
                            @error('telefono') <span class="config-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Fila 4: Descripción (Ancho Completo) --}}
                    <div class="info-item" style="width: 100%;">
                        <label class="info-label" for="descripcion">Descripción / Sobre mí</label>
                        <textarea id="descripcion" name="descripcion" class="filter-input-text" rows="4" placeholder="Contanos un poco sobre tu perfil profesional o académico...">{{ old('descripcion', $estudiante->descripcion ?? '') }}</textarea>
                    </div>

                </div>
            </div>

            {{-- ── Datos Académicos ── --}}
            <div class="perfil-card">
                <div class="perfil-card-header">
                    <i class="bi bi-book"></i> Datos Académicos
                </div>
                <div class="perfil-card-body" style="display: flex; flex-direction: column; gap: 16px;">

                    {{-- Fila 1 --}}
                    <div style="display: flex; gap: 16px; width: 100%;">
                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="id_carrera">Carrera</label>
                            <div class="select-wrapper">
                                <select id="id_carrera" name="id_carrera" class="filter-select {{ $errors->has('id_carrera') ? 'input-error' : '' }}" required>
                                    <option value="">Seleccionar...</option>
                                    @foreach($carreras as $c)
                                        <option value="{{ $c->id_carrera }}"
                                            {{ (int) old('id_carrera', $estudiante->id_carrera ?? 0) === $c->id_carrera ? 'selected' : '' }}>
                                            {{ $c->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('id_carrera') <span class="config-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="legajo">Legajo universitario</label>
                            <input type="text" id="legajo" name="legajo"
                                   class="filter-input-text {{ $errors->has('legajo') ? 'input-error' : '' }}"
                                   value="{{ old('legajo', $estudiante->legajo ?? '') }}" required>
                            @error('legajo') <span class="config-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Fila 2 --}}
                    <div style="display: flex; gap: 16px; width: 100%;">
                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="portfolio">URL Portafolio</label>
                            <input type="url" id="portfolio" name="portfolio"
                                   class="filter-input-text"
                                   placeholder="https://miportafolio.com"
                                   value="{{ old('portfolio', $estudiante->portfolio ?? '') }}">
                        </div>

                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="cv">Currículum Vitae (PDF)</label>
                            <input type="file" id="cv" name="cv"
                                   class="filter-input-text"
                                   accept=".pdf">
                            @if (!empty($estudiante->cv))
                                <span class="info-label" style="margin-top:4px; font-weight: normal;">
                                    CV actual: <a href="{{ Storage::url($estudiante->cv) }}" target="_blank" class="link-accion">ver CV</a>
                                </span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Preferencias Laborales ── --}}
            <div class="perfil-card">
                <div class="perfil-card-header">
                    <i class="bi bi-briefcase"></i> Preferencias Laborales
                </div>
                <div class="perfil-card-body" style="display: flex; flex-direction: column; gap: 16px;">

                    {{-- Fila 1 (Ancho Completo) --}}
                    <div class="info-item" style="width: 100%;">
                        <label class="info-label" for="modalidad_deseada">Modalidad deseada</label>
                        <div class="select-wrapper">
                            <select id="modalidad_deseada" name="modalidad_deseada" class="filter-select">
                                <option value="">Seleccionar...</option>
                                @foreach(['Full-Time', 'Part-Time', 'Hibrido', 'Remoto'] as $m)
                                    <option value="{{ $m }}"
                                        {{ old('modalidad_deseada', $estudiante->modalidad_deseada ?? '') === $m ? 'selected' : '' }}>
                                        {{ $m }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Fila 2 (Ancho Completo) --}}
                    <div class="info-item" style="width: 100%;">
                        <label class="info-label" for="disponibilidad_horaria">Disponibilidad horaria</label>
                        <input type="text" id="disponibilidad_horaria" name="disponibilidad_horaria"
                               class="filter-input-text"
                               placeholder="Lunes a Viernes, 9hs a 17hs"
                               value="{{ old('disponibilidad_horaria', $estudiante->disponibilidad_horaria ?? '') }}">
                    </div>

                </div>
            </div>

            {{-- ── Habilidades y Tecnologías ──
                 Mismo funcionamiento que el campo "Tecnologías/Herramientas" de Crear Oferta:
                 tags libres, se agregan al toque con Enter, el botón + o un clic en una sugerencia.
                 Al guardar, el controller hace firstOrCreate por nombre sobre la tabla `habilidad`
                 y sincroniza el pivot estudiante_habilidad (mismo patrón que oferta_habilidad). --}}
            <div class="perfil-card">
                <div class="perfil-card-header">
                    <i class="bi bi-code-slash"></i> Habilidades y Tecnologías
                </div>
                <div class="perfil-card-body">
                    <div class="info-item" style="width: 100%;">
                        <label class="info-label" for="habilidad-input">Tus habilidades y tecnologías</label>
                        <div class="tag-input-wrapper">
                            <input type="text" id="habilidad-input"
                                placeholder="Ej: React, Laravel, Git..."
                                class="filter-input-text"
                                autocomplete="off">
                            <button type="button" id="btn-add-habilidad" class="btn-input-append">+</button>
                        </div>

                        <div id="habilidades-tags-container" class="tags-flex-container">
                            @foreach($misHabilidadesNombres as $nombre)
                                <div class="tech-tag">
                                    <span>{{ $nombre }}</span>
                                    <input type="hidden" name="habilidades[]" value="{{ $nombre }}">
                                    <button type="button" class="btn-remove-tag">&times;</button>
                                </div>
                            @endforeach
                        </div>

                        {{-- Sugerencias rápidas --}}
                        <div class="tech-suggestions-label">Sugerencias rápidas:</div>
                        <div class="tech-suggestions" id="habilidades-suggestions">
                            <button type="button" class="tech-suggestion" data-tech="React">React</button>
                            <button type="button" class="tech-suggestion" data-tech="Laravel">Laravel</button>
                            <button type="button" class="tech-suggestion" data-tech="Python">Python</button>
                            <button type="button" class="tech-suggestion" data-tech="Node.js">Node.js</button>
                            <button type="button" class="tech-suggestion" data-tech="Docker">Docker</button>
                            <button type="button" class="tech-suggestion" data-tech="AWS">AWS</button>
                            <button type="button" class="tech-suggestion" data-tech="PostgreSQL">PostgreSQL</button>
                            <button type="button" class="tech-suggestion" data-tech="TypeScript">TypeScript</button>
                            <button type="button" class="tech-suggestion" data-tech="Git">Git</button>
                            <button type="button" class="tech-suggestion" data-tech="Figma">Figma</button>
                            <button type="button" class="tech-suggestion" data-tech="Java">Java</button>
                            <button type="button" class="tech-suggestion" data-tech="C#">C#</button>
                            <button type="button" class="tech-suggestion" data-tech="PHP">PHP</button>
                            <button type="button" class="tech-suggestion" data-tech="MySQL">MySQL</button>
                            <button type="button" class="tech-suggestion" data-tech="MongoDB">MongoDB</button>
                            <button type="button" class="tech-suggestion" data-tech="Vue.js">Vue.js</button>
                            <button type="button" class="tech-suggestion" data-tech="Angular">Angular</button>
                            <button type="button" class="tech-suggestion" data-tech="HTML">HTML</button>
                            <button type="button" class="tech-suggestion" data-tech="CSS">CSS</button>
                            <button type="button" class="tech-suggestion" data-tech="JavaScript">JavaScript</button>
                            <button type="button" class="tech-suggestion" data-tech="Kotlin">Kotlin</button>
                            <button type="button" class="tech-suggestion" data-tech="Swift">Swift</button>
                            <button type="button" class="tech-suggestion" data-tech="Django">Django</button>
                            <button type="button" class="tech-suggestion" data-tech="Spring Boot">Spring Boot</button>
                            <button type="button" class="tech-suggestion" data-tech=".NET">.NET</button>
                            <button type="button" class="tech-suggestion" data-tech="Linux">Linux</button>
                            <button type="button" class="tech-suggestion" data-tech="Excel">Excel</button>
                            <button type="button" class="tech-suggestion" data-tech="Power BI">Power BI</button>
                            <button type="button" class="tech-suggestion" data-tech="Scrum">Scrum</button>
                            <button type="button" class="tech-suggestion" data-tech="Jira">Jira</button>
                            <button type="button" class="tech-suggestion" data-tech="Photoshop">Photoshop</button>
                        </div>

                        @error('habilidades')
                            <span class="config-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ── Redes Profesionales ── --}}
            <div class="perfil-card">
                <div class="perfil-card-header">
                    <i class="bi bi-share"></i> Redes Profesionales
                </div>
                <div class="perfil-card-body" style="display: flex; flex-direction: column; gap: 16px;">

                    <div style="display: flex; gap: 16px; width: 100%;">
                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="linkedin">LinkedIn</label>
                            <input type="url" id="linkedin" name="linkedin"
                                   class="filter-input-text"
                                   placeholder="https://linkedin.com/in/usuario"
                                   value="{{ old('linkedin', $estudiante->linkedin ?? '') }}">
                        </div>

                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="github">GitHub</label>
                            <input type="url" id="github" name="github"
                                   class="filter-input-text"
                                   placeholder="https://github.com/usuario"
                                   value="{{ old('github', $estudiante->github ?? '') }}">
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Acciones ── --}}
            <div class="perfil-card">
                <div class="perfil-card-body" style="display: flex; flex-direction: row; justify-content: flex-end; gap: 12px;">
                    <a href="{{ route('estudiante.perfil') }}" class="btn-outline">
                        Cancelar
                    </a>
                    <button type="submit" class="btn-apply-filters">
                        <i class="bi bi-check-lg"></i> Guardar cambios
                    </button>
                </div>
            </div>

        </form>

    </div>

</div>
<div>
<style>
.avatar-overlay {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: rgba(0,0,0,0.55);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity .15s ease;
    color: #fff;
    font-size: 1.1rem;
}
.perfil-avatar:hover .avatar-overlay {
    opacity: 1;
}


textarea.filter-input-text,.filter-input-text,
.filter-select {
    width: 100%;
    padding: 0.65rem 0.875rem;
    border: 1px solid var(--border);
    border-radius: var(--radius, 6px);
    background: var(--toolbar_bg);
    color: var(--text);
    font-size: 0.9rem;
    font-family: var(--font-body);
    transition: border-color 0.2s, box-shadow 0.2s;
    outline: none;
    color-scheme: light dark;
}

textarea.filter-input-text {
    resize: vertical;
    line-height: 1.6;
    min-height: 100px;
}

.filter-input-text::placeholder {
    color: var(--muted);
    opacity: 0.7;
}

.filter-input-text:focus,
.filter-select:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(46, 204, 154, 0.10);
}

[data-theme="dark"] .filter-input-text:focus,
[data-theme="dark"] .filter-select:focus {
    box-shadow: 0 0 0 3px rgba(212, 168, 67, 0.12);
}

.filter-input-text.input-error,
.filter-select.input-error {
    border-color: var(--destructive);
    box-shadow: 0 0 0 3px rgba(212, 24, 61, 0.12);
}

.config-error {
    display: block;
    font-size: 0.8rem;
    color: var(--destructive);
    margin-top: 4px;
}

.field-hint {
    font-size: 0.8rem;
    color: var(--muted);
    margin-top: 2px;
}

/* El navegador suele pintar los inputs readonly con un gris propio
   (y en iOS/Safari además baja la opacidad del texto). Forzamos acá
   los mismos colores que el resto de los inputs del formulario. */
.filter-input-text[readonly] {
    background: var(--toolbar_bg) !important;
    color: var(--text) !important;
    -webkit-text-fill-color: var(--text);
    opacity: 1 !important;
}

/* Select con flecha propia — reemplaza la flecha nativa del navegador,
   que no toma los colores del sitio y suele romper el tema oscuro. */
.select-wrapper {
    position: relative;
}

.select-wrapper .filter-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    padding-right: 2.5rem;
    cursor: pointer;
}

.select-wrapper::after {
    content: '▼';
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--muted);
    font-size: 0.7rem;
    pointer-events: none;
}

/* Color correcto de las opciones del select (el navegador ignora el
   background del <select> y pinta las opciones con los colores del
   sistema operativo, lo que rompe el tema oscuro si no se fuerza acá). */
.filter-select option {
    background: var(--surface);
    color: var(--text);
}

.filter-select:disabled,
.filter-input-text:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: var(--bg-hover, var(--toolbar_bg));
}

/* ═══════════════════════════════════════════════════════════
   FECHA DE NACIMIENTO — datepicker propio (igual al de Crear Cuenta)
   ═══════════════════════════════════════════════════════════ */
.date-wrap {
    cursor: pointer;
}

.date-wrap > svg:first-child {
    position: absolute;
    left: 12px;
    color: var(--muted);
    pointer-events: none;
    flex-shrink: 0;
}

.input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.date-wrap input {
    cursor: pointer;
    caret-color: transparent;
    padding-left: 2.6rem;
    padding-right: 2.8rem;
}

.date-toggle-btn {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    padding: 6px;
    cursor: pointer;
    color: var(--accent);
    border-radius: 4px;
    transition: background-color 0.2s, transform 0.2s;
    z-index: 2;
}

.date-toggle-btn:hover {
    background-color: rgba(46, 204, 154, 0.1);
}

[data-theme="dark"] .date-toggle-btn:hover {
    background-color: rgba(212, 168, 67, 0.15);
}

.date-toggle-btn.open svg {
    transform: rotate(180deg);
}

.date-toggle-btn svg {
    transition: transform 0.2s;
}

.datepicker-panel {
    position: absolute;
    z-index: 30;
    margin-top: 6px;
    width: 280px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius, 6px);
    box-shadow: var(--shadow-card, 0 10px 30px rgba(0, 0, 0, 0.25));
    padding: 0.85rem;
    animation: fadeInUp 0.15s ease;
    color: var(--text);
}

.dp-header {
    display: grid;
    grid-template-columns: auto auto 1fr auto auto;
    align-items: center;
    gap: 2px;
    margin-bottom: 0.6rem;
}

.dp-nav {
    background: none;
    border: none;
    color: var(--muted);
    cursor: pointer;
    font-size: 0.95rem;
    font-family: var(--font-body);
    padding: 4px 7px;
    border-radius: 4px;
    transition: background-color 0.15s, color 0.15s;
    line-height: 1;
}

.dp-nav:hover {
    color: var(--accent);
    background-color: rgba(46, 204, 154, 0.1);
}

[data-theme="dark"] .dp-nav:hover {
    background-color: rgba(212, 168, 67, 0.15);
}

.dp-title {
    text-align: center;
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 0.85rem;
    color: var(--text);
    text-transform: capitalize;
}

.dp-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    margin-bottom: 4px;
}

.dp-weekdays span {
    text-align: center;
    font-size: 0.68rem;
    font-weight: 700;
    color: var(--muted);
    letter-spacing: 0.4px;
}

.dp-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    row-gap: 2px;
}

.dp-day {
    position: relative;
    background: none;
    border: none;
    color: var(--text);
    font-size: 0.82rem;
    font-family: var(--font-body);
    padding: 6px 0;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.15s, color 0.15s;
}

.dp-day:hover:not(:disabled) {
    background-color: rgba(46, 204, 154, 0.12);
}

[data-theme="dark"] .dp-day:hover:not(:disabled) {
    background-color: rgba(212, 168, 67, 0.15);
}

.dp-day.dp-outside {
    color: var(--muted);
    opacity: 0.45;
}

.dp-day.dp-today {
    font-weight: 700;
}

.dp-day.dp-today::after {
    content: '';
    position: absolute;
    bottom: 2px;
    left: 50%;
    transform: translateX(-50%);
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: var(--accent);
}

.dp-day.dp-selected {
    background: var(--accent);
    color: var(--text_btn);
    font-weight: 700;
}

[data-theme="dark"] .dp-day.dp-selected {
    color: #111118;
}

.dp-day:disabled {
    cursor: not-allowed;
    opacity: 0.3;
}

.dp-footer {
    display: flex;
    justify-content: space-between;
    margin-top: 0.7rem;
    padding-top: 0.6rem;
    border-top: 1px solid var(--border);
}

.dp-link {
    background: none;
    border: none;
    color: var(--accent);
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    padding: 2px 4px;
}

.dp-link:hover {
    text-decoration: underline;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(12px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ═══════════════════════════════════════════════════════════
   TAGS DE TECNOLOGÍAS (igual a Crear Oferta)
   ═══════════════════════════════════════════════════════════ */
.tag-input-wrapper {
    display: flex;
    gap: 0.5rem;
    align-items: stretch;
}

.tag-input-wrapper .filter-input-text {
    flex: 1;
}

.btn-input-append {
    background-color: var(--accent);
    color: var(--text_btn);
    border: none;
    border-radius: var(--radius, 6px);
    padding: 0.625rem 1rem;
    cursor: pointer;
    font-weight: bold;
    font-size: 1.1rem;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 44px;
}

.btn-input-append:hover {
    transform: scale(1.02);
    opacity: 0.9;
}

[data-theme="dark"] .btn-input-append {
    color: #111118;
}

.tags-flex-container {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.75rem;
}

.tech-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--toolbar_bg);
    border: 1px solid var(--border);
    color: var(--text);
    padding: 0.35rem 0.6rem;
    border-radius: 20px;
    font-size: 0.82rem;
    animation: tagPopIn 0.2s ease-in-out;
}

[data-theme="dark"] .tech-tag {
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(255, 255, 255, 0.16);
    color: #f2f2f5;
}

.btn-remove-tag {
    background: none;
    border: none;
    color: var(--muted);
    cursor: pointer;
    font-size: 1rem;
    line-height: 1;
    padding: 0;
    display: inline-flex;
}

[data-theme="dark"] .btn-remove-tag {
    color: rgba(255, 255, 255, 0.55);
}

.btn-remove-tag:hover {
    color: var(--destructive);
}

.tech-suggestions-label {
    font-size: 0.75rem;
    color: var(--muted);
    margin-top: 0.75rem;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.tech-suggestions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
    margin-top: 0.25rem;
    min-height: 1.6rem;
}

.tech-suggestion {
    padding: 0.25rem 0.6rem;
    border-radius: 20px;
    border: 1px solid var(--border);
    background: var(--bg);
    color: var(--text);
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.15s;
}

[data-theme="dark"] .tech-suggestion {
    background: rgba(255, 255, 255, 0.04);
    border-color: rgba(255, 255, 255, 0.14);
    color: #d8d8de;
}

.tech-suggestion:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: rgba(46, 204, 154, 0.12);
}

[data-theme="dark"] .tech-suggestion:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: rgba(212, 168, 67, 0.15);
}

@keyframes tagPopIn {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const avatar = document.getElementById('avatarPreview');
            avatar.style.backgroundImage = `url('${e.target.result}')`;
            avatar.style.backgroundSize = 'cover';
            avatar.style.backgroundPosition = 'center';
            document.getElementById('avatarInitial').textContent = '';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

/* ══════════════════════════════════════════════════
   DATEPICKER PROPIO — Fecha de nacimiento
   (misma lógica que en la pantalla de Crear Cuenta)
══════════════════════════════════════════════════ */
const MESES_ES = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

function initFechaNacimientoDatepicker() {
    const wrap = document.getElementById('fecha_nacimiento_wrap');
    const toggleBtn = document.getElementById('fecha_nacimiento_toggle');
    const panel = document.getElementById('fecha_nacimiento_panel');
    const displayInput = document.getElementById('fecha_nacimiento_input');
    const hiddenInput = document.getElementById('fecha_nacimiento');
    const titleEl = document.getElementById('fecha_nacimiento_title');
    const daysEl = document.getElementById('fecha_nacimiento_days');

    if (!wrap || !panel || !hiddenInput) return;

    const hoy = new Date(panel.dataset.hoy + 'T00:00:00');
    let selected = hiddenInput.value ? new Date(hiddenInput.value + 'T00:00:00') : null;
    let viewYear = selected ? selected.getFullYear() : hoy.getFullYear() - 18;
    let viewMonth = selected ? selected.getMonth() : hoy.getMonth();

    function pad(n) {
        return String(n).padStart(2, '0');
    }

    function toISO(d) {
        return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate());
    }

    function toDisplay(d) {
        return pad(d.getDate()) + '/' + pad(d.getMonth() + 1) + '/' + d.getFullYear();
    }

    function render() {
        titleEl.textContent = MESES_ES[viewMonth] + ' de ' + viewYear;
        daysEl.innerHTML = '';

        const primerDia = new Date(viewYear, viewMonth, 1);
        const inicioGrilla = new Date(primerDia);
        inicioGrilla.setDate(primerDia.getDate() - primerDia.getDay());

        for (let i = 0; i < 42; i++) {
            const fecha = new Date(inicioGrilla);
            fecha.setDate(inicioGrilla.getDate() + i);

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'dp-day';
            btn.textContent = fecha.getDate();

            const esDeOtroMes = fecha.getMonth() !== viewMonth;
            const esFutura = fecha > hoy;
            const esHoy = toISO(fecha) === toISO(hoy);
            const esSeleccionada = selected && toISO(fecha) === toISO(selected);

            if (esDeOtroMes) btn.classList.add('dp-outside');
            if (esHoy) btn.classList.add('dp-today');
            if (esSeleccionada) btn.classList.add('dp-selected');

            if (esFutura) {
                btn.disabled = true;
            } else {
                btn.addEventListener('click', function() {
                    selected = fecha;
                    hiddenInput.value = toISO(fecha);
                    displayInput.value = toDisplay(fecha);
                    displayInput.classList.remove('input-error');
                    render();
                    cerrarPanel();
                });
            }

            daysEl.appendChild(btn);
        }
    }

    function abrirPanel() {
        panel.hidden = false;
        toggleBtn.classList.add('open');
        render();
    }

    function cerrarPanel() {
        panel.hidden = true;
        toggleBtn.classList.remove('open');
    }

    function togglePanel() {
        if (panel.hidden) abrirPanel();
        else cerrarPanel();
    }

    toggleBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        togglePanel();
    });

    displayInput.addEventListener('click', togglePanel);

    panel.querySelectorAll('.dp-nav').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const nav = btn.dataset.nav;
            if (nav === 'prev-month') viewMonth--;
            if (nav === 'next-month') viewMonth++;
            if (nav === 'prev-year') viewYear--;
            if (nav === 'next-year') viewYear++;
            if (viewMonth < 0) { viewMonth = 11; viewYear--; }
            if (viewMonth > 11) { viewMonth = 0; viewYear++; }
            render();
        });
    });

    panel.querySelector('[data-action="clear"]').addEventListener('click', function(e) {
        e.stopPropagation();
        selected = null;
        hiddenInput.value = '';
        displayInput.value = '';
        render();
    });

    panel.querySelector('[data-action="today"]').addEventListener('click', function(e) {
        e.stopPropagation();
        viewYear = hoy.getFullYear();
        viewMonth = hoy.getMonth();
        render();
    });

    document.addEventListener('click', function(e) {
        if (!panel.hidden && !wrap.contains(e.target) && !panel.contains(e.target)) {
            cerrarPanel();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !panel.hidden) cerrarPanel();
    });
}

/* ══════════════════════════════════════════════════
   HABILIDADES / TECNOLOGÍAS — mismo funcionamiento que
   el campo "Tecnologías/Herramientas" de Crear Oferta:
   tag libre, se agrega al toque con Enter, + o clic en
   una sugerencia. Sin validar contra ningún catálogo.
══════════════════════════════════════════════════ */
function initHabilidadesTags() {
    const input = document.getElementById('habilidad-input');
    const btnAdd = document.getElementById('btn-add-habilidad');
    const container = document.getElementById('habilidades-tags-container');

    if (!input || !btnAdd || !container) return;

    let tagsList = Array.from(container.querySelectorAll('.tech-tag input[type="hidden"]'))
        .map(function(i) { return i.value.toLowerCase(); });

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function createTag(text) {
        const cleanedText = text.trim();

        if (cleanedText === '') return;
        if (tagsList.includes(cleanedText.toLowerCase())) {
            input.value = '';
            return;
        }

        tagsList.push(cleanedText.toLowerCase());

        const tagDiv = document.createElement('div');
        tagDiv.classList.add('tech-tag');

        tagDiv.innerHTML = `
            <span>${escapeHtml(cleanedText)}</span>
            <input type="hidden" name="habilidades[]" value="${escapeHtml(cleanedText)}">
            <button type="button" class="btn-remove-tag">&times;</button>
        `;

        tagDiv.querySelector('.btn-remove-tag').addEventListener('click', () => {
            tagsList = tagsList.filter(t => t !== cleanedText.toLowerCase());
            tagDiv.remove();
        });

        container.appendChild(tagDiv);
        input.value = '';
    }

    container.querySelectorAll('.btn-remove-tag').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const tag = btn.closest('.tech-tag');
            const val = tag.querySelector('input[type="hidden"]').value.toLowerCase();
            tagsList = tagsList.filter(t => t !== val);
            tag.remove();
        });
    });

    btnAdd.addEventListener('click', () => {
        createTag(input.value);
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            createTag(input.value);
        }
    });

    document.querySelectorAll('#habilidades-suggestions .tech-suggestion').forEach(btn => {
        btn.addEventListener('click', () => {
            createTag(btn.dataset.tech);
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initFechaNacimientoDatepicker();
    initHabilidadesTags();
});
</script>


@endsection
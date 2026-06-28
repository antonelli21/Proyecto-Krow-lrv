@extends('layouts.app')

@section('title', 'Editar Perfil — KROW')

@section('content')

@php
    $usuario = auth()->user();
    $misHabilidades = old('habilidades', $estudiante->habilidades->pluck('id_habilidad')->toArray());
@endphp

<div class="panel-page">

    {{-- ══ HEADER ══ --}}
    <div class="perfil-header-card">
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
        <div style="max-width: 720px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem;">

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
                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="fecha_nacimiento">Fecha de nacimiento</label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                                   class="filter-input-text {{ $errors->has('fecha_nacimiento') ? 'input-error' : '' }}"
                                   value="{{ old('fecha_nacimiento', optional($estudiante->fecha_nacimiento)->format('Y-m-d')) }}">
                            @error('fecha_nacimiento') <span class="config-error">{{ $message }}</span> @enderror
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

            {{-- ── Habilidades ── --}}
            <div class="perfil-card">
                <div class="perfil-card-header">
                    <i class="bi bi-code-slash"></i> Habilidades
                </div>
                <div class="perfil-card-body">
                    <div class="info-item" style="width: 100%;">
                        <label class="info-label" style="margin-bottom: 12px;">Habilidades y tecnologías</label>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
                            @foreach($habilidades as $h)
                                <label style="display:flex; align-items:center; gap:8px; font-weight:normal; margin: 0; cursor: pointer;">
                                    <input type="checkbox" name="habilidades[]" value="{{ $h->id_habilidad }}"
                                           {{ in_array($h->id_habilidad, $misHabilidades) ? 'checked' : '' }}>
                                    {{ $h->nombre }}
                                </label>
                            @endforeach
                        </div>
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
</script>

@endsection
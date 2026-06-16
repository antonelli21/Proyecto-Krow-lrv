@extends('layouts.app')
 
@section('title', 'Editar Perfil — KROW')
 
@section('content')
 
@php
    $usuario    = auth()->user();
    $estudiante = $usuario->estudiante ?? null;
@endphp
 
<div class="panel-page">
 
    {{-- ══ HEADER ══ --}}
    <div class="perfil-header-card">
        <div class="perfil-header-inner">
            <div class="perfil-avatar">
                {{ strtoupper(substr($usuario->name ?? 'E', 0, 1)) }}
            </div>
            <div class="perfil-header-info" style="flex: 1;">
                <h1 class="panel-page-title">Editar perfil</h1>
                <p class="panel-page-sub">{{ $usuario->name ?? '' }}</p>
            </div>
            <a href="{{ route('estudiante.perfil') }}" class="btn-outline">
                <i class="bi bi-arrow-left"></i> Volver al perfil
            </a>
        </div>
    </div>
 
    {{-- ══ FORMULARIO ══ --}}
    <div class="perfil-sections">
 
        <form action="{{ route('estudiante.perfil.update') }}" method="POST" enctype="multipart/form-data">
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
                <div class="perfil-card-body">
                    <div class="perfil-grid">
 
                        <div class="info-item">
                            <label class="info-label" for="nombre">Nombre completo</label>
                            <input type="text" id="nombre" name="nombre"
                                   class="filter-input-text {{ $errors->has('nombre') ? 'input-error' : '' }}"
                                   value="{{ old('nombre', $usuario->name ?? '') }}" required>
                            @error('nombre') <span class="config-error">{{ $message }}</span> @enderror
                        </div>
 
                        <div class="info-item">
                            <label class="info-label" for="edad">Edad</label>
                            <input type="number" id="edad" name="edad"
                                   class="filter-input-text {{ $errors->has('edad') ? 'input-error' : '' }}"
                                   value="{{ old('edad', $estudiante->edad ?? '') }}" min="16" max="99">
                            @error('edad') <span class="config-error">{{ $message }}</span> @enderror
                        </div>
 
                        <div class="info-item">
                            <label class="info-label" for="dni">DNI</label>
                            <input type="text" id="dni" name="dni"
                                   class="filter-input-text {{ $errors->has('dni') ? 'input-error' : '' }}"
                                   value="{{ old('dni', $estudiante->dni ?? '') }}">
                            @error('dni') <span class="config-error">{{ $message }}</span> @enderror
                        </div>
 
                        <div class="info-item">
                            <label class="info-label" for="email">Correo electrónico</label>
                            <input type="email" id="email" name="email"
                                   class="filter-input-text {{ $errors->has('email') ? 'input-error' : '' }}"
                                   value="{{ old('email', $usuario->email ?? '') }}" required>
                            @error('email') <span class="config-error">{{ $message }}</span> @enderror
                        </div>
 
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <label class="info-label" for="telefono">Teléfono</label>
                            <input type="text" id="telefono" name="telefono"
                                   class="filter-input-text {{ $errors->has('telefono') ? 'input-error' : '' }}"
                                   value="{{ old('telefono', $estudiante->telefono ?? '') }}">
                            @error('telefono') <span class="config-error">{{ $message }}</span> @enderror
                        </div>
 
                    </div>
                </div>
            </div>
 
            {{-- ── Datos Académicos ── --}}
            <div class="perfil-card">
                <div class="perfil-card-header">
                    <i class="bi bi-book"></i> Datos Académicos
                </div>
                <div class="perfil-card-body">
                    <div class="perfil-grid">
 
                        <div class="info-item">
                            <label class="info-label" for="carrera">Carrera</label>
                            <input type="text" id="carrera" name="carrera"
                                   class="filter-input-text"
                                   value="{{ old('carrera', $estudiante->carrera ?? '') }}">
                        </div>
 
                        <div class="info-item">
                            <label class="info-label" for="legajo">Legajo universitario</label>
                            <input type="text" id="legajo" name="legajo"
                                   class="filter-input-text"
                                   value="{{ old('legajo', $estudiante->legajo ?? '') }}">
                        </div>
 
                        <div class="info-item">
                            <label class="info-label" for="portafolio">URL Portafolio</label>
                            <input type="url" id="portafolio" name="portafolio"
                                   class="filter-input-text"
                                   placeholder="https://miportafolio.com"
                                   value="{{ old('portafolio', $estudiante->portafolio ?? '') }}">
                        </div>
 
                        <div class="info-item">
                            <label class="info-label" for="cv">Currículum Vitae (PDF)</label>
                            <input type="file" id="cv" name="cv"
                                   class="filter-input-text"
                                   accept=".pdf">
                            @if (!empty($estudiante->cv_link))
                                <span class="info-label" style="margin-top:4px;">
                                    CV actual: <a href="{{ $estudiante->cv_link }}" target="_blank" class="link-accion">ver CV</a>
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
                <div class="perfil-card-body">
                    <div class="perfil-grid">
 
                        <div class="info-item">
                            <label class="info-label" for="modalidad_deseada">Modalidad deseada</label>
                            <div class="select-wrapper">
                                <select id="modalidad_deseada" name="modalidad_deseada" class="filter-select">
                                    <option value="">Seleccionar...</option>
                                    @foreach(['Presencial', 'Remoto', 'Híbrido', 'Híbrido / Remoto'] as $m)
                                        <option value="{{ $m }}"
                                            {{ old('modalidad_deseada', $estudiante->modalidad_deseada ?? '') === $m ? 'selected' : '' }}>
                                            {{ $m }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
 
                        <div class="info-item">
                            <label class="info-label" for="puesto_interes">Puestos o áreas de interés</label>
                            <input type="text" id="puesto_interes" name="puesto_interes"
                                   class="filter-input-text"
                                   placeholder="Desarrollo Web, Backend..."
                                   value="{{ old('puesto_interes', $estudiante->puesto_interes ?? '') }}">
                        </div>
 
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <label class="info-label" for="disponibilidad">Disponibilidad horaria</label>
                            <input type="text" id="disponibilidad" name="disponibilidad"
                                   class="filter-input-text"
                                   placeholder="Lunes a Viernes, 9hs a 17hs"
                                   value="{{ old('disponibilidad', $estudiante->disponibilidad ?? '') }}">
                        </div>
 
                    </div>
                </div>
            </div>
 
            {{-- ── Habilidades e Idiomas ── --}}
            <div class="perfil-card">
                <div class="perfil-card-header">
                    <i class="bi bi-code-slash"></i> Habilidades e Idiomas
                </div>
                <div class="perfil-card-body">
                    <div class="perfil-grid">
 
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <label class="info-label" for="habilidades">
                                Habilidades y tecnologías <span class="config-muted">(separadas por coma)</span>
                            </label>
                            <input type="text" id="habilidades" name="habilidades"
                                   class="filter-input-text"
                                   placeholder="React, TypeScript, Node.js, SQL"
                                   value="{{ old('habilidades', $estudiante->habilidades ?? '') }}">
                        </div>
 
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <label class="info-label" for="idiomas">
                                Idiomas <span class="config-muted">(separados por coma)</span>
                            </label>
                            <input type="text" id="idiomas" name="idiomas"
                                   class="filter-input-text"
                                   placeholder="Español Nativo, Inglés Intermedio"
                                   value="{{ old('idiomas', $estudiante->idiomas ?? '') }}">
                        </div>
 
                    </div>
                </div>
            </div>
 
            {{-- ── Redes Profesionales ── --}}
            <div class="perfil-card">
                <div class="perfil-card-header">
                    <i class="bi bi-share"></i> Redes Profesionales
                </div>
                <div class="perfil-card-body">
                    <div class="perfil-grid">
 
                        <div class="info-item">
                            <label class="info-label" for="linkedin">LinkedIn</label>
                            <input type="url" id="linkedin" name="linkedin"
                                   class="filter-input-text"
                                   placeholder="https://linkedin.com/in/usuario"
                                   value="{{ old('linkedin', $estudiante->linkedin ?? '') }}">
                        </div>
 
                        <div class="info-item">
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
                <div class="perfil-card-body" style="flex-direction: row; justify-content: flex-end; gap: 12px;">
                    <a href="{{ route('estudiante.perfil') }}" class="btn-outline">
                        Cancelar
                    </a>
                    <button type="submit" class="btn-apply-filters">
                        <i class="bi bi-check-lg"></i> Guardar cambios
                    </button>
                </div>
            </div>
 
        </form>
 
    </div>{{-- /perfil-sections --}}
 
</div>{{-- /panel-page --}}
 
@endsection
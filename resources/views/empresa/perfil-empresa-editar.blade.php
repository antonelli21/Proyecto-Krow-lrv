@extends('layouts.app')

@section('title', 'Editar Perfil Empresa — KROW')

@section('content')

@php
    $usuario = auth()->user();
    $empresa = $usuario->empresa ?? null;
@endphp

<div class="panel-page">

    {{-- ══ HEADER ══ --}}
    <div class="perfil-header-card">
        <div class="perfil-header-inner">
            <div class="perfil-avatar">
                {{ strtoupper(substr($empresa->nombre ?? $usuario->name ?? 'E', 0, 1)) }}
            </div>
            <div class="perfil-header-info" style="flex: 1;">
                <h1 class="panel-page-title">Editar perfil</h1>
                <p class="panel-page-sub">{{ $empresa->nombre ?? $usuario->name ?? '' }}</p>
            </div>
            <a href="{{ route('empresa.perfil') }}" class="btn-outline">
                <i class="bi bi-arrow-left"></i> Volver al perfil
            </a>
        </div>
    </div>

    {{-- ══ FORMULARIO ══ --}}
    <div class="perfil-sections">

        <form action="{{ route('empresa.perfil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if (session('perfil_ok'))
                <div class="config-alert config-alert-success" style="margin-bottom:16px;">
                    <i class="bi bi-check-circle"></i> Perfil actualizado correctamente.
                </div>
            @endif

            {{-- ── Datos de la Empresa ── --}}
            <div class="perfil-card">
                <div class="perfil-card-header">
                    <i class="bi bi-building"></i> Datos de la Empresa
                </div>
                <div class="perfil-card-body" style="display: flex; flex-direction: column; gap: 16px;">
                    
                    {{-- Fila 1: Nombre y Rubro --}}
                    <div style="display: flex; gap: 16px; width: 100%;">
                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="nombre">Nombre de la empresa</label>
                            <input type="text" id="nombre" name="nombre"
                                   class="filter-input-text {{ $errors->has('nombre') ? 'input-error' : '' }}"
                                   value="{{ old('nombre', $empresa->nombre ?? '') }}" required>
                            @error('nombre') <span class="config-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="rubro">Rubro principal</label>
                            <input type="text" id="rubro" name="rubro"
                                   class="filter-input-text"
                                   placeholder="Software / Tecnología"
                                   value="{{ old('rubro', $empresa->rubro ?? '') }}">
                        </div>
                    </div>

                    {{-- Fila 2: Sitio Web (Ancho Completo) --}}
                    <div class="info-item" style="width: 100%;">
                        <label class="info-label" for="sitio_web">Sitio web</label>
                        <input type="url" id="sitio_web" name="sitio_web"
                               class="filter-input-text"
                               placeholder="https://miempresa.com"
                               value="{{ old('sitio_web', $empresa->sitio_web ?? '') }}">
                    </div>

                    {{-- Fila 3: Descripción (Ancho Completo) --}}
                    <div class="info-item" style="width: 100%;">
                        <label class="info-label" for="descripcion">Descripción de la organización</label>
                        <textarea id="descripcion" name="descripcion"
                                  class="filter-input-text"
                                  rows="4"
                                  placeholder="Describí brevemente a tu empresa...">{{ old('descripcion', $empresa->descripcion ?? '') }}</textarea>
                    </div>

                </div>
            </div>

            {{-- ── Ubicación ── --}}
            <div class="perfil-card">
                <div class="perfil-card-header">
                    <i class="bi bi-geo-alt"></i> Ubicación
                </div>
                <div class="perfil-card-body" style="display: flex; flex-direction: column; gap: 16px;">
                    
                    {{-- Fila 1: Dirección (Ancho Completo) --}}
                    <div class="info-item" style="width: 100%;">
                        <label class="info-label" for="direccion">Dirección</label>
                        <input type="text" id="direccion" name="direccion"
                               class="filter-input-text"
                               placeholder="Av. Siempreviva 742"
                               value="{{ old('direccion', $empresa->direccion ?? '') }}">
                    </div>

                    {{-- Fila 2: Localidad y Provincia --}}
                    <div style="display: flex; gap: 16px; width: 100%;">
                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="localidad">Localidad</label>
                            <input type="text" id="localidad" name="localidad"
                                   class="filter-input-text"
                                   value="{{ old('localidad', $empresa->localidad ?? '') }}">
                        </div>

                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="provincia">Provincia</label>
                            <input type="text" id="provincia" name="provincia"
                                   class="filter-input-text"
                                   value="{{ old('provincia', $empresa->provincia ?? '') }}">
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Contacto ── --}}
            <div class="perfil-card">
                <div class="perfil-card-header">
                    <i class="bi bi-envelope"></i> Contacto
                </div>
                <div class="perfil-card-body" style="display: flex; flex-direction: column; gap: 16px;">
                    
                    <div style="display: flex; gap: 16px; width: 100%;">
                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="email">Correo electrónico</label>
                            <input type="email" id="email" name="email"
                                   class="filter-input-text {{ $errors->has('email') ? 'input-error' : '' }}"
                                   value="{{ old('email', $usuario->email ?? '') }}" required>
                            @error('email') <span class="config-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="telefono">Teléfono de contacto</label>
                            <input type="text" id="telefono" name="telefono"
                                   class="filter-input-text"
                                   placeholder="+54 9 11 0000 0000"
                                   value="{{ old('telefono', $empresa->telefono ?? '') }}">
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Redes Sociales ── --}}
            <div class="perfil-card">
                <div class="perfil-card-header">
                    <i class="bi bi-share"></i> Redes Sociales
                </div>
                <div class="perfil-card-body" style="display: flex; flex-direction: column; gap: 16px;">
                    
                    <div style="display: flex; gap: 16px; width: 100%;">
                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="linkedin">LinkedIn</label>
                            <input type="url" id="linkedin" name="linkedin"
                                   class="filter-input-text"
                                   placeholder="https://linkedin.com/company/empresa"
                                   value="{{ old('linkedin', $empresa->linkedin ?? '') }}">
                        </div>

                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="facebook">Facebook</label>
                            <input type="url" id="facebook" name="facebook"
                                   class="filter-input-text"
                                   placeholder="https://facebook.com/empresa"
                                   value="{{ old('facebook', $empresa->facebook ?? '') }}">
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Acciones ── --}}
            <div class="perfil-card">
                <div class="perfil-card-body" style="display: flex; flex-direction: row; justify-content: flex-end; gap: 12px;">
                    <a href="{{ route('empresa.perfil') }}" class="btn-outline">
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

@endsection
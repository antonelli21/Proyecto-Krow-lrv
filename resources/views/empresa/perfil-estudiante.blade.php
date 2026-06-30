@extends('layouts.app')

@section('title', ($estudiante->nombre ?? 'Estudiante') . ' — Perfil')

@section('content')

@php
    $habilidades_array = $estudiante->habilidades->pluck('nombre')->toArray();
@endphp

<div class="panel-page">

  {{-- Header de perfil --}}
  <div class="perfil-header-card">
    <div class="perfil-header-inner">
      <div class="perfil-avatar" style="{{ $estudiante->foto_perfil ? 'background-image:url(\'' . \Illuminate\Support\Facades\Storage::url($estudiante->foto_perfil) . '\'); background-size:cover; background-position:center;' : '' }}">
        @if(!$estudiante->foto_perfil)
          {{ strtoupper(substr($estudiante->nombre ?? 'E', 0, 1)) }}
        @endif
      </div>
      <div class="perfil-header-info">
        <h1 class="panel-page-title" style="margin-bottom:2px;">{{ $estudiante->nombre }} {{ $estudiante->apellido }}</h1>
        <p class="panel-page-sub" style="margin-bottom:2px;">{{ $estudiante->carrera->nombre ?? 'Sin carrera' }}</p>
        <p class="panel-page-sub">Legajo: {{ $estudiante->legajo }}</p>
      </div>
      <a href="{{ url()->previous() }}" class="btn-outline">
        <i class="bi bi-arrow-left"></i> Volver
      </a>
    </div>
  </div>

  <div class="perfil-sections">
    <div class="perfil-column-main">

      {{-- Datos Personales --}}
      <div class="perfil-card">
        <div class="perfil-card-header">
          <i class="bi bi-person-circle"></i> Datos Personales
        </div>
        <div class="perfil-card-body">
          <div class="perfil-grid">
            <div class="info-item">
              <div class="info-label">Nombre completo</div>
              <div class="info-value">{{ $estudiante->nombre }} {{ $estudiante->apellido }}</div>
            </div>
            <div class="info-item">
              <div class="info-label">Edad</div>
              <div class="info-value">{{ $estudiante->fecha_nacimiento?->age ?? 'No especificada' }}</div>
            </div>
            <div class="info-item">
              <div class="info-label">Correo electrónico</div>
              <div class="info-value">{{ $estudiante->user->email ?? 'No especificado' }}</div>
            </div>
            <div class="info-item">
              <div class="info-label">Teléfono</div>
              <div class="info-value">{{ $estudiante->telefono ?? 'No especificado' }}</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Sobre mí --}}
      @if(!empty($estudiante->descripcion))
      <div class="perfil-card">
        <div class="perfil-card-header">
          <i class="bi bi-chat-quote"></i> Sobre mí
        </div>
        <div class="perfil-card-body">
          <p class="info-value">{{ $estudiante->descripcion }}</p>
        </div>
      </div>
      @endif

      {{-- Datos Académicos --}}
      <div class="perfil-card">
        <div class="perfil-card-header">
          <i class="bi bi-book"></i> Datos Académicos
        </div>
        <div class="perfil-card-body">
          <div class="perfil-grid">
            <div class="info-item">
              <div class="info-label">Carrera que cursa</div>
              <div class="info-value">{{ $estudiante->carrera->nombre ?? 'No especificada' }}</div>
            </div>
            <div class="info-item">
              <div class="info-label">Legajo universitario</div>
              <div class="info-value">{{ $estudiante->legajo ?? 'No especificado' }}</div>
            </div>
            <div class="info-item">
              <div class="info-label">Portafolio</div>
              <div class="info-value">
                @if(!empty($estudiante->portfolio))
                  <a href="{{ $estudiante->portfolio }}" target="_blank" class="link-accion">
                    <i class="bi bi-box-arrow-up-right"></i> Ver portafolio
                  </a>
                @else
                  <span class="text-muted">No cargado</span>
                @endif
              </div>
            </div>
            <div class="info-item">
              <div class="info-label">Currículum Vitae</div>
              <div class="info-value">
                @if(!empty($estudiante->cv))
                  <a href="{{ \Illuminate\Support\Facades\Storage::url($estudiante->cv) }}" target="_blank" class="link-accion">
                    <i class="bi bi-file-earmark-text"></i> Ver CV
                  </a>
                @else
                  <span class="text-muted">No cargado</span>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Preferencias Laborales --}}
      <div class="perfil-card">
        <div class="perfil-card-header">
          <i class="bi bi-briefcase"></i> Preferencias Laborales
        </div>
        <div class="perfil-card-body">
          <div class="perfil-grid">
            <div class="info-item">
              <div class="info-label">Modalidad deseada</div>
              <div class="info-value">{{ $estudiante->modalidad_deseada ?? 'No especificada' }}</div>
            </div>
            <div class="info-item" style="grid-column: 1 / -1;">
              <div class="info-label">Disponibilidad</div>
              <div class="info-value">{{ $estudiante->disponibilidad_horaria ?? 'No especificada' }}</div>
            </div>
          </div>
        </div>
      </div>
    </div> {{-- fin perfil-column-main --}}

    <div class="perfil-column-sidebar">
      {{-- Ubicación --}}
      <div class="perfil-card">
        <div class="perfil-card-header">
          <i class="bi bi-geo-alt"></i> Ubicación
        </div>
        <div class="perfil-card-body">
          <div class="perfil-grid">
            <div class="info-item">
              <div class="info-label">Localidad / Provincia</div>
              <div class="info-value">{{ $estudiante->localidad->nombre ?? 'No especificada' }} — {{ $estudiante->provincia->nombre ?? 'No especificada' }}</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Habilidades --}}
      <div class="perfil-card">
        <div class="perfil-card-header">
          <i class="bi bi-code-slash"></i> Habilidades
        </div>
        <div class="perfil-card-body">
          <div class="detalle-tags">
            @forelse($habilidades_array as $hab)
              <span class="detalle-tag">{{ $hab }}</span>
            @empty
              <span class="text-muted">No especificadas</span>
            @endforelse
          </div>
        </div>
      </div>

      {{-- Redes Profesionales --}}
      <div class="perfil-card">
        <div class="perfil-card-header">
          <i class="bi bi-share"></i> Redes Profesionales
        </div>
        <div class="perfil-card-body">
          <div class="perfil-grid">
            <div class="info-item">
              <div class="info-label">LinkedIn</div>
              <div class="info-value">
                @if(!empty($estudiante->linkedin))
                  <a href="{{ $estudiante->linkedin }}" target="_blank" class="link-accion">
                    <i class="bi bi-linkedin"></i> {{ $estudiante->linkedin }}
                  </a>
                @else
                  <span class="text-muted">No agregado</span>
                @endif
              </div>
            </div>
            <div class="info-item">
              <div class="info-label">GitHub</div>
              <div class="info-value">
                @if(!empty($estudiante->github))
                  <a href="{{ $estudiante->github }}" target="_blank" class="link-accion">
                    <i class="bi bi-github"></i> {{ $estudiante->github }}
                  </a>
                @else
                  <span class="text-muted">No agregado</span>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> {{-- fin perfil-column-sidebar --}}

  </div>
</div>

@endsection

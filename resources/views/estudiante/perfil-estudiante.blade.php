@extends('layouts.app')

@section('title', 'Mi Perfil — KROW')

@section('content')

@php
$usuario = [
    'nombre'   => 'Juan Pérez',
    'email'    => 'juan.perez@example.com',
    'telefono' => '+54 9 11 1234 5678',
];

$estudiante = [
    'legajo'             => '173456',
    'dni'                => '40123456',
    'edad'               => 22,
    'carrera'            => 'Ing. en Sistemas de Información',
    'modalidad_deseada'  => 'Híbrido / Remoto',
    'puesto_interes'     => 'Desarrollo Web, Backend',
    'disponibilidad'     => 'Lunes a Viernes, 9hs a 17hs',
    'habilidades'        => 'React, TypeScript, Node.js, SQL',
    'idiomas'            => 'Español Nativo, Inglés Intermedio',
    'linkedin'           => 'https://linkedin.com/in/juanperez',
    'github'             => 'https://github.com/juanperez',
    'portafolio'         => '',
    'cv_link'            => '',
];

$habilidades_array = !empty($estudiante['habilidades'])
    ? array_map('trim', explode(',', $estudiante['habilidades'])) : [];
$idiomas_array = !empty($estudiante['idiomas'])
    ? array_map('trim', explode(',', $estudiante['idiomas'])) : [];
@endphp

<div class="panel-page">

  {{-- Header de perfil --}}
  <div class="perfil-header-card">
    <div class="perfil-header-inner">
      <div class="perfil-avatar">
        {{ strtoupper(substr($usuario['nombre'], 0, 1)) }}
      </div>
      <div class="perfil-header-info">
        <h1 class="panel-page-title" style="margin-bottom:2px;">{{ $usuario['nombre'] }}</h1>
        <p class="panel-page-sub" style="margin-bottom:2px;">{{ $estudiante['carrera'] }}</p>
        <p class="panel-page-sub">Legajo: {{ $estudiante['legajo'] }}</p>
      </div>
      <a href="{{ route('configuracion') }}" class="btn-accent">
        <i class="bi bi-pencil"></i> Editar perfil
      </a>    
    </div>
  </div>

  <div class="perfil-sections">

    {{-- Datos Personales --}}
    <div class="perfil-card">
      <div class="perfil-card-header">
        <i class="bi bi-person-circle"></i> Datos Personales
      </div>
      <div class="perfil-card-body">
        <div class="perfil-grid">
          <div class="info-item">
            <div class="info-label">Nombre completo</div>
            <div class="info-value">{{ $usuario['nombre'] }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Edad</div>
            <div class="info-value">{{ $estudiante['edad'] ?? 'No especificada' }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">DNI</div>
            <div class="info-value">{{ $estudiante['dni'] ?? 'No especificado' }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Correo electrónico</div>
            <div class="info-value">{{ $usuario['email'] }}</div>
          </div>
          <div class="info-item" style="grid-column: 1 / -1;">
            <div class="info-label">Teléfono</div>
            <div class="info-value">{{ $usuario['telefono'] ?? 'No especificado' }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Datos Académicos --}}
    <div class="perfil-card">
      <div class="perfil-card-header">
        <i class="bi bi-book"></i> Datos Académicos
      </div>
      <div class="perfil-card-body">
        <div class="perfil-grid">
          <div class="info-item">
            <div class="info-label">Carrera que cursa</div>
            <div class="info-value">{{ $estudiante['carrera'] ?? 'No especificada' }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Legajo universitario</div>
            <div class="info-value">{{ $estudiante['legajo'] ?? 'No especificado' }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Portafolio</div>
            <div class="info-value">
              @if(!empty($estudiante['portafolio']))
                <a href="{{ $estudiante['portafolio'] }}" target="_blank" class="link-accion">
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
              @if(!empty($estudiante['cv_link']))
                <a href="{{ $estudiante['cv_link'] }}" target="_blank" class="link-accion">
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
            <div class="info-value">{{ $estudiante['modalidad_deseada'] ?? 'No especificada' }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Puestos o áreas de interés</div>
            <div class="info-value">{{ $estudiante['puesto_interes'] ?? 'No especificado' }}</div>
          </div>
          <div class="info-item" style="grid-column: 1 / -1;">
            <div class="info-label">Disponibilidad</div>
            <div class="info-value">{{ $estudiante['disponibilidad'] ?? 'No especificada' }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Habilidades e Idiomas --}}
    <div class="perfil-card">
      <div class="perfil-card-header">
        <i class="bi bi-code-slash"></i> Habilidades e Idiomas
      </div>
      <div class="perfil-card-body">
        <p class="info-label" style="margin-bottom:10px;"><i class="bi bi-tools"></i> Habilidades y tecnologías</p>
        <div class="detalle-tags" style="margin-bottom:20px;">
          @forelse($habilidades_array as $hab)
            <span class="detalle-tag">{{ $hab }}</span>
          @empty
            <span class="text-muted">No especificadas</span>
          @endforelse
        </div>

        <hr style="border-color:var(--border); margin-bottom:16px;">

        <p class="info-label" style="margin-bottom:10px;"><i class="bi bi-translate"></i> Idiomas</p>
        <div class="detalle-tags">
          @forelse($idiomas_array as $idioma)
            <span class="detalle-tag">{{ $idioma }}</span>
          @empty
            <span class="text-muted">No especificados</span>
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
              @if(!empty($estudiante['linkedin']))
                <a href="{{ $estudiante['linkedin'] }}" target="_blank" class="link-accion">
                  <i class="bi bi-linkedin"></i> {{ $estudiante['linkedin'] }}
                </a>
              @else
                <span class="text-muted">No agregado</span>
              @endif
            </div>
          </div>
          <div class="info-item">
            <div class="info-label">GitHub</div>
            <div class="info-value">
              @if(!empty($estudiante['github']))
                <a href="{{ $estudiante['github'] }}" target="_blank" class="link-accion">
                  <i class="bi bi-github"></i> {{ $estudiante['github'] }}
                </a>
              @else
                <span class="text-muted">No agregado</span>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

@endsection
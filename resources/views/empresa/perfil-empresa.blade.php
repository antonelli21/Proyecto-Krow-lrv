@extends('layouts.app')

@section('title', 'Perfil Empresa - Krow')

@section('content')

@php
    $empresa = $empresa ?? [
        'nombre'      => 'ACME S.A.',
        'rubro'       => 'Software / Tecnología',
        'email'       => 'contacto@acme.example',
        'telefono'    => '+54 9 11 9876 5432',
        'direccion'   => 'Av. Siempreviva 742',
        'localidad'   => 'Ciudad Ficticia',
        'provincia'   => 'Provincia Ejemplo',
        'sitio_web'   => 'https://acme.example',
        'descripcion' => 'Empresa dedicada a soluciones de software y consultoría tecnológica.',
        'linkedin'    => 'https://linkedin.com/company/acme',
        'facebook'    => '',
    ];

    $ofertas = $ofertas ?? [
        ['titulo' => 'Desarrollador Full Stack', 'modalidad' => 'Híbrido', 'salario' => 'AR$ 250.000'],
        ['titulo' => 'Analista QA',              'modalidad' => 'Remoto',  'salario' => 'AR$ 180.000'],
    ];
@endphp

<div class="panel-page">

    {{-- HEADER --}}
    <div class="perfil-header-card">
        <div class="perfil-header-inner">

            <div class="perfil-avatar">
                {{ strtoupper(substr($empresa['nombre'] ?? 'E', 0, 1)) }}
            </div>

            <div class="perfil-header-info" style="flex: 1;">
                <h1 class="panel-page-title">{{ $empresa['nombre'] ?? '' }}</h1>
                <p class="panel-page-sub" style="margin-bottom: 2px;">{{ $empresa['rubro'] ?? '' }}</p>
                <p class="panel-page-sub">{{ $empresa['direccion'] ?? '' }} — {{ $empresa['localidad'] ?? '' }}</p>
            </div>

            <div style="display: flex; flex-direction: column; gap: 10px; min-width: 160px;">
                <a href="{{ route('empresa.perfil.editar') }}" class="btn-accent">
                    <i class="fas fa-edit"></i> Editar perfil
                </a>
                <a href="{{ url('empresa/ofertas') }}" class="btn-outline" style="justify-content: center; text-align: center;">
                    <i class="fas fa-eye"></i> Ver ofertas
                </a>
            </div>

        </div>
    </div>

    {{-- SECCIONES --}}
    <div class="perfil-sections">

        <div class="perfil-card">
            <div class="perfil-card-header">
                <i class="fas fa-building"></i> Datos de la Empresa
            </div>
            <div class="perfil-grid">
                <div class="info-item">
                    <div class="info-label">Rubro principal</div>
                    <div class="info-value">{{ $empresa['rubro'] ?? 'No especificado' }}</div>
                </div>
                <div class="info-item" style="grid-column: 1 / -1;">
                    <div class="info-label">Descripción de la organización</div>
                    <div class="info-value">{{ $empresa['descripcion'] ?? 'Sin descripción' }}</div>
                </div>
            </div>
        </div>

        <div class="perfil-card">
            <div class="perfil-card-header">
                <i class="fas fa-map-marker-alt"></i> Ubicación
            </div>
            <div class="perfil-grid">
                <div class="info-item">
                    <div class="info-label">Dirección</div>
                    <div class="info-value">{{ $empresa['direccion'] ?? 'No especificada' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Localidad / Provincia</div>
                    <div class="info-value">{{ $empresa['localidad'] ?? '' }} — {{ $empresa['provincia'] ?? '' }}</div>
                </div>
            </div>
        </div>

        <div class="perfil-card">
            <div class="perfil-card-header">
                <i class="fas fa-briefcase"></i> Ofertas publicadas
            </div>
            @if (!empty($ofertas))
                <div class="perfil-grid">
                    @foreach ($ofertas as $of)
                        <div class="info-item" style="border-left-color: var(--accent);">
                            <div class="info-label">{{ $of['modalidad'] ?? 'No especificada' }}</div>
                            <div class="info-value" style="font-weight: 700;">{{ $of['titulo'] ?? '' }}</div>
                            <div class="info-label" style="color: var(--accent); margin-top: 4px;">
                                {{ $of['salario'] ?? '' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="info-item" style="grid-column: 1 / -1;">
                    <div class="info-value">No hay ofertas publicadas actualmente.</div>
                </div>
            @endif
        </div>

        {{-- Contacto y Redes --}}
        <div class="perfil-card">
            <div class="perfil-card-header">
                <i class="fas fa-address-book"></i> Contacto y Redes Profesionales
            </div>
            <div class="perfil-grid">

                {{-- Correo Electrónico Corporativo como Link Plateado --}}
                <div class="info-item {{ !empty($empresa['email']) ? 'info-item-link' : '' }}">
                    <div class="info-label">Correo electrónico</div>
                    <div class="info-value">
                        @if(!empty($empresa['email']))
                            <a href="mailto:{{ $empresa['email'] }}?subject=Contacto Institucional desde KROW" class="link-accion">
                                <i class="fas fa-envelope"></i> Enviar correo electrónico
                            </a>
                        @else
                            <span class="text-muted">No especificado</span>
                        @endif
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Teléfono de contacto</div>
                    <div class="info-value">{{ $empresa['telefono'] ?? 'No especificado' }}</div>
                </div>

                <div class="info-item {{ !empty($empresa['sitio_web']) ? 'info-item-link' : '' }}">
                    <div class="info-label">Sitio web oficial</div>
                    <div class="info-value">
                        @if (!empty($empresa['sitio_web']))
                            <a href="{{ $empresa['sitio_web'] }}" target="_blank" class="link-accion">
                                <i class="fas fa-external-link-alt fa-sm"></i> Visitar sitio web
                            </a>
                        @else
                            <span class="text-muted">No cargado</span>
                        @endif
                    </div>
                </div>

                <div class="info-item {{ !empty($empresa['linkedin']) ? 'info-item-link' : '' }}">
                    <div class="info-label">LinkedIn</div>
                    <div class="info-value">
                        @if (!empty($empresa['linkedin']))
                            <a href="{{ $empresa['linkedin'] }}" target="_blank" class="link-accion">
                                <i class="fab fa-linkedin"></i> Ver perfil corporativo
                            </a>
                        @else
                            <span class="text-muted">No agregado</span>
                        @endif
                    </div>
                </div>

                <div class="info-item {{ !empty($empresa['facebook']) ? 'info-item-link' : '' }}" style="grid-column: 1 / -1;">
                    <div class="info-label">Facebook</div>
                    <div class="info-value">
                        @if (!empty($empresa['facebook']))
                            <a href="{{ $empresa['facebook'] }}" target="_blank" class="link-accion">
                                <i class="fab fa-facebook"></i> Ver página oficial
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

@endsection
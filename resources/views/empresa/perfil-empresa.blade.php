@extends('layouts.app')

@section('title', 'Perfil Empresa - Krow')

@section('content')

<div class="panel-page">

    {{-- HEADER --}}
    <div class="perfil-header-card">
        <div class="perfil-header-inner">

            <div class="perfil-avatar" style="{{ $empresa->logo ? 'background-image:url(\'' . \Illuminate\Support\Facades\Storage::url($empresa->logo) . '\'); background-size:cover; background-position:center;' : '' }}">
                @if(!$empresa->logo)
                    {{ strtoupper(substr($empresa->nombre_empresa ?? 'E', 0, 1)) }}
                @endif
            </div>

            <div class="perfil-header-info" style="flex: 1;">
                <h1 class="panel-page-title">{{ $empresa->nombre_empresa ?? '' }}</h1>
                <p class="panel-page-sub" style="margin-bottom: 2px;">{{ $empresa->rubro ?? '' }}</p>
                <p class="panel-page-sub">{{ $empresa->direccion ?? '' }} — {{ $empresa->localidad->nombre ?? '' }}</p>
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
                    <div class="info-value">{{ $empresa->rubro ?? 'No especificado' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Razón social</div>
                    <div class="info-value">{{ $empresa->razon_social ?? 'No especificada' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">CUIT</div>
                    <div class="info-value">{{ $empresa->cuit ?? 'No especificado' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tamaño de la empresa</div>
                    <div class="info-value">{{ $empresa->tamano_empresa ?? 'No especificado' }}</div>
                </div>
                <div class="info-item" style="grid-column: 1 / -1;">
                    <div class="info-label">Descripción de la organización</div>
                    <div class="info-value">{{ $empresa->descripcion ?? 'Sin descripción' }}</div>
                </div>
            </div>
        </div>

        <div class="perfil-card">
            <div class="perfil-card-header">
                <i class="fas fa-id-badge"></i> Representante
            </div>
            <div class="perfil-grid">
                <div class="info-item">
                    <div class="info-label">Nombre</div>
                    <div class="info-value">{{ $empresa->representante ?? 'No especificado' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $empresa->email_representante ?? 'No especificado' }}</div>
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
                    <div class="info-value">{{ $empresa->direccion ?? 'No especificada' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Localidad / Provincia</div>
                    <div class="info-value">{{ $empresa->localidad->nombre ?? '' }} — {{ $empresa->provincia->nombre ?? '' }}</div>
                </div>
            </div>
        </div>

        <div class="perfil-card">
            <div class="perfil-card-header">
                <i class="fas fa-briefcase"></i> Ofertas publicadas
            </div>
            @if ($ofertas->isNotEmpty())
                <div class="perfil-grid">
                    @foreach ($ofertas as $of)
                        <div class="info-item" style="border-left-color: var(--accent);">
                            <div class="info-label">{{ $of->modalidad ?? 'No especificada' }}</div>
                            <div class="info-value" style="font-weight: 700;">{{ $of->titulo ?? '' }}</div>
                            <div class="info-label" style="color: var(--accent); margin-top: 4px;">
                                @if($of->salario_min)
                                    AR$ {{ number_format($of->salario_min, 0, ',', '.') }}
                                @endif
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

                <div class="info-item {{ !empty($empresa->email_contacto) ? 'info-item-link' : '' }}">
                    <div class="info-label">Correo electrónico</div>
                    <div class="info-value">
                        @if(!empty($empresa->email_contacto))
                            <a href="mailto:{{ $empresa->email_contacto }}?subject=Contacto Institucional desde KROW" class="link-accion">
                                <i class="fas fa-envelope"></i> Enviar correo electrónico
                            </a>
                        @else
                            <span class="text-muted">No especificado</span>
                        @endif
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Teléfono de contacto</div>
                    <div class="info-value">{{ $empresa->telefono ?? 'No especificado' }}</div>
                </div>

                <div class="info-item {{ !empty($empresa->sitio_web) ? 'info-item-link' : '' }}">
                    <div class="info-label">Sitio web oficial</div>
                    <div class="info-value">
                        @if (!empty($empresa->sitio_web))
                            <a href="{{ $empresa->sitio_web }}" target="_blank" class="link-accion">
                                <i class="fas fa-external-link-alt fa-sm"></i> Visitar sitio web
                            </a>
                        @else
                            <span class="text-muted">No cargado</span>
                        @endif
                    </div>
                </div>

                <div class="info-item {{ !empty($empresa->linkedin) ? 'info-item-link' : '' }}">
                    <div class="info-label">LinkedIn</div>
                    <div class="info-value">
                        @if (!empty($empresa->linkedin))
                            <a href="{{ $empresa->linkedin }}" target="_blank" class="link-accion">
                                <i class="fab fa-linkedin"></i> Ver perfil corporativo
                            </a>
                        @else
                            <span class="text-muted">No agregado</span>
                        @endif
                    </div>
                </div>

                <div class="info-item {{ !empty($empresa->facebook) ? 'info-item-link' : '' }}">
                    <div class="info-label">Facebook</div>
                    <div class="info-value">
                        @if (!empty($empresa->facebook))
                            <a href="{{ $empresa->facebook }}" target="_blank" class="link-accion">
                                <i class="fab fa-facebook"></i> Ver página oficial
                            </a>
                        @else
                            <span class="text-muted">No agregado</span>
                        @endif
                    </div>
                </div>

                <div class="info-item {{ !empty($empresa->instagram) ? 'info-item-link' : '' }}">
                    <div class="info-label">Instagram</div>
                    <div class="info-value">
                        @if (!empty($empresa->instagram))
                            <a href="{{ $empresa->instagram }}" target="_blank" class="link-accion">
                                <i class="fab fa-instagram"></i> Ver perfil
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
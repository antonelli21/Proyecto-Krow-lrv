@extends('layouts.app')

@section('title', 'Perfil Empresa - Krow')

@section('banner')
<div style="width:100%; height:380px; position:relative; overflow:hidden;">

    {{-- Imagen o placeholder --}}
    @if($empresa->banner)
        <img src="{{ \Illuminate\Support\Facades\Storage::url($empresa->banner) }}"
            alt="Banner"
            style="width:100%; height:100%; object-fit:cover; object-position:center; display:block; image-rendering:auto;">
    @else
        <div style="width:100%; height:100%; background:linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);"></div>
    @endif

    {{-- Degradado solo en la parte inferior --}}
    <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,0.80) 0%, rgba(0,0,0,0.3) 40%, transparent 70%);"></div>

    {{-- Contenido pegado al fondo, centrado horizontalmente --}}
    <div style="position:absolute; bottom:0; left:50%; transform:translateX(-50%); z-index:2; display:flex; flex-direction:column; align-items:center; text-align:center; padding-bottom:16px; gap:6px; width:100%;">

        {{-- Logo circular --}}
        <div style="width:84px; height:84px; border-radius:50%; border:3px solid rgba(255,255,255,0.35); overflow:hidden; background:var(--surface); display:flex; align-items:center; justify-content:center; font-size:2rem; font-weight:700; color:var(--accent); flex-shrink:0;
            {{ $empresa->logo ? 'background-image:url(\'' . \Illuminate\Support\Facades\Storage::url($empresa->logo) . '\'); background-size:cover; background-position:center;' : '' }}">
            @if(!$empresa->logo)
                {{ strtoupper(substr($empresa->nombre_empresa ?? 'E', 0, 1)) }}
            @endif
        </div>

        {{-- Nombre --}}
        <h1 style="color:#fff; font-size:1.5rem; font-weight:800; margin:0; text-shadow:0 2px 8px rgba(0,0,0,0.7); line-height:1.2;">
            {{ $empresa->nombre_empresa ?? '' }}
        </h1>

        {{-- Rubro --}}
        @if(!empty($empresa->rubro))
        <p style="color:rgba(255,255,255,0.8); font-size:0.9rem; margin:0; text-shadow:0 1px 4px rgba(0,0,0,0.6);">
            {{ $empresa->rubro }}
        </p>
        @endif

        {{-- Botones --}}
        <div style="display:flex; gap:10px; flex-wrap:wrap; justify-content:center; margin-top:4px;">
            <a href="{{ route('empresa.perfil.editar') }}" class="btn-accent" style="font-size:0.82rem; padding:6px 16px;">
                <i class="fas fa-edit"></i> Editar perfil
            </a>
            <a href="{{ route('inicio', ['empresa_id' => $empresa->id_empresa]) }}"
            class="empresa-btn empresa-btn--ofertas btn-outline"
            style="font-size:0.82rem; padding:6px 16px; color:#fff; border-color:rgba(255,255,255,0.4);">
                <i class="fas fa-eye"></i> Ver ofertas
            </a>
        </div>

    </div>
</div>
@endsection

@section('content')

<style>
.empresa-header-body {
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
}
.perfil-sections {
    width: 100% !important;
}
</style>

<style>
.empresa-header-banner {
    position: relative;
    width: calc(100% + 48px);
    margin-left: -24px;
    margin-right: -24px;
    margin-top: -24px;
    height: 320px;
    background: var(--surface);
    border-radius: var(--radius) var(--radius) 0 0;
    overflow: hidden;
    border: 1px solid var(--border);
    border-bottom: none;
}
.empresa-banner-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.empresa-banner-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--surface) 0%, var(--border) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--muted);
    font-size: 0.85rem;
}
.empresa-header-body {
    background: var(--surface);
    border: 1px solid var(--border);
    border-top: none;
    border-radius: 0 0 var(--radius) var(--radius);
    padding: 0 24px 24px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    margin-bottom: 24px;
}
.empresa-logo-wrap {
    margin-top: -44px;
    margin-bottom: 12px;
    position: relative;
    z-index: 1;
}
.empresa-logo-circle {
    width: 88px;
    height: 88px;
    border-radius: 50%;
    border: 4px solid var(--surface);
    background: var(--bg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
    color: var(--accent);
    overflow: hidden;
    background-size: cover;
    background-position: center;
}
.empresa-header-actions {
    display: flex;
    gap: 10px;
    margin-top: 16px;
    flex-wrap: wrap;
    justify-content: center;
}
</style>

<div class="panel-page">

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
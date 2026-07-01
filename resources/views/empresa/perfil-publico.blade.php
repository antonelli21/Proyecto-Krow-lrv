@extends('layouts.app')

@section('title', ($empresa->nombre_empresa ?? 'Empresa') . ' — KROW')

@section('banner')
<div style="width:100%; height:320px; position:sticky; top:0; overflow:hidden; z-index:50;">

    {{-- Imagen o placeholder --}}
    @if($empresa->banner)
        <img src="{{ \Illuminate\Support\Facades\Storage::url($empresa->banner) }}"
             alt="Banner" style="width:100%; height:100%; object-fit:cover; display:block;">
    @else
        <div style="width:100%; height:100%; background:linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);"></div>
    @endif

    {{-- Degradado solo en la parte inferior --}}
    <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,0.80) 0%, rgba(0,0,0,0.3) 40%, transparent 70%);"></div>

    {{-- Contenido pegado al fondo --}}
    <div style="position:absolute; bottom:0; left:50%; transform:translateX(-50%); z-index:2; display:flex; flex-direction:column; align-items:center; text-align:center; padding-bottom:16px; gap:6px; width:100%;">

        {{-- Logo --}}
        <div class="{{ $empresa->logo ? 'avatar-expandible' : '' }}"
             style="width:84px; height:84px; border-radius:50%; border:3px solid rgba(255,255,255,0.35); overflow:hidden; background:var(--surface); display:flex; align-items:center; justify-content:center; font-size:2rem; font-weight:700; color:var(--accent); flex-shrink:0;
            {{ $empresa->logo ? 'background-image:url(\'' . \Illuminate\Support\Facades\Storage::url($empresa->logo) . '\'); background-size:cover; background-position:center;' : '' }}"
             @if($empresa->logo) onclick="abrirModalAvatar('{{ \Illuminate\Support\Facades\Storage::url($empresa->logo) }}')" @endif>
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

        {{-- Botón volver --}}
        <div style="margin-top:4px;">
            <a href="{{ url()->previous() }}" class="btn-outline" style="font-size:0.82rem; padding:6px 16px; color:#fff; border-color:rgba(255,255,255,0.4);">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

    </div>
</div>
@endsection

@section('content')

<style>
main {
    overflow: visible !important;
}
</style>

<div class="panel-page">

    <div class="perfil-sections">
        <div class="perfil-column-main">
            {{-- Datos de la Empresa --}}
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
                        <div class="info-label">Tamaño</div>
                        <div class="info-value">{{ $empresa->tamano_empresa ?? 'No especificado' }}</div>
                    </div>
                    @if(!empty($empresa->descripcion))
                    <div class="info-item" style="grid-column: 1 / -1;">
                        <div class="info-label">Descripción</div>
                        <div class="info-value">{{ $empresa->descripcion }}</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Ofertas activas --}}
            <div class="perfil-card">
                <div class="perfil-card-header">
                    <i class="fas fa-briefcase"></i> Ofertas activas
                </div>
                @if($ofertas->isNotEmpty())
                    <div class="perfil-grid">
                        @foreach($ofertas as $oferta)
                            <a href="{{ route('ofertas.detalle', $oferta->id_oferta) }}"
                               style="text-decoration:none;">
                                <div class="info-item" style="border-left-color:var(--accent); cursor:pointer; transition: opacity .15s;" onmouseover="this.style.opacity='.8'" onmouseout="this.style.opacity='1'">
                                    <div class="info-label">{{ $oferta->modalidad ?? '' }} · {{ $oferta->tipo_oferta ?? '' }}</div>
                                    <div class="info-value" style="font-weight:700;">{{ $oferta->titulo }}</div>
                                    <div style="color:var(--accent); font-size:0.85rem; margin-top:4px;">
                                        @if($oferta->salario_min)
                                            AR$ {{ number_format($oferta->salario_min, 0, ',', '.') }}
                                            @if($oferta->salario_max)
                                                — AR$ {{ number_format($oferta->salario_max, 0, ',', '.') }}
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="info-item">
                        <div class="info-value" style="color:var(--muted);">No hay ofertas activas en este momento.</div>
                    </div>
                @endif
            </div>
        </div> {{-- fin perfil-column-main --}}

        <div class="perfil-column-sidebar">
            {{-- Ubicación --}}
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

            {{-- Contacto y Redes --}}
            <div class="perfil-card">
                <div class="perfil-card-header">
                    <i class="fas fa-address-book"></i> Contacto y Redes
                </div>
                <div class="perfil-grid">

                    <div class="info-item info-item-link">
                        <div class="info-label">Correo electrónico</div>
                        <div class="info-value">
                            @if(!empty($empresa->email_contacto))
                                <a href="mailto:{{ $empresa->email_contacto }}" class="link-accion">
                                    <i class="fas fa-envelope"></i> Enviar correo
                                </a>
                            @else
                                <span class="text-muted">No especificado</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Teléfono</div>
                        <div class="info-value">{{ $empresa->telefono ?? 'No especificado' }}</div>
                    </div>

                    <div class="info-item info-item-link">
                        <div class="info-label">Sitio web</div>
                        <div class="info-value">
                            @if(!empty($empresa->sitio_web))
                                <a href="{{ $empresa->sitio_web }}" target="_blank" class="link-accion">
                                    <i class="fas fa-external-link-alt"></i> Visitar sitio web
                                </a>
                            @else
                                <span class="text-muted">No cargado</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-item info-item-link">
                        <div class="info-label">LinkedIn</div>
                        <div class="info-value">
                            @if(!empty($empresa->linkedin))
                                <a href="{{ $empresa->linkedin }}" target="_blank" class="link-accion">
                                    <i class="fab fa-linkedin"></i> Ver perfil
                                </a>
                            @else
                                <span class="text-muted">No agregado</span>
                            @endif
                        </div>
                    </div>

                    @if(!empty($empresa->instagram))
                    <div class="info-item info-item-link">
                        <div class="info-label">Instagram</div>
                        <div class="info-value">
                            <a href="{{ $empresa->instagram }}" target="_blank" class="link-accion">
                                <i class="fab fa-instagram"></i> Ver perfil
                            </a>
                        </div>
                    </div>
                    @endif

                    @if(!empty($empresa->facebook))
                    <div class="info-item info-item-link">
                        <div class="info-label">Facebook</div>
                        <div class="info-value">
                            <a href="{{ $empresa->facebook }}" target="_blank" class="link-accion">
                                <i class="fab fa-facebook"></i> Ver página
                            </a>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div> {{-- fin perfil-column-sidebar --}}
    </div>

</div>
@endsection
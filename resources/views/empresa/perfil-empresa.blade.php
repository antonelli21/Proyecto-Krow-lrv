@extends('layouts.app')

@section('title', 'Perfil Empresa - Krow')

@section('banner')
{{-- Banner: en flujo normal, NO fixed, NO sticky. Scrollea como el resto de la página. --}}
<div style="width:100%; max-width:1600px; margin:0 auto;">
    <div style="width:100%; height:260px; overflow:hidden;">
        @if($empresa->banner)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($empresa->banner) }}"
                 alt="Banner" style="width:100%; height:100%; object-fit:cover; display:block;">
        @else
            <div style="width:100%; height:100%; background:linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);"></div>
        @endif
    </div>
</div>
@endsection

@section('content')

{{-- panel-page con margin-top negativo: sube el header de perfil para que solape el borde inferior del banner --}}
<div class="panel-page" style="margin-top: -70px !important; margin-bottom:80px;background-color:var(--bg) ;opacity: 0.95; border-radius: 8px; border:1px solid var(--accent); justify-content:start;box-shadow:
0 20px 50px var(--shadow-color),
0 0px 30px var(--shadow-glow);">
<div class="panel-page">

  {{-- Header de perfil: mismas clases que ya usás en el resto del sitio --}}
  <div class="perfil-header-card">
    <div class="perfil-header-inner">
      <div class="perfil-avatar {{ $empresa->logo ? 'avatar-expandible' : '' }}"
           style="{{ $empresa->logo ? 'background-image:url(\'' . \Illuminate\Support\Facades\Storage::url($empresa->logo) . '\'); background-size:cover; background-position:center;' : '' }}"
           @if($empresa->logo) onclick="abrirModalAvatar('{{ \Illuminate\Support\Facades\Storage::url($empresa->logo) }}')" @endif>
        @if(!$empresa->logo)
          {{ strtoupper(substr($empresa->nombre_empresa ?? 'E', 0, 1)) }}
        @endif
      </div>
      <div class="perfil-header-info">
        <h1 class="panel-page-title" style="margin-bottom:2px;">{{ $empresa->nombre_empresa ?? '' }}</h1>
        @if(!empty($empresa->rubro))
        <p class="panel-page-sub" style="margin-bottom:2px;">{{ $empresa->rubro }}</p>
        @endif
      </div>
      <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <a href="{{ route('empresa.perfil.editar') }}" class="btn-accent">
          <i class="fas fa-edit"></i> Editar perfil
        </a>
        <a href="{{ route('inicio', ['empresa_id' => $empresa->id_empresa]) }}" class="btn-outline">
          <i class="fas fa-eye"></i> Ver ofertas
        </a>
      </div>
    </div>
  </div>

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
            <div class="info-value" style="word-break:break-word; overflow-wrap:break-word; overflow:hidden;">{{ $empresa->descripcion ?? 'Sin descripción' }}</div>
          </div>
        </div>
      </div>

      {{-- Representante --}}
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
    </div> {{-- fin perfil-column-main --}}

    <div class="perfil-column-sidebar">
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
              @if(!empty($empresa->sitio_web))
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
              @if(!empty($empresa->linkedin))
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
              @if(!empty($empresa->facebook))
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
              @if(!empty($empresa->instagram))
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
    </div> {{-- fin perfil-column-sidebar --}}

  </div>
</div>
</div>
@endsection
@extends('layouts.app')

@section('title', ($empresa->nombre_empresa ?? 'Empresa') . ' — KROW')

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
        @if(!empty($empresa->tamano_empresa))
        <p class="panel-page-sub">{{ $empresa->tamano_empresa }}</p>
        @endif
      </div>
      <a href="{{ url()->previous() }}" class="btn-outline">
        <i class="bi bi-arrow-left"></i> Volver
      </a>
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
            <div class="info-label">Tamaño</div>
            <div class="info-value">{{ $empresa->tamano_empresa ?? 'No especificado' }}</div>
          </div>
          @if(!empty($empresa->descripcion))
          <div class="info-item" style="grid-column: 1 / -1;">
            <div class="info-label">Descripción</div>
            <div class="info-value" style="word-break:break-word; overflow-wrap:break-word; overflow:hidden; display:-webkit-box; -webkit-line-clamp:5; -webkit-box-orient:vertical;">{{ $empresa->descripcion }}</div>
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
              <a href="{{ route('ofertas.detalle', $oferta->id_oferta) }}" style="text-decoration:none;">
                <div class="info-item" style="cursor:pointer;">
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
</div>
@endsection
@extends('layouts.app')

@section('title', ($oferta->titulo ?? 'Ver Oferta') . ' — KROW')

@section('content')

{{-- ════════════════════════════════════════
    VER OFERTA — KROW
════════════════════════════════════════ --}}

@php
    $titulo       = $oferta->titulo          ?? '';
    $empresa      = $oferta->empresa->nombre_empresa
                    ?? $oferta->empresa->nombre
                    ?? '';
    $empresaSlug  = $oferta->empresa->slug   ?? '';
    $logoLetras   = strtoupper(substr($empresa ?: '?', 0, 2));
    $tipo         = $oferta->tipo_trabajo    ?? $oferta->tipo ?? '';
    $modalidad    = $oferta->modalidad       ?? '';
    $salario      = $oferta->rango_salarial  ?? $oferta->salario ?? '';
    $experiencia  = $oferta->experiencia_requerida ?? '';
    $descripcion  = $oferta->descripcion     ?? '';
    $requisitos   = $oferta->requisitos      ?? '';
    $tecnologias  = $oferta->tecnologias     ?? [];   
    $estado       = $oferta->estado          ?? 'activa';
    
    // Corregido: Ahora apunta a ->direccion de la empresa
    $ubicacion    = $oferta->ubicacion       ?? ($oferta->empresa->direccion ?? '');
    
    $fechaTxt     = $oferta->fecha_texto     ?? ($oferta->created_at ? \Carbon\Carbon::parse($oferta->created_at)->diffForHumans() : '');
    $yaPostulado  = $oferta->ya_postulado    ?? false;
@endphp

<div class="ver-oferta-page">

    {{-- Breadcrumb / volver ──────────────────────── --}}
    <div class="ver-oferta-header">
        <a href="{{ route('inicio') }}" class="link-volver">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke-width="2">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
            Volver a ofertas
        </a>
    </div>

    <div class="ver-oferta-layout">

        {{-- ── COLUMNA PRINCIPAL ─────────────────── --}}
        <div>
            <div class="ver-oferta-card" style="max-width: 100%; overflow: hidden;">

                {{-- Encabezado ── --}}
                <div class="oferta-head">
                    <div class="oferta-head-left">
                        <div class="oferta-empresa-logo">{{ $logoLetras }}</div>
                        <div class="oferta-head-text">
                            <h1 style="overflow-wrap: break-word; word-break: break-word;">{{ $titulo }}</h1>
                            <div class="oferta-empresa-meta">
                                <span>{{ $empresa }}</span>
                            </div>
                        </div>
                    </div>
                    <span class="oferta-estado-badge {{ $estado }}">{{ ucfirst($estado) }}</span>
                </div>

                {{-- Badges rápidos ── --}}
                <div class="oferta-badges">
                    @if ($tipo)
                        <span class="badge-info">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                            </svg>
                            {{ ucfirst(str_replace('-', ' ', $tipo)) }}
                        </span>
                    @endif
                    @if ($modalidad)
                        <span class="badge-info">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                            </svg>
                            {{ ucfirst($modalidad) }}
                        </span>
                    @endif
                    @if ($salario)
                        <span class="badge-info">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                            {{ $salario }}
                        </span>
                    @endif
                    @if ($experiencia)
                        <span class="badge-info">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                            </svg>
                            {{ ucfirst(str_replace('-', ' ', $experiencia)) }}
                        </span>
                    @endif
                </div>

                {{-- Descripción ── --}}
                @if ($descripcion)
                    <div class="oferta-section">
                        <div class="oferta-section-title">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                            </svg>
                            Descripción del puesto
                        </div>
                        <div class="oferta-section-body" style="white-space: pre-line; overflow-wrap: break-word; word-break: break-word; max-width: 100%;">{{ $descripcion }}</div>
                    </div>
                    <hr class="oferta-divider">
                @endif

                {{-- Requisitos ── --}}
                @if ($requisitos)
                    <div class="oferta-section">
                        <div class="oferta-section-title">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                            </svg>
                            Requisitos
                        </div>
                        <ul class="oferta-requisitos-list" style="overflow-wrap: break-word; word-break: break-word; max-width: 100%;">
                            @foreach (array_filter(preg_split('/\r\n|\r|\n/', $requisitos)) as $req)
                                <li style="overflow-wrap: break-word; word-break: break-word;">{{ trim($req) }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Tecnologías ── --}}
                @if (!empty($tecnologias) && (is_array($tecnologias) ? count($tecnologias) : $tecnologias->count()) > 0)
                    <hr class="oferta-divider">
                    <div class="oferta-section">
                        <div class="oferta-section-title">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>
                            </svg>
                            Tecnologías / Herramientas
                        </div>
                        <div class="oferta-tags-wrap">
                            @foreach ($tecnologias as $tech)
                                <span class="tech-tag">
                                    <span>{{ is_string($tech) ? $tech : ($tech->nombre ?? $tech) }}</span>
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>

{{-- ── SIDEBAR ────────────────────────────── --}}
        <aside class="ver-oferta-sidebar">

            @if(!auth()->check() || auth()->user()->rol === 'estudiante')
            <div class="sidebar-card">
                @auth
                    @if ($yaPostulado)
                        <button class="btn-postular ya-postulado" disabled>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Ya postulado
                        </button>
                    @else
                        <form method="POST" action="{{ route('estudiante.ofertas.postular', $oferta->id_oferta) }}">
                            @csrf
                            <button type="submit" class="btn-postular">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <line x1="22" y1="2" x2="11" y2="13"/>
                                    <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                                </svg>
                                Postularme
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-postular">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="22" y1="2" x2="11" y2="13"/>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                        </svg>
                        Iniciar sesión para postular
                    </a>
                @endauth

                @if ($fechaTxt)
                    <p class="oferta-meta-bottom">Publicado {{ $fechaTxt }}</p>
                @endif
            </div>
            @endif

                {{-- Resumen ── --}}
        <div class="sidebar-card">
            <div class="sidebar-card-title">Resumen</div>

            @if ($tipo)
                <div class="sidebar-dato">
                    <span class="sidebar-dato-label">Tipo</span>
                    <span class="sidebar-dato-value">{{ ucfirst(str_replace('-', ' ', $tipo)) }}</span>
                </div>
            @endif

            @if ($modalidad)
                <div class="sidebar-dato">
                    <span class="sidebar-dato-label">Modalidad</span>
                    <span class="sidebar-dato-value">{{ ucfirst($modalidad) }}</span>
                </div>
            @endif

            @if ($salario)
                <div class="sidebar-dato">
                    <span class="sidebar-dato-label">Salario</span>
                    <span class="sidebar-dato-value">{{ $salario }}</span>
                </div>
            @endif

            @if ($experiencia)
                <div class="sidebar-dato">
                    <span class="sidebar-dato-label">Experiencia</span>
                    <span class="sidebar-dato-value">{{ ucfirst(str_replace('-', ' ', $experiencia)) }}</span>
                </div>
            @endif

            {{-- Nueva Fila: Categoría / Área --}}
            @if ($oferta->area)
                <div class="sidebar-dato" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;">
                    <span class="sidebar-dato-label" style="flex-shrink: 0;">Categoría</span>
                    <span class="sidebar-dato-value" style="word-break: break-word; text-align: right;">
                        {{ $oferta->area }}
                    </span>
                </div>
            @endif

            {{-- Nueva Fila: Carrera Destinada --}}
            @if ($oferta->id_carrera)
                <div class="sidebar-dato" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;">
                    <span class="sidebar-dato-label" style="flex-shrink: 0;">Carrera</span>
                    <span class="sidebar-dato-value" style="word-break: break-word; text-align: right;">
                        {{ $oferta->carrera?->nombre ?? 'No especificada' }}
                    </span>
                </div>
            @endif

            {{-- Procesamos la ubicación --}}
            @php
                $locNombre = $oferta->localidad?->nombre;
                $provNombre = $oferta->localidad?->provincia?->nombre;
                $ubicacionFinal = ($locNombre && $provNombre) ? $locNombre . ', ' . $provNombre : ($ubicacion ?? '');
            @endphp

            {{-- Fila: Ubicación --}}
            @if ($ubicacionFinal)
                <div class="sidebar-dato" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;">
                    <span class="sidebar-dato-label" style="flex-shrink: 0;">Ubicación</span>
                    <span class="sidebar-dato-value" style="word-break: break-word; text-align: right;">
                        {{ $ubicacionFinal }}
                    </span>
                </div>
            @endif

            {{-- Fila: Dirección física --}}
            @if (!empty($oferta->empresa?->direccion)) 
                <div class="sidebar-dato" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;">
                    <span class="sidebar-dato-label" style="flex-shrink: 0;">Dirección</span>
                    <span class="sidebar-dato-value" style="word-break: break-word; text-align: right;">
                        {{ $oferta->empresa->direccion }}
                    </span>
                </div>
            @endif
        </div> {{-- Cierre limpio del Resumen --}}

            {{-- Empresa ── --}}
            @if ($empresa)
                <div class="sidebar-card">
                    <div class="sidebar-card-title">Empresa</div>
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                        <div class="oferta-empresa-logo" style="width:38px;height:38px;font-size:0.85rem; flex-shrink: 0;">{{ $logoLetras }}</div>
                        <span style="font-weight:600; color:var(--text); font-size:0.95rem; overflow-wrap: break-word; word-break: break-word;">{{ $empresa }}</span>
                    </div>
                </div>
            @endif

        </aside>

    </div>
</div>

@endsection
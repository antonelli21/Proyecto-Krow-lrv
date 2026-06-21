@extends('layouts.app')

@section('title', 'KROW — Portal de Empleos')

@section('content')
@php
$rol = auth()->check() ? (auth()->user()->rol ?? 'invitado') : 'invitado';
$ofertas = $ofertas ?? collect();
@endphp

<div class="page-body" id="page-body" data-rol="{{ $rol }}">

    <div class="mobile-sidebar-bar">
        <button class="sidebar-mobile-toggle" id="sidebar-drawer-toggle" aria-controls="sidebar-filtros" aria-expanded="false" aria-label="Abrir filtros">
            <span class="sidebar-mobile-toggle-text">Ver filtros</span>
            <span class="sidebar-mobile-toggle-icon">+</span>
        </button>
    </div>
    <div class="sidebar-mobile-overlay" id="sidebar-overlay" aria-hidden="true"></div>

    {{-- SIDEBAR --}}
    <aside class="sidebar-filtros" id="sidebar-filtros" aria-label="Filtros de búsqueda">
        @include('layouts.sidebar-filtros')
    </aside>

    {{-- MAIN --}}
    <main class="main-content" id="main-content">

        <div class="content-head">
            <div>
                <h2 class="content-title">Ofertas de Empleo</h2>
                <p class="result-count" id="result-count">
                    Se encontraron {{ $ofertas instanceof \Illuminate\Pagination\LengthAwarePaginator ? $ofertas->total() : $ofertas->count() }} resultados
                </p>
            </div>
            <div class="sort-bar">
                <select class="sort-select" id="sort-select" aria-label="Ordenar resultados">
                    <option value="recientes">Más recientes</option>
                    <option value="salario-asc">Menor salario</option>
                    <option value="salario-desc">Mayor salario</option>
                </select>
            </div>
        </div>

        {{-- Tarjetas --}}
        @forelse ($ofertas as $oferta)

        @php
        $id = data_get($oferta, 'id', data_get($oferta, 'id_oferta', $loop->index));
        $titulo = data_get($oferta, 'titulo', '');
        $empresa = data_get($oferta, 'empresa.nombre_empresa', data_get($oferta, 'empresa.nombre', data_get($oferta, 'empresa', '')));
        $modalidad = data_get($oferta, 'modalidad', '');
        $salario = data_get($oferta, 'salario', '');
        if (!$salario && data_get($oferta, 'salario_min')) {
        $salario = '$' . number_format(data_get($oferta, 'salario_min'), 0, ',', '.')
        . (data_get($oferta, 'salario_max') ? ' - $' . number_format(data_get($oferta, 'salario_max'), 0, ',', '.') : '');
        }
        $salarioNum = data_get($oferta, 'salario_num', 0);
        $tipo = data_get($oferta, 'tipo', data_get($oferta, 'tipo_oferta', ''));
        $esNueva = data_get($oferta, 'es_nueva', false);
        $descripcion= data_get($oferta, 'descripcion', '');
        $fechaTxt = data_get($oferta, 'fecha_texto',
        $oferta->fecha_publicacion
        ? \Carbon\Carbon::parse($oferta->fecha_publicacion)->diffForHumans()
        : ''
        );
        $fechaTs = data_get($oferta, 'created_at')
        ? \Carbon\Carbon::parse(data_get($oferta, 'created_at'))->timestamp
        : data_get($oferta, 'fecha_timestamp', 0);
        $guardado = data_get($oferta, 'guardado', false);
        $logoLetras = strtoupper(substr($empresa ?: '?', 0, 2));
        @endphp

        <article
            class="job-card"
            data-id="{{ $id }}"
            data-salario="{{ $salarioNum }}"
            data-fecha="{{ $fechaTs }}">
            <div class="job-card-top">
                <div class="company-logo" aria-hidden="true">{{ $logoLetras }}</div>
                <div class="job-info">
                    <h3 class="job-title">{{ $titulo }}</h3>
                    <p class="job-meta">
                        <span>{{ $empresa }}</span> &bull; <span>{{ $modalidad }}</span>
                    </p>
                </div>
            </div>

            <div class="job-badges">
                @if ($salario)
                <span class="badge badge-salary">{{ $salario }}</span>
                @endif
                @if ($tipo)
                <span class="badge badge-outline">{{ $tipo }}</span>
                @endif
                @if ($esNueva)
                <span class="badge badge-new">Nuevo</span>
                @endif
            </div>

            @if ($descripcion)
            <p class="job-desc">{{ $descripcion }}</p>
            @endif

            <div class="job-footer">
                <span class="job-date">{{ $fechaTxt }}</span>
                <a href="{{ route('ofertas.detalle', $id) }}" class="btn-ver">Ver oferta</a>
            </div>
        </article>

        @empty

        <article class="job-card" data-id="mock-1" data-salario="450000" data-fecha="9999999999">
            <div class="job-card-top">
                <div class="company-logo" aria-hidden="true">MC</div>
                <div class="job-info">
                    <h3 class="job-title">Fullstack Developer Node / React</h3>
                    <p class="job-meta"><span>MegaCorp</span> &bull; <span>Remoto</span></p>
                </div>
            </div>
            <div class="job-badges">
                <span class="badge badge-salary">$450.000 / mes</span>
                <span class="badge badge-outline">Full-time</span>
                <span class="badge badge-new">Nuevo</span>
            </div>
            <p class="job-desc">
                Buscamos un desarrollador proactivo para sumarse al equipo de core-banking...
            </p>
            <div class="job-footer">
                <span class="job-date">Publicado hace 2 días</span>
                <a href="{{ route('ofertas.detalle', 1) }}" class="btn-ver">Ver oferta</a>
            </div>
        </article>

        <article class="job-card" data-id="mock-2" data-salario="300000" data-fecha="9999999990">
            <div class="job-card-top">
                <div class="company-logo" aria-hidden="true">DS</div>
                <div class="job-info">
                    <h3 class="job-title">Analista QA Semi-Senior</h3>
                    <p class="job-meta"><span>DevSoft</span> &bull; <span>Híbrido</span></p>
                </div>
            </div>
            <div class="job-badges">
                <span class="badge badge-salary">$300.000 / mes</span>
                <span class="badge badge-outline">Part-time</span>
            </div>
            <p class="job-desc">
                Incorporamos QA con experiencia en testing funcional y automatizado...
            </p>
            <div class="job-footer">
                <span class="job-date">Publicado hace 5 días</span>
                <a href="{{ route('ofertas.detalle', 1) }}" class="btn-ver">Ver oferta</a>
            </div>
        </article>

        @endforelse

        {{-- Paginación --}}
        @if ($ofertas instanceof \Illuminate\Pagination\LengthAwarePaginator && $ofertas->hasPages())
        <nav class="pagination" id="pagination" aria-label="Páginas de resultados">

            {{-- Anterior --}}
            @if ($ofertas->onFirstPage())
            <button class="pg-btn" id="pg-prev" aria-label="Página anterior" disabled>
                <i class="bi bi-chevron-left"></i>
            </button>
            @else
            <a href="{{ $ofertas->previousPageUrl() }}" class="pg-btn" id="pg-prev" aria-label="Página anterior">
                <i class="bi bi-chevron-left"></i>
            </a>
            @endif

            {{-- Números --}}
            @foreach ($ofertas->getUrlRange(1, $ofertas->lastPage()) as $page => $url)
            @if ($page == $ofertas->currentPage())
            <button class="pg-btn active" aria-current="page">
                {{ $page }}
            </button>
            @else
            <a href="{{ $url }}" class="pg-btn">
                {{ $page }}
            </a>
            @endif
            @endforeach

            {{-- Siguiente --}}
            @if ($ofertas->hasMorePages())
            <a href="{{ $ofertas->nextPageUrl() }}" class="pg-btn" id="pg-next" aria-label="Página siguiente">
                <i class="bi bi-chevron-right"></i>
            </a>
            @else
            <button class="pg-btn" id="pg-next" aria-label="Página siguiente" disabled>
                <i class="bi bi-chevron-right"></i>
            </button>
            @endif

        </nav>
        @endif
    </main>

    {{-- RIGHT PANEL --}}
    <div id="right-panel">
        <div class="role-panel-content" data-panel-role="invitado">
            @include('layouts.partials.invitado')
        </div>
        <div class="role-panel-content" data-panel-role="estudiante" style="display: none;">
            @include('layouts.partials.estudiante')
        </div>
        <div class="role-panel-content" data-panel-role="empresa" style="display: none;">
            @include('layouts.partials.empresa')
        </div>
        <div class="role-panel-content" data-panel-role="admin" style="display: none;">
            @include('layouts.partials.admin')
        </div>
    </div>


</div>{{-- /page-body --}}

@endsection
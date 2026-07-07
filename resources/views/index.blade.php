@extends('layouts.app')

@section('title', 'KROW — Portal de Empleos')

@section('content')
@php
$rol = auth()->check() ? (auth()->user()->rol ?? 'invitado') : 'invitado';
$ofertas = $ofertas ?? collect();
@endphp

<div id="banner-overlay" style="
    position: relative;
    width: 100%;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    background-image: url('{{ asset('img/banner.jpg') }}');
    background-size: cover;
    background-position: top;
    background-repeat: no-repeat;
">

    <h1 id="banner-title" class="gradient-text" style="
    margin-top: 20px;
    margin-bottom: 20px;
    width: 100%;
  max-width: 1320px;
    text-align: center;
    padding: 12px 28px;
    border-radius: 18px;
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    box-shadow: 0 8px 24px rgba(0,0,0,.35);
">
    KROW
</h1>

    <div class="page-body" id="page-body" data-rol="{{ $rol }}" style="
        margin-bottom:80px;
        background-color:var(--bg);
        opacity:0.95;
        border-radius:8px;
        border:1px solid var(--accent);
        justify-content:start;
        box-shadow:
            0 20px 50px var(--shadow-color),
            0 0px 30px var(--shadow-glow);
    ">
     


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

    {{-- MAIN --}}
<main class="main-content" id="main-content">

    @if(request()->filled('empresa_id'))
        @php $emp = \App\Models\Empresa::find(request('empresa_id')); @endphp
        <div class="empresa-filtro-activo">
            <span>Mostrando ofertas de <strong>{{ $emp->nombre_empresa ?? 'empresa' }}</strong></span>
            <a href="{{ route('inicio') }}" class="empresa-filtro-limpiar">&times; Ver todas las ofertas</a>
        </div>
    @endif

    {{-- BUSCADOR --}}
    <div class="krow-search-bar">
        <i class="bi bi-search krow-search-icon"></i>
        <input
            type="text"
            id="buscar"
            name="buscar"
            placeholder="Buscar trabajo, empresa o habilidad..."
            class="krow-search-input"
            value="{{ request('buscar') }}"
            autocomplete="off"
        >
        @if(request()->filled('buscar'))
            <a href="{{ url()->current() }}?{{ http_build_query(request()->except('buscar')) }}"
               class="krow-search-clear" title="Limpiar búsqueda">
                <i class="bi bi-x-lg"></i>
            </a>
        @endif
    </div>

    <div class="content-head">
        <div>
            <h2 class="content-title">Ofertas de Empleo</h2>
            <p class="result-count" id="result-count">
                Se encontraron {{ $ofertas instanceof \Illuminate\Pagination\LengthAwarePaginator ? $ofertas->total() : $ofertas->count() }} resultados
            </p>
        </div>
        <div class="sort-bar">
            <select class="sort-select" id="sort-select" aria-label="Ordenar resultados"
                    onchange="document.getElementById('orden-hidden').value = this.value; fetchOfertas();">
                <option value="recientes"    {{ request('orden','recientes') === 'recientes'   ? 'selected' : '' }}>Más recientes</option>
                <option value="salario-asc"  {{ request('orden') === 'salario-asc'             ? 'selected' : '' }}>Menor salario</option>
                <option value="salario-desc" {{ request('orden') === 'salario-desc'            ? 'selected' : '' }}>Mayor salario</option>
            </select>
        </div>
    </div>

    <div id="cards-container" style="display:flex;flex-direction:column;gap:14px;">
        @include('layouts.partials.ofertas-cards')
    </div>
</main>

    {{-- RIGHT PANEL --}}
<div id="right-panel" class="right-panel">
    @auth
        @if(auth()->user()->rol === 'estudiante')
            <div class="role-panel-content">
                @include('layouts.partials.estudiante')
            </div>
        @elseif(auth()->user()->rol === 'empresa')
            <div class="role-panel-content">
                @include('layouts.partials.empresa')
            </div>
        @elseif(auth()->user()->rol === 'admin')
            <div class="role-panel-content">
                @include('layouts.partials.admin')
            </div>
        @endif
    @else
        <div class="role-panel-content">
            @include('layouts.partials.invitado')
        </div>
    @endauth
</div>


</div>{{-- /page-body --}}
</div>
@endsection
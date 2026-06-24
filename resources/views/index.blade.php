@extends('layouts.app')

@section('title', 'KROW — Portal de Empleos')

@section('banner')
<div id="banner-index" style="
    width:100%;
    height:clamp(140px, 22vw, 420px);
    position:relative;
    overflow:hidden;
">

    <img id="banner-img"
     src="{{ asset('img/banner-estudiante.jpg') }}"
     alt="Banner"
     style="width:100%; height:100%; object-fit:cover; display:block;">

    <div style="
        position:absolute;
        inset:0;
        background:linear-gradient(to right, rgba(0,0,0,.8), rgba(0,0,0,.8));
    "></div>

    <div style="
        position:absolute;
        inset:0;
        display:flex;
        flex-direction:column;
        justify-content:center;
        align-items:center;
        text-align:center;
        z-index:2;
        padding:12px;
    ">

<h1 id="banner-title" style="
    color:#fff;
    font-size:clamp(1.2rem, 3vw, 2rem);
    font-weight:800;
    margin-top:-250px;
">
    Krow
</h1>
</div>
</div>
@endsection

@section('content')
@php
$rol = auth()->check() ? (auth()->user()->rol ?? 'invitado') : 'invitado';
$ofertas = $ofertas ?? collect();
@endphp


<div class="page-body" id="page-body" data-rol="{{ $rol }}" style="margin-top: -260px; position: relative; z-index: 5;       background-color:var(--bg); border-radius: 8px; border:1px solid var(--surface);">

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
        @if(request()->filled('empresa_id'))
@php $emp = \App\Models\Empresa::find(request('empresa_id')); @endphp
<div class="empresa-filtro-activo">
    <span>
        Mostrando ofertas de <strong>{{ $emp->nombre_empresa ?? 'empresa' }}</strong>
    </span>
    <a href="{{ route('inicio') }}" class="empresa-filtro-limpiar">
        &times; Ver todas las ofertas
    </a>
</div>
@endif
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
        


        <div id="cards-container" style="display:flex; flex-direction:column; gap:14px;">
            @include('layouts.partials.ofertas-cards')
        </div>
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
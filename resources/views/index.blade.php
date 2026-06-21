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
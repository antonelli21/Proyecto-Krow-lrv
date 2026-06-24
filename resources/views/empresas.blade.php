@extends('layouts.app')

@section('title', 'Base de Empresas — KROW')


@section('banner')
<div style="
    width:100%;
    height:clamp(140px, 22vw, 220px);
    position:relative;
    overflow:hidden;
">

    <img src="{{ asset('img/banner-estudiante.jpg') }}"
         alt="Mis Postulaciones"
         style="width:100%; height:100%; object-fit:cover; display:block;">

    <div style="
        position:absolute;
        inset:0;
        background:linear-gradient(
            to right,
            rgba(0,0,0,.8),
            rgba(0,0,0,.8)
        );
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

        <h1 style="
            color:#fff;
            margin:0;
            font-size:clamp(1.2rem, 3vw, 2rem);
            font-weight:800;
        ">
            Base de Empresas
        </h1>

        <p style="
            color:rgba(255,255,255,.85);
            margin-top:6px;
            max-width:600px;
            font-size:clamp(.75rem,1.5vw,.95rem);
        ">

    Explorá las empresas registradas en KROW y conocé sus oportunidades laborales.
        </p>

    </div>
</div>
@endsection

@section('content')

@php
$rubros = $empresas->pluck('rubro')->filter()->unique()->values();
$ubicaciones = $empresas->map(function($emp) {
    return $emp->provincia->nombre ?? null;
})->filter()->unique()->values();
@endphp

<main class="empresas-page">

    <div class="empresas-header">
        <h1>Base de Empresas</h1>
        <p>Explorá las empresas registradas en la plataforma y sus ofertas laborales activas.</p>
    </div>

    <div class="empresas-toolbar">
        <div class="toolbar-search">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.35-4.35" />
            </svg>
            <input type="text" id="buscador" placeholder="Buscar empresa, rubro, ubicación..." autocomplete="off">
        </div>
        <div class="toolbar-select">
            <select id="filtro-rubro">
                <option value="">Todos los rubros</option>
                @foreach($rubros as $r)
                <option value="{{ $r }}">{{ $r }}</option>
                @endforeach
            </select>
        </div>
        <div class="toolbar-select">
            <select id="filtro-ubicacion">
                <option value="">Todas las ubicaciones</option>
                @foreach($ubicaciones as $u)
                <option value="{{ $u }}">{{ $u }}</option>
                @endforeach
            </select>
        </div>
        <div class="toolbar-count" id="contador">{{ count($empresas) }} empresas</div>
    </div>

    <div class="empresas-grid" id="grid-empresas">

        @foreach($empresas as $emp)
        @php
        $modalidadesEmpresa = $emp->ofertas->pluck('modalidad')->filter()->unique()->toArray();
        $modalidadesString = implode(',', $modalidadesEmpresa);
        @endphp
        <article class="empresa-card"
            data-nombre="{{ strtolower($emp->nombre_empresa) }}"
            data-rubro="{{ strtolower($emp->rubro ?? '') }}"
            data-ubicacion="{{ strtolower(($emp->localidad->nombre ?? '') . ', ' . ($emp->provincia->nombre ?? '')) }}"
            data-modalidades="{{ strtolower($modalidadesString) }}">

            <div class="empresa-card-header">
                <div class="empresa-info-title">
                    <div class="empresa-icon" style="overflow:hidden; border-radius:4px; width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                        @if($emp->logo)
                            <img src="{{ asset('storage/' . $emp->logo) }}" alt="Logo {{ $emp->nombre_empresa }}" style="width:100%; height:100%; object-fit:cover;">
                        @else
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M3 21h18M5 21V7l8-4 8 4v14M9 21v-6h6v6" />
                            </svg>
                        @endif
                    </div>
                    <div class="empresa-info-text">
                        <div class="empresa-nombre">{{ $emp->nombre_empresa }}</div>
                        <div class="empresa-meta">
                            <span>{{ $emp->rubro ?? 'General' }}</span>
                            <span>·</span>
                            <span>{{ ($emp->localidad->nombre ?? '') . ', ' . ($emp->provincia->nombre ?? '') }}</span>
                            <span>·</span>
                            <span>{{ $emp->tamano_empresa ?? 'No especificado' }}</span>
                        </div>
                    </div>
                </div>
                <div class="empresa-badge-ofertas">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                    </svg>
                    {{ $emp->ofertas->count() }} oferta{{ $emp->ofertas->count() != 1 ? 's' : '' }}
                </div>
            </div>

            <div class="empresa-tags">
                @if(empty($modalidadesEmpresa))
                <span class="empresa-tag">{{ $emp->rubro ?? 'General' }}</span>
                @else
                @foreach($modalidadesEmpresa as $mod)
                <span class="empresa-tag">{{ ucfirst($mod) }}</span>
                @endforeach
                @endif
            </div>

            <div class="empresa-desc">
                {{ \Illuminate\Support\Str::limit($emp->descripcion ?? 'Sin descripción', 120) }}
            </div>

            <div class="empresa-actions">
    <a href="{{ route('empresas.perfil', $emp->id_empresa) }}" class="empresa-btn empresa-btn--perfil">
        Ver perfil
    </a>
    <a href="{{ route('inicio', ['empresa_id' => $emp->id_empresa]) }}" class="empresa-btn empresa-btn--ofertas">
        Ver ofertas
    </a>
</div>

    </article>
        @endforeach

        <div class="empresas-empty" id="empty-state" style="display:none">
            <h3>No se encontraron empresas</h3>
            <p>Probá ajustando los filtros o el término de búsqueda.</p>
        </div>

    </div>

</main>


<style>
    .empresa-actions {
    display: flex;
    gap: 8px;
    margin-top: 12px;
}

.empresa-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: var(--radius);
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: background .2s, color .2s;
    flex: 1;
    justify-content: center;
}

.empresa-btn--perfil {
    background: transparent;
    border: 1px solid var(--border);
    color: var(--text);
}

.empresa-btn--perfil:hover {
    background: var(--surface);
    border-color: var(--accent);
    color: var(--accent);
}

.empresa-btn--ofertas {
    background: var(--accent);
    border: 1px solid transparent;
    color: #0D1A13;
}

.empresa-btn--ofertas:hover {
    filter: brightness(1.4);
}

.empresa-btn-count {
    background: rgba(0,0,0,.15);
    border-radius: 20px;
    padding: 1px 7px;
    font-size: 11px;
    font-weight: 700;
}
    /* ── TOOLBAR RESPONSIVE ── */
    .empresas-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        margin-bottom: 24px;
        padding: 14px 16px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
    }

    .toolbar-search {
        flex: 1 1 300px;
        min-width: 200px;
        position: relative;
    }

    .toolbar-search svg {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        pointer-events: none;
    }

    .toolbar-search input {
        width: 100%;
        padding: 10px 12px 10px 38px;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        background: var(--bg);
        color: var(--text);
        font-size: 14px;
        transition: border-color .2s;
    }

    .toolbar-search input:focus {
        outline: none;
        border-color: var(--accent);
    }

    .toolbar-select {
        flex: 0 1 180px;
        min-width: 150px;
        position: relative;
    }

    .toolbar-select select {
        width: 100%;
        padding: 10px 32px 10px 12px;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        background: var(--bg);
        color: var(--text);
        font-size: 14px;
        appearance: none;
        cursor: pointer;
        transition: border-color .2s;
    }

    .toolbar-select select:focus {
        outline: none;
        border-color: var(--accent);
    }

    .toolbar-select::after {
        content: "";
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 10px;
        height: 10px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%236B6B78' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: center;
        pointer-events: none;
    }

    .toolbar-count {
        margin-left: auto;
        font-size: 14px;
        color: var(--muted);
        font-weight: 500;
        white-space: nowrap;
    }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .empresas-toolbar {
            flex-direction: column;
            align-items: stretch;
            padding: 12px;
            gap: 10px;
        }

        .toolbar-search {
            flex: 1 1 auto;
            width: 100%;
            min-width: unset;
        }

        .toolbar-select {
            flex: 1 1 auto;
            width: 100%;
            min-width: unset;
        }

        .toolbar-count {
            margin-left: 0;
            text-align: center;
            padding-top: 8px;
            border-top: 1px solid var(--border);
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .empresas-toolbar {
            padding: 10px;
            gap: 8px;
        }

        .toolbar-search input,
        .toolbar-select select {
            padding: 10px 12px;
            font-size: 14px;
        }

        .toolbar-search input {
            padding-left: 38px;
        }

        .toolbar-count {
            font-size: 13px;
            padding-top: 8px;
        }
    }

    /* ── Grid responsive ── */
    @media (max-width: 1024px) {
        .empresas-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
    }

    @media (max-width: 640px) {
        .empresas-grid {
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .empresas-header h1 {
            font-size: 22px;
        }

        .empresas-header p {
            font-size: 14px;
        }

        .empresa-card {
            padding: 16px;
        }

        .empresa-nombre {
            font-size: 15px;
        }
    }
</style>

@endsection
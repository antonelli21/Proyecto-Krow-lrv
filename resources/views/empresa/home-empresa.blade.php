@extends('layouts.app')

@section('title', 'Panel Empresa — KROW')

@section('content')
<div class="panel-page">

    <h1 class="panel-page-title">Panel de Empresa</h1>
    <p class="panel-page-sub">Gestiona tus ofertas laborales y revisa los postulantes</p>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-card">
            <div>
                <p class="stat-card-label">Ofertas Activas</p>
                <span class="stat-card-value">{{ $ofertas->where('estado', 'activa')->count() ?? 0 }}</span>
            </div>
            <i class="bi bi-briefcase stat-card-icon"></i>
        </div>
        <div class="stat-card">
            <div>
                <p class="stat-card-label">Total Postulantes</p>
                <span class="stat-card-value">{{ $totalPostulantes ?? 0 }}</span>
            </div>
            <i class="bi bi-people stat-card-icon"></i>
        </div>
        <div class="stat-card">
            <div>
                <p class="stat-card-label">Vistas Totales</p>
                <span class="stat-card-value">{{ $totalVistas ?? 0 }}</span>
            </div>
            <i class="bi bi-eye stat-card-icon"></i>
        </div>
    </div>

    <!-- Mis Ofertas -->
    <div class="section-header">
        <h2 class="section-title">Mis Ofertas</h2>
        <div class="section-actions">
            <a href="{{ route('mensajes') }}" class="btn-outline"><i class="bi bi-chat-dots"></i> Mensajes</a>
            <a href="{{ route('empresa.crear-oferta') }}" class="btn-accent"><i class="bi bi-plus-lg"></i> Nueva Oferta</a>
        </div>
    </div>

    @if(isset($ofertas) && count($ofertas) > 0)
    <!-- Desktop: tabla -->
    <div class="table-responsive-desktop">
        <table class="ofertas-table">
            <thead>
                <tr>
                    <th>Puesto</th>
                    <th>Ubicación</th>
                    <th>Tipo</th>
                    <th>Salario</th>
                    <th>Postulantes</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ofertas as $oferta)
                <tr>
                    <td class="td-puesto">{{ $oferta->titulo }}</td>
                    <td class="td-ubicacion">{{ $oferta->ubicacion ?? 'No especificada' }}</td>
                    <td><span class="badge-tipo">{{ $oferta->tipo_contrato ?? 'No especificado' }}</span></td>
                    <td>{{ $oferta->salario ?? 'A convenir' }}</td>
                    <td><span class="td-postulantes"><i class="bi bi-people-fill"></i> {{ $oferta->postulaciones_count ?? 0 }}</span></td>
                    <td class="td-fecha">{{ $oferta->created_at ? $oferta->created_at->format('d/m/Y') : 'Sin fecha' }}</td>
                    <td>
                        <a href="{{ route('empresa.empresa.ofertas.postulantes', $oferta->id_oferta) }}" class="link-accion">Ver postulantes →</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile: tarjetas -->
    <div class="ofertas-mobile-cards">
        @foreach($ofertas as $oferta)
        <div class="oferta-mobile-card">
            <div class="oferta-mobile-header">
                <span class="oferta-mobile-titulo">{{ $oferta->titulo }}</span>
                <span class="badge-tipo">{{ $oferta->tipo_contrato ?? 'No especificado' }}</span>
            </div>
            <div class="oferta-mobile-body">
                <div class="oferta-mobile-item">
                    <span class="item-label">📍 Ubicación</span>
                    <span class="item-value">{{ $oferta->ubicacion ?? 'No especificada' }}</span>
                </div>
                <div class="oferta-mobile-item">
                    <span class="item-label">💰 Salario</span>
                    <span class="item-value">{{ $oferta->salario ?? 'A convenir' }}</span>
                </div>
                <div class="oferta-mobile-item">
                    <span class="item-label">📅 Fecha</span>
                    <span class="item-value">{{ $oferta->created_at ? $oferta->created_at->format('d/m/Y') : 'Sin fecha' }}</span>
                </div>
                <div class="oferta-mobile-item">
                    <span class="item-label">👥 Postulantes</span>
                    <span class="item-value"><i class="bi bi-people-fill"></i> {{ $oferta->postulaciones_count ?? 0 }}</span>
                </div>
            </div>
            <div class="oferta-mobile-footer">
                <a href="{{ route('empresa.empresa.ofertas.postulantes', $oferta->id_oferta) }}" class="link-accion">Ver postulantes →</a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div style="text-align: center; padding: 3rem; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius);">
        <p style="color: var(--muted);">📭 No tenés ofertas publicadas aún.</p>
        <a href="#" class="btn-accent" style="display: inline-block; margin-top: 1rem;">+ Crear primera oferta</a>
    </div>
    @endif

</div>

<style>
    /* ── Responsive: mobile cards ── */
    .ofertas-mobile-cards {
        display: none;
        flex-direction: column;
        gap: 12px;
    }

    .oferta-mobile-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .oferta-mobile-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--border);
    }

    .oferta-mobile-titulo {
        font-family: var(--font-display);
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
    }

    .oferta-mobile-body {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .oferta-mobile-item {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        padding: 4px 0;
    }

    .oferta-mobile-item .item-label {
        color: var(--muted);
        font-weight: 500;
    }

    .oferta-mobile-item .item-value {
        color: var(--text);
        font-weight: 500;
    }

    .oferta-mobile-footer {
        padding-top: 10px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: flex-end;
    }

    /* ── Media queries ── */
    @media (max-width: 768px) {
        .table-responsive-desktop {
            display: none;
        }

        .ofertas-mobile-cards {
            display: flex;
        }

        .stats-row {
            grid-template-columns: 1fr 1fr;
        }

        .section-header {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }

        .section-actions {
            display: flex;
            gap: 10px;
        }

        .section-actions .btn-outline,
        .section-actions .btn-accent {
            flex: 1;
            justify-content: center;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .stats-row {
            grid-template-columns: 1fr;
        }

        .stat-card {
            padding: 14px 16px;
        }

        .stat-card-value {
            font-size: 28px;
        }

        .oferta-mobile-titulo {
            font-size: 14px;
        }

        .oferta-mobile-item {
            font-size: 13px;
        }

        .section-actions {
            flex-direction: column;
        }

        .oferta-mobile-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

@endsection
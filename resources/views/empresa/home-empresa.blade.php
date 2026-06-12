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
          <span class="stat-card-value">3</span>
        </div>
        <i class="bi bi-briefcase stat-card-icon"></i>
      </div>
      <div class="stat-card">
        <div>
          <p class="stat-card-label">Total Postulantes</p>
          <span class="stat-card-value">54</span>
        </div>
        <i class="bi bi-people stat-card-icon"></i>
      </div>
      <div class="stat-card">
        <div>
          <p class="stat-card-label">Vistas Totales</p>
          <span class="stat-card-value">1,247</span>
        </div>
        <i class="bi bi-eye stat-card-icon"></i>
      </div>
    </div>

    <!-- Mis Ofertas -->
    <div class="section-header">
      <h2 class="section-title">Mis Ofertas</h2>
      <div class="section-actions">
        <a href="#" class="btn-outline"><i class="bi bi-chat-dots"></i> Mensajes</a>
        <a href="#" class="btn-accent"><i class="bi bi-plus-lg"></i> Nueva Oferta</a>
      </div>
    </div>

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
        @forelse($ofertas as $oferta)
        <tr>
          <td class="td-puesto">{{ $oferta->titulo }}</td>
          <td class="td-ubicacion">{{ $oferta->ubicacion ?? 'No especificada' }}</td>
          <td><span class="badge-tipo">{{ $oferta->tipo_contrato ?? 'No especificado' }}</span></td>
          <td>{{ $oferta->salario ?? 'A convenir' }}</td>
          <td><span class="td-postulantes"><i class="bi bi-people-fill"></i> {{ $oferta->postulaciones_count ?? 0 }}</span></td>
          <td class="td-fecha">{{ $oferta->created_at->format('d/m/Y') }}</td>
          <td>
            <a href="{{ route('empresa.ofertas.postulantes', $oferta->id_oferta) }}" class="link-accion">
              Ver postulantes →
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="text-align: center; padding: 2rem;">
            No tenés ofertas publicadas aún.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>

  </div>
@endsection


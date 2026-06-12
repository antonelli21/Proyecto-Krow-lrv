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

    <!-- Mis Ofertas -->
    <div class="section-header">
      <h2 class="section-title">Mis Ofertas</h2>
      <div class="section-actions">
        <a href="{{ route('mensajes') }}" class="btn-outline"><i class="bi bi-chat-dots"></i> Mensajes</a>
        <a href="{{ route('empresa.crear-oferta') }}" class="btn-accent"><i class="bi bi-plus-lg"></i> Nueva Oferta</a>
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

  <!-- Mis Ofertas -->
  <div class="section-header">
    <h2 class="section-title">Mis Ofertas</h2>
    <div class="section-actions">
      <a href="{{ route('mensajes') }}" class="btn-outline"><i class="bi bi-chat-dots"></i> Mensajes</a>
      <a href="{{ route('empresa.crear-oferta') }}" class="btn-accent"><i class="bi bi-plus-lg"></i> Nueva Oferta</a>
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
      <tr>
        <td class="td-puesto">Desarrollador Full Stack</td>
        <td class="td-ubicacion">Buenos Aires, Argentina</td>
        <td><span class="badge-tipo">Tiempo completo</span></td>
        <td>USD 3000–5000</td>
        <td><span class="td-postulantes"><i class="bi bi-people-fill"></i> 24</span></td>
        <td class="td-fecha">14/1/2024</td>
        <td><a href="#" class="link-accion">Ver postulantes →</a></td>
      </tr>
      <tr>
        <td class="td-puesto">Diseñador UX/UI Senior</td>
        <td class="td-ubicacion">Remoto</td>
        <td><span class="badge-tipo">Tiempo completo</span></td>
        <td>USD 2500–4000</td>
        <td><span class="td-postulantes"><i class="bi bi-people-fill"></i> 18</span></td>
        <td class="td-fecha">9/1/2024</td>
        <td><a href="#" class="link-accion">Ver postulantes →</a></td>
      </tr>
      <tr>
        <td class="td-puesto">Data Analyst</td>
        <td class="td-ubicacion">Córdoba, Argentina</td>
        <td><span class="badge-tipo">Medio tiempo</span></td>
        <td>USD 1500–2500</td>
        <td><span class="td-postulantes"><i class="bi bi-people-fill"></i> 12</span></td>
        <td class="td-fecha">4/1/2024</td>
        <td><a href="#" class="link-accion">Ver postulantes →</a></td>
      </tr>
    </tbody>
  </table>

</div>
@endsection
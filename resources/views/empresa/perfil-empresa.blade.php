@extends('layouts.app')

@section('title', 'Perfil Empresa - Krow')

@section('content')
  @php
    // Mock (modo desarrollo): datos de empresa sin base de datos
    $empresa = $empresa ?? [
      'nombre' => 'ACME S.A.',
      'rubro' => 'Software / Tecnología',
      'email' => 'contacto@acme.example',
      'telefono' => '+54 9 11 9876 5432',
      'direccion' => 'Av. Siempreviva 742',
      'localidad' => 'Ciudad Ficticia',
      'provincia' => 'Provincia Ejemplo',
      'sitio_web' => 'https://acme.example',
      'descripcion' => 'Empresa dedicada a soluciones de software y consultoría tecnológica.',
      'linkedin' => 'https://linkedin.com/company/acme',
      'facebook' => '',
    ];

    $ofertas = $ofertas ?? [
      ['titulo' => 'Desarrollador Full Stack', 'modalidad' => 'Híbrido', 'salario' => 'AR$ 250.000'],
      ['titulo' => 'Analista QA', 'modalidad' => 'Remoto', 'salario' => 'AR$ 180.000'],
    ];
  @endphp

  <div class="container mt-4">
    <div class="card perfil-header-card shadow-sm mb-4">
      <div class="card-body">
        <div class="row align-items-center gy-3">
          <div class="col-auto">
            <div class="perfil-avatar">
              {{ strtoupper(substr($empresa['nombre'] ?? 'E', 0, 1)) }}
            </div>
          </div>

          <div class="col">
            <h1 class="h4 mb-1">{{ $empresa['nombre'] ?? '' }}</h1>
            <p class="mb-1 text-muted">{{ $empresa['rubro'] ?? '' }}</p>
            <p class="mb-0 text-muted">
              {{ $empresa['direccion'] ?? '' }} — {{ $empresa['localidad'] ?? '' }}
            </p>
          </div>

          <div class="col-auto">
            <a href="{{ url('empresa/perfil-empresa-editar') }}" class="btn btn-warning btn-sm">
              <i class="fas fa-edit me-1"></i> Editar perfil
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-12">
        <div class="card perfil-card shadow-sm">
          <div class="card-header"><i class="fas fa-building me-2"></i> Datos de la Empresa</div>
          <div class="card-body">
            <dl class="row mb-0">
              <dt class="col-sm-4 text-muted">Rubro</dt>
              <dd class="col-sm-8 mb-2">{{ $empresa['rubro'] ?? '' }}</dd>

              <dt class="col-sm-4 text-muted">Descripción</dt>
              <dd class="col-sm-8 mb-0">{{ $empresa['descripcion'] ?? '' }}</dd>
            </dl>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="card perfil-card shadow-sm">
          <div class="card-header"><i class="fas fa-map-marker-alt me-2"></i> Ubicación</div>
          <div class="card-body">
            <dl class="row mb-0">
              <dt class="col-sm-4 text-muted">Dirección</dt>
              <dd class="col-sm-8 mb-2">{{ $empresa['direccion'] ?? '' }}</dd>

              <dt class="col-sm-4 text-muted">Localidad / Provincia</dt>
              <dd class="col-sm-8 mb-0">{{ $empresa['localidad'] ?? '' }} — {{ $empresa['provincia'] ?? '' }}</dd>
            </dl>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="card perfil-card shadow-sm">
          <div class="card-header"><i class="fas fa-briefcase me-2"></i> Ofertas publicadas</div>
          <div class="card-body">
            @if(!empty($ofertas))
              <ul class="list-unstyled">
                @foreach($ofertas as $of)
                  <li class="mb-3">
                    <strong>{{ $of['titulo'] ?? '' }}</strong>
                    <div class="text-muted small">{{ $of['modalidad'] ?? '' }} · {{ $of['salario'] ?? '' }}</div>
                  </li>
                @endforeach
              </ul>
            @else
              <div class="text-muted">No hay ofertas publicadas</div>
            @endif
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="card perfil-card shadow-sm">
          <div class="card-header"><i class="fas fa-address-book me-2"></i> Contacto y Redes</div>
          <div class="card-body">
            <dl class="row mb-0">
              <dt class="col-sm-4 text-muted">Correo</dt>
              <dd class="col-sm-8 mb-2">{{ $empresa['email'] ?? '' }}</dd>

              <dt class="col-sm-4 text-muted">Teléfono</dt>
              <dd class="col-sm-8 mb-2">{{ $empresa['telefono'] ?? '' }}</dd>

              <dt class="col-sm-4 text-muted">Sitio web</dt>
              <dd class="col-sm-8 mb-2">
                @if(!empty($empresa['sitio_web']))
                  <a href="{{ $empresa['sitio_web'] }}" target="_blank">{{ $empresa['sitio_web'] }}</a>
                @endif
              </dd>

              <dt class="col-sm-4 text-muted">Redes</dt>
              <dd class="col-sm-8 mb-0">
                @if(!empty($empresa['linkedin']))
                  <a href="{{ $empresa['linkedin'] }}" target="_blank" class="me-2"><i class="fab fa-linkedin fa-lg"></i></a>
                @endif
                @if(!empty($empresa['facebook']))
                  <a href="{{ $empresa['facebook'] }}" target="_blank" class="me-2"><i class="fab fa-facebook fa-lg"></i></a>
                @endif
              </dd>
            </dl>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection


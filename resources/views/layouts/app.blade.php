<!DOCTYPE html>
<html lang="es" data-theme="dark" data-role="{{ auth()->check() ? auth()->user()->rol : 'invitado' }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'KROW — Banco de Trabajo')</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('img/logo_claro.png') }}">
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

{{-- ════ HEADER ════ --}}
@php
  $rol = auth()->check() ? auth()->user()->rol : 'invitado';
@endphp

<header class="krow-header" id="krow-header">
  <div class="header-inner">

    <a href="{{ route('inicio') }}" class="header-logo">
      <img src="{{ asset('img/logo_claro.png') }}" alt="KROW" class="logo-image logo-light">
      <img src="{{ asset('img/logo_oscuro.png') }}" alt="KROW" class="logo-image logo-dark">
      <div class="logo-brand">
        <span class="logo-text">KROW</span>
        <span class="brand-name">Banco de Trabajo</span>
      </div>
    </a>

    <nav class="header-nav" id="header-nav">
      <a href="{{ route('inicio') }}" class="nav-link {{ request()->routeIs('inicio') ? 'active' : '' }}">Inicio</a>

      @if($rol === 'estudiante')
        <a href="{{ route('estudiante.empresas') }}" class="nav-link {{ request()->routeIs('estudiante.empresas') ? 'active' : '' }}">Empresas</a>
        <a href="{{ route('estudiante.home') }}"     class="nav-link {{ request()->routeIs('estudiante.home') ? 'active' : '' }}">Mis Postulaciones</a>
      @elseif($rol === 'empresa')
        <a href="{{ route('empresa.home') }}"        class="nav-link {{ request()->routeIs('empresa.home') ? 'active' : '' }}">Panel Empresa</a>
      @elseif($rol === 'admin')
        <a href="{{ route('admin.home') }}"          class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">Administrar</a>
      @else
        <a href="{{ route('login') }}"               class="nav-link">Empresas</a>
        <a href="{{ route('login') }}"               class="nav-link">Mis Postulaciones</a>
      @endif

      <a href="{{ route('ayuda') }}" class="nav-link {{ request()->routeIs('ayuda') ? 'active' : '' }}">Ayuda</a>
    </nav>

    <div class="header-actions">

      <button class="action-btn" id="theme-toggle" aria-label="Cambiar tema">
        <svg class="icon-sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <circle cx="12" cy="12" r="4"/>
          <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
        </svg>
        <svg class="icon-moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="display:none">
          <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
        </svg>
      </button>

      @auth
        <button class="action-btn notif-btn" aria-label="Notificaciones">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
          </svg>
        </button>

        <div class="dropdown" id="account-dropdown">
          <button class="account-btn" id="account-toggle" aria-haspopup="true" aria-expanded="false">
            <div class="account-avatar">{{ strtoupper(substr($rol, 0, 1)) }}</div>
            <span class="account-name">Mi Cuenta</span>
            <svg class="chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="6 9 12 15 18 9"/>
            </svg>
          </button>
            <div class="dropdown-menu" id="account-menu" role="menu">
            @if(Route::has($rol . '.perfil'))
                <a href="{{ route($rol . '.perfil') }}" class="dropdown-item" role="menuitem">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                    </svg>
                    Mi Perfil
                </a>
            @endif
            <a href="{{ route('mensajes') }}" class="dropdown-item" role="menuitem">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              Mensajes
            </a>
            <a href="{{ route('notificaciones') }}" class="dropdown-item" role="menuitem">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
              Notificaciones
            </a>
            <a href="{{ route('configuracion') }}" class="dropdown-item" role="menuitem">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="3"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
              Configuración
            </a>
            <hr class="dropdown-divider">
            <a href="{{ route('logout') }}" class="dropdown-item dropdown-item-danger" role="menuitem"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
              Cerrar sesión
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">
              @csrf
            </form>
          </div>
        </div>
      @else
        <a href="{{ route('login') }}"    class="btn-ghost-sm">Ingresar</a>
        <a href="{{ route('register') }}" class="btn-primary-sm">Registro</a>
      @endauth

      <button class="hamburger" id="hamburger" aria-label="Menú" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>

    </div>
  </div>
</header>

<main style="flex:1; display:flex; flex-direction:column; overflow:hidden;">
  @yield('content')
</main>

{{-- ════ FOOTER ════ --}}
<footer>
  <div class="site-footer">
    <div class="footer-column">
      <h4>Redes Sociales</h4>
      <ul>
        <li><a href="#">LinkedIn</a></li>
        <li><a href="#">Instagram</a></li>
        <li><a href="#">Twitter</a></li>
      </ul>
    </div>
    <div class="footer-column">
      <h4>Sobre KROW</h4>
      <p>KROW es una plataforma dedicada a conectar profesionales y empresas.</p>
    </div>
    <div class="footer-column">
      <h4>Contacto</h4>
      <ul>
        <li><a href="{{ route('ayuda') }}">Contacto</a></li>
        <li><a href="#">Servicio</a></li>
        <li><a href="#">Privacidad</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; {{ date('Y') }} KROW. Todos los derechos reservados.</p>
  </div>
</footer>

<script src="{{ asset('js/main.js') }}"></script>
@yield('scripts')
</body>
</html>

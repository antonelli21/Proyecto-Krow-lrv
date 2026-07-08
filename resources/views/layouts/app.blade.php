<!DOCTYPE html>
<html lang="es" data-theme="dark" data-role="{{ auth()->check() ? auth()->user()->rol : 'invitado' }}">

<head>
  <script>
      (function () {
        try {
          var theme = localStorage.getItem('krow-theme') || 'dark';
          document.documentElement.setAttribute('data-theme', theme);
        } catch (e) {
          document.documentElement.setAttribute('data-theme', 'dark');
        }
      })();
  </script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="base-url" content="{{ url('') }}">
  <title>@yield('title', 'KROW — Banco de Trabajo')</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('img/logo_claro.png') }}">
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="preload" as="image" href="{{ asset('img/banner.jpg') }}">
  <link rel="preload" as="image" href="{{ asset('img/cat/ingenieria.jpg') }}">
  <link rel="preload" as="image" href="{{ asset('img/cat/diseno.jpg') }}">
  <link rel="preload" as="image" href="{{ asset('img/cat/administracion.jpg') }}">
  <link rel="preload" as="image" href="{{ asset('img/cat/finanzas.jpg') }}">
  <link rel="preload" as="image" href="{{ asset('img/cat/marketing.jpg') }}">
  <link rel="preload" as="image" href="{{ asset('img/cat/recursos-humanos.jpg') }}">
  <link rel="preload" as="image" href="{{ asset('img/cat/tecnologia.jpg') }}">
  <link rel="preload" as="image" href="{{ asset('img/cat/ventas.jpg') }}">
  <link rel="preload" as="image" href="{{ asset('img/cat/industria-y-produccion.jpg') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Sora:wght@700;800&display=swap" rel="stylesheet">
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
        <a href="{{ route('empresas') }}" class="nav-link {{ request()->routeIs('empresas') ? 'active' : '' }}">Empresas</a>
        <a href="{{ route('estudiante.home') }}" class="nav-link {{ request()->routeIs('estudiante.home') ? 'active' : '' }}">Mis Postulaciones</a>
        @elseif($rol === 'empresa')
        <a href="{{ route('empresas') }}" class="nav-link {{ request()->routeIs('empresas') ? 'active' : '' }}">Empresas</a>
        <a href="{{ route('empresa.home') }}" class="nav-link {{ request()->routeIs('empresa.home') ? 'active' : '' }}">Panel Empresa</a>
        @elseif($rol === 'admin')
        <a href="{{ route('empresas') }}" class="nav-link {{ request()->routeIs('empresas') ? 'active' : '' }}">Empresas</a>
        <a href="{{ route('admin.home') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">Administrar</a>
        @else
        <a href="{{ route('empresas') }}" class="nav-link {{ request()->routeIs('empresas') ? 'active' : '' }}">Empresas</a>
        <a href="{{ route('login') }}" class="nav-link">Mis Postulaciones</a>
        @endif

        <a href="{{ route('ayuda') }}" class="nav-link {{ request()->routeIs('ayuda') ? 'active' : '' }}">Ayuda</a>

        @auth
        <div class="nav-mobile-account" aria-label="Mi cuenta móvil">
          <div class="mobile-account-title">Mi Cuenta</div>
          @if(Route::has($rol . '.perfil'))
          <a href="{{ route($rol . '.perfil') }}" class="nav-link mobile-account-link">Mi Perfil</a>
          @endif
          <a href="{{ route('mensajes') }}" class="nav-link mobile-account-link">Mensajes</a>
          <a href="{{ route('configuracion') }}" class="nav-link mobile-account-link">Seguridad</a>
          <a href="{{ route('logout') }}" class="nav-link mobile-account-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar sesión</a>
        </div>
        @else
        <div class="nav-mobile-account" aria-label="Acceso móvil">
          <a href="{{ route('login') }}" class="nav-link mobile-account-link">Ingresar</a>
          <a href="{{ route('register') }}" class="nav-link mobile-account-link">Registro</a>
        </div>
      @endauth
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
        @include('layouts.notificaciones_dropdown')

        @php
          $fotoPerfil = null;
          $inicial = 'E';
          $user = auth()->user();

          if ($user->rol === 'estudiante') {
              $estudianteData = \App\Models\Estudiante::where('id_usuario', $user->id)->first();
              if ($estudianteData) {
                  $fotoPerfil = $estudianteData->foto_perfil;
              }
              $inicial = substr($user->name ?? 'E', 0, 1);
          } elseif ($user->rol === 'empresa') {
              $empresaData = \App\Models\Empresa::where('id_usuario', $user->id)->first();
              if ($empresaData) {
                  $fotoPerfil = $empresaData->logo;
                  $inicial = substr($empresaData->nombre_empresa ?? 'E', 0, 1);
              } else {
                  $inicial = substr($user->name ?? 'E', 0, 1);
              }
          }
        @endphp

        <div class="dropdown" id="account-dropdown">
          <button class="account-btn" id="account-toggle" aria-haspopup="true" aria-expanded="false">
            <div class="account-avatar" style="{{ $fotoPerfil ? 'background-image:url(\'' . \Illuminate\Support\Facades\Storage::url($fotoPerfil) . '\'); background-size:cover; background-position:center;' : '' }}">
              @if(!$fotoPerfil)
              <span class="avatar-initial">{{ strtoupper($inicial) }}</span>
              @endif
            </div>
            <span class="account-name">Mi Cuenta</span>
            <svg class="chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="6 9 12 15 18 9" />
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
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
              </svg>
              Mensajes
            </a>
            <a href="{{ $rol === 'admin' ? route('admin.reportes') : route('ayuda', ['#Contacto']) }}" class="dropdown-item" role="menuitem">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
              </svg>
              Reportes
            </a>
            <a href="{{ route('configuracion') }}" class="dropdown-item" role="menuitem">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 3L5 6v6c0 5 3.5 8 7 9 3.5-1 7-4 7-9V6l-7-3z"/>
                <path d="M10 11V9a2 2 0 1 1 4 0v2"/>
                <rect x="9" y="11" width="6" height="5" rx="1"/>
              </svg>
              Seguridad
            </a>
            <hr class="dropdown-divider">
            <a href="{{ route('logout') }}" class="dropdown-item dropdown-item-danger" role="menuitem"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
              </svg>
              Cerrar sesión
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">
              @csrf
            </form>
          </div>
        </div>

      @else
        <a href="{{ route('login') }}" class="btn-ghost-sm">Ingresar</a>
        <a href="{{ route('register') }}" class="btn-primary-sm">Registro</a>
        @endauth

        <button class="hamburger" id="hamburger" aria-label="Menú" aria-expanded="false">
          <span></span><span></span><span></span>
        </button>

    </div>
    <div class="nav-overlay" id="nav-overlay"></div>
  </div>
</header>

  <main style="flex:1; display:flex; flex-direction:column;">
    @yield('banner')
    @yield('content')
  </main>
  {{-- ════ FOOTER ════ --}}
  <footer>
    <div class="site-footer">
      <div class="footer-column">
        <h4>Redes Sociales</h4>
        <ul>
          <li><a href="https://www.linkedin.com/school/utn-facultad-regional-haedo/">LinkedIn</a></li>
          <li><a href="https://www.instagram.com/utn.frh/?hl=es">Instagram</a></li>
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

  {{-- ════ MODAL GLOBAL PARA EXPANDIR AVATARES/LOGOS ════ --}}
  {{-- Compartido por todos los perfiles (estudiante, empresa, admin). --}}
  {{-- Se abre/cierra con las funciones abrirModalAvatar() / cerrarModalAvatar() de main.js --}}
  <div id="avatarModal" class="avatar-modal" onclick="cerrarModalAvatar()">
      <button type="button" class="avatar-modal-close" onclick="event.stopPropagation(); cerrarModalAvatar()">&times;</button>
      <img class="avatar-modal-content" id="imgModalTarget" onclick="event.stopPropagation()">
  </div>

  <script src="{{ asset('js/main.js') }}"></script>
  @yield('scripts')
</body>
</html>
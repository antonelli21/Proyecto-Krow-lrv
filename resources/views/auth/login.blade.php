{{-- ═══════════════════════════════════════════════════════════
     Vista de Login.
     Formulario de inicio de sesión con validación server-side.
     Muestra errores de Laravel y mantiene el email al fallar.
     Usa @csrf para protección contra ataques CSRF.
═══════════════════════════════════════════════════════════ --}}
@extends('layouts.app')

@section('title', 'Iniciar Sesión - Krow')

@section('content')
<main class="auth-page">
  <div class="auth-card">
    <div class="auth-head">
      <h1>Iniciar Sesión</h1>
      <p>Accede a tu cuenta de Banco de Trabajo</p>
    </div>

    {{-- Mostrar mensaje de éxito (ej: después de verificar email) --}}
    @if(session('success'))
    <div style="background:rgba(108,92,231,0.15); border:1px solid rgba(108,92,231,0.3); border-radius:8px; padding:12px 16px; margin-bottom:20px; color:#a29bfe; font-size:14px;">
      {{ session('success') }}
    </div>
    @endif

    {{-- Mostrar mensaje de error general --}}
    @if(session('error'))
    <div style="background:rgba(231,76,60,0.15); border:1px solid rgba(231,76,60,0.3); border-radius:8px; padding:12px 16px; margin-bottom:20px; color:#e74c3c; font-size:14px;">
      {{ session('error') }}
    </div>
    @endif

    {{-- Formulario de login — envía POST a /login --}}
    <form class="auth-form" id="loginForm" method="POST" action="{{ route('login.post') }}" novalidate>
      {{-- Token CSRF obligatorio en formularios POST de Laravel --}}
      @csrf

      <!-- Campo de Email -->
      <div class="form-group">
        <label for="email">Email</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="2" y="4" width="20" height="16" rx="2" />
            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
          </svg>
          {{-- old('email') mantiene el valor ingresado si falla la validación --}}
          <input type="email" id="email" name="email" placeholder="tu@email.com"
            autocomplete="email" value="{{ old('email') }}">
        </div>
        {{-- Mostrar error de validación del campo email --}}
        @error('email')
        <span class="error-msg" style="display:flex;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          {{ $message }}
        </span>
        @enderror
      </div>

      <!-- Campo de Contraseña -->
      <div class="form-group">
        <label for="password">Contraseña</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
          </svg>
          <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password">
          <button type="button" class="btn-eye" aria-label="Mostrar contraseña" onclick="window.togglePass('password', this)">
            <svg class="icon-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
              <circle cx="12" cy="12" r="3" />
            </svg>
            <svg class="icon-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="display:none">
              <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
              <line x1="1" y1="1" x2="23" y2="23" />
            </svg>
          </button>
        </div>
        {{-- Mostrar error de validación del campo password --}}
        @error('password')
        <span class="error-msg" style="display:flex;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          {{ $message }}
        </span>
        @enderror
      </div>

      <!-- Opciones: Recordarme y Recuperar contraseña -->
        <div class="form-options">
          <label class="checkbox-label">
            {{-- Checkbox "recordarme" para mantener la sesión abierta --}}
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            Recordarme
          </label>
          <a href="{{ route('password.request') }}" class="link-recover">¿Olvidaste tu contraseña?</a>
        </div>

      {{-- Botón de submit del login --}}
      <button type="submit" class="btn-submit">Iniciar Sesión</button>
    </form>

    {{-- Link para ir al registro --}}
    <div class="auth-footer">
      ¿No tienes cuenta? <a href="{{ route('register') }}">Registrate aquí</a>
    </div>
  </div>
</main>
@endsection
@section('scripts')
<script src="{{ asset('js/main.js') }}"></script>
@endsection
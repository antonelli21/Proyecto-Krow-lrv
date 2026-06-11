{{-- ═══════════════════════════════════════════════════════════
     Vista de verificación de email.
     Muestra un formulario para ingresar el código de 6 dígitos
     que el usuario recibió por email después de registrarse.
     Incluye opción para reenviar el código si expiró.
═══════════════════════════════════════════════════════════ --}}
@extends('layouts.app')

@section('title', 'Verificar Email - Krow')

@section('content')
  <main class="auth-page">
    <div class="auth-card">
      <div class="auth-head">
        <h1>Verificar Email</h1>
        <p>Ingresá el código de 6 dígitos que enviamos a tu correo</p>
      </div>

      {{-- Mostrar mensajes de éxito (ej: código reenviado) --}}
      @if(session('success'))
        <div class="alert-success" style="background:rgba(108,92,231,0.15); border:1px solid rgba(108,92,231,0.3); border-radius:8px; padding:12px 16px; margin-bottom:20px; color:#a29bfe; font-size:14px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle; margin-right:6px;">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
          </svg>
          {{ session('success') }}
        </div>
      @endif

      {{-- Formulario de verificación de código --}}
      <form class="auth-form active" id="formVerificacion" method="POST" action="{{ route('verificacion.verificar') }}" novalidate>
        @csrf

        {{-- Campo de código de verificación --}}
        <div class="form-group">
          <label for="codigo">Código de verificación</label>
          <div class="input-wrap">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
              <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
            <input type="text" id="codigo" name="codigo" placeholder="123456"
                   maxlength="6" pattern="[0-9]{6}" inputmode="numeric"
                   autocomplete="one-time-code"
                   value="{{ old('codigo') }}"
                   style="letter-spacing:8px; text-align:center; font-size:20px; font-weight:600;">
          </div>
          {{-- Mostrar errores de validación del código --}}
          @error('codigo')
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

        {{-- Botón de verificar --}}
        <button type="submit" class="btn-submit">Verificar Email</button>
      </form>

      {{-- Opción para reenviar el código --}}
      <div class="auth-footer" style="text-align:center; margin-top:16px;">
        <p style="color:var(--text-secondary); font-size:14px; margin-bottom:8px;">
          ¿No recibiste el código?
        </p>
        {{-- Formulario para reenviar código (usa POST) --}}
        <form method="POST" action="{{ route('verificacion.reenviar') }}" style="display:inline;">
          @csrf
          <button type="submit" style="background:none; border:none; color:var(--accent); cursor:pointer; font-size:14px; text-decoration:underline; padding:0;">
            Reenviar código
          </button>
        </form>
      </div>

      {{-- Link para volver al login --}}
      <div class="auth-footer">
        <a href="{{ route('login') }}">← Volver al inicio de sesión</a>
      </div>
    </div>
  </main>
@endsection

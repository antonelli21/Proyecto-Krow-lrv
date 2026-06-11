@extends('layouts.app')

@section('title', 'Recuperar Contraseña - Krow')

@section('content')
<main class="auth-page">
  <div class="auth-card">
    <div class="auth-head">
      <h1>Recuperar Contraseña</h1>
      <p>Ingresá tu email y te enviaremos un enlace para restablecerla</p>
    </div>

    @if (session('status'))
      <div style="background:rgba(108,92,231,0.15); border:1px solid rgba(108,92,231,0.3); border-radius:8px; padding:12px 16px; margin-bottom:20px; color:#a29bfe; font-size:14px;">
        {{ session('status') }}
      </div>
    @endif

    <form class="auth-form" method="POST" action="{{ route('password.email') }}" novalidate>
      @csrf

      <div class="form-group">
        <label for="email">Email</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="2" y="4" width="20" height="16" rx="2" />
            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
          </svg>
          <input type="email" id="email" name="email" placeholder="tu@email.com" value="{{ old('email') }}" required autocomplete="email" autofocus>
        </div>
        @error('email')
          <span class="error-msg" style="display:flex;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ $message }}
          </span>
        @enderror
      </div>

      <button type="submit" class="btn-submit">Enviar enlace de recuperación</button>
    </form>

    <div class="auth-footer">
      <a href="{{ route('login') }}">← Volver a Iniciar Sesión</a>
    </div>
  </div>
</main>
@endsection

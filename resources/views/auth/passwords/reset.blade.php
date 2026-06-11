@extends('layouts.app')

@section('title', 'Restablecer Contraseña - Krow')

@section('content')
<main class="auth-page">
  <div class="auth-card">
    <div class="auth-head">
      <h1>Nueva Contraseña</h1>
      <p>Ingresá tu nueva contraseña para acceder a tu cuenta</p>
    </div>

    <form class="auth-form" method="POST" action="{{ route('password.update') }}" novalidate>
      @csrf

      <input type="hidden" name="token" value="{{ $token }}">

      <div class="form-group">
        <label for="email">Email</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="2" y="4" width="20" height="16" rx="2" />
            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
          </svg>
          <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" readonly>
        </div>
        @error('email')
          <span class="error-msg" style="display:flex;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ $message }}
          </span>
        @enderror
      </div>

      <div class="form-group">
        <label for="password">Nueva Contraseña</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
          </svg>
          <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="new-password">
          <button type="button" class="btn-eye" aria-label="Mostrar contraseña" onclick="window.togglePass('password', this)">
            <svg class="icon-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            <svg class="icon-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
          </button>
        </div>
        @error('password')
          <span class="error-msg" style="display:flex;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ $message }}
          </span>
        @enderror
      </div>

      <div class="form-group">
        <label for="password-confirm">Confirmar Contraseña</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
          </svg>
          <input type="password" id="password-confirm" name="password_confirmation" placeholder="••••••••" required autocomplete="new-password">
          <button type="button" class="btn-eye" aria-label="Mostrar contraseña" onclick="window.togglePass('password-confirm', this)">
            <svg class="icon-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            <svg class="icon-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
          </button>
        </div>
      </div>

      <button type="submit" class="btn-submit">Restablecer Contraseña</button>
    </form>
  </div>
</main>
@endsection

@section('scripts')
<script>
  window.togglePass = function(inputId, btn) {
      const input = document.getElementById(inputId);
      if (!input || !btn) return;
      const open = btn.querySelector('.icon-open');
      const closed = btn.querySelector('.icon-closed');
      if (input.type === 'password') {
          input.type = 'text';
          if (open) open.style.display = 'none';
          if (closed) closed.style.display = 'inline';
          btn.setAttribute('aria-label', 'Ocultar contraseña');
      } else {
          input.type = 'password';
          if (open) open.style.display = 'inline';
          if (closed) closed.style.display = 'none';
          btn.setAttribute('aria-label', 'Mostrar contraseña');
      }
  };
</script>
@endsection

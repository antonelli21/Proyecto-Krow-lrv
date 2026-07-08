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
 <style>
.auth-page {
    position: relative;
    width: 100%;
    min-height: calc(100vh - 80px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
    background: var(--bg) url('{{ asset("img/img_registro.png") }}') center top/cover no-repeat;
    overflow: hidden;
}

  /* ── Viñeta: oscurece hacia los bordes, deja el centro más visible ── */
  .auth-page::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(
      ellipse at center,
      rgba(0, 0, 0, 0) 0%,
      rgba(0, 0, 0, 0.35) 55%,
      rgba(0, 0, 0, 0.75) 100%
    );
    pointer-events: none;
    z-index: 1;
  }

  .auth-card {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 520px;
    background: rgba(20, 16, 12, 0.65);
    backdrop-filter: blur(8px);
    border: 1px solid var(--border);
    border-radius: 0px;
    padding: 2.25rem 2rem;
    box-shadow: var(--shadow-card);
    animation: fadeInUp 0.35s ease;
  }

  .auth-head {
    margin-bottom: 1.25rem;
  }

  .auth-head h1 {
    font-family: var(--font-display);
    font-size: 1.75rem;
    font-weight: 800;
    color: white; 
    margin-bottom: 0.35rem;
    line-height: 1.2;
  }

  .auth-head p {
    font-size: 0.9rem;
    color: var(--muted);
  }

    .auth-card .form-group label{
      color:#fff;
      font-weight:500;
  }


</style>
@endsection

@extends('layouts.app')

@section('title', 'Crear Cuenta - Krow')

@section('content')

@php
$carreras = [
      'Ingeniería Aeronáutica/Aeroespacial',
      'Ingeniería Electrónica',
      'Ingeniería Ferroviaria',
      'Ingeniería Industrial',
      'Ingeniería Mecánica',
      'Bioingeniería',
      'Tecnicatura en Programación',
      'Tecnicatura en Operación de Aeronaves',
      'Tecnicatura en Material Rodante Ferroviario',
      'Tecnicatura en Desarrollo y Producción de Videojuegos',
      'Tecnicatura en Higiene y Seguridad en el Trabajo',
      'Tecnicatura en Comercio Electrónico y Marketing Digital',
      'Tecnicatura en Logística',
];
@endphp

<style>
  /* ═══════════════════════════════════════════════════════════
   REGISTRO / AUTH — KROW
   CSS normalizado, compatible y responsive
   ═══════════════════════════════════════════════════════════ */

  /* ── Layout ── */
  .auth-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
    background: var(--bg);
  }

  .auth-card {
    width: 100%;
    max-width: 520px;
    background: var(--surface);
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
    color: var(--text);
    margin-bottom: 0.35rem;
    line-height: 1.2;
  }

  .auth-head p {
    font-size: 0.9rem;
    color: var(--muted);
  }

  /* ── Tabs ── */
  .role-tabs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
  }

  .role-tab {
    padding: 0.6rem;
    border: 1px solid var(--border);
    border-radius: 0px;
    background: var(--bg-input);
    color: var(--muted);
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s;
    text-align: center;
  }

  .role-tab:hover {
    border-color: var(--accent);
    color: var(--text);
  }

  .role-tab.active {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
  }

  [data-theme="dark"] .role-tab.active {
    background: var(--accent);
    color: #111118;
    border-color: var(--accent);
  }

  /* ── Forms ── */
  .auth-form {
    display: none;
    flex-direction: column;
    gap: 1.1rem;
  }

  .auth-form.active {
    display: flex;
  }

  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
  }

  .form-group {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
  }

  .form-group label {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text);
    text-transform: uppercase;
    letter-spacing: 0.6px;
  }

  .input-wrap {
    position: relative;
    display: flex;
    align-items: center;
  }

  .input-wrap>svg:first-child {
    position: absolute;
    left: 12px;
    color: var(--muted);
    pointer-events: none;
    flex-shrink: 0;
  }

  .input-wrap input,
  .input-wrap select {
    width: 100%;
    padding: 0.7rem 2.8rem 0.7rem 2.6rem;
    border: 1px solid var(--border);
    border-radius: 0px;
    background: var(--bg-input);
    color: var(--text);
    font-size: 0.95rem;
    font-family: var(--font-body);
    transition: border-color 0.2s, box-shadow 0.2s;
    appearance: none;
    line-height: 1.4;
    min-height: 44px;
  }

  .input-wrap select {
    cursor: pointer;
    padding-right: 2.8rem;
  }

  .input-wrap input:focus,
  .input-wrap select:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(43, 107, 232, 0.12);
  }

  [data-theme="dark"] .input-wrap input:focus,
  [data-theme="dark"] .input-wrap select:focus {
    box-shadow: 0 0 0 3px rgba(212, 168, 67, 0.12);
  }

  .input-wrap input::placeholder {
    color: var(--text-muted);
  }

  .input-wrap input.input-error,
  .input-wrap select.input-error {
    border-color: var(--destructive);
    box-shadow: 0 0 0 3px rgba(212, 24, 61, 0.12);
  }

  .select-chevron {
    position: absolute;
    right: 12px;
    color: var(--muted);
    pointer-events: none;
    flex-shrink: 0;
  }

  /* ── Botón ojo ── */
  .btn-eye {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: var(--muted);
    padding: 4px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
    transition: color 0.15s;
  }

  .btn-eye:hover {
    color: var(--accent);
  }

  /* ── Error msg ── */
  .error-msg {
    font-size: 0.8rem;
    color: var(--destructive);
    display: none;
    align-items: center;
    gap: 4px;
    margin-top: 2px;
  }

  .error-msg.show {
    display: flex;
  }

  /* ── Submit ── */
  .btn-submit {
    width: 100%;
    padding: 0.85rem;
    border: none;
    border-radius: 0px;
    background: var(--primary);
    color: #fff;
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: opacity 0.2s, transform 0.15s;
    margin-top: 0.25rem;
    min-height: 48px;
  }

  .btn-submit:hover {
    opacity: 0.9;
    transform: translateY(-1px);
  }

  [data-theme="dark"] .btn-submit {
    background: var(--accent);
    color: #111118;
  }

  /* ── Footer link ── */
  .auth-footer {
    text-align: center;
    margin-top: 1.25rem;
    font-size: 0.85rem;
    color: var(--muted);
  }

  .auth-footer a {
    color: var(--primary);
    font-weight: 600;
    text-decoration: none;
  }

  .auth-footer a:hover {
    text-decoration: underline;
  }

  [data-theme="dark"] .auth-footer a {
    color: var(--accent);
  }

  /* ── Animaciones ── */
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(12px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* ═══════════════════════════════════════════════════════════
   RESPONSIVE — Registro
   ═══════════════════════════════════════════════════════════ */

  @media (max-width: 640px) {
    .auth-page {
      padding: 1rem;
      align-items: flex-start;
      padding-top: 1.5rem;
    }

    .auth-card {
      padding: 1.5rem 1.25rem;
      max-width: 100%;
    }

    .auth-head h1 {
      font-size: 1.5rem;
    }

    .form-row {
      grid-template-columns: 1fr;
      gap: 1.1rem;
    }

    .input-wrap input,
    .input-wrap select {
      font-size: 16px;
      /* Previene zoom en iOS */
      padding: 0.75rem 2.6rem 0.75rem 2.4rem;
    }

    .role-tab {
      font-size: 0.85rem;
      padding: 0.55rem;
    }

    .btn-submit {
      font-size: 0.95rem;
      padding: 0.9rem;
    }
  }

  @media (max-width: 380px) {
    .auth-card {
      padding: 1.25rem 1rem;
    }

    .auth-head h1 {
      font-size: 1.35rem;
    }

    .input-wrap input,
    .input-wrap select {
      padding: 0.7rem 2.4rem 0.7rem 2.2rem;
      font-size: 15px;
    }

    .input-wrap>svg:first-child {
      left: 10px;
      width: 16px;
      height: 16px;
    }

    .btn-eye svg {
      width: 16px;
      height: 16px;
    }
  }
</style>

<main class="auth-page">
  <div class="auth-card">
    <div class="auth-head">
      <h1>Crear Cuenta</h1>
      <p>Únete a Banco de Trabajo hoy</p>
    </div>

    <!-- Tabs -->
    <div class="role-tabs" id="roleTabs">
      <button type="button" class="role-tab active" data-role="candidato">Candidato</button>
      <button type="button" class="role-tab" data-role="empresa">Empresa</button>
    </div>

    <!-- FORM CANDIDATO — Envía POST a /registro/estudiante -->
    <form class="auth-form active" id="formCandidato" method="POST" action="{{ route('register.estudiante') }}" novalidate>
      @csrf
      <div class="form-row">
        <div class="form-group">
          <label for="c-nombre">Nombre</label>
          <div class="input-wrap">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
            <input type="text" id="c-nombre" name="nombre" placeholder="Juan" autocomplete="given-name" value="{{ old('nombre') }}">
          </div>
          <span class="error-msg" id="err-c-nombre">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10" />
              <line x1="12" y1="8" x2="12" y2="12" />
              <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            Ingresa tu nombre.
          </span>
        </div>
        <div class="form-group">
          <label for="c-apellido">Apellido</label>
          <div class="input-wrap">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
            <input type="text" id="c-apellido" name="apellido" placeholder="Pérez" autocomplete="family-name" value="{{ old('apellido') }}">
          </div>
          <span class="error-msg" id="err-c-apellido">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10" />
              <line x1="12" y1="8" x2="12" y2="12" />
              <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            Ingresa tu apellido.
          </span>
        </div>
      </div>

      <div class="form-group">
        <label for="c-email">Email institucional</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="2" y="4" width="20" height="16" rx="2" />
            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
          </svg>
          <input type="email" id="c-email" name="email" placeholder="tu@alumnos.frh.utn.edu.ar" autocomplete="email" value="{{ old('email') }}">
        </div>
        @error('email')
        <span class="error-msg show">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          {{ $message }}
        </span>
        @enderror
        <span class="error-msg" id="err-c-email">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          Ingresa un email válido.
        </span>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="c-telefono">Teléfono</label>
          <div class="input-wrap">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
            </svg>
            <input type="tel" id="c-telefono" name="telefono" placeholder="11 2345-6789" autocomplete="tel" value="{{ old('telefono') }}">
          </div>
          <span class="error-msg" id="err-c-telefono">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10" />
              <line x1="12" y1="8" x2="12" y2="12" />
              <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            Ingresa un teléfono válido.
          </span>
        </div>
        <div class="form-group">
          <label for="c-nacimiento">Fecha de nacimiento</label>
          <div class="input-wrap">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
              <line x1="16" y1="2" x2="16" y2="6" />
              <line x1="8" y1="2" x2="8" y2="6" />
              <line x1="3" y1="10" x2="21" y2="10" />
            </svg>
            <input type="date" id="c-nacimiento" name="nacimiento" autocomplete="bday" value="{{ old('nacimiento') }}">
          </div>
          <span class="error-msg" id="err-c-nacimiento">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10" />
              <line x1="12" y1="8" x2="12" y2="12" />
              <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            Selecciona tu fecha de nacimiento.
          </span>
        </div>
      </div>

      <div class="form-group">
        <label for="c-carrera">Carrera (UTN Haedo)</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
            <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5" />
          </svg>
          <select id="c-carrera" name="carrera">
            <option value="" disabled {{ old('carrera') ? '' : 'selected' }}>Seleccioná tu carrera</option>
            @foreach($carreras as $c)
            <option value="{{ $c }}" {{ old('carrera') == $c ? 'selected' : '' }}>{{ $c }}</option>
            @endforeach
          </select>
          <svg class="select-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="6 9 12 15 18 9" />
          </svg>
        </div>
        <span class="error-msg" id="err-c-carrera">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          Seleccioná una carrera.
        </span>
      </div>

      <div class="form-group">
        <label for="c-password">Contraseña</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
          </svg>
          <input type="password" id="c-password" name="password" placeholder="••••••••" autocomplete="new-password">
          <button type="button" class="btn-eye" aria-label="Mostrar contraseña" onclick="togglePass('c-password', this)">
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
        <span class="error-msg" id="err-c-password">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          Mínimo 6 caracteres.
        </span>
      </div>

      <div class="form-group">
        <label for="c-password2">Confirmar contraseña</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
          </svg>
          <input type="password" id="c-password2" name="password_confirmation" placeholder="••••••••" autocomplete="new-password">
          <button type="button" class="btn-eye" aria-label="Mostrar contraseña" onclick="togglePass('c-password2', this)">
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
        <span class="error-msg" id="err-c-password2">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          Las contraseñas no coinciden.
        </span>
      </div>

      <button type="submit" class="btn-submit">Crear Cuenta</button>
    </form>

    <!-- FORM EMPRESA — Envía POST a /registro/empresa -->
    <form class="auth-form" id="formEmpresa" method="POST" action="{{ route('register.empresa') }}" novalidate>
      @csrf
      <div class="form-group">
        <label for="e-nombre">Nombre de la empresa</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M3 21h18M5 21V7l8-4 8 4v14M9 21v-6h6v6" />
          </svg>
          <input type="text" id="e-nombre" name="nombre_empresa" placeholder="Ej: TechCorp SA" autocomplete="organization" value="{{ old('nombre_empresa') }}">
        </div>
        <span class="error-msg" id="err-e-nombre">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          Ingresa el nombre de la empresa.
        </span>
      </div>

      <div class="form-group">
        <label for="e-razon">Razón social</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
            <polyline points="14 2 14 8 20 8" />
            <line x1="16" y1="13" x2="8" y2="13" />
            <line x1="16" y1="17" x2="8" y2="17" />
          </svg>
          <input type="text" id="e-razon" name="razon_social" placeholder="Ej: TechCorp S.A." autocomplete="organization" value="{{ old('razon_social') }}">
        </div>
        <span class="error-msg" id="err-e-razon">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          Ingresa la razón social.
        </span>
      </div>

      <div class="form-group">
        <label for="e-email">Email</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="2" y="4" width="20" height="16" rx="2" />
            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
          </svg>
          <input type="email" id="e-email" name="email" placeholder="contacto@empresa.com" autocomplete="email" value="{{ old('email') }}">
        </div>
        <span class="error-msg" id="err-e-email">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          Ingresa un email válido.
        </span>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="e-cuit">CUIT</label>
          <div class="input-wrap">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <line x1="4" y1="9" x2="20" y2="9" />
              <line x1="4" y1="15" x2="20" y2="15" />
              <line x1="10" y1="3" x2="8" y2="21" />
              <line x1="16" y1="3" x2="14" y2="21" />
            </svg>
            <input type="text" id="e-cuit" name="cuit" placeholder="30-12345678-9" maxlength="13" autocomplete="off" value="{{ old('cuit') }}">
          </div>
          @error('cuit')
          <span class="error-msg show">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10" />
              <line x1="12" y1="8" x2="12" y2="12" />
              <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            {{ $message }}
          </span>
          @enderror
          <span class="error-msg" id="err-e-cuit">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10" />
              <line x1="12" y1="8" x2="12" y2="12" />
              <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            Ingresa un CUIT válido (11 dígitos).
          </span>
        </div>
        <div class="form-group">
          <label for="e-telefono">Teléfono</label>
          <div class="input-wrap">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
            </svg>
            <input type="tel" id="e-telefono" name="telefono" placeholder="11 2345-6789" autocomplete="tel" value="{{ old('telefono') }}">
          </div>
          <span class="error-msg" id="err-e-telefono">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10" />
              <line x1="12" y1="8" x2="12" y2="12" />
              <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            Ingresa un teléfono válido.
          </span>
        </div>
      </div>

      <div class="form-group">
        <label for="e-ubicacion">Ubicación</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
            <circle cx="12" cy="10" r="3" />
          </svg>
          <input type="text" id="e-ubicacion" name="ubicacion" placeholder="Ciudad / Provincia" autocomplete="address-level1" value="{{ old('ubicacion') }}">
        </div>
        <span class="error-msg" id="err-e-ubicacion">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          Ingresa la ubicación.
        </span>
      </div>

      <div class="form-group">
        <label for="e-password">Contraseña</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
          </svg>
          <input type="password" id="e-password" name="password" placeholder="••••••••" autocomplete="new-password">
          <button type="button" class="btn-eye" aria-label="Mostrar contraseña" onclick="togglePass('e-password', this)">
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
        <span class="error-msg" id="err-e-password">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          Mínimo 6 caracteres.
        </span>
      </div>

      <div class="form-group">
        <label for="e-password2">Confirmar contraseña</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
          </svg>
          <input type="password" id="e-password2" name="password_confirmation" placeholder="••••••••" autocomplete="new-password">
          <button type="button" class="btn-eye" aria-label="Mostrar contraseña" onclick="togglePass('e-password2', this)">
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
        <span class="error-msg" id="err-e-password2">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          Las contraseñas no coinciden.
        </span>
      </div>

      <button type="submit" class="btn-submit">Crear Cuenta</button>
    </form>

    <div class="auth-footer">
      ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
    </div>
  </div>
</main>

<script>
  /* ── Mostrar / Ocultar contraseña ── */
  function togglePass(inputId, btn) {
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
  }

  /* ── Cambio de Tabs (Candidato / Empresa) ── */
  document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.role-tab');
    const formCand = document.getElementById('formCandidato');
    const formEmp = document.getElementById('formEmpresa');

    // Restaurar tab activo si hubo error de validación en empresa
    const hasEmpresaErrors = {
      {
        (old('cuit') || $errors - > has('cuit') || $errors - > has('nombre_empresa') || $errors - > has('razon_social')) ? 'true' : 'false'
      }
    };

    if (hasEmpresaErrors) {
      tabs.forEach(t => t.classList.remove('active'));
      document.querySelector('[data-role="empresa"]').classList.add('active');
      formCand.classList.remove('active');
      formEmp.classList.add('active');
    }

    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        const role = tab.dataset.role;
        if (role === 'candidato') {
          formCand.classList.add('active');
          formEmp.classList.remove('active');
        } else {
          formEmp.classList.add('active');
          formCand.classList.remove('active');
        }
      });
    });
  });
  

  /* ── Validación de formularios ── */
  document.addEventListener('DOMContentLoaded', function() {
    const formCand = document.getElementById('formCandidato');
    const formEmp = document.getElementById('formEmpresa');

    function showError(id, show) {
      const el = document.getElementById(id);
      if (el) el.classList.toggle('show', show);
    }

    function setInputError(id, isError) {
      const input = document.getElementById(id);
      if (input) input.classList.toggle('input-error', isError);
    }

    function clearErrors(form) {
      form.querySelectorAll('.error-msg').forEach(e => e.classList.remove('show'));
      form.querySelectorAll('input, select').forEach(i => i.classList.remove('input-error'));
    }

    function validateEmail(email) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function validatePhone(tel) {
      return /^[\d\s\-\+\(\)]{7,}$/.test(tel);
    }

    function validateCUIT(cuit) {
      const clean = cuit.replace(/\D/g, '');
      return clean.length === 11;
    }

    if (formCand) {
      formCand.addEventListener('submit', function(e) {
        clearErrors(formCand);
        let valid = true;

        const nombre = document.getElementById('c-nombre');
        if (!nombre.value.trim() || nombre.value.trim().length < 2) {
          showError('err-c-nombre', true);
          setInputError('c-nombre', true);
          valid = false;
        }

        const apellido = document.getElementById('c-apellido');
        if (!apellido.value.trim() || apellido.value.trim().length < 2) {
          showError('err-c-apellido', true);
          setInputError('c-apellido', true);
          valid = false;
        }

        const email = document.getElementById('c-email');
        if (!email.value.trim() || !validateEmail(email.value.trim())) {
          showError('err-c-email', true);
          setInputError('c-email', true);
          valid = false;
        }

        const telefono = document.getElementById('c-telefono');
        if (!telefono.value.trim() || !validatePhone(telefono.value.trim())) {
          showError('err-c-telefono', true);
          setInputError('c-telefono', true);
          valid = false;
        }

        const nacimiento = document.getElementById('c-nacimiento');
        if (!nacimiento.value) {
          showError('err-c-nacimiento', true);
          setInputError('c-nacimiento', true);
          valid = false;
        }

        const carrera = document.getElementById('c-carrera');
        if (!carrera.value) {
          showError('err-c-carrera', true);
          setInputError('c-carrera', true);
          valid = false;
        }

        const pass = document.getElementById('c-password');
        if (!pass.value || pass.value.length < 6) {
          showError('err-c-password', true);
          setInputError('c-password', true);
          valid = false;
        }

        const pass2 = document.getElementById('c-password2');
        if (pass2.value !== pass.value || !pass2.value) {
          showError('err-c-password2', true);
          setInputError('c-password2', true);
          valid = false;
        }

        if (!valid) e.preventDefault();
      });

      // Limpiar errores al escribir
      formCand.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('input', function() {
          this.classList.remove('input-error');
          const errId = 'err-' + this.id;
          const errEl = document.getElementById(errId);
          if (errEl) errEl.classList.remove('show');
        });
      });
    }

    if (formEmp) {
      formEmp.addEventListener('submit', function(e) {
        clearErrors(formEmp);
        let valid = true;

        const nombre = document.getElementById('e-nombre');
        if (!nombre.value.trim() || nombre.value.trim().length < 2) {
          showError('err-e-nombre', true);
          setInputError('e-nombre', true);
          valid = false;
        }

        const razon = document.getElementById('e-razon');
        if (!razon.value.trim() || razon.value.trim().length < 2) {
          showError('err-e-razon', true);
          setInputError('e-razon', true);
          valid = false;
        }

        const email = document.getElementById('e-email');
        if (!email.value.trim() || !validateEmail(email.value.trim())) {
          showError('err-e-email', true);
          setInputError('e-email', true);
          valid = false;
        }

        const cuit = document.getElementById('e-cuit');
        if (!cuit.value.trim() || !validateCUIT(cuit.value.trim())) {
          showError('err-e-cuit', true);
          setInputError('e-cuit', true);
          valid = false;
        }

        const telefono = document.getElementById('e-telefono');
        if (!telefono.value.trim() || !validatePhone(telefono.value.trim())) {
          showError('err-e-telefono', true);
          setInputError('e-telefono', true);
          valid = false;
        }

        const ubicacion = document.getElementById('e-ubicacion');
        if (!ubicacion.value.trim()) {
          showError('err-e-ubicacion', true);
          setInputError('e-ubicacion', true);
          valid = false;
        }

        const pass = document.getElementById('e-password');
        if (!pass.value || pass.value.length < 6) {
          showError('err-e-password', true);
          setInputError('e-password', true);
          valid = false;
        }

        const pass2 = document.getElementById('e-password2');
        if (pass2.value !== pass.value || !pass2.value) {
          showError('err-e-password2', true);
          setInputError('e-password2', true);
          valid = false;
        }

        if (!valid) e.preventDefault();
      });

      // Limpiar errores al escribir
      formEmp.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('input', function() {
          this.classList.remove('input-error');
          const errId = 'err-' + this.id;
          const errEl = document.getElementById(errId);
          if (errEl) errEl.classList.remove('show');
        });
      });
    }
  });
</script>

@endsection
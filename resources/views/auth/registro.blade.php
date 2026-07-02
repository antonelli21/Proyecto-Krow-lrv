@extends('layouts.app')

@section('title', 'Crear Cuenta - Krow')

@section('content')

@php
/*
* $carreras — Collection de App\Models\Carrera (id_carrera, nombre)
* $provincias — Collection de App\Models\Provincia (id_provincia, nombre)
* Ambas se pasan desde RegisterController::showRegistrationForm()
*/

// Detectar si hubo errores del lado empresa para restaurar el tab activo.
// Se usa una variable PHP para evitar mezclar -> de Eloquent dentro de {{ }} en JS,
// lo que causaba errores de parseo de Blade.
$tabEmpresaActivo = $errors->hasAny(['cuit', 'nombre_empresa', 'razon_social', 'id_provincia', 'id_localidad'])
|| old('nombre_empresa')
|| old('cuit');

// Agrupar localidades por id_provincia para pasarlas a JS como JSON.
$localidadesPorProvincia = \App\Models\Localidad::orderBy('nombre')
->get()
->groupBy('id_provincia');
@endphp

<style>
  /* ═══════════════════════════════════════════════════════════
   REGISTRO / AUTH — KROW
   ═══════════════════════════════════════════════════════════ */

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
    color: var(--text);
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
    background: var(--accent);
    color: var(--text_btn);
    border-color: var(--accent);
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
    background: var(--toolbar_bg);
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

  .input-wrap select:disabled {
    opacity: 0.5;
    cursor: not-allowed;
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
    background: var(--accent);
    color: var(--text_btn);
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
    color: var(--accent);
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
   RESPONSIVE
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

<main class="auth-page"
  data-localidades="{{ json_encode($localidadesPorProvincia) }}"
  data-old-provincia="{{ old('id_provincia', '') }}"
  data-old-localidad="{{ old('id_localidad', '') }}">
  <div class="auth-card">
    <div class="auth-head">
      <h1>Crear Cuenta</h1>
      <p>Únete a Banco de Trabajo hoy</p>
    </div>

    <!-- Tabs -->
    <div class="role-tabs" id="roleTabs">
      <button type="button" class="role-tab {{ $tabEmpresaActivo ? '' : 'active' }}" data-role="candidato">Candidato</button>
      <button type="button" class="role-tab {{ $tabEmpresaActivo ? 'active' : '' }}" data-role="empresa">Empresa</button>
    </div>

    {{-- ═══════════════════════════════════════════════
         FORM CANDIDATO — POST a /registro/estudiante
    ════════════════════════════════════════════════ --}}
    <form class="auth-form {{ $tabEmpresaActivo ? '' : 'active' }}" id="formCandidato" method="POST" action="{{ route('register.estudiante') }}" novalidate>
      @csrf
      <div class="form-row">
        {{-- Nombre --}}
        <div class="form-group">
          <label for="c-nombre">Nombre</label>
          <div class="input-wrap">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
            <input type="text" id="c-nombre" name="nombre" placeholder="Juan" autocomplete="given-name" value="{{ old('nombre') }}">
          </div>
          @error('nombre')
          <span class="error-msg show">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10" />
              <line x1="12" y1="8" x2="12" y2="12" />
              <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            {{ $message }}
          </span>
          @enderror
          <span class="error-msg" id="err-c-nombre">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10" />
              <line x1="12" y1="8" x2="12" y2="12" />
              <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            Ingresa tu nombre.
          </span>
        </div>

        {{-- Apellido --}}
        <div class="form-group">
          <label for="c-apellido">Apellido</label>
          <div class="input-wrap">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
            <input type="text" id="c-apellido" name="apellido" placeholder="Pérez" autocomplete="family-name" value="{{ old('apellido') }}">
          </div>
          @error('apellido')
          <span class="error-msg show">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10" />
              <line x1="12" y1="8" x2="12" y2="12" />
              <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            {{ $message }}
          </span>
          @enderror
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

      {{-- Email --}}
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
        {{-- Teléfono --}}
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

        {{-- Fecha de nacimiento --}}
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
            Seleccioná tu fecha de nacimiento.
          </span>
        </div>
      </div>

      {{-- Carrera — datos desde DB via controller --}}
      <div class="form-group">
        <label for="c-carrera">Carrera (UTN Haedo)</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
            <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5" />
          </svg>
          <select id="c-carrera" name="carrera">
            <option value="" disabled {{ old('carrera') ? '' : 'selected' }}>Seleccioná tu carrera</option>
            @forelse($carreras as $carrera)
            <option value="{{ $carrera->nombre }}" {{ old('carrera') === $carrera->nombre ? 'selected' : '' }}>
              {{ $carrera->nombre }}
            </option>
            @empty
            <option value="" disabled>No hay carreras disponibles</option>
            @endforelse
          </select>
          <svg class="select-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="6 9 12 15 18 9" />
          </svg>
        </div>
        @error('carrera')
        <span class="error-msg show">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          {{ $message }}
        </span>
        @enderror
        <span class="error-msg" id="err-c-carrera">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          Seleccioná una carrera.
        </span>
      </div>

      {{-- Contraseña --}}
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

      {{-- Confirmar contraseña --}}
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

    {{-- ═══════════════════════════════════════════════
         FORM EMPRESA — POST a /registro/empresa
    ════════════════════════════════════════════════ --}}
    <form class="auth-form {{ $tabEmpresaActivo ? 'active' : '' }}" id="formEmpresa" method="POST" action="{{ route('register.empresa') }}" novalidate>
      @csrf

      {{-- Nombre empresa --}}
      <div class="form-group">
        <label for="e-nombre">Nombre de la empresa</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M3 21h18M5 21V7l8-4 8 4v14M9 21v-6h6v6" />
          </svg>
          <input type="text" id="e-nombre" name="nombre_empresa" placeholder="Ej: TechCorp SA" autocomplete="organization" value="{{ old('nombre_empresa') }}">
        </div>
        @error('nombre_empresa')
        <span class="error-msg show">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          {{ $message }}
        </span>
        @enderror
        <span class="error-msg" id="err-e-nombre">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          Ingresa el nombre de la empresa.
        </span>
      </div>

      {{-- Razón social --}}
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
        @error('razon_social')
        <span class="error-msg show">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          {{ $message }}
        </span>
        @enderror
        <span class="error-msg" id="err-e-razon">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          Ingresa la razón social.
        </span>
      </div>

      {{-- Email --}}
      <div class="form-group">
        <label for="e-email">Email</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="2" y="4" width="20" height="16" rx="2" />
            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
          </svg>
          <input type="email" id="e-email" name="email" placeholder="contacto@empresa.com" autocomplete="email" value="{{ old('email') }}">
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
        {{-- CUIT --}}
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

        {{-- Teléfono --}}
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

      {{-- Provincia + Localidad: solo se muestran si hay datos en la DB.
           Si la tabla provincia está vacía, los campos se omiten completamente
           para que el form siempre pueda enviarse. --}}
      @if($provincias->isNotEmpty())
      <div class="form-group">
        <label for="e-provincia">Provincia</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
            <circle cx="12" cy="10" r="3" />
          </svg>
          <select id="e-provincia" name="id_provincia">
            <option value="">Seleccioná provincia</option>
            @foreach($provincias as $provincia)
            <option value="{{ $provincia->id_provincia }}" {{ old('id_provincia') == $provincia->id_provincia ? 'selected' : '' }}>
              {{ $provincia->nombre }}
            </option>
            @endforeach
          </select>
          <svg class="select-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="6 9 12 15 18 9" />
          </svg>
        </div>
        @error('id_provincia')
        <span class="error-msg show">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          {{ $message }}
        </span>
        @enderror
        <span class="error-msg" id="err-e-provincia">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          Seleccioná una provincia.
        </span>
      </div>

      {{-- Localidad: sin "disabled" — los campos disabled NO se envían en el POST.
           Se usa pointer-events + opacity para la apariencia de desactivado. --}}
      <div class="form-group">
        <label for="e-localidad">Localidad</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
            <circle cx="12" cy="10" r="3" />
          </svg>
          <select id="e-localidad" name="id_localidad" style="opacity:0.45;pointer-events:none;">
            <option value="">Primero seleccioná una provincia</option>
          </select>
          <svg class="select-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="6 9 12 15 18 9" />
          </svg>
        </div>
        @error('id_localidad')
        <span class="error-msg show">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          {{ $message }}
        </span>
        @enderror
      </div>
      @endif

      {{-- Contraseña --}}
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

      {{-- Confirmar contraseña --}}
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
  /* ═══════════════════════════════════════════════════════════
   Localidades agrupadas por provincia y valores old() se pasan
   como data-attributes en <main> para evitar que el linter de
   VS Code los confunda con sintaxis JS inválida.
   ═══════════════════════════════════════════════════════════ */
  const _authPage = document.querySelector('.auth-page');
  const localidadesPorProvincia = JSON.parse(_authPage.dataset.localidades || '{}');
  const oldProvincia = _authPage.dataset.oldProvincia || '';
  const oldLocalidad = _authPage.dataset.oldLocalidad || '';

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

  /* ── Carga dinámica de localidades ──
     NO usar .disabled: los campos disabled no se envían en el POST.
     Se usa style.opacity + style.pointerEvents para la apariencia. ── */
  function cargarLocalidades(idProvincia, selectedId) {
    const sel = document.getElementById('e-localidad');
    if (!sel) return;
    const localidades = localidadesPorProvincia[idProvincia] || [];

    sel.innerHTML = '';

    if (localidades.length === 0) {
      sel.innerHTML = '<option value="">Sin localidades cargadas</option>';
      sel.style.opacity = '0.45';
      sel.style.pointerEvents = 'none';
      return;
    }

    const placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.disabled = true;
    placeholder.selected = !selectedId;
    placeholder.textContent = 'Seleccioná una localidad';
    sel.appendChild(placeholder);

    localidades.forEach(function(loc) {
      const opt = document.createElement('option');
      opt.value = loc.id_localidad;
      opt.textContent = loc.nombre;
      if (String(loc.id_localidad) === String(selectedId)) {
        opt.selected = true;
      }
      sel.appendChild(opt);
    });

    sel.style.opacity = '1';
    sel.style.pointerEvents = 'auto';
  }

  const formCand = document.getElementById('formCandidato');
  const formEmp = document.getElementById('formEmpresa');

  document.addEventListener('DOMContentLoaded', function() {

    /* ── Tabs (Candidato / Empresa) ── */
    const tabs = document.querySelectorAll('.role-tab');
    const formCand = document.getElementById('formCandidato');
    const formEmp = document.getElementById('formEmpresa');

    tabs.forEach(function(tab) {
      tab.addEventListener('click', function() {
        tabs.forEach(function(t) {
          t.classList.remove('active');
        });
        tab.classList.add('active');

        if (tab.dataset.role === 'candidato') {
          formCand.classList.add('active');
          formEmp.classList.remove('active');
        } else {
          formEmp.classList.add('active');
          formCand.classList.remove('active');
        }
      });
    });
  });


  /* ── Provincia → carga localidades al cambiar ── */
  const selectProv = document.getElementById('e-provincia');
  selectProv.addEventListener('change', function() {
    cargarLocalidades(this.value, null);
  });

  /* Restaurar localidades si hubo error de validación y había valores old() */
  if (oldProvincia) {
    cargarLocalidades(oldProvincia, oldLocalidad);
  }

  /* ══════════════════════════════════════════════════
     VALIDACIÓN — Candidato
  ══════════════════════════════════════════════════ */
  function showError(id, show) {
    const el = document.getElementById(id);
    if (el) el.classList.toggle('show', show);
  }

  function setInputError(id, isError) {
    const input = document.getElementById(id);
    if (input) input.classList.toggle('input-error', isError);
  }

  function clearErrors(form) {
    form.querySelectorAll('.error-msg').forEach(function(e) {
      e.classList.remove('show');
    });
    form.querySelectorAll('input, select').forEach(function(i) {
      i.classList.remove('input-error');
    });
  }

  function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function validatePhone(tel) {
    return /^[\d\s\-\+\(\)]{7,}$/.test(tel);
  }

  function validateCUIT(cuit) {
    return cuit.replace(/\D/g, '').length === 11;
  }

  /* Candidato */
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
      if (telefono.value.trim() && !validatePhone(telefono.value.trim())) {
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
      if (!pass2.value || pass2.value !== pass.value) {
        showError('err-c-password2', true);
        setInputError('c-password2', true);
        valid = false;
      }

      if (!valid) e.preventDefault();
    });

    formCand.querySelectorAll('input, select').forEach(function(input) {
      input.addEventListener('input', function() {
        this.classList.remove('input-error');
        const errEl = document.getElementById('err-' + this.id);
        if (errEl) errEl.classList.remove('show');
      });
    });
  }

  /* Empresa */
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

      const provincia = document.getElementById('e-provincia');
      if (provincia && !provincia.value) {
        showError('err-e-provincia', true);
        setInputError('e-provincia', true);
        valid = false;
      }

      const pass = document.getElementById('e-password');
      if (!pass.value || pass.value.length < 6) {
        showError('err-e-password', true);
        setInputError('e-password', true);
        valid = false;
      }

      const pass2 = document.getElementById('e-password2');
      if (!pass2.value || pass2.value !== pass.value) {
        showError('err-e-password2', true);
        setInputError('e-password2', true);
        valid = false;
      }

      if (!valid) e.preventDefault();
    });

    formEmp.querySelectorAll('input, select').forEach(function(input) {
      input.addEventListener('input', function() {
        this.classList.remove('input-error');
        const errEl = document.getElementById('err-' + this.id);
        if (errEl) errEl.classList.remove('show');
      });
    });
  } /* fin DOMContentLoaded */
</script>

@endsection
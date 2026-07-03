@extends('layouts.app')

@section('title', 'Configuración — KROW')

@push('styles')
@endpush

@section('content')

@php
$usuario = auth()->user();
@endphp

<div class="config-layout"  >

    {{-- ══ SEGURIDAD ══ --}}
    <main class="config-main" >

        <h3 class="config-section-title">
            <i class="bi bi-shield-lock"></i> Seguridad
        </h3>

        {{-- ── Cambiar contraseña ── --}}
        <div class="config-card" style="margin-bottom: 2rem;">
            <div class="config-card-header">Cambiar contraseña</div>
            <div class="config-card-body">

                @if (session('password_ok'))
                <div class="config-alert config-alert-success">
                    <i class="bi bi-check-circle"></i> Contraseña actualizada correctamente.
                </div>
                @endif

                <form action="{{ route('configuracion.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="config-field">
                        <label class="config-label" for="password_actual">Contraseña actual</label>
                        <div class="input-wrap">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                            <input type="password" id="password_actual" name="password_actual"
                                class="{{ $errors->has('password_actual') ? 'error' : '' }}"
                                autocomplete="current-password" placeholder="••••••••" required>
                            <button type="button" class="btn-eye" aria-label="Mostrar contraseña" onclick="window.togglePass('password_actual', this)">
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
                        @error('password_actual')
                        <span class="config-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="config-field">
                        <label class="config-label" for="password_nuevo">Nueva contraseña</label>
                        <div class="input-wrap">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                            <input type="password" id="password_nuevo" name="password"
                                class="{{ $errors->has('password') ? 'error' : '' }}"
                                autocomplete="new-password" placeholder="••••••••" required>
                            <button type="button" class="btn-eye" aria-label="Mostrar contraseña" onclick="window.togglePass('password_nuevo', this)">
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
                        @error('password')
                        <span class="config-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="config-field">
                        <label class="config-label" for="password_confirmar">Confirmar contraseña</label>
                        <div class="input-wrap">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                            <input type="password" id="password_confirmar" name="password_confirmation"
                                autocomplete="new-password" placeholder="••••••••" required>
                            <button type="button" class="btn-eye" aria-label="Mostrar contraseña" onclick="window.togglePass('password_confirmar', this)">
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
                    </div>

                    <div class="config-actions">
                        <button type="submit" class="btn-apply-filters">Guardar cambios</button>
                    </div>

                </form>
            </div>
        </div>

        {{-- ── Información de sesión ── --}}
        <div class="config-card" style="margin-bottom: 2rem;">
            <div class="config-card-header">Información de sesión</div>
            <div class="config-card-body">
                <div class="stat-row">
                    <span class="stat-label">Último acceso</span>
                    <span class="stat-value">
                        {{ $usuario->last_login_at
                            ? \Carbon\Carbon::parse($usuario->last_login_at)->format('d/m/Y - H:i')
                            : 'No disponible' }}
                    </span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Dispositivo actual</span>
                    <span class="stat-value" id="config-device">—</span>
                </div>
            </div>
        </div>

        {{-- ── Zona de peligro ── --}}
        <div class="config-card config-card-danger">
            <div class="config-card-header">Zona de peligro</div>
            <div class="config-card-body">

                @if (session('logout_all_ok'))
                <div class="config-alert config-alert-success">
                    <i class="bi bi-check-circle"></i> Se cerraron todas las demás sesiones activas.
                </div>
                @endif

                <p class="config-muted">
                    Cerrá sesión en todos los dispositivos conectados a tu cuenta.
                </p>
                <form action="{{ route('configuracion.logout-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-danger">
                        <i class="bi bi-box-arrow-right"></i> Cerrar sesiones
                    </button>
                </form>
            </div>
        </div>

    </main>

</div>

@endsection
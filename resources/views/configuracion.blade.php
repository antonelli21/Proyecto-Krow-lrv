@extends('layouts.app')
 
@section('title', 'Configuración — KROW')
 
@push('styles')
<link rel="stylesheet" href="{{ asset('css/configuracion.css') }}">
@endpush
 
@section('content')
 
@php
    $usuario = auth()->user();
@endphp
 
<div class="config-layout">
 
    {{-- ══ SEGURIDAD ══ --}}
    <main class="config-main">
 
        <h3 class="config-section-title">
            <i class="bi bi-shield-lock"></i> Seguridad
        </h3>
 
        {{-- ── Cambiar contraseña ── --}}
        <div class="config-card">
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
                        <input type="password" id="password_actual" name="password_actual"
                               class="filter-input-text {{ $errors->has('password_actual') ? 'input-error' : '' }}"
                               autocomplete="current-password" required>
                        @error('password_actual')
                            <span class="config-error">{{ $message }}</span>
                        @enderror
                    </div>
 
                    <div class="config-field">
                        <label class="config-label" for="password_nuevo">Nueva contraseña</label>
                        <input type="password" id="password_nuevo" name="password"
                               class="filter-input-text {{ $errors->has('password') ? 'input-error' : '' }}"
                               autocomplete="new-password" required>
                        @error('password')
                            <span class="config-error">{{ $message }}</span>
                        @enderror
                    </div>
 
                    <div class="config-field">
                        <label class="config-label" for="password_confirmar">Confirmar contraseña</label>
                        <input type="password" id="password_confirmar" name="password_confirmation"
                               class="filter-input-text"
                               autocomplete="new-password" required>
                    </div>
 
                    <div class="config-actions">
                        <button type="submit" class="btn-apply-filters">Guardar cambios</button>
                    </div>
 
                </form>
            </div>
        </div>
 
        {{-- ── Información de sesión ── --}}
        <div class="config-card">
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
                <p class="config-muted">
                    Cerrá sesión en todos los dispositivos conectados a tu cuenta.
                </p>
                <form action="{{ route('configuracion.logout-all') }}" method="POST" id="form-logout-all">
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
 
@section('scripts')
<script src="{{ asset('js/configuracion.js') }}"></script>
@endsection
 
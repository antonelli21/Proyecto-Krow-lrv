@extends('layouts.app')

@section('title', 'Iniciar Sesión - Krow')

@section('content')

<section class="login-page">

    <div class="login-overlay"></div>

    <section class="login-card">

        <div class="login-header">
            <h1>Iniciar Sesión</h1>
            <p>Accede a tu cuenta de Banco de Trabajo</p>
        </div>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        <form class="auth-form" method="POST" action="{{ route('login.post') }}" novalidate>

            @csrf

            <div class="form-group">

                <label for="email">Email</label>

                <div class="input-wrap">

                    <!-- Icono -->

                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="tu@email.com"
                        autocomplete="email"
                        value="{{ old('email') }}"
                    >

                </div>

                @error('email')
                    <span class="error-msg">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <div class="form-group">

                <label for="password">Contraseña</label>

                <div class="input-wrap">

                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        autocomplete="current-password"
                    >

                  <button
                      type="button"
                      class="btn-eye"
                      aria-label="Mostrar contraseña"
                      onclick="window.togglePass('password', this)">

                      <svg class="icon-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                          <circle cx="12" cy="12" r="3"/>
                      </svg>

                      <svg class="icon-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="display:none">
                          <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                          <line x1="1" y1="1" x2="23" y2="23"/>
                      </svg>

                  </button>

                </div>

                @error('password')
                    <span class="error-msg">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <div class="form-options">

                <label class="checkbox-label">

                    <input
                        type="checkbox"
                        name="remember"
                        {{ old('remember') ? 'checked' : '' }}
                    >

                    Recordarme

                </label>

                <a href="{{ route('password.request') }}" class="link-recover">
                    ¿Olvidaste tu contraseña?
                </a>

            </div>

            <button class="btn-submit">
                Iniciar Sesión
            </button>

        </form>

        <div class="auth-footer">

            ¿No tienes cuenta?

            <a href="{{ route('register') }}">
                Registrate aquí
            </a>

        </div>

    </section>

</section>

@endsection

@push('scripts')
<script src="{{ asset('js/main.js') }}"></script>
@endpush
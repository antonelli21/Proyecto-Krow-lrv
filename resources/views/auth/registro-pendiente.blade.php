{{-- ═══════════════════════════════════════════════════════════
     Vista de Registro Pendiente.
     Se muestra a las empresas después de verificar su correo
     indicando que su cuenta está pendiente de aprobación.
═══════════════════════════════════════════════════════════ --}}
@extends('layouts.app')

@section('title', 'Cuenta Pendiente de Aprobación - Krow')

@section('content')
  <main class="auth-page">
    <div class="auth-card" style="text-align:center; padding: 40px;">
      <div style="margin-bottom: 24px; color: #a29bfe;">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin: 0 auto;">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="8" x2="12" y2="12"></line>
          <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
      </div>
      <div class="auth-head">
        <h1 style="font-size: 24px; margin-bottom: 12px; color: #fff;">Cuenta creada con éxito</h1>
        <p style="font-size: 16px; color: #b0b0b0;">Tu cuenta de empresa está en revisión.</p>
      </div>
      <div style="margin-top: 24px; margin-bottom: 32px; color: #888; font-size: 15px; line-height: 1.6;">
        <p>Aguardá la aprobación del administrador.</p>
        <p>Te enviaremos un correo electrónico cuando tu cuenta sea verificada y aprobada para que puedas acceder al sistema y publicar ofertas.</p>
      </div>
      <a href="{{ route('inicio') }}" class="btn-submit" style="display: inline-block; text-align: center; text-decoration: none; padding: 12px 24px; background: #6c5ce7; color: #fff; border-radius: 8px; font-weight: 600; width: auto;">
        Volver a inicio
      </a>
    </div>
  </main>
@endsection

@extends('layouts.app')

@section('title', 'Ayuda — KROW')

@section('banner')
<style>
#banner-index::before{
    content: "";
    position: absolute;
    inset: 0;

    background: url("{{ asset('img/banner.jpg') }}") center top / cover no-repeat;

    filter: blur(8px);
    transform: scale(1.08);

    z-index: 0;
}

#banner-index > *{
    position: relative;
    z-index: 1;
}
</style>
<div id="banner-index" style="
    width:100%;
    height:600px;
    position:relative;
    overflow:hidden;
    margin:0;
">

    <div  style="
        position:absolute;
        inset:0;
        background:linear-gradient(to right, rgba(0,0,0,.6), rgba(0,0,0,.4));
    "></div>

    <div id="pad" style="
        position:absolute;
        inset:0;
        display:flex;
        flex-direction:column;
        justify-content:start;
        align-items:center;
        text-align:center;
        z-index:2;
        padding-top: 1.5rem;
    ">
</div>
</div>
@endsection

@section('content')

@php
$faqs = [
['id'=>1,'pregunta'=>'¿Cómo creo una cuenta en KROW?','respuesta'=>'Para crear una cuenta, hacé clic en "Registro" en la parte superior derecha de la página. Completá tus datos personales, verificá tu email y listo. El proceso toma menos de 2 minutos. Podés registrarte como estudiante para buscar ofertas laborales, o como empresa para publicar vacantes.'],
['id'=>2,'pregunta'=>'¿Cómo postulo a una oferta de trabajo?','respuesta'=>'Iniciá sesión con tu cuenta de estudiante, navegá por la base de ofertas o usá los filtros para encontrar lo que buscás. Al hacer clic en una oferta, verás el botón "Postularme". Tu perfil y CV se enviarán automáticamente a la empresa. Podés seguir el estado de tu postulación desde "Mis Postulaciones".'],
['id'=>3,'pregunta'=>'¿Puedo editar mi perfil después de crearlo?','respuesta'=>'Sí, en cualquier momento. Ingresá a "Mi Perfil" desde el menú desplegable de tu cuenta (arriba a la derecha). Ahí podés actualizar tus datos personales, experiencia laboral, estudios, habilidades y subir un nuevo CV. Los cambios se reflejan inmediatamente en tus futuras postulaciones.'],
['id'=>4,'pregunta'=>'¿Cómo puedo ver el estado de mis postulaciones?','respuesta'=>'Accedé a la sección "Mis Postulaciones" desde el menú principal. Allí verás una tabla con todas tus postulaciones, la fecha de envío, el puesto, la empresa y el estado actual (Postulado, En revisión, Preseleccionado, Contacto directo o Rechazado). Hacé clic en cualquier postulación para ver más detalles.'],
['id'=>5,'pregunta'=>'¿Las empresas pueden contactarme directamente?','respuesta'=>'Sí. Si tu perfil está completo y marcás la opción "Visible para empresas", las empresas registradas en KROW podrán encontrarte en la base de candidatos y contactarte directamente a través de la plataforma. Siempre recibirás una notificación cuando una empresa te envíe un mensaje.'],
['id'=>7,'pregunta'=>'¿Cómo publico una oferta laboral como empresa?','respuesta'=>'Registrate como empresa, verificá tu cuenta y accedé al "Panel Empresa". Desde allí hacé clic en "Nueva Oferta", completá los datos del puesto (título, descripción, requisitos, modalidad, salario) y publicala. Las ofertas se revisan en menos de 24 horas antes de aparecer en la plataforma.'],
['id'=>8,'pregunta'=>'¿Qué hago si olvidé mi contraseña?','respuesta'=>'En la pantalla de inicio de sesión, hacé clic en "¿Olvidaste tu contraseña?". Ingresá tu email registrado y te enviaremos un enlace seguro para restablecerla. El enlace expira en 1 hora por seguridad. Si no recibís el email, revisá tu carpeta de spam.'],
];
@endphp

<style>
  /* ═══════════════════════════════════════════════════════════
   AYUDA / HELP PAGE — KROW
   CSS corregido, compatible con el design system global
   y completamente responsive
   ═══════════════════════════════════════════════════════════ */

  /* ── Contenedor principal de la página de ayuda ── */
  .ayuda-page {
    max-width: 900px;
    margin: 0 auto;
    padding: 36px 24px 60px;
    display: flex;
    flex-direction: column;
    gap: 40px;
  }

  /* Cuando está dentro del layout de 3 columnas, ajustamos */
  .page-body .ayuda-page {
    max-width: 100%;
    padding: 0;
    margin: 0;
  }

  /* ── Títulos de sección ── */
  .ayuda-section-title {
    font-family: var(--font-display, system-ui);
    font-size: 24px;
    font-weight: 800;
    color: var(--accent);
    margin-bottom: 10px;
    line-height: 1.2;
  }

  .ayuda-section-sub {
    font-size: 14.5px;
    color: var(--muted);
    margin-top: 10px;
    margin-bottom: 20px;
    line-height: 1.5;
  }

  /* ── FAQ Acordeones ── */
  .faq-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .faq-item {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 0px;
    overflow: hidden;
    transition: border-color .2s, box-shadow .2s;
  }

  .faq-item:hover {
    border-color: var(--accent);
  }

  .faq-item.active {
    border-color: var(--accent);
    box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
  }

  [data-theme="dark"] .faq-item.active {
    box-shadow: 0 2px 12px rgba(0, 0, 0, .25);
  }

  .faq-question {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 16px 20px;
    background: none;
    border: none;
    cursor: pointer;
    text-align: left;
    font-size: 15px;
    font-weight: 600;
    color: var(--text);
    transition: color .2s;
    font-family: inherit;
  }

  .faq-question:hover {
    color: var(--accent);
  }

  .faq-question-text {
    flex: 1;
    line-height: 1.4;
  }

  .faq-chevron {
    width: 20px;
    height: 20px;
    color: var(--muted);
    flex-shrink: 0;
    transition: transform .3s ease, color .2s;
  }

  .faq-item.active .faq-chevron {
    transform: rotate(180deg);
    color: var(--accent);
  }

  .faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height .35s ease, padding .35s ease;
  }

  .faq-item.active .faq-answer {
    max-height: 500px;
  }

  .faq-answer-inner {
    padding: 0 20px 18px 20px;
    font-size: 14px;
    color: var(--muted);
    line-height: 1.7;
  }

  /* ── Formulario de contacto ── */
  .contacto-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 0px;
    padding: 28px;
  }

  .contacto-form {
    display: flex;
    flex-direction: column;
    gap: 18px;
  }

  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }

  .form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .form-group label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text);
  }

  .form-group label .required {
    color: var(--destructive, #d4183d);
    margin-left: 2px;
  }

  .form-input,
  .form-textarea {
    width: 100%;
    padding: 11px 14px;
    border: 1px solid var(--border);
    border-radius: 0px;
    background: var(--toolbar_bg);
    color: var(--text);
    font-size: 14px;
    font-family: inherit;
    transition: border-color .2s, box-shadow .2s;
    resize: vertical;
  }

  .form-input::placeholder,
  .form-textarea::placeholder {
    color: var(--muted);
    opacity: .6;
  }

  .form-input:focus,
  .form-textarea:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(43, 107, 232, .10);
  }

  [data-theme="dark"] .form-input:focus,
  [data-theme="dark"] .form-textarea:focus {
    box-shadow: 0 0 0 3px rgba(212, 168, 67, .12);
  }

  .form-textarea {
    min-height: 120px;
    line-height: 1.6;
  }

  /* Estado de error — el JS agrega .input-error a los campos inválidos
     al enviar el formulario; faltaba el estilo correspondiente para
     que se note tanto en inputs como en el textarea. */
  .form-input.input-error,
  .form-textarea.input-error {
    border-color: var(--destructive);
    box-shadow: 0 0 0 3px rgba(212, 24, 61, .12);
  }

  .form-hint {
    font-size: 12px;
    color: var(--muted);
    margin-top: 2px;
  }

  /* ── Mensajes de error de validación ── */
  .form-error {
    font-size: 12.5px;
    color: var(--destructive);
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .form-error::before {
    content: "⚠";
    font-size: 14px;
  }

  /* ── Botón enviar ── */
  .btn-enviar {
    width: 100%;
    padding: 12px;
    background: var(--accent);
    color: var(--text_btn);
    border: none;
    border-radius: 0px;
    font-family: var(--font-display, system-ui);
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    transition: opacity .2s, box-shadow .2s, transform .15s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 4px;
  }

  .btn-enviar:hover {
    opacity: .9;
    box-shadow: 0 4px 16px rgba(26, 58, 110, .25);
    transform: translateY(-1px);
  }

  .btn-enviar:active {
    transform: translateY(0);
  }

  [data-theme="dark"] .btn-enviar {
    background: var(--accent);
    color: #111118;
  }

  [data-theme="dark"] .btn-enviar:hover {
    box-shadow: 0 4px 20px rgba(212, 168, 67, .25);
  }

  .btn-enviar:disabled {
    opacity: .5;
    cursor: not-allowed;
    transform: none;
  }

  /* ── Estado de envío ── */
  .form-status {
    display: none;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    border-radius: 0px;
    font-size: 14px;
    font-weight: 500;
    animation: fadeInUp .3s ease;
  }

  .form-status.show {
    display: flex;
  }

  .form-status.success {
    background: rgba(46, 204, 154, .10);
    border: 1px solid rgba(46, 204, 154, .3);
    color: #2a9d6f;
  }

  [data-theme="dark"] .form-status.success {
    background: rgba(46, 204, 154, .08);
    color: #2ECC9A;
  }

  .form-status.error {
    background: rgba(212, 24, 61, .08);
    border: 1px solid rgba(212, 24, 61, .25);
    color: var(--destructive);
  }

  /* Spinner */
  .spinner {
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255, 255, 255, .3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin .8s linear infinite;
    display: none;
  }

  [data-theme="dark"] .spinner {
    border-color: rgba(17, 17, 24, .3);
    border-top-color: #111118;
  }

  .btn-enviar.loading .spinner {
    display: block;
  }

  .btn-enviar.loading .btn-text {
    display: none;
  }

  /* ── Animaciones ── */
  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(8px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* ═══════════════════════════════════════════════════════════
   RESPONSIVE — Ayuda
   ═══════════════════════════════════════════════════════════ */

  @media (max-width: 900px) {
    .ayuda-page {
      padding: 24px 16px 40px;
      gap: 32px;
    }

    .contacto-card {
      padding: 20px;
    }
  }

  @media (max-width: 640px) {
    .form-row {
      grid-template-columns: 1fr;
    }

    .ayuda-section-title {
      font-size: 20px;
    }

    .faq-question {
      padding: 14px 16px;
      font-size: 14px;
    }

    .faq-answer-inner {
      padding: 0 16px 14px 16px;
      font-size: 13.5px;
    }

    .contacto-card {
      padding: 16px;
    }

    .btn-enviar {
      padding: 10px;
      font-size: 13px;
    }
  }

  @media (max-width: 480px) {
    .ayuda-page {
      padding: 16px 12px 32px;
      gap: 24px;
    }

    .ayuda-section-title {
      font-size: 18px;
    }

    .ayuda-section-sub {
      font-size: 13px;
    }

    .faq-question {
      padding: 12px 14px;
      font-size: 13.5px;
    }

    .faq-answer-inner {
      padding: 0 14px 12px 14px;
      font-size: 13px;
    }

    .form-input,
    .form-textarea {
      padding: 10px 12px;
      font-size: 13px;
    }

    .contacto-card {
      padding: 14px;
    }
  }


    /* ── Mapa ── */
  .mapa-container{
      width:100%;
      height:380px;
      border:1px solid var(--border);
      border-radius:8px;
      overflow:hidden;
      margin-top:16px;
  }

  .mapa-container iframe{
      width:100%;
      height:100%;
      border:0;
      display:block;
  }

  @media (max-width:640px){
      .mapa-container{
          height:280px;
      }
  }

  /* ── Ajuste para layout de 3 columnas ── */
  @media (max-width: 680px) {
    .page-body .ayuda-page {
      padding: 16px;
    }
  }
</style>


<main class="ayuda-page" style="position: relative; z-index: 5; margin-top: -530px !important; margin-bottom:80px;background-color:var(--bg) ;opacity: 0.95; border-radius: 8px; border:1px solid var(--accent); justify-content:start;box-shadow:
0 20px 50px var(--shadow-color),
0 0px 30px var(--shadow-glow);">

  {{-- FAQs --}}
  <section>
    <h1 class="ayuda-section-title">Preguntas Frecuentes</h1>
    <p class="ayuda-section-sub">Encontrá respuestas a las consultas más comunes sobre el uso de KROW.</p>

    <div class="faq-list" id="faq-list">
      @foreach($faqs as $faq)
      <div class="faq-item" data-faq-id="{{ $faq['id'] }}">
        <button class="faq-question" aria-expanded="false" onclick="toggleFaq(this)">
          <span class="faq-question-text">{{ $faq['pregunta'] }}</span>
          <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="6 9 12 15 18 9" />
          </svg>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            {!! nl2br(e($faq['respuesta'])) !!}
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </section>

  {{-- Formulario de contacto --}}
  <section>
    <div class="contacto-card" id="Contacto" style="scroll-margin-top: 100px;">
      <h2 class="ayuda-section-title" style="margin-bottom:4px;">Contáctanos</h2>
      <p class="ayuda-section-sub">¿No encontraste lo que buscabas? Envianos un mensaje y te responderemos lo antes posible.</p>

      <form class="contacto-form" id="form-contacto" action="{{ route('ayuda.contacto') }}" method="POST" novalidate>
        @csrf

        <div class="form-row">
          <div class="form-group">
            <label for="nombre">Nombre <span class="required">*</span></label>
            <input type="text" id="nombre" name="nombre" class="form-input" placeholder="Tu nombre"
              value="{{ old('nombre') }}" required>
            @error('nombre')<span class="form-error">{{ $message }}</span>@enderror
          </div>
          <div class="form-group">
            <label for="email">Email <span class="required">*</span></label>
            <input type="email" id="email" name="email" class="form-input" placeholder="tu@email.com"
              value="{{ old('email') }}" required>
            @error('email')<span class="form-error">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-group">
          <label for="asunto">Asunto <span class="required">*</span></label>
          <input type="text" id="asunto" name="asunto" class="form-input" placeholder="¿En qué podemos ayudarte?"
            value="{{ old('asunto') }}" required>
          @error('asunto')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
          <label for="mensaje">Mensaje <span class="required">*</span></label>
          <textarea id="mensaje" name="mensaje" class="form-textarea"
            placeholder="Describí tu consulta o problema..." required>{{ old('mensaje') }}</textarea>
          <span class="form-hint">Mínimo 20 caracteres. Sé lo más específico posible para que podamos ayudarte mejor.</span>
          @error('mensaje')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        @if(session('contacto_ok'))
        <div class="form-status form-status-ok show">✓ Mensaje enviado correctamente. Te responderemos pronto.</div>
        @endif

        <div class="form-status" id="form-status"></div>

        <button type="submit" class="btn-enviar" id="btn-enviar">
          <span class="btn-text">Enviar mensaje</span>
          <span class="spinner"></span>
        </button>

      </form>
    </div>
  </section>
    {{-- Ubicación --}}
    <section>
        <div class="contacto-card">
            <h2 class="ayuda-section-title" style="margin-bottom:4px;">¿Dónde estamos?</h2>
            <p class="ayuda-section-sub">
                Podés visitarnos en nuestras oficinas de la UTN Haedo.
            </p>

            <div class="mapa-container">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3282.5591441910246!2d-58.60461128823923!3d-34.64057945933379!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bc951c0fe2d9f5%3A0x9f1c540898efecbe!2sUTN%20HAEDO!5e0!3m2!1ses-419!2sar!4v1782676186106!5m2!1ses-419!2sar"
                    loading="lazy"
                    allowfullscreen
                    referrerpolicy="strict-origin-when-cross-origin">
                </iframe>
            </div>
        </div>
    </section>



</main>

<script>
  /**
   * Toggle FAQ accordion
   */
  function toggleFaq(btn) {
    const item = btn.closest('.faq-item');
    const wasActive = item.classList.contains('active');

    // Cerrar todos los demás (comportamiento acordeón)
    document.querySelectorAll('.faq-item.active').forEach(function(el) {
      el.classList.remove('active');
      el.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
    });

    // Abrir el clickeado si no estaba activo
    if (!wasActive) {
      item.classList.add('active');
      btn.setAttribute('aria-expanded', 'true');
    }
  }

  /**
   * Validación del formulario de contacto
   */
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-contacto');
    const btn = document.getElementById('btn-enviar');
    const status = document.getElementById('form-status');

    if (!form) return;

    form.addEventListener('submit', function(e) {
      let errors = [];

      // Validar nombre
      const nombre = document.getElementById('nombre');
      if (!nombre.value.trim() || nombre.value.trim().length < 2) {
        errors.push('El nombre debe tener al menos 2 caracteres.');
        nombre.classList.add('input-error');
      } else {
        nombre.classList.remove('input-error');
      }

      // Validar email
      const email = document.getElementById('email');
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!email.value.trim() || !emailRegex.test(email.value.trim())) {
        errors.push('Ingresá un email válido.');
        email.classList.add('input-error');
      } else {
        email.classList.remove('input-error');
      }

      // Validar asunto
      const asunto = document.getElementById('asunto');
      if (!asunto.value.trim() || asunto.value.trim().length < 3) {
        errors.push('El asunto debe tener al menos 3 caracteres.');
        asunto.classList.add('input-error');
      } else {
        asunto.classList.remove('input-error');
      }

      // Validar mensaje
      const mensaje = document.getElementById('mensaje');
      if (!mensaje.value.trim() || mensaje.value.trim().length < 20) {
        errors.push('El mensaje debe tener al menos 20 caracteres.');
        mensaje.classList.add('input-error');
      } else {
        mensaje.classList.remove('input-error');
      }

      // Mostrar errores o enviar
      if (errors.length > 0) {
        e.preventDefault();
        status.className = 'form-status error show';
        status.innerHTML = '⚠ ' + errors.join(' ');
        return false;
      }

      // Estado de carga
      btn.classList.add('loading');
      btn.disabled = true;
      status.className = 'form-status';
    });

    // Limpiar errores al escribir
    ['nombre', 'email', 'asunto', 'mensaje'].forEach(function(id) {
      const el = document.getElementById(id);
      if (el) {
        el.addEventListener('input', function() {
          this.classList.remove('input-error');
          if (status.classList.contains('error')) {
            status.className = 'form-status';
          }
        });
      }
    });
  });
</script>

@endsection
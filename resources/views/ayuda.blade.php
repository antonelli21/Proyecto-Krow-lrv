@extends('layouts.app')

@section('title', 'Ayuda — KROW')

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

<main>

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
            <polyline points="6 9 12 15 18 9"/>
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
    <div class="contacto-card">
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
          <div class="form-status form-status-ok">✓ Mensaje enviado correctamente. Te responderemos pronto.</div>
        @endif

        <div class="form-status" id="form-status"></div>

        <button type="submit" class="btn-enviar" id="btn-enviar">
          <span class="btn-text">Enviar mensaje</span>
          <span class="spinner"></span>
        </button>

      </form>
    </div>
  </section>

</main>

@endsection
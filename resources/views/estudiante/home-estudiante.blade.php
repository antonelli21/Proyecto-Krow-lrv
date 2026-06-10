@extends('layouts.app')

@section('title', 'Panel Estudiante — KROW')

@section('content')

<div class="panel-page">

  <h1 class="panel-page-title">Panel del Estudiante</h1>
  <p class="panel-page-sub">Seguí el estado de tus postulaciones y gestioná tu perfil</p>

  <!-- Stats -->
  <div class="stats-row">
    <div class="stat-card">
      <div>
        <p class="stat-card-label">Postulaciones Activas</p>
        <span class="stat-card-value">4</span>
      </div>
      <i class="bi bi-send stat-card-icon"></i>
    </div>
    <div class="stat-card">
      <div>
        <p class="stat-card-label">Total Postulaciones</p>
        <span class="stat-card-value">12</span>
      </div>
      <i class="bi bi-clipboard-check stat-card-icon"></i>
    </div>
    <div class="stat-card">
      <div>
        <p class="stat-card-label">Mensajes</p>
        <span class="stat-card-value">3</span>
      </div>
      <i class="bi bi-chat-dots stat-card-icon"></i>
    </div>
  </div>

  <!-- Mis Postulaciones -->
  <div class="section-header">
    <h2 class="section-title">Mis Postulaciones</h2>
    <div class="section-actions">
      <a href="{{ route('mensajes') }}" class="btn-outline">
        <i class="bi bi-chat-dots"></i> Mensajes
      </a>
    </div>
  </div>

  <table class="postulaciones-table">
    <thead>
      <tr>
        <th>Puesto</th>
        <th>Tipo</th>
        <th>Salario</th>
        <th>Estado</th>
        <th>Fecha</th>
        <th>Detalle</th>
      </tr>
    </thead>
    <tbody>

      <!-- Postulación 1 -->
      <tr>
        <td>
          <div class="td-puesto">Desarrollador Full Stack</div>
          <div class="td-empresa">MegaCorp Technologies</div>
        </td>
        <td>
          <span class="badge-tipo" style="border:0.5px solid var(--border);color:var(--muted);padding:3px 10px;border-radius:20px;font-size:11.5px;">Tiempo completo</span>
        </td>
        <td>USD 3000–5000</td>
        <td><span class="badge-estado estado-contacto">En contacto</span></td>
        <td class="td-fecha">14/1/2024</td>
        <td><button class="toggle-detalle" onclick="toggleDetalle('d1', this)">Ver detalle ↓</button></td>
      </tr>
      <tr class="detalle-row" id="d1">
        <td colspan="6">
          <div class="detalle-inner">
            <div>
              <p class="detalle-block-title">Descripción de la oferta</p>
              <p class="detalle-desc">Buscamos un desarrollador proactivo orientado a resultados para sumarse al equipo de core-banking. Trabajo 100% remoto con equipo distribuido en LATAM.</p>
              <a href="#" class="btn-ver-oferta"><i class="bi bi-box-arrow-up-right"></i> Ver oferta completa</a>
            </div>
            <div>
              <p class="detalle-block-title">Tecnologías requeridas</p>
              <div class="detalle-tags">
                <span class="detalle-tag">Node.js</span>
                <span class="detalle-tag">React</span>
                <span class="detalle-tag">PostgreSQL</span>
                <span class="detalle-tag">Docker</span>
                <span class="detalle-tag">Git</span>
              </div>
            </div>
          </div>
        </td>
      </tr>

      <!-- Postulación 2 -->
      <tr>
        <td>
          <div class="td-puesto">Analista de Datos</div>
          <div class="td-empresa">DataSoft S.A.</div>
        </td>
        <td>
          <span class="badge-tipo" style="border:0.5px solid var(--border);color:var(--muted);padding:3px 10px;border-radius:20px;font-size:11.5px;">Tiempo completo</span>
        </td>
        <td>USD 2000–3000</td>
        <td><span class="badge-estado estado-preseleccionado">Preseleccionado</span></td>
        <td class="td-fecha">10/1/2024</td>
        <td><button class="toggle-detalle" onclick="toggleDetalle('d2', this)">Ver detalle ↓</button></td>
      </tr>
      <tr class="detalle-row" id="d2">
        <td colspan="6">
          <div class="detalle-inner">
            <div>
              <p class="detalle-block-title">Descripción de la oferta</p>
              <p class="detalle-desc">Posición para analista junior con experiencia en Python y SQL. Se valorará conocimiento en visualización de datos con Tableau o Power BI.</p>
              <a href="#" class="btn-ver-oferta"><i class="bi bi-box-arrow-up-right"></i> Ver oferta completa</a>
            </div>
            <div>
              <p class="detalle-block-title">Tecnologías requeridas</p>
              <div class="detalle-tags">
                <span class="detalle-tag">Python</span>
                <span class="detalle-tag">SQL</span>
                <span class="detalle-tag">Power BI</span>
                <span class="detalle-tag">Excel</span>
              </div>
            </div>
          </div>
        </td>
      </tr>

      <!-- Postulación 3 -->
      <tr>
        <td>
          <div class="td-puesto">Pasantía Ingeniería Industrial</div>
          <div class="td-empresa">Grupo Metálico</div>
        </td>
        <td>
          <span class="badge-tipo" style="border:0.5px solid var(--border);color:var(--muted);padding:3px 10px;border-radius:20px;font-size:11.5px;">Pasantía</span>
        </td>
        <td>$180.000 / mes</td>
        <td><span class="badge-estado estado-revision">En revisión</span></td>
        <td class="td-fecha">5/1/2024</td>
        <td><button class="toggle-detalle" onclick="toggleDetalle('d3', this)">Ver detalle ↓</button></td>
      </tr>
      <tr class="detalle-row" id="d3">
        <td colspan="6">
          <div class="detalle-inner">
            <div>
              <p class="detalle-block-title">Descripción de la oferta</p>
              <p class="detalle-desc">Pasantía de 6 meses para estudiantes avanzados de Ingeniería Industrial. Participación en proyectos de mejora continua y optimización de procesos productivos.</p>
              <a href="#" class="btn-ver-oferta"><i class="bi bi-box-arrow-up-right"></i> Ver oferta completa</a>
            </div>
            <div>
              <p class="detalle-block-title">Modalidad</p>
              <div class="detalle-tags">
                <span class="detalle-tag">Presencial</span>
                <span class="detalle-tag">Buenos Aires</span>
                <span class="detalle-tag">Lunes a Viernes</span>
              </div>
            </div>
          </div>
        </td>
      </tr>

      <!-- Postulación 4 -->
      <tr>
        <td>
          <div class="td-puesto">Técnico en Programación</div>
          <div class="td-empresa">StartupX</div>
        </td>
        <td>
          <span class="badge-tipo" style="border:0.5px solid var(--border);color:var(--muted);padding:3px 10px;border-radius:20px;font-size:11.5px;">Part-time</span>
        </td>
        <td>$220.000 / mes</td>
        <td><span class="badge-estado estado-postulado">Postulado</span></td>
        <td class="td-fecha">2/1/2024</td>
        <td><button class="toggle-detalle" onclick="toggleDetalle('d4', this)">Ver detalle ↓</button></td>
      </tr>
      <tr class="detalle-row" id="d4">
        <td colspan="6">
          <div class="detalle-inner">
            <div>
              <p class="detalle-block-title">Descripción de la oferta</p>
              <p class="detalle-desc">Buscamos estudiantes de TUP o carreras afines para sumarse al equipo de desarrollo web. Posibilidad de continuar con relación de dependencia al finalizar los estudios.</p>
              <a href="#" class="btn-ver-oferta"><i class="bi bi-box-arrow-up-right"></i> Ver oferta completa</a>
            </div>
            <div>
              <p class="detalle-block-title">Tecnologías requeridas</p>
              <div class="detalle-tags">
                <span class="detalle-tag">PHP</span>
                <span class="detalle-tag">JavaScript</span>
                <span class="detalle-tag">MySQL</span>
                <span class="detalle-tag">HTML/CSS</span>
              </div>
            </div>
          </div>
        </td>
      </tr>

      <!-- Postulación 5 - rechazada -->
      <tr>
        <td>
          <div class="td-puesto">Diseñador UI Junior</div>
          <div class="td-empresa">CreativeAgency</div>
        </td>
        <td>
          <span class="badge-tipo" style="border:0.5px solid var(--border);color:var(--muted);padding:3px 10px;border-radius:20px;font-size:11.5px;">Tiempo completo</span>
        </td>
        <td>USD 1200–1800</td>
        <td><span class="badge-estado estado-rechazado">Rechazado</span></td>
        <td class="td-fecha">20/12/2023</td>
        <td><button class="toggle-detalle" onclick="toggleDetalle('d5', this)">Ver detalle ↓</button></td>
      </tr>
      <tr class="detalle-row" id="d5">
        <td colspan="6">
          <div class="detalle-inner">
            <div>
              <p class="detalle-block-title">Descripción de la oferta</p>
              <p class="detalle-desc">Posición para diseñador con conocimientos en Figma y sistemas de diseño. Se requería portfolio con al menos 3 proyectos de UI/UX.</p>
              <a href="#" class="btn-ver-oferta"><i class="bi bi-box-arrow-up-right"></i> Ver oferta completa</a>
            </div>
            <div>
              <p class="detalle-block-title">Herramientas</p>
              <div class="detalle-tags">
                <span class="detalle-tag">Figma</span>
                <span class="detalle-tag">Adobe XD</span>
                <span class="detalle-tag">Illustrator</span>
              </div>
            </div>
          </div>
        </td>
      </tr>

    </tbody>
  </table>

</div>

@endsection


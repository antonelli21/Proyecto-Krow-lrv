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
        <span class="stat-card-value" id="stat-activas">0</span>
      </div>
      <i class="bi bi-send stat-card-icon"></i>
    </div>
    <div class="stat-card">
      <div>
        <p class="stat-card-label">Total Postulaciones</p>
        <span class="stat-card-value" id="stat-totales">0</span>
      </div>
      <i class="bi bi-clipboard-check stat-card-icon"></i>
    </div>
    <div class="stat-card">
      <div>
        <p class="stat-card-label">Mensajes</p>
        <span class="stat-card-value">0</span>
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
    <tbody id="tabla-postulaciones">
      <tr>
        <td colspan="6" style="text-align: center; padding: 2rem;">Cargando postulaciones...</td>
      </tr>
    </tbody>
  </table>

  @include('estudiante.oferta-detalle')

</div>

@endsection

@section('scripts')
<script>
  const estudianteId = "{{ auth()->user()->estudiante?->id_estudiante ?? '' }}";
  document.addEventListener('DOMContentLoaded', () => {
    if (!estudianteId) return;

    fetch(`/api/estudiantes/${estudianteId}`)
      .then(r => r.json())
      .then(data => {
        const postulaciones = data.postulaciones || [];
        renderStats(postulaciones);
        renderTable(postulaciones);
      })
      .catch(err => {
        console.error("Error cargando postulaciones:", err);
        document.getElementById('tabla-postulaciones').innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 2rem; color: var(--danger);">Error cargando postulaciones</td></tr>';
      });
  });

  function renderStats(postulaciones) {
    document.getElementById('stat-totales').innerText = postulaciones.length;
    document.getElementById('stat-activas').innerText = postulaciones.filter(p => p.estado && p.estado.toLowerCase() !== 'rechazado').length;
  }

  function renderTable(postulaciones) {
    const tbody = document.getElementById('tabla-postulaciones');
    if (postulaciones.length === 0) {
      tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 2rem;">No tienes postulaciones activas.</td></tr>';
      return;
    }

    let html = '';
    postulaciones.forEach(p => {
      const oferta = p.oferta;
      if (!oferta) return;

      const estadoLimpio = p.estado ? p.estado.trim().toLowerCase() : '';
      let estadoClase = 'postulado';
      if (estadoLimpio === 'en contacto') estadoClase = 'contacto';
      else if (estadoLimpio === 'en revisión' || estadoLimpio === 'en revision') estadoClase = 'revision';
      else if (estadoLimpio === 'preseleccionado') estadoClase = 'preseleccionado';
      else if (estadoLimpio === 'rechazado') estadoClase = 'rechazado';

      const d = new Date(p.fecha_postulacion);
      const fecha = isNaN(d.getTime()) ? '-' : d.toLocaleDateString('es-AR');
      const salario = oferta.salario_min ? `USD ${oferta.salario_min}–${oferta.salario_max}` : 'No especificado';
      const empresa = oferta.empresa ? oferta.empresa.nombre_empresa : 'Confidencial';
      const estadoMayus = p.estado ? p.estado.charAt(0).toUpperCase() + p.estado.slice(1) : 'Postulado';

      const ofertaEncoded = encodeURIComponent(JSON.stringify(oferta));

      html += `
            <tr>
                <td>
                    <div class="td-puesto">${oferta.titulo}</div>
                    <div class="td-empresa">${empresa}</div>
                </td>
                <td>
                    <span class="badge-tipo" style="border:0.5px solid var(--border);color:var(--muted);padding:3px 10px;border-radius:20px;font-size:11.5px;">${oferta.tipo_oferta}</span>
                </td>
                <td>${salario}</td>
                <td>
                    <span class="badge-estado estado-${estadoClase}">${estadoMayus}</span>
                </td>
                <td class="td-fecha">${fecha}</td>
                <td>
                    <button class="toggle-detalle" onclick="openModalOferta(decodeURIComponent('${ofertaEncoded}'))" style="text-decoration: underline; text-underline-offset: 3px; border: none; background: transparent; cursor: pointer; color: var(--primary);">Ver Oferta</button>
                </td>
            </tr>`;
    });
    tbody.innerHTML = html;
  }

  window.openModalOferta = function(ofertaStr) {
    const oferta = JSON.parse(ofertaStr);
    document.getElementById('modal-titulo').innerText = oferta.titulo || '';
    document.getElementById('modal-empresa').innerText = oferta.empresa ? oferta.empresa.nombre_empresa : 'Confidencial';
    document.getElementById('modal-descripcion').innerText = oferta.descripcion || '';

    if (oferta.requisitos) {
      document.getElementById('modal-requisitos-container').style.display = 'block';
      document.getElementById('modal-requisitos').innerText = oferta.requisitos;
    } else {
      document.getElementById('modal-requisitos-container').style.display = 'none';
    }

    document.getElementById('modal-modalidad').innerText = oferta.modalidad || '';
    document.getElementById('modal-tipo').innerText = oferta.tipo_oferta || '';

    const habContainer = document.getElementById('modal-habilidades-container');
    const habDiv = document.getElementById('modal-habilidades');
    if (oferta.habilidades && oferta.habilidades.length > 0) {
      habContainer.style.display = 'block';
      habDiv.innerHTML = oferta.habilidades.map(h => `<span style="background: var(--bg-body); border: 1px solid var(--primary); color: var(--primary); padding: 4px 10px; border-radius: 20px; font-size: 0.85rem;">${h.nombre}</span>`).join('');
    } else {
      habContainer.style.display = 'none';
    }

    document.getElementById('modalOferta').showModal();
  };
</script>

<style>
@media (max-width: 768px) {

  /* ── Página contenedora ── */
  .panel-page {
    padding: 20px 14px 48px;
  }

  .panel-page-title {
    font-size: 22px;
    margin-bottom: 2px;
  }

  .panel-page-sub {
    font-size: 13px;
    margin-bottom: 22px;
  }

  /* ── Stats: apiladas en columna única ── */
  .stats-row {
    grid-template-columns: 1fr;
    gap: 10px;
    margin-bottom: 26px;
  }

  .stat-card {
    padding: 18px 20px;
    border-radius: 8px;
  }

  .stat-card-value {
    font-size: 40px;
  }

  .stat-card-label {
    font-size: 12.5px;
  }

  .stat-card-icon {
    font-size: 20px;
  }

  /* ── Section header ── */
  .section-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 12px;
  }

  .section-title {
    font-size: 17px;
  }

  .section-actions {
    width: 100%;
    flex-direction: column;
    gap: 8px;
  }

  .section-actions .btn-outline,
  .section-actions .btn-accent {
    width: 100%;
    justify-content: center;
    padding: 10px 16px;
    font-size: 13.5px;
  }

  /* ── Tabla de postulaciones: tarjetas apiladas ── */
  .postulaciones-table,
  .postulaciones-table thead,
  .postulaciones-table tbody,
  .postulaciones-table th,
  .postulaciones-table td,
  .postulaciones-table tr {
    display: block;
    width: 100%;
  }

  .postulaciones-table {
    border: none;
    background: transparent;
  }

  .postulaciones-table thead {
    display: none; /* ocultamos cabeceras, usamos data-label */
  }

  .postulaciones-table tbody tr {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 8px;
    margin-bottom: 12px;
    padding: 14px 16px;
    position: relative;
  }

  .postulaciones-table tbody tr:last-child td {
    border-bottom: none;
  }

  .postulaciones-table tbody tr:hover {
    background: var(--surface);
  }

  .postulaciones-table td {
    border-bottom: none;
    padding: 4px 0;
    font-size: 13px;
    display: flex;
    align-items: flex-start;
    gap: 6px;
  }

  /* Etiqueta inline antes de cada celda */
  .postulaciones-table td::before {
    content: attr(data-label);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: var(--text-muted);
    min-width: 68px;
    padding-top: 1px;
    flex-shrink: 0;
  }

  /* Celda de puesto: sin etiqueta, ocupa todo el ancho */
  .postulaciones-table td:first-child {
    flex-direction: column;
    gap: 2px;
    margin-bottom: 8px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--border);
  }

  .postulaciones-table td:first-child::before {
    display: none;
  }

  .td-puesto {
    font-size: 15px;
  }

  .td-empresa {
    font-size: 12px;
  }

  /* Celda de acción (Ver Oferta) */
  .postulaciones-table td:last-child {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid var(--border);
    justify-content: flex-end;
  }

  .postulaciones-table td:last-child::before {
    display: none;
  }

  .toggle-detalle {
    font-size: 13px;
    text-decoration: underline;
    text-underline-offset: 3px;
  }

  /* ── Badges ── */
  .badge-tipo {
    font-size: 11px;
    padding: 3px 8px;
  }

  .badge-estado {
    font-size: 11px;
    padding: 3px 10px;
  }

  /* ── Detalle expandible: columna única ── */
  .detalle-row td {
    padding: 16px 14px;
  }

  .detalle-inner {
    grid-template-columns: 1fr;
    gap: 16px;
  }

  /* ── Modal oferta ── */
  dialog#modalOferta {
    width: calc(100vw - 28px);
    max-width: 100%;
    margin: auto;
    padding: 20px 16px;
    border-radius: 10px;
  }
}

/* ── Breakpoint medio: tablet (768–1024px) ── */
@media (min-width: 769px) and (max-width: 1024px) {

  .panel-page {
    padding: 28px 20px 56px;
  }

  .stats-row {
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
  }

  .stat-card-value {
    font-size: 30px;
  }

  .postulaciones-table th,
  .postulaciones-table td {
    padding: 12px 12px;
    font-size: 13px;
  }

  .detalle-inner {
    grid-template-columns: 1fr 1fr;
  }
}
</style>
@include('estudiante.oferta-detalle')
@endsection
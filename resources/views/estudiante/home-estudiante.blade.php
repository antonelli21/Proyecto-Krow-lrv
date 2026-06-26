@extends('layouts.app')

@section('title', 'Panel Estudiante — KROW')

@section('banner')
<div style="
    width:100%;
    height:clamp(140px, 18vw, 280px);
    position:relative;
    overflow:hidden;
">

    <img src="{{ asset('img/banner-estudiante.jpg') }}"
         alt="Banner"
         style="width:100%; height:100%; object-fit:cover; display:block;">

    <div style="
        position:absolute;
        inset:0;
        background:linear-gradient(to right, rgba(0,0,0,.8), rgba(0,0,0,.8));
    "></div>

</div>
@endsection

@section('content')
<!-- Dialog confirmar — genérico -->
<dialog id="dialogConfirmar" class="modal-confirmar">
    <div class="modal-confirmar-content">
        <h3 class="modal-confirmar-title" id="dialogConfirmarTitle">Confirmar acción</h3>
        <p class="modal-confirmar-msg" id="dialogConfirmarMsg"></p>
        <div class="modal-confirmar-btns">
            <button onclick="document.getElementById('dialogConfirmar').close()"
                    class="btn-cancelar-dialog">Cancelar</button>
            <button id="btnConfirmarAccion"
                    class="btn-confirmar-eliminar">Confirmar</button>
        </div>
    </div>
</dialog>
<div class="panel-page" style="margin-top: clamp(-190px, -16vw, -140px); position: relative; z-index: 5; background-color:var(--bg); border-radius: 8px; border:1px solid var(--surface);">

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
        <p class="stat-card-label">Postulaciones Rechazadas</p>
        <span class="stat-card-value"  id="stat-rechazo">0</span>
      </div>
      <i class="bi bi-send stat-card-icon"></i>
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
<div id="bulk-bar-est" style="display:none; align-items:center; gap:8px; padding:10px 14px; background:var(--surface); border:1px solid var(--accent); border-radius:8px; margin-bottom:12px; flex-wrap:wrap;">
  <span id="bulk-count-est" style="font-size:12.5px; font-weight:700; color:var(--accent);"></span>
  <button id="bulk-delete-est"
          style="display:inline-flex; align-items:center; gap:5px; padding:5px 11px; font-size:12px; font-weight:700; border-radius:6px; cursor:pointer; border:1px solid rgba(212,24,61,.4); background:transparent; color:#e05577;">
    <i class="bi bi-trash"></i> Eliminar seleccionadas
  </button>
  <button onclick="clearBulkEst()"
          style="display:inline-flex; align-items:center; gap:5px; padding:5px 11px; font-size:12px; border-radius:6px; cursor:pointer; border:1px solid var(--border); background:transparent; color:var(--muted); margin-left:auto;">
    Cancelar
  </button>
</div>
  <table class="postulaciones-table">
    <thead>
      <tr>
        <th style="width:36px;"><input type="checkbox" id="check-all-est"></th>
        <th>Puesto</th>
        <th>Tipo</th>
        <th>Salario</th>
        <th>Estado</th>
        <th>Fecha</th>
        <th>Detalle</th>
        <th>X</th>
      </tr>
    </thead>
    <tbody id="tabla-postulaciones">
      <tr>
        <td colspan="6" style="text-align: center; padding: 2rem;">Cargando postulaciones...</td>
      </tr>
    </tbody>
  </table>

</div>

<!-- Modal oferta (iframe) -->
<dialog id="modalOferta" class="modal-oferta-dialog">
  <div class="modal-oferta-header">
    <button onclick="cerrarModalOferta()" class="modal-close-btn">&times;</button>
  </div>
  <iframe id="iframeOferta" src="" class="modal-oferta-iframe"></iframe>
</dialog>

<style>
  dialog:not([open]) { display: none !important; }

  .modal-oferta-dialog {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--surface);
    padding: 0;
    margin: 0;
    width: min(920px, 95vw);
    height: 88vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }
  .modal-oferta-dialog[open] {
    animation: modalIn 0.2s ease forwards;
  }
  .modal-oferta-dialog::backdrop {
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(3px);
  }
  .modal-oferta-header {
    display: flex;
    justify-content: flex-end;
    padding: 8px 12px;
    border-bottom: 1px solid var(--border);
    flex-shrink: 0;
  }
  .modal-close-btn {
    background: none;
    border: none;
    font-size: 24px;
    color: var(--muted);
    cursor: pointer;
    line-height: 1;
    padding: 0 4px;
  }
  .modal-close-btn:hover { color: var(--text); }
  .modal-oferta-iframe {
    flex: 1;
    width: 100%;
    border: none;
    background: var(--bg);
  }
  @keyframes modalIn {
    from { opacity: 0; transform: translate(-50%, -48%) scale(0.97); }
    to   { opacity: 1; transform: translate(-50%, -50%) scale(1); }
  }

  @media (max-width: 768px) {
    .panel-page { padding: 20px 14px 48px; }
    .panel-page-title { font-size: 22px; margin-bottom: 2px; }
    .panel-page-sub { font-size: 13px; margin-bottom: 22px; }
    .stats-row { grid-template-columns: 1fr; gap: 10px; margin-bottom: 26px; }
    .stat-card { padding: 18px 20px; border-radius: 8px; }
    .stat-card-value { font-size: 40px; }
    .stat-card-label { font-size: 12.5px; }
    .stat-card-icon  { font-size: 20px; }
    .section-header { flex-direction: column; align-items: flex-start; gap: 10px; margin-bottom: 12px; }
    .section-title  { font-size: 17px; }
    .section-actions { width: 100%; flex-direction: column; gap: 8px; }
    .section-actions .btn-outline,
    .section-actions .btn-accent { width: 100%; justify-content: center; padding: 10px 16px; font-size: 13.5px; }
    .postulaciones-table,
    .postulaciones-table thead,
    .postulaciones-table tbody,
    .postulaciones-table th,
    .postulaciones-table td,
    .postulaciones-table tr { display: block; width: 100%; }
    .postulaciones-table { border: none; background: transparent; }
    .postulaciones-table thead { display: none; }
    .postulaciones-table tbody tr {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 8px;
      margin-bottom: 12px;
      padding: 14px 16px;
      position: relative;
    }
    .postulaciones-table tbody tr:last-child td { border-bottom: none; }
    .postulaciones-table tbody tr:hover { background: var(--surface); }
    .postulaciones-table td {
      border-bottom: none;
      padding: 4px 0;
      font-size: 13px;
      display: flex;
      align-items: flex-start;
      gap: 6px;
    }
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
    .postulaciones-table td:first-child {
      flex-direction: column;
      gap: 2px;
      margin-bottom: 8px;
      padding-bottom: 10px;
      border-bottom: 1px solid var(--border);
    }
    .postulaciones-table td:first-child::before { display: none; }
    .td-puesto  { font-size: 15px; }
    .td-empresa { font-size: 12px; }
    .postulaciones-table td:last-child {
      margin-top: 10px;
      padding-top: 10px;
      border-top: 1px solid var(--border);
      justify-content: flex-end;
    }
    .postulaciones-table td:last-child::before { display: none; }
    .toggle-detalle { font-size: 13px; text-decoration: underline; text-underline-offset: 3px; }
    .badge-tipo   { font-size: 11px; padding: 3px 8px; }
    .badge-estado { font-size: 11px; padding: 3px 10px; }
    .detalle-row td { padding: 16px 14px; }
    .detalle-inner  { grid-template-columns: 1fr; gap: 16px; }
    .modal-oferta-dialog { width: calc(100vw - 16px); height: 92vh; }
  }

  @media (min-width: 769px) and (max-width: 1024px) {
    .panel-page { padding: 28px 20px 56px; }
    .stats-row  { grid-template-columns: repeat(3, 1fr); gap: 12px; }
    .stat-card-value { font-size: 30px; }
    .postulaciones-table th,
    .postulaciones-table td { padding: 12px; font-size: 13px; }
    .detalle-inner { grid-template-columns: 1fr 1fr; }
  }

  .modal-confirmar {
  position: fixed;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  border: 1px solid var(--border);
  border-radius: 12px;
  background: var(--surface);
  padding: 0;
  width: min(400px, 92vw);
  margin: 0;
}
.modal-confirmar::backdrop {
  background: rgba(0,0,0,0.5);
  backdrop-filter: blur(3px);
}
.modal-confirmar-content { padding: 24px; }
.modal-confirmar-title   { font-size: 18px; font-weight: 600; color: var(--text); margin-bottom: 10px; }
.modal-confirmar-msg     { color: var(--muted); margin-bottom: 24px; font-size: 14px; }
.modal-confirmar-btns    { display: flex; gap: 10px; justify-content: flex-end; }
.btn-cancelar-dialog     { padding: 8px 16px; background: var(--bg); border: 1px solid var(--border); color: var(--text); border-radius: 6px; cursor: pointer; }
.btn-confirmar-eliminar  { padding: 8px 16px; background: #dc3545; color: white; border: none; border-radius: 6px; cursor: pointer; }
</style>

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
        document.getElementById('tabla-postulaciones').innerHTML =
          '<tr><td colspan="6" style="text-align: center; padding: 2rem; color: var(--danger);">Error cargando postulaciones</td></tr>';
      });
  });

  function renderStats(postulaciones) {
    document.getElementById('stat-totales').innerText = postulaciones.length;
    document.getElementById('stat-activas').innerText =
      postulaciones.filter(p => p.estado && p.estado !== 'Rechazado').length;
    document.getElementById('stat-rechazo').innerText =
      postulaciones.filter(p => p.estado && p.estado === 'Rechazado').length;
  }

  function renderTable(postulaciones) {
    const tbody = document.getElementById('tabla-postulaciones');
    if (postulaciones.length === 0) {
      tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 2rem;">No tienes postulaciones activas.</td></tr>';
      return;
    }

    const estadoConfig = {
      'Postulado':       { clase: 'postulado',       texto: 'Postulado' },
      'Preseleccionado': { clase: 'preseleccionado', texto: 'Preseleccionado' },
      'En Contacto':     { clase: 'contacto',        texto: 'En Contacto' },
      'Rechazado':       { clase: 'rechazado',       texto: 'Rechazado' },
    };

    let html = '';
    postulaciones.forEach(p => {
      const oferta = p.oferta;
      if (!oferta) return;

      const cfg     = estadoConfig[p.estado] ?? estadoConfig['Postulado'];
      const d       = new Date(p.fecha_postulacion);
      const fecha   = isNaN(d.getTime()) ? '-' : d.toLocaleDateString('es-AR');
      const salario = oferta.salario_min
        ? `$${Number(oferta.salario_min).toLocaleString('es-AR')} – $${Number(oferta.salario_max).toLocaleString('es-AR')}`
        : 'No especificado';
      const empresa = oferta.empresa ? oferta.empresa.nombre_empresa : 'Confidencial';

      html += `
  <tr data-postulacion-id="${p.id_postulacion}">
    <td><input type="checkbox" class="check-est" data-id="${p.id_postulacion}"></td>
    <td>
      <div class="td-puesto">${oferta.titulo}</div>
      <div class="td-empresa">${empresa}</div>
    </td>
    <td data-label="Tipo">
      <span class="badge-tipo" style="border:0.5px solid var(--border);color:var(--muted);padding:3px 10px;border-radius:20px;font-size:11.5px;">
        ${oferta.tipo_oferta}
      </span>
    </td>
    <td data-label="Salario">${salario}</td>
    <td data-label="Estado">
      <span class="badge-estado estado-${cfg.clase}">${cfg.texto}</span>
    </td>
    <td data-label="Fecha" class="td-fecha">${fecha}</td>
    <td data-label="Detalle">
      <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
        <button onclick="abrirModalOferta(${oferta.id_oferta})"
                style="text-decoration:underline; text-underline-offset:3px; border:none; background:transparent; cursor:pointer; color:var(--primary); font-size:13px;">
          Ver oferta
        </button>
        
        </button>
      </div>
      <td data-label="Accion"> 
        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
          <button onclick="eliminarPostulacion(${p.id_postulacion}, '${oferta.titulo.replace(/'/g, "\\'")}')"
                style="background:none; border:none; color:#e05577; cursor:pointer; font-size:15px;padding-right:8px;border-radius:6px; transition:background 0.2s; line-height:1;"
                title="Eliminar postulación"
                onmouseover="this.style.background='rgba(212,24,61,0.1)'"
                onmouseout="this.style.background='none'">
          <i class="bi bi-trash"></i>
        </div>
      </td>

      

  </tr>`;
    });

    tbody.innerHTML = html;
  }

  // ── Modal iframe ──────────────────────────────
  function abrirModalOferta(idOferta) {
    document.getElementById('iframeOferta').src = `/estudiante/oferta/${idOferta}/preview`;
    document.getElementById('modalOferta').showModal();
  }

  function cerrarModalOferta() {
    document.getElementById('modalOferta').close();
    setTimeout(() => { document.getElementById('iframeOferta').src = ''; }, 200);
  }

  document.getElementById('modalOferta').addEventListener('click', function(e) {
    const rect = this.getBoundingClientRect();
    if (e.clientX < rect.left || e.clientX > rect.right ||
        e.clientY < rect.top  || e.clientY > rect.bottom) {
      cerrarModalOferta();
    }
  });
  /* ── Estudiante bulk ── */
document.getElementById('check-all-est')?.addEventListener('change', function() {
  document.querySelectorAll('.check-est').forEach(c => c.checked = this.checked);
  updateBulkEst();
});
document.addEventListener('change', e => {
  if (!e.target.classList.contains('check-est')) return;
  const all = [...document.querySelectorAll('.check-est')];
  const checkAll = document.getElementById('check-all-est');
  if (checkAll) checkAll.checked = all.length > 0 && all.every(c => c.checked);
  updateBulkEst();
});

function getSelectedEst() {
  return [...document.querySelectorAll('.check-est:checked')].map(c => c.dataset.id);
}
function updateBulkEst() {
  const ids = getSelectedEst();
  const bar = document.getElementById('bulk-bar-est');
  if (!bar) return;
  bar.style.display = ids.length > 0 ? 'flex' : 'none';
  document.getElementById('bulk-count-est').textContent =
    `${ids.length} seleccionada${ids.length !== 1 ? 's' : ''}`;
}
function clearBulkEst() {
  document.querySelectorAll('.check-est, #check-all-est').forEach(c => c.checked = false);
  updateBulkEst();
}

document.getElementById('bulk-delete-est')?.addEventListener('click', async () => {
    const ids = getSelectedEst();
    if (!ids.length) return;

    const ok = await modalConfirm(
        'Eliminar postulaciones',
        `¿Eliminar ${ids.length} postulación(es)? No se puede deshacer.`,
        'Sí, eliminar'
    );
    if (!ok) return;

    Promise.all(ids.map(id =>
        fetch(`/estudiante/postulacion/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? ''
            }
        }).then(r => r.json())
    )).then(() => {
        ids.forEach(id => {
            document.querySelector(`tr[data-postulacion-id="${id}"]`)?.remove();
        });
        clearBulkEst();
        // Actualizar stats contando filas restantes
        const total = document.querySelectorAll('#tabla-postulaciones tr[data-postulacion-id]').length;
        document.getElementById('stat-totales').textContent = total;
        document.getElementById('stat-activas').textContent =
            [...document.querySelectorAll('.badge-estado')].filter(b => !b.classList.contains('estado-rechazado')).length;
    });
});
// Devuelve una Promise<boolean>, igual que el adminConfirm del admin
function modalConfirm(titulo, mensaje, labelBoton = 'Confirmar') {
    return new Promise(resolve => {
        const dialog = document.getElementById('dialogConfirmar');
        document.getElementById('dialogConfirmarTitle').textContent = titulo;
        document.getElementById('dialogConfirmarMsg').textContent   = mensaje;

        const btn = document.getElementById('btnConfirmarAccion');
        btn.textContent = labelBoton;

        // Limpiar listener anterior para evitar doble-disparo
        const nuevo = btn.cloneNode(true);
        btn.parentNode.replaceChild(nuevo, btn);

        nuevo.addEventListener('click', () => {
            dialog.close();
            resolve(true);
        });

        dialog.addEventListener('close', () => resolve(false), { once: true });

        // Cerrar clickeando backdrop
        dialog.addEventListener('click', function handler(e) {
            const rect = dialog.getBoundingClientRect();
            if (e.clientX < rect.left || e.clientX > rect.right ||
                e.clientY < rect.top  || e.clientY > rect.bottom) {
                dialog.close();
                dialog.removeEventListener('click', handler);
            }
        });

        dialog.showModal();
    });
}
async function eliminarPostulacion(idPostulacion, tituloOferta) {
  const ok = await modalConfirm(
    'Eliminar postulación',
    `¿Confirmás eliminar tu postulación a "${tituloOferta}"? Esta acción no se puede deshacer.`,
    'Sí, eliminar'
  );
  if (!ok) return;

  fetch(`/estudiante/postulacion/${idPostulacion}`, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? ''
    }
  })
  .then(r => r.json())
  .then(data => {
    if (!data.success) { alert('Error al eliminar.'); return; }

    // Quitar la fila del DOM
    document.querySelector(`tr[data-postulacion-id="${idPostulacion}"]`)?.remove();

    // Actualizar stats
    const filas = document.querySelectorAll('#tabla-postulaciones tr[data-postulacion-id]');
    document.getElementById('stat-totales').textContent = filas.length;
    document.getElementById('stat-activas').textContent =
      [...filas].filter(f => !f.querySelector('.badge-estado.estado-rechazado')).length;
    document.getElementById('stat-rechazo').textContent =
      [...filas].filter(f => f.querySelector('.badge-estado.estado-rechazado')).length;

    // Limpiar selección bulk si la fila estaba seleccionada
    clearBulkEst();
  })
  .catch(() => alert('Error de red.'));
}
</script>
@endsection
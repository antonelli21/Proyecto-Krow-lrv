<dialog id="modalOferta" class="modal-dialog">
  <div class="modal-content" style="display: flex; flex-direction: column; gap: 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
      <h2 id="modal-titulo" style="margin: 0; font-size: 1.5rem; font-weight: 600;"></h2>
      <button onclick="document.getElementById('modalOferta').close()" style="background: none; border: none; font-size: 1.5rem; color: var(--muted); cursor: pointer;">&times;</button>
    </div>
    <p style="margin: 0; font-size: 1rem; color: var(--muted);"><strong>Empresa:</strong> <span id="modal-empresa"></span></p>
    
    <div>
      <p style="margin-bottom: 8px; font-weight: 600;">Descripción de la oferta</p>
      <p id="modal-descripcion" style="margin: 0; font-size: 0.95rem; line-height: 1.5;"></p>
    </div>

    <div id="modal-requisitos-container">
      <p style="margin-bottom: 8px; font-weight: 600;">Requisitos</p>
      <p id="modal-requisitos" style="margin: 0; font-size: 0.95rem; line-height: 1.5;"></p>
    </div>

    <div>
      <p style="margin-bottom: 8px; font-weight: 600;">Modalidad</p>
      <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        <span id="modal-modalidad" style="background: var(--bg-body); padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; border: 1px solid var(--border);"></span>
        <span id="modal-tipo" style="background: var(--bg-body); padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; border: 1px solid var(--border);"></span>
      </div>
    </div>

    <div id="modal-habilidades-container">
      <p style="margin-bottom: 8px; font-weight: 600;">Tecnologías / Habilidades requeridas</p>
      <div id="modal-habilidades" style="display: flex; gap: 8px; flex-wrap: wrap;">
      </div>
    </div>

    <div style="margin-top: 16px; text-align: right;">
        <button onclick="document.getElementById('modalOferta').close()" class="btn-outline">Cerrar</button>
    </div>
  </div>
</dialog>

<style>
  dialog.modal-dialog {
    margin: auto;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--bg-card);
    color: var(--text);
    padding: 24px;
    max-width: 600px;
    width: 100%;
  }
  dialog.modal-dialog[open] {
    animation: modalFadeIn 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  }
  dialog.modal-dialog::backdrop {
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(3px);
    animation: backdropFadeIn 0.25s ease-out forwards;
  }
  @keyframes modalFadeIn {
    from { opacity: 0; transform: scale(0.95) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
  }
  @keyframes backdropFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }
</style>

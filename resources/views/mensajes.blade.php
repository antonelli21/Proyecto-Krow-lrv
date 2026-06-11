 @extends('layouts.app')
 
@section('title', 'Mensajes — KROW')
 
@section('content')
 
<div id="mensajes-page" style="display:flex; height:calc(100vh - var(--header-h)); overflow:hidden;">
 
  {{-- ═══ SIDEBAR ═══ --}}
  <aside style="
    width:300px; flex-shrink:0;
    background:var(--surface);
    border-right:0.5px solid var(--border);
    display:flex; flex-direction:column; overflow:hidden;
  ">
    <div style="padding:18px 16px 12px; border-bottom:0.5px solid var(--border);">
      <p style="font-family:var(--font-display);font-size:15px;font-weight:800;color:var(--text);margin-bottom:12px;">
        Conversaciones
      </p>
      <div style="position:relative;display:flex;align-items:center;">
        <i class="bi bi-search" style="position:absolute;left:10px;color:var(--muted);font-size:13px;"></i>
        <input id="mensajes-search" type="text" placeholder="Buscar conversación..."
          style="width:100%;padding:8px 10px 8px 30px;background:var(--bg);border:0.5px solid var(--border);border-radius:var(--radius);color:var(--text);font-size:12.5px;outline:none;">
      </div>
    </div>
    <div id="chats-lista" style="flex:1;overflow-y:auto;scrollbar-width:thin;">
      <p style="text-align:center;color:var(--muted);padding:20px;font-size:13px;">Cargando...</p>
    </div>
  </aside>
 
  {{-- ═══ PANEL DERECHO ═══ --}}
  <section style="flex:1;display:flex;flex-direction:column;overflow:hidden;background:var(--bg);">
 
    <div id="panel-header" style="display:none;padding:14px 20px;border-bottom:0.5px solid var(--border);background:var(--surface);align-items:center;gap:12px;">
      <div id="panel-avatar" style="width:36px;height:36px;border-radius:50%;background:var(--accent);color:#0D1A13;font-family:var(--font-display);font-size:13px;font-weight:800;display:grid;place-items:center;flex-shrink:0;">?</div>
      <div>
        <div id="panel-nombre" style="font-family:var(--font-display);font-size:14px;font-weight:700;color:var(--text);">—</div>
        <div id="panel-rol"    style="font-size:11.5px;color:var(--muted);"></div>
      </div>
    </div>
 
    <div id="mensajes-historial" style="flex:1;overflow-y:auto;padding:20px;display:flex;flex-direction:column;gap:10px;scrollbar-width:thin;"></div>
 
    <div id="mensajes-vacio" style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;color:var(--text-muted);gap:12px;">
      <i class="bi bi-chat-dots" style="font-size:42px;color:var(--border);"></i>
      <p style="font-size:14px;">Seleccioná un chat para empezar a hablar.</p>
    </div>
 
    <form id="form-mensaje" style="display:none;padding:14px 16px;border-top:0.5px solid var(--border);background:var(--surface);align-items:center;gap:10px;">
      @csrf
      <textarea id="input-contenido" placeholder="Escribí un mensaje..." rows="1"
        style="flex:1;padding:10px 14px;background:var(--bg);border:0.5px solid var(--border);border-radius:20px;color:var(--text);font-size:13.5px;outline:none;resize:none;font-family:inherit;"
        required></textarea>
      <button type="submit"
        style="width:40px;height:40px;border-radius:50%;border:none;background:var(--accent);color:#0D1A13;display:grid;place-items:center;cursor:pointer;font-size:16px;flex-shrink:0;">
        <i class="bi bi-send-fill"></i>
      </button>
    </form>
 
  </section>
</div>
 
<script>
(function () {
 
  const miId  = {{ auth()->id() }};
  const csrf  = document.querySelector('meta[name="csrf-token"]')?.content ?? '{{ csrf_token() }}';
 
  const listEl      = document.getElementById('chats-lista');
  const historialEl = document.getElementById('mensajes-historial');
  const panelHeader = document.getElementById('panel-header');
  const panelAvatar = document.getElementById('panel-avatar');
  const panelNombre = document.getElementById('panel-nombre');
  const panelRol    = document.getElementById('panel-rol');
  const vacioEl     = document.getElementById('mensajes-vacio');
  const formEl      = document.getElementById('form-mensaje');
  const inputEl     = document.getElementById('input-contenido');
  const searchEl    = document.getElementById('mensajes-search');
 
  let chatActivoId = null;
  let intervalo    = null;
 
  const inicial    = n => n ? n.charAt(0).toUpperCase() : '?';
  const formatHora = iso => new Date(iso).toLocaleTimeString('es-AR', { hour: '2-digit', minute: '2-digit' });
  const formatFecha = iso => {
    const d = new Date(iso), hoy = new Date();
    if (d.toDateString() === hoy.toDateString()) return 'Hoy';
    const ayer = new Date(); ayer.setDate(hoy.getDate() - 1);
    if (d.toDateString() === ayer.toDateString()) return 'Ayer';
    return d.toLocaleDateString('es-AR', { day: '2-digit', month: 'long' });
  };
 
  /* ── 1. Cargar lista de chats ── */
  const cargarChats = () => {
    fetch('/api/chats')
      .then(r => r.json())
      .then(chats => {
        listEl.innerHTML = '';
        const query = searchEl?.value.toLowerCase() ?? '';
 
        if (!chats.length) {
          listEl.innerHTML = '<p style="text-align:center;color:var(--muted);padding:20px;font-size:13px;">Sin conversaciones aún.</p>';
          return;
        }
 
        chats.forEach(chat => {
          // CONTROL CRÍTICO: Aseguramos mapear el usuario correcto comparando contra la tabla de Users
          const otro    = parseInt(chat.id_usuario_1) === miId ? chat.usuario2 : chat.usuario1;
          const nombre  = otro?.name ?? 'Usuario';
          const ultimo  = chat.mensajes?.at(-1);
          const activo  = chat.id_chat === chatActivoId;
          if (query && !nombre.toLowerCase().includes(query)) return;
 
          const noLeidos = (chat.mensajes ?? []).filter(m => parseInt(m.id_remitente) !== miId && !m.leido).length;
 
          const item = document.createElement('div');
          item.dataset.chatId = chat.id_chat;
          item.style.cssText = `display:flex;align-items:center;gap:12px;padding:14px 16px;border-bottom:0.5px solid var(--border);cursor:pointer;background:${activo ? 'var(--accent-dim)' : 'transparent'};border-right:${activo ? '2px solid var(--accent)' : 'none'};transition:background .15s;`;
          item.innerHTML = `
            <div style="width:40px;height:40px;border-radius:50%;background:var(--accent);color:#0D1A13;font-family:var(--font-display);font-size:15px;font-weight:800;display:grid;place-items:center;flex-shrink:0;">${inicial(nombre)}</div>
            <div style="flex:1;min-width:0;">
              <div style="font-family:var(--font-display);font-size:13.5px;font-weight:700;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${nombre}</div>
              <div style="font-size:12px;color:var(--muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:2px;">${ultimo ? ultimo.contenido : 'Sin mensajes aún'}</div>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;flex-shrink:0;">
              <span style="font-size:11px;color:var(--text-muted);">${ultimo ? formatHora(ultimo.fecha_envio) : ''}</span>
              ${noLeidos > 0 ? `<span style="background:var(--accent);color:#0D1A13;font-size:10px;font-weight:700;border-radius:20px;padding:1px 6px;">${noLeidos}</span>` : ''}
            </div>`;
 
          item.addEventListener('mouseenter', () => { if (!activo) item.style.background = 'var(--bg-hover)'; });
          item.addEventListener('mouseleave', () => { if (!activo) item.style.background = 'transparent'; });
          item.addEventListener('click', () => abrirChat(chat, otro));
          listEl.appendChild(item);
        });
      })
      .catch(() => {});
  };
 
  /* ── 2. Abrir chat ── */
  const abrirChat = (chat, otro) => {
    chatActivoId = chat.id_chat;
    const nombre = otro?.name ?? 'Usuario';
    panelAvatar.textContent = inicial(nombre);
    panelNombre.textContent = nombre;
    panelRol.textContent    = otro?.rol ?? '';
    panelHeader.style.display = 'flex';
    vacioEl.style.display     = 'none';
    formEl.style.display      = 'flex';
    cargarMensajes();
    cargarChats();
    clearInterval(intervalo);
    intervalo = setInterval(cargarMensajes, 5000);
  };
 
  /* ── 3. Cargar historial ── */
  const cargarMensajes = () => {
    if (!chatActivoId) return;
    fetch(`/api/chats/${chatActivoId}`)
      .then(r => r.json())
      .then(chat => {
        const mensajes = chat.mensajes ?? [];
        historialEl.innerHTML = '';
        let ultimaFecha = null;
 
        mensajes.forEach(msg => {
          const fecha = formatFecha(msg.fecha_envio);
          if (fecha !== ultimaFecha) {
            const sep = document.createElement('div');
            sep.style.cssText = 'text-align:center;font-size:11px;color:var(--text-muted);font-family:var(--font-display);font-weight:700;letter-spacing:.5px;padding:8px 0;';
            sep.textContent = fecha;
            historialEl.appendChild(sep);
            ultimaFecha = fecha;
          }
 
          // CONTROL CRÍTICO: validación estricta de emisor contra el ID de la sesión activa
          const propio  = parseInt(msg.id_remitente) === miId;
          const burbuja = document.createElement('div');
          burbuja.style.cssText = `display:flex;flex-direction:column;max-width:68%;align-self:${propio ? 'flex-end' : 'flex-start'};align-items:${propio ? 'flex-end' : 'flex-start'};`;
          burbuja.innerHTML = `
            <div style="padding:10px 14px;border-radius:14px;${propio
              ? 'border-bottom-right-radius:4px;background:var(--accent);color:#0D1A13;'
              : 'border-bottom-left-radius:4px;background:var(--surface);color:var(--text);border:0.5px solid var(--border);'}font-size:13.5px;line-height:1.5;">${msg.contenido}</div>
            <span style="font-size:10.5px;color:var(--text-muted);margin-top:3px;padding:0 4px;">${formatHora(msg.fecha_envio)}</span>`;
          historialEl.appendChild(burbuja);
        });
 
        historialEl.scrollTop = historialEl.scrollHeight;
      })
      .catch(() => {});
  };
 
  /* ── 4. Enviar mensaje ── */
  formEl.addEventListener('submit', e => {
    e.preventDefault();
    const contenido = inputEl.value.trim();
    if (!contenido || !chatActivoId) return;
 
    fetch('/api/mensajes', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
      body: JSON.stringify({ id_chat: chatActivoId, id_remitente: miId, contenido }),
    })
    .then(r => r.json())
    .then(() => { inputEl.value = ''; cargarMensajes(); })
    .catch(() => {});
  });
 
  inputEl.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); formEl.dispatchEvent(new Event('submit')); }
  });
 
  searchEl?.addEventListener('input', cargarChats);
 
  cargarChats();
})();
</script>
@endsection
 
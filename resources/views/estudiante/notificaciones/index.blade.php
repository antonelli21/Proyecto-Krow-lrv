@extends('layouts.app')

@section('content')
<div class="nhistorial-page">

    {{-- Header --}}
    <div class="nhistorial-header">
        <div class="nhistorial-header-left">
            <a href="{{ url()->previous() }}" class="nhistorial-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 5l-7 7 7 7"/>
                </svg>
                Volver
            </a>
            <h1 class="nhistorial-titulo">Notificaciones</h1>
        </div>
        @if($notificaciones->total() > 0)
            <span class="nhistorial-total">{{ $notificaciones->total() }} en total</span>
        @endif

        @if($notificaciones->total() > 0)
            <div class="nhistorial-actions">
                <button class="nhistorial-mark-all" onclick="marcarTodasLeidas()">
                    Marcar todas como leídas
                </button>
                <button class="nhistorial-delete-all" onclick="confirmarEliminarTodas()">
                    Eliminar todas
                </button>
            </div>
        @endif
    </div>

    {{-- Lista --}}
    <div class="nhistorial-lista">
        @forelse($notificaciones as $n)
            <div class="nhistorial-item {{ $n->leida ? '' : 'unread' }}" id="notif-item-{{ $n->id }}">

                <div class="notif-icon notif-icon-{{ $n->tipo }}">
                    {{ $n->icono }}
                </div>

                <a href="{{ $n->url ?? '#' }}" class="nhistorial-body"
                   onclick="marcarComoLeida({{ $n->id }}, document.getElementById('notif-item-{{ $n->id }}'))">
                    <p class="nhistorial-titulo-item">{{ $n->titulo }}</p>
                    <p class="nhistorial-mensaje">{{ $n->mensaje }}</p>
                    <span class="notif-time">{{ $n->created_at->diffForHumans() }}</span>
                </a>

                @unless($n->leida)
                    <span class="notif-dot"></span>
                @endunless

                <button
                    class="nhistorial-delete"
                    title="Eliminar notificación"
                    onclick="eliminarNotificacion({{ $n->id }})">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/>
                    </svg>
                </button>

            </div>
        @empty
            <div class="nhistorial-empty">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                <p>No hay notificaciones.</p>
            </div>
        @endforelse
    </div>

    @if($notificaciones->hasPages())
        <div class="nhistorial-pagination">
            {{ $notificaciones->links() }}
        </div>
    @endif

</div>

<style>
.nhistorial-actions { display:flex; align-items:center; gap:10px; }
.nhistorial-mark-all {
    border: 1px solid var(--border-accent, var(--border));
    background: var(--accent-dim);
    color: var(--accent);
    padding: 8px 14px;
    border-radius: 7px;
    cursor: pointer;
    font-size: .82rem;
    font-weight: 600;
    transition: .2s;
}
.nhistorial-mark-all:hover { opacity: .8; }
.nhistorial-delete-all { border:none; background:#d4183d; color:white; padding:8px 14px; border-radius:7px; cursor:pointer; font-size:.82rem; transition:.2s; }
.nhistorial-delete-all:hover { opacity:.85; }
.nhistorial-page { max-width:680px; margin:32px auto; padding:0 16px 40px; }
.nhistorial-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; gap:12px; }
.nhistorial-header-left { display:flex; align-items:center; gap:14px; }
.nhistorial-back { display:inline-flex; align-items:center; gap:6px; font-size:0.82rem; font-weight:500; color:var(--accent); text-decoration:none; padding:6px 10px; border:1px solid var(--border-accent,var(--border)); border-radius:6px; background:var(--accent-dim); transition:opacity var(--trans); white-space:nowrap; }
.nhistorial-back:hover { opacity:0.75; }
.nhistorial-titulo { font-size:1.1rem; font-weight:700; color:var(--text); margin:0; }
.nhistorial-total { font-size:0.78rem; color:var(--muted); white-space:nowrap; }
.nhistorial-lista { border:1px solid var(--border); border-radius:8px; overflow:hidden; background:var(--surface); box-shadow:var(--shadow-card); }
.nhistorial-item { display:flex; align-items:flex-start; gap:12px; padding:14px 16px; border-bottom:1px solid var(--border); background:var(--bgnoti); transition:background var(--trans); position:relative; }
.nhistorial-item:last-child { border-bottom:none; }
.nhistorial-item:hover { background:var(--bg-hover); }
.nhistorial-item.unread { background:var(--accent-dim); }
.nhistorial-item.unread:hover { filter:brightness(0.97); }
.nhistorial-body { flex:1; min-width:0; text-decoration:none; }
.nhistorial-titulo-item { font-size:0.85rem; font-weight:600; color:var(--text); margin:0 0 3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.nhistorial-mensaje { font-size:0.8rem; color:var(--muted); line-height:1.4; margin:0 0 5px; }
.nhistorial-delete { flex-shrink:0; display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; border:none; border-radius:6px; background:transparent; color:var(--muted); cursor:pointer; transition:background var(--trans),color var(--trans); margin-top:2px; }
.nhistorial-delete:hover { background:rgba(212,24,61,0.10); color:var(--destructive); }
.nhistorial-empty { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:12px; padding:48px 16px; color:var(--muted); font-size:0.85rem; }
.nhistorial-empty svg { opacity:0.4; }
.nhistorial-empty p { margin:0; }
.nhistorial-pagination { margin-top:20px; display:flex; justify-content:center; }
.modal-confirm { position:fixed; inset:0; background:rgba(0,0,0,.45); display:flex; align-items:center; justify-content:center; z-index:9999; }
.modal-confirm-box { background:var(--surface); border-radius:10px; padding:22px; width:360px; box-shadow:0 10px 35px rgba(0,0,0,.3); }
.modal-confirm-box h3 { margin:0 0 10px; }
.modal-confirm-box p { margin:0 0 18px; color:var(--muted); }
.modal-confirm-buttons { display:flex; justify-content:flex-end; gap:10px; }
.modal-confirm-buttons button { border:none; border-radius:6px; padding:8px 16px; cursor:pointer; }
.modal-confirm-buttons .cancelar { background:#999; color:white; }
.modal-confirm-buttons .aceptar { background:#d4183d; color:white; }

.pagination {
    display: flex;
    align-items: center;
    gap: 4px;
    list-style: none;
    padding: 12px 0;
    margin: 0;
    justify-content: center;
    flex-wrap: wrap;
}
.page-item .page-link,
.page-item span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 8px;
    border-radius: 6px;
    border: 1px solid var(--border);
    background: var(--surface);
    color: var(--text);
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: background var(--trans), border-color var(--trans), color var(--trans);
}
.page-item .page-link:hover {
    background: var(--accent-dim);
    border-color: var(--accent);
    color: var(--accent);
}
.page-item.active .page-link,
.page-item.active span {
    background: var(--accent);
    border-color: var(--accent);
    color: #fff;
    font-weight: 700;
}
[data-theme="dark"] .page-item.active .page-link,
[data-theme="dark"] .page-item.active span {
    color: #111118;
}
.page-item.disabled .page-link,
.page-item.disabled span {
    opacity: 0.35;
    cursor: not-allowed;
    pointer-events: none;
}
</style>

<script>
function mostrarModal(texto, aceptar) {
    const fondo = document.createElement('div');
    fondo.className = 'modal-confirm';
    fondo.innerHTML = `
        <div class="modal-confirm-box">
            <h3>Eliminar</h3>
            <p>${texto}</p>
            <div class="modal-confirm-buttons">
                <button class="cancelar">Cancelar</button>
                <button class="aceptar">Eliminar</button>
            </div>
        </div>
    `;
    document.body.appendChild(fondo);
    fondo.querySelector('.cancelar').onclick = () => fondo.remove();
    fondo.querySelector('.aceptar').onclick  = () => { fondo.remove(); aceptar(); };
}

async function eliminarNotificacion(id) {
    mostrarModal('¿Querés eliminar esta notificación?', async () => {
        try {
            const res = await fetch(`/notificaciones/api/eliminar/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            });
            if (res.ok) {
                const el = document.getElementById(`notif-item-${id}`);
                el.style.transition = 'opacity 0.2s, max-height 0.3s';
                el.style.opacity    = '0';
                el.style.maxHeight  = '0';
                el.style.overflow   = 'hidden';
                setTimeout(() => el.remove(), 300);
            }
        } catch (e) {
            console.error(e);
        }
    });
}

function confirmarEliminarTodas() {
    mostrarModal('¿Querés eliminar TODAS las notificaciones?', async () => {
        const res = await fetch('/notificaciones/api/eliminar-todas', {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        });
        if (res.ok) {
            document.querySelectorAll('.nhistorial-item').forEach(el => {
                el.style.transition = 'opacity 0.2s, max-height 0.3s';
                el.style.opacity    = '0';
                el.style.maxHeight  = '0';
                el.style.overflow   = 'hidden';
                setTimeout(() => el.remove(), 300);
            });
        }
    });
}

/* ════════════════════════════════════════
   MARCAR COMO LEÍDA (individual)
════════════════════════════════════════ */
async function marcarComoLeida(id, el) {
    if (!el || !el.classList.contains('unread')) return;

    try {
        await fetch('/notificaciones/api/marcar-leida', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ id }),
        });

        el.classList.remove('unread');
        const dot = el.querySelector('.notif-dot');
        if (dot) dot.remove();
    } catch (e) {
        console.error(e);
    }
}

/* ════════════════════════════════════════
   MARCAR TODAS COMO LEÍDAS
════════════════════════════════════════ */
async function marcarTodasLeidas() {
    try {
        const res = await fetch('/notificaciones/api/marcar-todas', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        });
        if (res.ok) {
            document.querySelectorAll('.nhistorial-item.unread').forEach(el => {
                el.classList.remove('unread');
                const dot = el.querySelector('.notif-dot');
                if (dot) dot.remove();
            });
        }
    } catch (e) {
        console.error(e);
    }
}
</script>
@endsection
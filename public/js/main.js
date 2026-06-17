document.addEventListener('DOMContentLoaded', () => {

  /* ════════════════════════════════════════
     1. TEMA CLARO / OSCURO
     Persiste en localStorage.
     Aplica data-theme="dark" al <html>.
  ════════════════════════════════════════ */
  const root = document.documentElement;
  const themeBtn = document.getElementById('theme-toggle');
  const iconSun = themeBtn?.querySelector('.icon-sun');
  const iconMoon = themeBtn?.querySelector('.icon-moon');

  // Aplicar tema guardado o dark por defecto
  const savedTheme = localStorage.getItem('krow-theme') || 'dark';
  applyTheme(savedTheme);

  themeBtn?.addEventListener('click', () => {
    const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    applyTheme(next);
    localStorage.setItem('krow-theme', next);
  });

  function applyTheme(theme) {
    root.setAttribute('data-theme', theme);
    if (iconSun && iconMoon) {
      iconSun.style.display = theme === 'dark' ? 'block' : 'none';
      iconMoon.style.display = theme === 'light' ? 'block' : 'none';
    }
  }


  /* ════════════════════════════════════════
     2. DROPDOWN MI CUENTA
     Abre/cierra con click.
     Cierra con click fuera o tecla Escape.
     Navegación con flechas del teclado.
  ════════════════════════════════════════ */
  const accountToggle = document.getElementById('account-toggle');
  const accountMenu = document.getElementById('account-menu');

  accountToggle?.addEventListener('click', (e) => {
    e.stopPropagation();
    const isOpen = accountMenu.classList.toggle('open');
    accountToggle.setAttribute('aria-expanded', isOpen);
  });

  document.addEventListener('click', (e) => {
    if (!accountToggle?.contains(e.target) && !accountMenu?.contains(e.target)) {
      accountMenu?.classList.remove('open');
      accountToggle?.setAttribute('aria-expanded', 'false');
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      accountMenu?.classList.remove('open');
      accountToggle?.setAttribute('aria-expanded', 'false');
      accountToggle?.focus();
    }
  });

  accountMenu?.addEventListener('keydown', (e) => {
    const items = [...accountMenu.querySelectorAll('.dropdown-item')];
    const idx = items.indexOf(document.activeElement);
    if (e.key === 'ArrowDown') { e.preventDefault(); items[idx + 1]?.focus(); }
    if (e.key === 'ArrowUp') { e.preventDefault(); items[idx - 1]?.focus(); }
  });


  /* ════════════════════════════════════════
     3. MENÚ HAMBURGUESA (mobile)
     Abre/cierra el nav en pantallas chicas.
     Se cierra al tocar un link.
  ════════════════════════════════════════ */
  const hamburger = document.getElementById('hamburger');
  const headerNav = document.getElementById('header-nav');

  hamburger?.addEventListener('click', () => {
    const isOpen = headerNav.classList.toggle('open');
    hamburger.classList.toggle('open', isOpen);
    hamburger.setAttribute('aria-expanded', isOpen);
  });

  headerNav?.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', () => {
      headerNav.classList.remove('open');
      hamburger?.classList.remove('open');
      hamburger?.setAttribute('aria-expanded', 'false');
    });
  });


  /* ════════════════════════════════════════
     4. LINK ACTIVO EN NAV
     Marca el link que coincide con la URL actual.
  ════════════════════════════════════════ */
  const currentPath = window.location.pathname;
  document.querySelectorAll('.nav-link').forEach(link => {
    if (link.getAttribute('href') === currentPath) {
      link.classList.add('active');
    }
  });


  /* ════════════════════════════════════════
     5. VALIDACIÓN DE FORMULARIOS
     Muestra error si un campo requerido está vacío.
     Agrega clase .input-error al input.
  ════════════════════════════════════════ */
  document.querySelectorAll('form[data-validate]').forEach(form => {
    form.addEventListener('submit', (e) => {
      let valid = true;

      form.querySelectorAll('[required]').forEach(field => {
        field.classList.remove('input-error');
        if (!field.value.trim()) {
          field.classList.add('input-error');
          valid = false;
        }
      });

      if (!valid) {
        e.preventDefault();
        form.querySelector('.input-error')?.focus();
      }
    });
  });


  /* ════════════════════════════════════════
     6. FILTROS — checkboxes con estilo
     Agrega/quita clase .active en los checks custom.
  ════════════════════════════════════════ */
  document.querySelectorAll('.filter-option').forEach(option => {
    option.addEventListener('click', () => {
      option.querySelector('.fcheck')?.classList.toggle('active');
    });
  });


  /* ════════════════════════════════════════
     7. SORT BAR — botones de ordenamiento
     Solo uno activo a la vez.
  ════════════════════════════════════════ */
  document.querySelectorAll('.sort-bar').forEach(bar => {
    bar.querySelectorAll('.sort-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        bar.querySelectorAll('.sort-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
      });
    });
  });


  /* ════════════════════════════════════════
     8. PAGINACIÓN
     Solo un botón activo a la vez.
  ════════════════════════════════════════ */
  document.querySelectorAll('.pagination').forEach(pgn => {
    pgn.querySelectorAll('.pg-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        pgn.querySelectorAll('.pg-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
      });
    });
  });

});


/* ════════════════════════════════════════
   9. FILTROS SIDEBAR - Accordions dinámicos
   Maneja apertura/cierre de acordeones
════════════════════════════════════════ */

function initFiltersSidebar() {
  // Accordions toggle
  const accordions = document.querySelectorAll('.filter-accordion');

  accordions.forEach(accordion => {
    const header = accordion.querySelector('.accordion-header');
    const chevron = header?.querySelector('.accordion-chevron');

    // Símbolo inicial según estado
    if (chevron) chevron.textContent = accordion.classList.contains('open') ? '−' : '+';

    if (header && !accordion.hasAttribute('data-initialized')) {
      accordion.setAttribute('data-initialized', 'true');

      header.addEventListener('click', () => {
        const isOpen = accordion.classList.toggle('open');
        if (chevron) chevron.textContent = isOpen ? '−' : '+';
      });
    }
  });

  // Provincia y Localidad (cascada)
  const provinciaSelect = document.getElementById('provincia');
  const localidadSelect = document.getElementById('localidad');

  if (provinciaSelect && localidadSelect) {
    // Datos de ejemplo - puedes expandir con más provincias/localidades
    const localidadesPorProvincia = {
      'Buenos Aires': ['La Plata', 'Mar del Plata', 'Bahía Blanca', 'Tandil', 'San Nicolás'],
      'CABA': ['Palermo', 'Recoleta', 'Belgrano', 'Nuñez', 'Caballito'],
      'Córdoba': ['Córdoba Capital', 'Villa Carlos Paz', 'Río Cuarto', 'San Francisco'],
      'Santa Fe': ['Rosario', 'Santa Fe Capital', 'Rafaela', 'Venado Tuerto'],
      'Mendoza': ['Mendoza Capital', 'San Rafael', 'Godoy Cruz', 'Luján de Cuyo'],
      'default': ['Seleccioná una provincia primero']
    };

    provinciaSelect.addEventListener('change', (e) => {
      const provincia = e.target.value;
      const localidades = localidadesPorProvincia[provincia] || localidadesPorProvincia['default'];

      // Limpiar y habilitar select de localidad
      localidadSelect.innerHTML = '<option value="" disabled selected>Seleccioná una localidad</option>';
      localidades.forEach(localidad => {
        const option = document.createElement('option');
        option.value = localidad.toLowerCase().replace(/\s+/g, '-');
        option.textContent = localidad;
        localidadSelect.appendChild(option);
      });

      localidadSelect.disabled = false;
    });
  }

  // Tags de tecnologías
  const inputTech = document.getElementById('tecnologia-input');
  const btnAdd = document.getElementById('btn-add-tag');
  const containerTags = document.getElementById('tags-container');

  if (inputTech && btnAdd && containerTags) {
    let tagsList = [];

    function createTag(text) {
      const cleanedText = text.trim();

      if (cleanedText === '') return;
      if (tagsList.includes(cleanedText.toLowerCase())) {
        inputTech.value = '';
        return;
      }

      tagsList.push(cleanedText.toLowerCase());

      const tagDiv = document.createElement('div');
      tagDiv.classList.add('tech-tag');

      tagDiv.innerHTML = `
        <span>${escapeHtml(cleanedText)}</span>
        <input type="hidden" name="tecnologias[]" value="${escapeHtml(cleanedText.toLowerCase())}">
        <button type="button" class="btn-remove-tag">&times;</button>
      `;

      tagDiv.querySelector('.btn-remove-tag').addEventListener('click', () => {
        tagsList = tagsList.filter(t => t !== cleanedText.toLowerCase());
        tagDiv.remove();
      });

      containerTags.appendChild(tagDiv);
      inputTech.value = '';
    }

    // Función auxiliar para escapar HTML
    function escapeHtml(str) {
      const div = document.createElement('div');
      div.textContent = str;
      return div.innerHTML;
    }

    btnAdd.addEventListener('click', () => {
      createTag(inputTech.value);
    });

    inputTech.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        createTag(inputTech.value);
      }
    });
  }
}

// Inicializar filtros cuando el DOM esté listo
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initFiltersSidebar);
} else {
  initFiltersSidebar();
}

/* ════════════════════════════════════════
   10. ANIMACIONES SMOOTH PARA FILTROS
   Efecto de fade al hacer scroll
════════════════════════════════════════ */

function animateFilterGroups() {
  const filterGroups = document.querySelectorAll('.filter-group, .filter-accordion');

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '0';
        entry.target.style.transform = 'translateY(20px)';
        entry.target.style.transition = 'opacity 0.4s ease, transform 0.4s ease';

        setTimeout(() => {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }, 50);

        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1, rootMargin: '50px' });

  filterGroups.forEach(group => {
    group.style.opacity = '0';
    observer.observe(group);
  });
}

// Ejecutar animación después de cargar
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', animateFilterGroups);
} else {
  animateFilterGroups();
}
/* ════════════════════════════════════════
   SIDEBAR COLAPSABLE
════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
  const sidebarEl = document.querySelector('.sidebar-filtros');
  const toggleBtn = document.getElementById('sidebar-toggle');
  const toggleSymbol = document.getElementById('sidebar-toggle-symbol');

  if (sidebarEl && toggleBtn) {
    const setToggleState = (collapsed) => {
      toggleBtn.setAttribute('aria-label', collapsed ? 'Expandir filtros' : 'Colapsar filtros');
      toggleBtn.setAttribute('aria-pressed', collapsed ? 'true' : 'false');
      if (toggleSymbol) toggleSymbol.textContent = collapsed ? '+' : '−';
    };

    // Restaurar estado guardado
    const collapsed = localStorage.getItem('krow_sidebar_collapsed') === 'true';
    if (collapsed) sidebarEl.classList.add('collapsed');
    setToggleState(collapsed);

    toggleBtn.addEventListener('click', () => {
      const isCollapsed = sidebarEl.classList.toggle('collapsed');
      setToggleState(isCollapsed);
      localStorage.setItem('krow_sidebar_collapsed', isCollapsed);
    });
  }
});



/* ════════════════════════════════════════
   BETA AYUDA.PHP 
════════════════════════════════════════ */
(function () {
  // ─── FAQ Acordeón ───
  window.toggleFaq = function (btn) {
    const item = btn.closest('.faq-item');
    const isOpen = item.classList.contains('active');

    // Cerrar todos los demás (comportamiento tipo acordeón)
    document.querySelectorAll('.faq-item.active').forEach(el => {
      if (el !== item) {
        el.classList.remove('active');
        el.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
      }
    });

    if (isOpen) {
      item.classList.remove('active');
      btn.setAttribute('aria-expanded', 'false');
    } else {
      item.classList.add('active');
      btn.setAttribute('aria-expanded', 'true');
    }
  };

  // ─── Formulario de contacto ───
  const form = document.getElementById('form-contacto');
  const btn = document.getElementById('btn-enviar');
  const status = document.getElementById('form-status');

  if (!form) return;
  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const nombre = form.nombre.value.trim();
    const email = form.email.value.trim();
    const asunto = form.asunto.value.trim();
    const mensaje = form.mensaje.value.trim();

    // Validación básica
    if (!nombre || !email || !asunto || !mensaje) {
      showStatus('Completá todos los campos obligatorios.', 'error');
      return;
    }
    if (mensaje.length < 20) {
      showStatus('El mensaje debe tener al menos 20 caracteres.', 'error');
      return;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      showStatus('Ingresá un email válido.', 'error');
      return;
    }

    // Simular envío (reemplazar por fetch a backend real)
    btn.disabled = true;
    btn.classList.add('loading');
    status.classList.remove('show', 'success', 'error');

    setTimeout(function () {
      btn.disabled = false;
      btn.classList.remove('loading');
      showStatus('¡Mensaje enviado con éxito! Te responderemos a la brevedad.', 'success');
      form.reset();

      // Ocultar mensaje después de 5 segundos
      setTimeout(function () {
        status.classList.remove('show');
      }, 5000);
    }, 1500);
  });

  function showStatus(msg, type) {
    status.textContent = msg;
    status.className = 'form-status show ' + type;
  }
})();



/* ════════════════════════════════════════
   BETA EMPRESAS.PHP 
════════════════════════════════════════ */


(function () {
  const grid = document.getElementById('grid-empresas');
  if (!grid) return;
  const cards = Array.from(grid.querySelectorAll('.empresa-card'));
  const empty = document.getElementById('empty-state');
  const contador = document.getElementById('contador');
  const inputBuscar = document.getElementById('buscador');
  const selectRubro = document.getElementById('filtro-rubro');
  const selectModalidad = document.getElementById('filtro-modalidad');

  function filtrar() {
    const q = inputBuscar.value.trim().toLowerCase();
    const rubro = selectRubro.value.toLowerCase();
    const modalidad = selectModalidad.value.toLowerCase();
    let visibles = 0;

    cards.forEach(card => {
      const nombre = card.dataset.nombre;
      const cardRubro = card.dataset.rubro;
      const ubicacion = card.dataset.ubicacion;
      const mods = card.dataset.modalidades;

      const matchText = !q || nombre.includes(q) || cardRubro.includes(q) || ubicacion.includes(q);
      const matchRubro = !rubro || cardRubro === rubro;
      const matchModalidad = !modalidad || mods.split(',').includes(modalidad);

      if (matchText && matchRubro && matchModalidad) {
        card.style.display = '';
        visibles++;
      } else {
        card.style.display = 'none';
      }
    });

    empty.style.display = visibles === 0 ? 'block' : 'none';
    contador.textContent = visibles + ' empresa' + (visibles !== 1 ? 's' : '');
  }

  inputBuscar.addEventListener('input', filtrar);
  selectRubro.addEventListener('change', filtrar);
  selectModalidad.addEventListener('change', filtrar);

  // Exponer globalmente para el onclick inline
  window.togglePerfil = function (id) {
    const expanded = document.getElementById('expanded-' + id);
    const toggle = document.getElementById('toggle-' + id);
    const isOpen = expanded.classList.contains('open');

    if (isOpen) {
      expanded.classList.remove('open');
      toggle.setAttribute('aria-expanded', 'false');
      toggle.querySelector('span').textContent = 'Ver perfil completo';
    } else {
      expanded.classList.add('open');
      toggle.setAttribute('aria-expanded', 'true');
      toggle.querySelector('span').textContent = 'Ver menos';
    }
  };
})();



/* ════════════════════════════════════════
   Home=ESTUDIANTE BETA.PHP 
════════════════════════════════════════ */





function toggleDetalle(id, btn) {
  const row = document.getElementById(id);
  const isOpen = row.classList.toggle('open');
  btn.textContent = isOpen ? 'Cerrar ↑' : 'Ver detalle ↓';
}



/* ════════════════════════════════════════
   Registro BETA.PHP 
════════════════════════════════════════ */
/* ── Toggle password (global, fuera del IIFE) ── */
window.togglePass = function (inputId, btn) {
  const input = document.getElementById(inputId);
  if (!input || !btn) return;
  const open = btn.querySelector('.icon-open');
  const closed = btn.querySelector('.icon-closed');
  if (input.type === 'password') {
    input.type = 'text';
    if (open) open.style.display = 'none';
    if (closed) closed.style.display = 'inline';
    btn.setAttribute('aria-label', 'Ocultar contraseña');
  } else {
    input.type = 'password';
    if (open) open.style.display = 'inline';
    if (closed) closed.style.display = 'none';
    btn.setAttribute('aria-label', 'Mostrar contraseña');
  }
};

// formCand.addEventListener('submit', function (e) {
//   if (ok) {
//     // console.log('Registro candidato OK', Object.fromEntries(new FormData(formCand)));
//     formCand.submit(); // Enviar a Laravel
//   }
// });


// formEmp.addEventListener('submit', function (ev) {
//   if (ok) {
//     // console.log('Registro empresa OK', Object.fromEntries(new FormData(formEmp)));
//     formEmp.submit(); // Enviar a Laravel
//   }
// });


/* ════════════════════════════════════════
   Login BETA.PHP 
════════════════════════════════════════ */

(function () {
  const form = document.getElementById('loginForm');
  if (!form) return;
  const email = document.getElementById('email');
  const password = document.getElementById('password');
  const errEmail = document.getElementById('err-email');
  const errPassword = document.getElementById('err-password');

  function isValidEmail(v) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
  }

  function showError(input, errEl, show) {
    if (show) {
      input.classList.add('error');
      errEl.classList.add('show');
    } else {
      input.classList.remove('error');
      errEl.classList.remove('show');
    }
  }

  email.addEventListener('input', () => showError(email, errEmail, false));
  password.addEventListener('input', () => showError(password, errPassword, false));

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    let ok = true;

    const vEmail = email.value.trim();
    if (!vEmail || !isValidEmail(vEmail)) {
      showError(email, errEmail, true);
      ok = false;
    }

    const vPass = password.value;
    if (!vPass) {
      showError(password, errPassword, true);
      ok = false;
    }

    if (ok) {
      form.submit(); // Enviar login a Laravel
    }
  });
})();

/* ════════════════════════════════════════
   ADMIN PANEL — Tabs / Filtros / Detalle
════════════════════════════════════════ */

/* ── Helpers globales (fuera del IIFE para que el modal los use) ── */
function showAdminNotice(message, type = 'success') {
  let notice = document.querySelector('.admin-action-notice');
  if (!notice) {
    notice = document.createElement('div');
    notice.className = 'admin-action-notice';
    document.body.appendChild(notice);
  }
  notice.className = `admin-action-notice ${type} show`;
  notice.textContent = message;
  clearTimeout(notice.hideTimer);
  notice.hideTimer = setTimeout(() => notice.classList.remove('show'), 2800);
}

function adminEntityLabel(rowId) {
  if (rowId?.startsWith('e')) return 'Empresa';
  if (rowId?.startsWith('o')) return 'Oferta';
  return 'Alumno';
}

function removeAdminRow(rowId) {
  document.querySelector(`tr[data-id="${rowId}"]`)?.remove();
  document.getElementById('admin-det-' + rowId)?.remove();
}

/* ════════════════════════════════════════
   CONFIRM MODAL
════════════════════════════════════════ */
(function injectConfirmModal() {
  if (document.getElementById('krow-confirm-overlay')) return;
  document.body.insertAdjacentHTML('beforeend', `
    <div class="confirm-overlay" id="krow-confirm-overlay" role="dialog" aria-modal="true" aria-labelledby="krow-confirm-title">
      <div class="confirm-box">
        <div class="confirm-icon"><i class="bi bi-trash3"></i></div>
        <p class="confirm-title" id="krow-confirm-title">Eliminar registro</p>
        <div class="confirm-body">
          <span>Estás por eliminar <span class="confirm-name" id="krow-confirm-name"></span>.</span>
          <p class="confirm-warning">Esta acción no se puede deshacer.</p>
        </div>
        <div class="confirm-actions">
          <button class="confirm-btn-cancel" id="krow-confirm-cancel">Cancelar</button>
          <button class="confirm-btn-delete" id="krow-confirm-ok">
            <i class="bi bi-trash3"></i> Eliminar
          </button>
        </div>
      </div>
    </div>
  `);
})();

function adminConfirm(name) {
  return new Promise(resolve => {
    const overlay   = document.getElementById('krow-confirm-overlay');
    const nameEl    = document.getElementById('krow-confirm-name');
    const btnOk     = document.getElementById('krow-confirm-ok');
    const btnCancel = document.getElementById('krow-confirm-cancel');

    nameEl.textContent = `"${name}"`;
    overlay.classList.add('open');
    btnCancel.focus();

    function close(result) {
      overlay.classList.remove('open');
      btnOk.removeEventListener('click', onOk);
      btnCancel.removeEventListener('click', onCancel);
      overlay.removeEventListener('click', onBackdrop);
      document.removeEventListener('keydown', onEsc);
      resolve(result);
    }

    const onOk       = () => close(true);
    const onCancel   = () => close(false);
    const onBackdrop = e => { if (e.target === overlay) close(false); };
    const onEsc      = e => { if (e.key === 'Escape') close(false); };

    btnOk.addEventListener('click', onOk);
    btnCancel.addEventListener('click', onCancel);
    overlay.addEventListener('click', onBackdrop);
    document.addEventListener('keydown', onEsc);
  });
}

/* ════════════════════════════════════════
   INIT ADMIN
════════════════════════════════════════ */
(function initAdmin() {

  /* ── TABS ── */
  const tabs   = document.querySelectorAll('.admin-tab');
  const panels = document.querySelectorAll('.admin-tab-panel');
  if (!tabs.length) return;

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('active'));
      panels.forEach(p => p.classList.remove('active'));
      tab.classList.add('active');
      const target = document.getElementById('panel-' + tab.dataset.tab);
      if (target) target.classList.add('active');
    });
  });

  /* ── DETALLE EXPANDIBLE ── */
  window.toggleAdminDetalle = (id, btn) => {
    const row = document.getElementById('admin-det-' + id);
    if (!row) return;
    const isOpen = row.classList.toggle('open');
    const label = btn.querySelector('.det-label');
    if (label) label.textContent = isOpen ? 'Cerrar ↑' : 'Ver perfil ↓';
  };

  /* ── FILTRO GENÉRICO ── */
  document.querySelectorAll('.admin-table-wrap').forEach(wrap => {
    const searchInput = wrap.closest('.admin-tab-panel')?.querySelector('.admin-search input');
    const selects     = wrap.closest('.admin-tab-panel')?.querySelectorAll('.admin-filter-select');
    const rows        = wrap.querySelectorAll('tbody tr:not(.admin-detalle-row)');
    const emptyState  = wrap.closest('.admin-tab-panel')?.querySelector('.admin-empty');

    const applyFilters = () => {
      const query = searchInput?.value.toLowerCase() ?? '';
      const activeFilters = {};
      selects?.forEach(sel => {
        if (sel.dataset.filter) activeFilters[sel.dataset.filter] = sel.value.toLowerCase();
      });

      let visible = 0;
      rows.forEach(row => {
        const matchSearch  = !query || (row.dataset.search?.toLowerCase() ?? '').includes(query);
        const matchFilters = Object.entries(activeFilters).every(([key, val]) =>
          !val || (row.dataset[key] ?? '').toLowerCase() === val
        );
        const show = matchSearch && matchFilters;
        row.style.display = show ? '' : 'none';
        const det = document.getElementById('admin-det-' + row.dataset.id);
        if (det) det.style.display = show ? '' : 'none';
        if (show) visible++;
      });
      if (emptyState) emptyState.style.display = visible === 0 ? 'block' : 'none';
    };

    searchInput?.addEventListener('input', applyFilters);
    selects?.forEach(sel => sel.addEventListener('change', applyFilters));
  });

  /* ── CHECKBOX SELECCIONAR TODOS ── */
  document.querySelectorAll('.check-all').forEach(chkAll => {
    chkAll.addEventListener('change', () => {
      chkAll.closest('.admin-tab-panel')?.querySelectorAll('.check-row').forEach(chk => {
        chk.checked = chkAll.checked;
      });
    });
  });

  /* ── CAMBIO DE ESTADO ── */
  document.addEventListener('click', e => {
    const btn = e.target.closest('[data-action]');
    if (!btn) return;
    const action = btn.dataset.action;
    const id     = btn.dataset.id;
    const row    = document.querySelector(`tr[data-id="${id}"]`);
    const badge  = row?.querySelector('.badge-admin');
    const mapa   = {
      aprobar:  ['badge-aprobado',  'Aprobado',  'aprobada'],
      activar:  ['badge-activo',    'Activo',    'aprobado'],
      suspender:['badge-suspendido','Suspendido','suspendido'],
      rechazar: ['badge-rechazado', 'Rechazado', 'rechazada'],
      pausar:   ['badge-pausada',   'Pausada',   'pausada'],
      publicar: ['badge-publicada', 'Publicada', 'publicada'],
    };
    if (badge && mapa[action]) {
      badge.className = 'badge-admin';
      badge.classList.add(mapa[action][0]);
      badge.textContent = mapa[action][1];
      row.dataset.estado = mapa[action][1].toLowerCase();
      showAdminNotice(`${adminEntityLabel(id)} ${mapa[action][2]} correctamente.`);
    }
    const det = document.getElementById('admin-det-' + id);
    if (det) det.classList.remove('open');
  });

  /* ── ELIMINAR con modal propio ── */
  document.addEventListener('click', async e => {
    const deleteBtn = e.target.closest('[data-delete-type]');
    if (!deleteBtn) return;

    const type  = deleteBtn.dataset.deleteType;
    const id    = deleteBtn.dataset.deleteId;
    const rowId = deleteBtn.dataset.deleteRowId;
    const name  = deleteBtn.dataset.deleteName || 'este registro';

    if (!type || !id || !rowId) return;

    const confirmed = await adminConfirm(name);
    if (!confirmed) return;

    const csrf    = document.querySelector('meta[name="csrf-token"]')?.content;
    const baseUrl = document.querySelector('meta[name="base-url"]')?.content?.replace(/\/$/, '');
    const basePath = window.location.pathname.includes('/public/')
      ? window.location.pathname.split('/public/')[0] + '/public'
      : '';

    deleteBtn.disabled = true;

    try {
      const response = await fetch(`${baseUrl || basePath}/api/${type}/${id}`, {
        method: 'DELETE',
        headers: {
          'Accept': 'application/json',
          ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {}),
        },
      });

      if (!response.ok && response.status !== 404) {
        throw new Error('No se pudo eliminar el registro.');
      }

      removeAdminRow(rowId);
      showAdminNotice(`${adminEntityLabel(rowId)} eliminado correctamente.`);
    } catch (error) {
      showAdminNotice(error.message || 'Ocurrió un error al eliminar.', 'error');
      deleteBtn.disabled = false;
    }
  });

})();

/* ════════════════════════════════════════
   MÓDULO GENERAL (bookmarks, sort, roles)
════════════════════════════════════════ */
(function () {
  'use strict';

  function renderRightPanel() {
    const pageBody  = document.getElementById('page-body');
    const rolActual = pageBody?.dataset.rol || 'invitado';
    document.querySelectorAll('.role-panel-content').forEach(panel => {
      panel.style.display = panel.dataset.panelRole === rolActual ? 'block' : 'none';
    });
  }

  function initBookmarks() {
    document.getElementById('main-content')
      ?.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-bookmark');
        if (!btn) return;
        const saved = btn.classList.toggle('saved');
        const icon  = btn.querySelector('i');
        if (icon) icon.className = saved ? 'bi bi-bookmark-fill' : 'bi bi-bookmark';
      });
  }

  function initSort() {
    const select = document.getElementById('sort-select');
    const main   = document.getElementById('main-content');
    if (!select || !main) return;

    select.addEventListener('change', function () {
      const cards = [...main.querySelectorAll('.job-card')];
      if (!cards.length) return;
      cards.sort((a, b) => {
        const sa = Number(a.dataset.salario ?? 0);
        const sb = Number(b.dataset.salario ?? 0);
        const fa = Number(a.dataset.fecha   ?? 0);
        const fb = Number(b.dataset.fecha   ?? 0);
        if (this.value === 'salario-asc')  return sa - sb;
        if (this.value === 'salario-desc') return sb - sa;
        return fb - fa;
      });
      const ref = main.querySelector('.pagination');
      cards.forEach(c => main.insertBefore(c, ref ?? null));
    });
  }

  function initRoleSwitcher() {
    const switcher = document.getElementById('role-switcher');
    if (!switcher) return;
    switcher.addEventListener('click', function (e) {
      const btn = e.target.closest('.role-btn');
      if (!btn) return;
      const nuevoRol = btn.dataset.rol;
      document.documentElement.dataset.role = nuevoRol;
      renderRightPanel();
      switcher.querySelectorAll('.role-btn').forEach(b => {
        b.classList.toggle('active', b.dataset.rol === nuevoRol);
      });
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    renderRightPanel();
    initBookmarks();
    initSort();
    initRoleSwitcher();
  });
})();


/* ════════════════════════════════════════
   CONFIGURACIÓN — JS
   Solo se carga en esta página.
════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', () => {

  /* ── Detectar dispositivo ── */
  const deviceEl = document.getElementById('config-device');
  if (deviceEl) {
    const ua = navigator.userAgent;
    const browser = ua.includes('Chrome') && !ua.includes('Edg') ? 'Chrome'
      : ua.includes('Edg') ? 'Edge'
        : ua.includes('Firefox') ? 'Firefox'
          : ua.includes('Safari') ? 'Safari'
            : 'Navegador desconocido';
    const os = ua.includes('Windows') ? 'Windows'
      : ua.includes('Mac') ? 'macOS'
        : ua.includes('Android') ? 'Android'
          : ua.includes('iPhone') ? 'iPhone'
            : ua.includes('Linux') ? 'Linux'
              : 'Desconocido';
    deviceEl.textContent = `${browser} — ${os}`;
  }
}); // ← punto y coma acá
//   /* ── Confirmación zona de peligro ── */
//   document.getElementById('form-logout-all')
//     ?.addEventListener('submit', (e) => {
//       if (!confirm('¿Cerrar sesión en todos los dispositivos?')) {
//         e.preventDefault();
//       }
//     });

//   (function () {
//     const btnGuardar = document.getElementById('btn-guardar-oferta');
//     if (!btnGuardar) return;

//     btnGuardar.addEventListener('click', function () {
//       const guardado = btnGuardar.classList.toggle('guardado');
//       const icon = btnGuardar.querySelector('svg');

//       if (guardado) {
//         if (icon) icon.setAttribute('fill', 'currentColor');
//         btnGuardar.innerHTML = btnGuardar.innerHTML.replace('Guardar oferta', 'Guardado');
//         btnGuardar.style.borderColor = 'var(--accent)';
//         btnGuardar.style.color = 'var(--accent)';
//       } else {
//         if (icon) icon.setAttribute('fill', 'none');
//         btnGuardar.innerHTML = btnGuardar.innerHTML.replace('Guardado', 'Guardar oferta');
//         btnGuardar.style.borderColor = '';
//         btnGuardar.style.color = '';
//       }

//       // Llamada al backend (opcional, descomentar cuando exista la ruta)
// //       // fetch(`/ofertas/${btnGuardar.dataset.id}/guardar`, {
// //       //     method: 'POST',
// //       //     headers: {
// //       //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
//       //         'Accept': 'application/json',
//       //     }
//       // });
//     });
//   })();

// })

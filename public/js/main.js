 
document.addEventListener('DOMContentLoaded', () => {
 
  /* ════════════════════════════════════════
     1. TEMA CLARO / OSCURO
     Persiste en localStorage.
     Aplica data-theme="dark" al <html>.
  ════════════════════════════════════════ */
  const root      = document.documentElement;
  const themeBtn  = document.getElementById('theme-toggle');
  const iconSun   = themeBtn?.querySelector('.icon-sun');
  const iconMoon  = themeBtn?.querySelector('.icon-moon');
 
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
      iconSun.style.display  = theme === 'dark'  ? 'block' : 'none';
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
  const accountMenu   = document.getElementById('account-menu');
 
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
    const idx   = items.indexOf(document.activeElement);
    if (e.key === 'ArrowDown') { e.preventDefault(); items[idx + 1]?.focus(); }
    if (e.key === 'ArrowUp')   { e.preventDefault(); items[idx - 1]?.focus(); }
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
    const header  = accordion.querySelector('.accordion-header');
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
   11. SISTEMA DE ROLES — KROW
   Gestiona nav, panel derecho y acciones
   de header según el rol del usuario.
════════════════════════════════════════ */
 
const KROW_ROLES = {
  invitado: {
    nav: [
      { label: 'Inicio',           url: '/guardalo_aca/proyecto_krow/index.php',              active: true  },
      { label: 'Empresas',         url: '/guardalo_aca/proyecto_krow/vistas/auth/login.php',  active: false },
      { label: 'Mis Postulaciones',url: '/guardalo_aca/proyecto_krow/vistas/auth/login.php',  active: false },
      { label: 'Ayuda',            url: '/guardalo_aca/proyecto_krow/vistas/ayuda.php',        active: false },
            

    ],
    rightPanel: () => `
      <div class="panel-card cta-card">
        <p class="panel-card-title">Encontrá tu primer trabajo</p>
        <p>Registrate gratis y accedé a cientos de ofertas para estudiantes UTN.</p>
        <a href="/proyecto_krow/vistas/auth/registro-estudiante.php" class="btn-primary-sm">Crear cuenta</a>
        <a href="/proyecto_krow/vistas/auth/login.php" class="btn-ghost-sm" style="display:block;text-align:center;margin-top:6px">Ya tengo cuenta</a>
      </div>
      <div class="panel-card featured-card">
        <div class="featured-badge"><i class="bi bi-star-fill"></i> Destacado</div>
        <p class="featured-title">Senior Backend Engineer</p>
        <p class="featured-company">MegaCorp Technologies</p>
        <button class="btn-quick-apply" onclick="location.href='/proyecto_krow/vistas/auth/login.php'">Postularme rápido</button>
      </div>
    `,
  },
  estudiante: {
    nav: [
      { label: 'Inicio',           url: '/guardalo_aca/proyecto_krow/index.php',                                            active: true  },
      { label: 'Empresas',         url: '/proyecto_krow/vistas/empresas-lista.php',                            active: false },
      { label: 'Mis Postulaciones',url: '/guardalo_aca/proyecto_krow/vistas/estudiante/home-estudiante.php',   active: false },
      { label: 'Ayuda',            url: '/guardalo_aca/proyecto_krow/vistas/ayuda.php',                        active: false },

    ],
    rightPanel: () => `
      <div class="panel-card">
        <p class="panel-card-title">Mis Estadísticas</p>
        <div class="stat-row"><span class="stat-label">Postulaciones enviadas</span><span class="stat-value">24</span></div>
        <div class="stat-row"><span class="stat-label">Empresas que te aceptaron</span><span class="stat-value">8</span></div>
        <div class="stat-row"><span class="stat-label">En revisión</span><span class="stat-value">12</span></div>
      </div>
      <div class="panel-card featured-card">
        <div class="featured-badge"><i class="bi bi-star-fill"></i> Destacado</div>
        <p class="featured-title">Senior Backend Engineer</p>
        <p class="featured-company">MegaCorp Technologies</p>
        <button class="btn-quick-apply">Postularme rápido</button>
      </div>
      <div class="panel-card">
        <p class="panel-card-title">Últimas empresas vistas</p>
        <div class="companies-grid">
          <div class="company-thumb"><span>TC</span></div>
          <div class="company-thumb"><span>DS</span></div>
          <div class="company-thumb"><span>MC</span></div>
          <div class="company-thumb"><span>DC</span></div>
        </div>
      </div>
    `,
  },
  empresa: {
    nav: [
      { label: 'Inicio',       url: '/guardalo_aca/proyecto_krow/index.php',                                                active: true  },
      { label: 'Panel Empresa',url: '/guardalo_aca/proyecto_krow/vistas/empresa/home-empresa.php/',                         active: false },
      { label: 'Empresas',     url: 'guardalo_aca/proyecto_krow/vistas/empresas-lista.php',                                 active: false },
      { label: 'Ayuda',        url: '/proyecto_krow/vistas/ayuda.php',                                                      active: false },
    ],
    rightPanel: () => `
      <div class="panel-card">
        <p class="panel-card-title">Panel Empresa</p>
        <div class="stat-row"><span class="stat-label">Ofertas activas</span><span class="stat-value" style="color:#2ECC9A">7</span></div>
        <div class="stat-row"><span class="stat-label">Postulantes recibidos</span><span class="stat-value" style="color:#2ECC9A">143</span></div>
        <div class="stat-row"><span class="stat-label">Entrevistas pautadas</span><span class="stat-value" style="color:#2ECC9A">12</span></div>
        <button class="btn-new-offer">+ Nueva Oferta</button>
      </div>
      <div class="panel-card">
        <p class="panel-card-title">Postulantes destacados</p>
        <div class="companies-grid">
          <div class="company-thumb"><span>MA</span></div>
          <div class="company-thumb"><span>LG</span></div>
          <div class="company-thumb"><span>RD</span></div>
          <div class="company-thumb"><span>SV</span></div>
        </div>
      </div>
    `,
  },
  admin: {
    nav: [
      { label: 'Inicio',      url: '/proyecto_krow/index.php',          active: true  },
      { label: 'Administrar', url: '#',                                  active: false },
      { label: 'Usuarios',    url: '#',                                  active: false },
      { label: 'Reportes',    url: '#',                                  active: false },
      { label: 'Ayuda',       url: '/proyecto_krow/vistas/ayuda.php',    active: false },
    ],
    rightPanel: () => `
      <div class="panel-card">
        <p class="panel-card-title">Administración</p>
        <div class="admin-alert"><i class="bi bi-exclamation-triangle-fill"></i> 3 ofertas pendientes de revisión</div>
        <div class="admin-alert" style="background:rgba(46,204,154,.08);border-color:rgba(46,204,154,.3);color:#2ECC9A">
          <i class="bi bi-people-fill"></i> 8 nuevos registros hoy
        </div>
        <div class="stat-row"><span class="stat-label">Usuarios totales</span><span class="stat-value">1.2k</span></div>
        <div class="stat-row"><span class="stat-label">Empresas activas</span><span class="stat-value">38</span></div>
        <div class="stat-row"><span class="stat-label">Ofertas publicadas</span><span class="stat-value">124</span></div>
      </div>
    `,
  },
};
 
/**
 * Aplica un rol al layout: actualiza nav, acciones de header y panel derecho.
 * @param {string} role  - 'invitado' | 'estudiante' | 'empresa' | 'admin'
 */
function krowSetRole(role) {
  const data = KROW_ROLES[role];
  if (!data) return;
 
  /* ── Nav ── */
  const nav = document.getElementById('header-nav');
  if (nav) {
    nav.innerHTML = data.nav.map(item =>
      `<a href="${item.url}" class="nav-link${item.active ? ' active' : ''}">${item.label}</a>`
    ).join('');
  }
 
  /* ── Header actions ── */
  const loggedIn   = document.getElementById('logged-in-actions');
  const guestEl    = document.getElementById('guest-actions');
 
  if (role === 'invitado') {
    if (loggedIn) loggedIn.style.display = 'none';
    if (guestEl)  guestEl.style.display  = 'flex';
  } else {
    if (loggedIn) loggedIn.style.display = 'flex';
    if (guestEl)  guestEl.style.display  = 'none';
 
    const labelMap = { estudiante: 'Mi Cuenta', empresa: 'Mi Empresa', admin: 'Admin' };
 
    const avatarEl = document.getElementById('avatar-letter');
    const labelEl  = document.getElementById('account-label');
    const perfilEl = document.getElementById('link-perfil');
 
    if (avatarEl) avatarEl.textContent = role.charAt(0).toUpperCase();
    if (labelEl)  labelEl.textContent  = labelMap[role] || 'Mi Cuenta';
    if (perfilEl) perfilEl.href = `/proyecto_krow/vistas/${role}/perfil-${role}.php`;
 
    // Actualizar menu items del dropdown con los 4 items fijos
    const menu = document.getElementById('account-menu');
    if (menu) {
      menu.innerHTML = `
        <a href="/guardalo_aca/proyecto_krow/vistas/${role}/perfil-${role}.php" id="link-perfil" class="dropdown-item" role="menuitem">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
          Mi Perfil
        </a>
        <a href="/guardalo_aca/proyecto_krow/vistas/mensajes.php" class="dropdown-item" role="menuitem">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
          Mensajes
        </a>
        <a href="/guardalo_aca/proyecto_krow/vistas/notificaciones.php" class="dropdown-item" role="menuitem">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          Notificaciones
        </a>
        <a href="/guardalo_aca/proyecto_krow/vistas/configuracion.php" class="dropdown-item" role="menuitem">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="3"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
          Configuración
        </a>
        <hr class="dropdown-divider">
        <a href="/guardalo_aca/proyecto_krow/src/logout.php" class="dropdown-item dropdown-item-danger" role="menuitem">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          Cerrar sesión
        </a>`;
    }
  }
 
  /* ── Right panel ── */
  const panel = document.getElementById('right-panel');
  if (panel) panel.innerHTML = data.rightPanel();
 
  /* ── Role switcher buttons ── */
  document.querySelectorAll('.role-btn').forEach(btn => {
    btn.classList.toggle('active', btn.dataset.role === role);
  });
}
 
/* ── Init desde PHP (data-role en <html>) o default estudiante ── */
document.addEventListener('DOMContentLoaded', () => {
  const roleFromPHP = document.documentElement.dataset.role || 'invitado';
  krowSetRole(roleFromPHP);
 
  /* Role switcher demo (solo si existe en la página) */
  document.querySelectorAll('.role-btn').forEach(btn => {
    btn.addEventListener('click', () => krowSetRole(btn.dataset.role));
  });
});
 
/* ════════════════════════════════════════
   SIDEBAR COLAPSABLE
════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
  const sidebarEl    = document.querySelector('.sidebar-filtros');
  const toggleBtn    = document.getElementById('sidebar-toggle');
  const toggleSymbol = document.getElementById('sidebar-toggle-symbol');
 
  if (sidebarEl && toggleBtn) {
    const setToggleState = (collapsed) => {
      toggleBtn.setAttribute('aria-label',   collapsed ? 'Expandir filtros' : 'Colapsar filtros');
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
   (function() {
        // ─── FAQ Acordeón ───
        window.toggleFaq = function(btn) {
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

        form.addEventListener('submit', function(e) {
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

            setTimeout(function() {
                btn.disabled = false;
                btn.classList.remove('loading');
                showStatus('¡Mensaje enviado con éxito! Te responderemos a la brevedad.', 'success');
                form.reset();

                // Ocultar mensaje después de 5 segundos
                setTimeout(function() {
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


        (function() {
        const grid = document.getElementById('grid-empresas');
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
        window.togglePerfil = function(id) {
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
        window.togglePass = function(inputId, btn) {
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

        (function() {
            const tabs = document.querySelectorAll('.role-tab');
            const formCand = document.getElementById('formCandidato');
            const formEmp = document.getElementById('formEmpresa');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    const role = tab.dataset.role;
                    if (role === 'candidato') {
                        formCand.classList.add('active');
                        formEmp.classList.remove('active');
                    } else {
                        formEmp.classList.add('active');
                        formCand.classList.remove('active');
                    }
                });
            });

            function isValidEmail(v) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
            }

            function isValidPhone(v) {
                return /^[\d\s\-+()]{7,20}$/.test(v);
            }

            function isValidCuit(v) {
                const digits = v.replace(/\D/g, '');
                return digits.length === 11;
            }

            function showError(input, errEl, show) {
                if (!input || !errEl) return;
                if (show) {
                    input.classList.add('error');
                    errEl.classList.add('show');
                } else {
                    input.classList.remove('error');
                    errEl.classList.remove('show');
                }
            }

            /* ── Candidato ── */
            const c = {
                nombre: document.getElementById('c-nombre'),
                apellido: document.getElementById('c-apellido'),
                email: document.getElementById('c-email'),
                telefono: document.getElementById('c-telefono'),
                nacimiento: document.getElementById('c-nacimiento'),
                carrera: document.getElementById('c-carrera'),
                pass: document.getElementById('c-password'),
                pass2: document.getElementById('c-password2'),
                terms: document.getElementById('c-terms'),
            };
            const ce = {
                nombre: document.getElementById('err-c-nombre'),
                apellido: document.getElementById('err-c-apellido'),
                email: document.getElementById('err-c-email'),
                telefono: document.getElementById('err-c-telefono'),
                nacimiento: document.getElementById('err-c-nacimiento'),
                carrera: document.getElementById('err-c-carrera'),
                pass: document.getElementById('err-c-password'),
                pass2: document.getElementById('err-c-password2'),
                terms: document.getElementById('err-c-terms'),
            };
            Object.keys(c).forEach(k => {
                const el = c[k];
                if (!el) return;
                el.addEventListener('input', () => showError(el, ce[k], false));
                el.addEventListener('change', () => showError(el, ce[k], false));
            });
            formCand.addEventListener('submit', function(e) {
                e.preventDefault();
                let ok = true;
                if (!c.nombre.value.trim()) {
                    showError(c.nombre, ce.nombre, true);
                    ok = false;
                }
                if (!c.apellido.value.trim()) {
                    showError(c.apellido, ce.apellido, true);
                    ok = false;
                }
                if (!c.email.value.trim() || !isValidEmail(c.email.value.trim())) {
                    showError(c.email, ce.email, true);
                    ok = false;
                }
                if (!c.telefono.value.trim() || !isValidPhone(c.telefono.value.trim())) {
                    showError(c.telefono, ce.telefono, true);
                    ok = false;
                }
                if (!c.nacimiento.value) {
                    showError(c.nacimiento, ce.nacimiento, true);
                    ok = false;
                }
                if (!c.carrera.value) {
                    showError(c.carrera, ce.carrera, true);
                    ok = false;
                }
                if (!c.pass.value || c.pass.value.length < 6) {
                    showError(c.pass, ce.pass, true);
                    ok = false;
                }
                if (c.pass.value !== c.pass2.value || !c.pass2.value) {
                    showError(c.pass2, ce.pass2, true);
                    ok = false;
                }
                if (!c.terms.checked) {
                    showError(c.terms, ce.terms, true);
                    ok = false;
                }
                if (ok) {
                    console.log('Registro candidato OK', Object.fromEntries(new FormData(formCand)));
                }
            });

            /* ── Empresa ── */
            const e = {
                nombre: document.getElementById('e-nombre'),
                razon: document.getElementById('e-razon'),
                email: document.getElementById('e-email'),
                cuit: document.getElementById('e-cuit'),
                telefono: document.getElementById('e-telefono'),
                ubicacion: document.getElementById('e-ubicacion'),
                pass: document.getElementById('e-password'),
                pass2: document.getElementById('e-password2'),
                terms: document.getElementById('e-terms'),
            };
            const ee = {
                nombre: document.getElementById('err-e-nombre'),
                razon: document.getElementById('err-e-razon'),
                email: document.getElementById('err-e-email'),
                cuit: document.getElementById('err-e-cuit'),
                telefono: document.getElementById('err-e-telefono'),
                ubicacion: document.getElementById('err-e-ubicacion'),
                pass: document.getElementById('err-e-password'),
                pass2: document.getElementById('err-e-password2'),
                terms: document.getElementById('err-e-terms'),
            };
            Object.keys(e).forEach(k => {
                const el = e[k];
                if (!el) return;
                el.addEventListener('input', () => showError(el, ee[k], false));
                el.addEventListener('change', () => showError(el, ee[k], false));
            });
            e.cuit.addEventListener('input', function() {
                let v = this.value.replace(/\D/g, '').slice(0, 11);
                if (v.length > 2 && v.length <= 10) v = v.slice(0, 2) + '-' + v.slice(2, 10) + '-' + v.slice(10);
                else if (v.length > 10) v = v.slice(0, 2) + '-' + v.slice(2, 10) + '-' + v.slice(10);
                this.value = v;
            });
            formEmp.addEventListener('submit', function(ev) {
                ev.preventDefault();
                let ok = true;
                if (!e.nombre.value.trim()) {
                    showError(e.nombre, ee.nombre, true);
                    ok = false;
                }
                if (!e.razon.value.trim()) {
                    showError(e.razon, ee.razon, true);
                    ok = false;
                }
                if (!e.email.value.trim() || !isValidEmail(e.email.value.trim())) {
                    showError(e.email, ee.email, true);
                    ok = false;
                }
                if (!isValidCuit(e.cuit.value)) {
                    showError(e.cuit, ee.cuit, true);
                    ok = false;
                }
                if (!e.telefono.value.trim() || !isValidPhone(e.telefono.value.trim())) {
                    showError(e.telefono, ee.telefono, true);
                    ok = false;
                }
                if (!e.ubicacion.value.trim()) {
                    showError(e.ubicacion, ee.ubicacion, true);
                    ok = false;
                }
                if (!e.pass.value || e.pass.value.length < 6) {
                    showError(e.pass, ee.pass, true);
                    ok = false;
                }
                if (e.pass.value !== e.pass2.value || !e.pass2.value) {
                    showError(e.pass2, ee.pass2, true);
                    ok = false;
                }
                if (!e.terms.checked) {
                    showError(e.terms, ee.terms, true);
                    ok = false;
                }
                if (ok) {
                    console.log('Registro empresa OK', Object.fromEntries(new FormData(formEmp)));
                }
            });
        })();

/* ════════════════════════════════════════
   Login BETA.PHP 
════════════════════════════════════════ */
       /* ── Toggle password (global) ── */
        window.togglePass = function(inputId, btn) {
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

        (function() {
            const form = document.getElementById('loginForm');
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

            form.addEventListener('submit', function(e) {
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
                    console.log('Login OK', {
                        email: vEmail,
                        remember: document.getElementById('remember').checked
                    });
                }
            });
        })();
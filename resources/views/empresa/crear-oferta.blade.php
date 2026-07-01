@extends('layouts.app')

@section('title', 'Crear Nueva Oferta')

@section('content')

{{-- ════════════════════════════════════════
   CREAR OFERTA — KROW
   Estilos nuevos (no repetidos del design system)
════════════════════════════════════════ --}}
<style>
    /* ── Variables locales de página ── */
    :root {
        --form-max-w: 680px;
        --form-gap: 1.25rem;
        --input-h: 2.75rem;
        --label-size: 0.8rem;
        --label-weight: 600;
        --label-ls: 0.6px;
    }

    /* ── Layout de página ── */
    .oferta-page {
        max-width: var(--form-max-w);
        margin: 0 auto;
        padding: 32px 20px 60px;
        animation: fadeInUp 0.35s ease;
    }

    .oferta-page-header {
        margin-bottom: 28px;
    }

    .oferta-page-header h1 {
        font-family: var(--font-display);
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .oferta-page-header p {
        font-size: 0.9rem;
        color: var(--muted);
    }

    /* Link volver con color accent (dorado/verde) */
    .link-volver {
        font-size: 0.85rem;
        color: var(--accent);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 8px;
        font-weight: 600;
        transition: opacity 0.15s;
    }

    .link-volver:hover {
        opacity: 0.8;
    }

    .link-volver svg {
        stroke: currentColor;
    }

    /* ── Card del formulario ── */
    .oferta-card {
        background: var(--surface);
        border: 0.5px solid var(--border);
        border-radius: var(--radius);
        padding: 28px;
        box-shadow: var(--shadow-card);
    }

    /* ── Formulario grid ── */
    .oferta-form {
        display: flex;
        flex-direction: column;
        gap: var(--form-gap);
    }

    /* ── Grupos de campo ── */
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .form-group label {
        font-size: var(--label-size);
        font-weight: var(--label-weight);
        color: var(--text);
        text-transform: uppercase;
        letter-spacing: var(--label-ls);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .required-mark {
        color: var(--destructive);
        font-size: 0.9em;
    }

    /* ── Inputs, selects, textareas ── */
    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.65rem 0.875rem;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        background: var(--bg-input);
        color: var(--text);
        font-size: 0.95rem;
        font-family: var(--font-body);
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(46, 204, 154, 0.10);
    }

    [data-theme="dark"] .form-input:focus,
    [data-theme="dark"] .form-select:focus,
    [data-theme="dark"] .form-textarea:focus {
        box-shadow: 0 0 0 3px rgba(212, 168, 67, 0.12);
    }

    .form-input::placeholder,
    .form-textarea::placeholder {
        color: var(--text-muted);
        opacity: 0.7;
    }

    .form-input:disabled,
    .form-select:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: var(--bg-hover);
    }

    /* Select con flecha */
    .select-wrap {
        position: relative;
    }

    .select-wrap .form-select {
        padding-right: 2.5rem;
        appearance: none;
        cursor: pointer;
    }

    .select-chevron {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        pointer-events: none;
        font-size: 0.875rem;
    }

    /* Textarea */
    .form-textarea {
        min-height: 120px;
        resize: vertical;
        line-height: 1.6;
    }

    .form-textarea.desc-large {
        min-height: 160px;
    }

    /* Hint debajo del campo */
    .field-hint {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 2px;
    }

    /* ── Grid de 2 columnas ── */
    .form-row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--form-gap);
    }

    @media (max-width: 560px) {
        .form-row-2 {
            grid-template-columns: 1fr;
        }
    }

    /* ── Tags de tecnologías (compatibles con tu JS existente) ── */
    /* Usa las clases del design system: .tags-flex-container, .tech-tag, .btn-remove-tag */
    /* Solo agrego ajustes de layout para el formulario */

    .oferta-form .filter-accordion {
        border-bottom: 1px solid var(--border);
        margin-bottom: 0.5rem;
    }

    .oferta-form .accordion-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        cursor: pointer;
        font-weight: 600;
        color: var(--text);
        transition: color 0.2s ease;
        user-select: none;
        font-size: var(--label-size);
        text-transform: uppercase;
        letter-spacing: var(--label-ls);
    }

    .oferta-form .accordion-header:hover {
        color: var(--accent);
    }

    .oferta-form .accordion-chevron {
        transition: transform 0.3s ease;
        color: var(--muted);
        font-size: 0.875rem;
    }

    .oferta-form .filter-accordion.open .accordion-chevron {
        transform: rotate(180deg);
    }

    .oferta-form .accordion-content {
        display: none;
        flex-direction: column;
        gap: 2px;
        padding-bottom: 6px;
    }

    .oferta-form .filter-accordion.open .accordion-content {
        display: flex;
    }

    /* Ajuste del tag-input-wrapper para el formulario */
    .oferta-form .tag-input-wrapper {
        display: flex;
        gap: 0.5rem;
        align-items: stretch;
    }

    .oferta-form .tag-input-wrapper .filter-input-text {
        flex: 1;
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        background-color: var(--surface);
        color: var(--text);
        font-size: 0.875rem;
        transition: all 0.2s ease;
        outline: none;
    }

    .oferta-form .tag-input-wrapper .filter-input-text:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(46, 204, 154, 0.1);
    }

    .oferta-form .tag-input-wrapper .filter-input-text::placeholder {
        color: var(--muted);
        font-size: 0.8rem;
    }

    .oferta-form .btn-input-append {
        background-color: var(--primary);
        color: #fff;
        border: none;
        border-radius: var(--radius);
        padding: 0.625rem 1rem;
        cursor: pointer;
        font-weight: bold;
        font-size: 1.1rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 44px;
    }

    .oferta-form .btn-input-append:hover {
        background-color: var(--accent);
        color: var(--primary);
        transform: scale(1.02);
    }

    [data-theme="dark"] .oferta-form .btn-input-append {
        color: #111118;
    }

    /* Tags container */
    .oferta-form .tags-flex-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }

    /* tech-tag ya está en tu design system, solo aseguro animación */
    .oferta-form .tech-tag {
        animation: tagPopIn 0.2s ease-in-out;
    }

    /* Sugerencias de tecnologías */
    .tech-suggestions-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.5rem;
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .tech-suggestions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-top: 0.25rem;
    }

    .tech-suggestion {
        padding: 0.25rem 0.6rem;
        border-radius: 20px;
        border: 0.5px solid var(--border);
        background: var(--bg);
        color: var(--muted);
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.15s;
    }

    .tech-suggestion:hover {
        border-color: var(--accent);
        color: var(--accent);
        background: var(--accent-dim);
    }

    /* ── Botones de acción ── */
    .form-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 0.5rem;
        padding-top: 1rem;
        border-top: 0.5px solid var(--border);
    }

    .btn-submit {
        flex: 1;
        padding: 0.85rem;
        border: none;
        border-radius: var(--radius);
        background: var(--primary);
        color: #fff;
        font-family: var(--font-display);
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-submit:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(13, 79, 60, 0.25);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    [data-theme="dark"] .btn-submit {
        background: var(--accent);
        color: #111118;
    }

    [data-theme="dark"] .btn-submit:hover {
        box-shadow: 0 4px 20px rgba(212, 168, 67, 0.25);
    }

    .btn-cancel {
        padding: 0.85rem 1.5rem;
        border: 0.5px solid var(--border);
        border-radius: var(--radius);
        background: transparent;
        color: var(--muted);
        font-family: var(--font-display);
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.15s;
    }

    .btn-cancel:hover {
        border-color: var(--destructive);
        color: var(--destructive);
        background: rgba(212, 24, 61, 0.04);
    }

    /* ── Validación / errores ── */
    .form-input.error,
    .form-select.error,
    .form-textarea.error,
    .filter-input-text.error {
        border-color: var(--destructive);
        box-shadow: 0 0 0 3px rgba(212, 24, 61, 0.12);
    }

    .field-error {
        font-size: 0.8rem;
        color: var(--destructive);
        display: none;
        align-items: center;
        gap: 4px;
        margin-top: 2px;
    }

    .field-error.show {
        display: flex;
    }

    /* ── Animaciones ── */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes tagPopIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* ── Responsive ── */
    @media (max-width: 560px) {
        .oferta-card {
            padding: 20px 16px;
        }

        .oferta-page-header h1 {
            font-size: 1.45rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-cancel {
            width: 100%;
        }
    }
</style>

{{-- ════════════════════════════════════════
   HTML DEL FORMULARIO CONSOLIDADO
════════════════════════════════════════ --}}

<div class="oferta-page">

    {{-- Header --}}
    <div class="oferta-page-header">
        <a href="{{ route('empresa.home') }}" class="link-volver">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke-width="2">
                <polyline points="15 18 9 12 15 6" />
            </svg>
            Volver al Dashboard
        </a>
        <h1>Crear Nueva Oferta</h1>
        <p>Completa los detalles de la oferta laboral</p>
    </div>

    {{-- Card --}}
    <div class="oferta-card">        @if($errors->any())
            <div style="margin-bottom:16px;padding:13px 16px;border:1px solid rgba(212,24,61,.35);background:rgba(14,24,22,.96);color:#e05577;font-size:13px;font-weight:700;display:flex;align-items:flex-start;gap:8px;">
                <i class="bi bi-exclamation-circle"></i>
                <div>
                    <div>Completá todos los campos obligatorios para publicar la oferta.</div>
                    <ul style="margin:6px 0 0 16px; font-weight:600;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <form class="oferta-form" id="ofertaForm" action="{{ route('empresa.ofertas.store') }}" method="POST" novalidate>
            @csrf

            {{-- Título del Puesto --}}
            <div class="form-group">
                <label for="titulo">Título del Puesto <span class="required-mark">*</span></label>
                <input type="text" id="titulo" name="titulo" class="form-input" placeholder="ej. Desarrollador Full Stack" required maxlength="120">
                <span class="field-error" id="error-titulo">El título es obligatorio</span>
            </div>

            {{-- Tipo de Trabajo + Modalidad --}}
            <div class="form-row-2">
                <div class="form-group">
                    <label for="tipo_trabajo">Tipo de Trabajo <span class="required-mark">*</span></label>
                    <div class="select-wrap">
                        <select id="tipo_trabajo" name="tipo_trabajo" class="form-select" required>
                            <option value="" disabled selected>Seleccionar...</option>
                            <option value="Pasantia">Pasantia</option>
                            <option value="Practica Profesional">Practica Profesional</option>
                            <option value="Part-Time">Part Time</option>
                            <option value="Full-Time">Full Time</option>
                        </select>
                        <span class="select-chevron">▼</span>
                    </div>
                    <span class="field-error" id="error-tipo_trabajo">Selecciona un tipo de trabajo</span>
                </div>

                <div class="form-group">
                    <label for="modalidad">Modalidad <span class="required-mark">*</span></label>
                    <div class="select-wrap">
                        <select id="modalidad" name="modalidad" class="form-select" required>
                            <option value="" disabled selected>Seleccionar...</option>
                            <option value="presencial">Presencial</option>
                            <option value="remoto">Remoto</option>
                            <option value="hibrido">Híbrido</option>
                        </select>
                        <span class="select-chevron">▼</span>
                    </div>
                    <span class="field-error" id="error-modalidad">Selecciona una modalidad</span>
                </div>
            </div>

            {{-- Rango Salarial - Experiencia --}}
            <div class="form-row-2">
                <div class="form-group">
                    <label for="rango_salarial">Rango Salarial <span class="required-mark">*</span></label>
                    <input type="text" id="rango_salarial" name="rango_salarial" class="form-input" placeholder="USD 3000 - 5000" required>
                    <span class="field-hint">Ejemplo: USD 3000 - 5000 o ARS 450000 - 600000</span>
                    <span class="field-error" id="error-rango_salarial">El rango salarial es obligatorio</span>
                </div>
                <div class="form-group">
                    <label for="experiencia-requerida">Experiencia Requerida <span class="required-mark">*</span></label>
                    <div class="select-wrap">
                        <select id="experiencia-requerida" name="experiencia_requerida" class="form-select" required>
                            <option value="" disabled selected>Seleccionar...</option>
                            <option value="sin-experiencia">Sin Experiencia</option>
                            <option value="junior">Junior</option>
                            <option value="semi-senior">Semi Senior</option>
                            <option value="senior">Senior</option>
                        </select>
                        <span class="select-chevron">▼</span>
                    </div>
                    <span class="field-error" id="error-modalidad">Selecciona el Nivel de Experiencia</span>
                </div>
            </div>

            {{-- Provincia y Localidad --}}
            <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                <div style="flex: 1;">
                    <label style="display: block; color: #fff; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem;">PROVINCIA *</label>
                    <select name="id_provincia" id="select-provincia" required 
                            style="width: 100%; background: #2d3248; border: 1px solid rgba(255,255,255,0.1); padding: 12px; border-radius: 6px; color: #fff;">
                        <option value="">Seleccionar...</option>
                        @foreach($provincias as $provincia)
                            <option value="{{ $provincia->id_provincia }}">{{ $provincia->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div style="flex: 1;">
                    <label style="display: block; color: #fff; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem;">LOCALIDAD *</label>
                    <select name="id_localidad" id="select-localidad" required disabled
                            style="width: 100%; background: #2d3248; border: 1px solid rgba(255,255,255,0.1); padding: 12px; border-radius: 6px; color: #fff;">
                        <option value="">Seleccionar provincia primero...</option>
                    </select>
                </div>
            </div>

            {{-- Dirección --}}
            <div style="margin-bottom: 20px;">
                <label style="display: block; color: #fff; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem;">DIRECCIÓN</label>
                <input type="text" name="direccion" placeholder="ej. Av. Corrientes 1234, Piso 2" 
                    style="width: 100%; background: #2d3248; border: 1px solid rgba(255,255,255,0.1); padding: 12px; border-radius: 6px; color: #fff;">
            </div>

            {{-- NUEVA FILA: Categoría + Carrera Destinada (Alineados nativos) --}}
            <div class="form-row-2">
                <div class="form-group">
                    <label for="area">Categoría <span class="required-mark">*</span></label>
                    <div class="select-wrap">
                        <select name="area" id="area" class="form-select" required>
                            <option value="" disabled selected>Seleccionar...</option>
                            @foreach(['Ingeniería', 'Tecnología', 'Industria y producción', 'Marketing', 'Ventas', 'Recursos Humanos', 'Diseño', 'Administración', 'Finanzas'] as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                        <span class="select-chevron">▼</span>
                    </div>
                    <span class="field-error" id="error-area">La categoría es obligatoria</span>
                </div>

                <div class="form-group">
                    <label for="id_carrera">Carrera Destinada <span class="required-mark">*</span></label>
                    <div class="select-wrap">
                        <select name="id_carrera" id="id_carrera" class="form-select" required>
                            <option value="" disabled selected>Seleccionar...</option>
                            @foreach($carreras as $carrera)
                                <option value="{{ $carrera->id_carrera }}">{{ $carrera->nombre }}</option>
                            @endforeach
                        </select>
                        <span class="select-chevron">▼</span>
                    </div>
                    <span class="field-error" id="error-id_carrera">La carrera es obligatoria</span>
                </div>
            </div>

            {{-- Descripción del Puesto --}}
            <div class="form-group">
                <label for="descripcion">Descripción del Puesto <span class="required-mark">*</span></label>
                <textarea id="descripcion" name="descripcion" class="form-textarea desc-large" placeholder="Describe las responsabilidades y el día a día del puesto..." required></textarea>
                <span class="field-hint">Sé claro sobre las responsabilidades, beneficios y cultura de la empresa.</span>
                <span class="field-error" id="error-descripcion">La descripción es obligatoria</span>
            </div>

            {{-- Requisitos --}}
            <div class="form-group">
                <label for="requisitos">Requisitos <span class="required-mark">*</span></label>
                <textarea id="requisitos" name="requisitos" class="form-textarea" placeholder="Lista los requisitos necesarios (experiencia, habilidades, educación...)" required></textarea>
                <span class="field-hint">Separá cada requisito con un salto de línea para mejor legibilidad.</span>
                <span class="field-error" id="error-requisitos">Los requisitos son obligatorios</span>
            </div>

            {{-- Tecnologías / Tags --}}
            <div class="form-group">
                <label for="tecnologia-input">Tecnologias/Herramientas</label>
                <div class="tag-input-wrapper">
                    <input type="text" id="tecnologia-input"
                        placeholder="Ej: C#, Oracle, Git..."
                        class="filter-input-text"
                        value="{{ request('tecnologia') }}">
                    <button type="button" id="btn-add-tag" class="btn-input-append">+</button>
                </div>
                <div id="tags-container" class="tags-flex-container"></div>

                {{-- Sugerencias rápidas --}}
                <div class="tech-suggestions-label">Sugerencias rápidas:</div>
                <div class="tech-suggestions" id="tech-suggestions">
                    <button type="button" class="tech-suggestion" data-tech="React">React</button>
                    <button type="button" class="tech-suggestion" data-tech="Laravel">Laravel</button>
                    <button type="button" class="tech-suggestion" data-tech="Python">Python</button>
                    <button type="button" class="tech-suggestion" data-tech="Node.js">Node.js</button>
                    <button type="button" class="tech-suggestion" data-tech="Docker">Docker</button>
                    <button type="button" class="tech-suggestion" data-tech="AWS">AWS</button>
                    <button type="button" class="tech-suggestion" data-tech="PostgreSQL">PostgreSQL</button>
                    <button type="button" class="tech-suggestion" data-tech="TypeScript">TypeScript</button>
                    <button type="button" class="tech-suggestion" data-tech="Git">Git</button>
                    <button type="button" class="tech-suggestion" data-tech="Figma">Figma</button>
                </div>
            </div>

            {{-- Acciones --}}
            <div class="form-actions">
                <button type="submit" class="btn-submit" id="btn-submit">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                    Publicar Oferta
                </button>
                <a href="{{ route('empresa.home') }}" class="btn-cancel">Cancelar</a>
            </div>

        </form>
    </div>
</div>

{{-- ════════════════════════════════════════
   JAVASCRIPT — Compatible con tu JS existente
════════════════════════════════════════ --}}
<script>
    (function() {
        'use strict';

        // ── Tags de tecnologías (tu JS existente, adaptado) ──
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

            // Sugerencias rápidas
            document.querySelectorAll('.tech-suggestion').forEach(btn => {
                btn.addEventListener('click', () => {
                    createTag(btn.dataset.tech);
                });
            });
        }

        // ── Validación del formulario ──
        const form = document.getElementById('ofertaForm');
        const btnSubmit = document.getElementById('btn-submit');
        const camposRequeridos = ['titulo', 'tipo_trabajo', 'modalidad', 'rango_salarial', 'descripcion', 'requisitos'];
        const camposSelect = ['select-provincia', 'select-localidad', 'area', 'id_carrera', 'experiencia-requerida'];

        function validarCampo(id) {
            const el = document.getElementById(id);
            const errorEl = document.getElementById('error-' + id);
            let valido = true;

            if (!el || !el.value || !String(el.value).trim()) {
                valido = false;
                el?.classList.add('error');
                if (errorEl) errorEl.classList.add('show');
            } else {
                el?.classList.remove('error');
                if (errorEl) errorEl.classList.remove('show');
            }
            return valido;
        }

        function validarSelect(id) {
            const el = document.getElementById(id);
            const errorEl = document.getElementById('error-' + id);
            let valido = true;

            if (!el || !el.value || !String(el.value).trim()) {
                valido = false;
                el?.classList.add('error');
                if (errorEl) errorEl.classList.add('show');
            } else {
                el?.classList.remove('error');
                if (errorEl) errorEl.classList.remove('show');
            }
            return valido;
        }

        camposRequeridos.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('blur', () => validarCampo(id));
                el.addEventListener('input', () => {
                    if (el.classList.contains('error')) validarCampo(id);
                });
            }
        });

        camposSelect.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('change', () => validarSelect(id));
                el.addEventListener('blur', () => validarSelect(id));
            }
        });

        form.addEventListener('submit', (e) => {
            if (inputTech && inputTech.value.trim()) {
                createTag(inputTech.value.trim());
            }

            let todoValido = true;
            camposRequeridos.forEach(id => {
                if (!validarCampo(id)) todoValido = false;
            });
            camposSelect.forEach(id => {
                if (!validarSelect(id)) todoValido = false;
            });

            if (!todoValido) {
                e.preventDefault();
                const primerError = form.querySelector('.error');
                if (primerError) {
                    primerError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    primerError.focus();
                }
            } else {
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = `<span style="display:inline-flex;align-items:center;gap:6px;"><span style="width:16px;height:16px;border:2px solid rgba(255,255,255,0.3);border-top-color:#fff;border-radius:50%;animation:spin 0.8s linear infinite;display:inline-block;"></span> Publicando...</span>`;
            }
        });

        // Spinner keyframes
        if (!document.getElementById('spin-style')) {
            const style = document.createElement('style');
            style.id = 'spin-style';
            style.textContent = '@keyframes spin{to{transform:rotate(360deg)}}';
            document.head.appendChild(style);
        }


        document.getElementById('select-provincia').addEventListener('change', function() {
            const provinciaId = this.value;
            const selectLocalidad = document.getElementById('select-localidad');
            
            selectLocalidad.innerHTML = '<option value="">Cargando localidades...</option>';
            selectLocalidad.disabled = true;

            if (!provinciaId) {
                selectLocalidad.innerHTML = '<option value="">Seleccionar provincia primero...</option>';
                return;
            }

            // Llamada a tu ruta de la API (revisá si tu ruta es así o similar)
            fetch(`/api/provincias/${provinciaId}/localidades`)
                .then(response => response.json())
                .then(data => {
                    selectLocalidad.innerHTML = '<option value="">Seleccionar...</option>';
                    data.forEach(localidad => {
                        selectLocalidad.innerHTML += `<option value="${localidad.id_localidad}">${localidad.nombre}</option>`;
                    });
                    selectLocalidad.disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    selectLocalidad.innerHTML = '<option value="">Error al cargar</option>';
                });
        });



    })();
</script>

@endsection
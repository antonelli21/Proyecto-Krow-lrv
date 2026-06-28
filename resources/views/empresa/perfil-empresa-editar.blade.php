@extends('layouts.app')

@section('title', 'Editar Perfil Empresa — KROW')

@section('content')

<div class="panel-page">

    {{-- ══ BANNER ══ --}}
    <div id="banner-trigger" style="position:relative; left:50%; transform:translateX(-50%); width:100vw; margin-top:-1.5rem; height:320px; overflow:hidden; cursor:pointer;" title="Tocar para cambiar el banner">
        <div id="bannerPreview" style="width:100%; height:100%; background:{{ $empresa->banner ? 'url(\'' . \Illuminate\Support\Facades\Storage::url($empresa->banner) . '\') center/cover no-repeat' : 'linear-gradient(135deg, var(--surface) 0%, var(--border) 100%)' }}; display:flex; align-items:center; justify-content:center;">
            @if(!$empresa->banner)
                <span style="color:var(--muted); font-size:0.85rem;">
                    <i class="bi bi-image"></i> Tocar para subir un banner
                </span>
            @endif
        </div>
        <div id="banner-overlay" style="position:absolute; inset:0; background:rgba(0,0,0,0.4); display:flex; align-items:center; justify-content:center; opacity:0; transition:opacity .15s;">
            <span style="color:#fff; font-size:0.9rem;"><i class="bi bi-camera-fill"></i> Cambiar banner</span>
        </div>
    </div>
    <input type="file" id="banner" name="banner" accept="image/*"
           form="formEditarEmpresa" style="display:none;"
           onchange="previewBanner(this)">

    {{-- ══ HEADER centrado ══ --}}
    <div style="max-width: 720px; margin: 0 auto;">
        <div class="perfil-header-card">
            <div class="perfil-header-inner">

                <div class="perfil-avatar" id="logoPreview"
                     style="cursor:pointer; position:relative; {{ $empresa->logo ? 'background-image:url(\'' . \Illuminate\Support\Facades\Storage::url($empresa->logo) . '\'); background-size:cover; background-position:center;' : '' }}"
                     onclick="document.getElementById('logo').click()"
                     title="Tocar para cambiar el logo">
                    <span id="logoInitial">
                        @if(!$empresa->logo)
                            {{ strtoupper(substr($empresa->nombre_empresa ?? $usuario->name ?? 'E', 0, 1)) }}
                        @endif
                    </span>
                    <div class="avatar-overlay">
                        <i class="bi bi-camera-fill"></i>
                    </div>
                </div>
                <input type="file" id="logo" name="logo" accept="image/*"
                       form="formEditarEmpresa"
                       style="display:none;" onchange="previewLogo(this)">

                <div class="perfil-header-info" style="flex: 1;">
                    <h1 class="panel-page-title">Editar perfil</h1>
                    <p class="panel-page-sub">{{ $empresa->nombre_empresa ?? $usuario->name ?? '' }}</p>
                    @error('logo') <span class="config-error">{{ $message }}</span> @enderror
                </div>
                <a href="{{ route('empresa.perfil') }}" class="btn-outline">
                    <i class="bi bi-arrow-left"></i> Volver al perfil
                </a>
            </div>
        </div>
    </div>

    {{-- ══ FORMULARIO ══ --}}
    <div style="max-width: 720px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem;">

        <form id="formEditarEmpresa" action="{{ route('empresa.perfil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if (session('perfil_ok'))
                <div class="config-alert config-alert-success" style="margin-bottom:16px;">
                    <i class="bi bi-check-circle"></i> Perfil actualizado correctamente.
                </div>
            @endif

            {{-- ── Datos de la Empresa ── --}}
            <div class="perfil-card">
                <div class="perfil-card-header">
                    <i class="bi bi-building"></i> Datos de la Empresa
                </div>
                <div class="perfil-card-body" style="display: flex; flex-direction: column; gap: 16px;">

                    <div style="display: flex; gap: 16px; width: 100%;">
                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="nombre_empresa">Nombre de la empresa</label>
                            <input type="text" id="nombre_empresa" name="nombre_empresa"
                                   class="filter-input-text {{ $errors->has('nombre_empresa') ? 'input-error' : '' }}"
                                   value="{{ old('nombre_empresa', $empresa->nombre_empresa ?? '') }}" required>
                            @error('nombre_empresa') <span class="config-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="rubro">Rubro principal</label>
                            <input type="text" id="rubro" name="rubro"
                                   class="filter-input-text {{ $errors->has('rubro') ? 'input-error' : '' }}"
                                   placeholder="Software / Tecnología"
                                   value="{{ old('rubro', $empresa->rubro ?? '') }}" required>
                            @error('rubro') <span class="config-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div style="display: flex; gap: 16px; width: 100%;">
                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="razon_social">Razón social</label>
                            <input type="text" id="razon_social" name="razon_social"
                                   class="filter-input-text {{ $errors->has('razon_social') ? 'input-error' : '' }}"
                                   value="{{ old('razon_social', $empresa->razon_social ?? '') }}" required>
                            @error('razon_social') <span class="config-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="cuit">CUIT</label>
                            <input type="text" id="cuit" name="cuit"
                                   class="filter-input-text {{ $errors->has('cuit') ? 'input-error' : '' }}"
                                   placeholder="20123456789"
                                   value="{{ old('cuit', $empresa->cuit ?? '') }}" required>
                            @error('cuit') <span class="config-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="info-item" style="width: 100%;">
                        <label class="info-label" for="tamano_empresa">Tamaño de la empresa</label>
                        <div class="select-wrapper">
                            <select id="tamano_empresa" name="tamano_empresa" class="filter-select">
                                <option value="">Seleccionar...</option>
                                @foreach(['Microempresa', 'Pequena', 'Mediana', 'Grande'] as $t)
                                    <option value="{{ $t }}" {{ old('tamano_empresa', $empresa->tamano_empresa ?? '') === $t ? 'selected' : '' }}>
                                        {{ $t }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="info-item" style="width: 100%;">
                        <label class="info-label" for="sitio_web">Sitio web</label>
                        <input type="url" id="sitio_web" name="sitio_web"
                               class="filter-input-text"
                               placeholder="https://miempresa.com"
                               value="{{ old('sitio_web', $empresa->sitio_web ?? '') }}">
                    </div>

                    <div class="info-item" style="width: 100%;">
                        <label class="info-label" for="descripcion">Descripción de la organización</label>
                        <textarea id="descripcion" name="descripcion"
                                  class="filter-input-text"
                                  rows="4"
                                  placeholder="Describí brevemente a tu empresa...">{{ old('descripcion', $empresa->descripcion ?? '') }}</textarea>
                    </div>

                </div>
            </div>

            {{-- ── Representante ── --}}
            <div class="perfil-card">
                <div class="perfil-card-header">
                    <i class="bi bi-person-badge"></i> Representante
                </div>
                <div class="perfil-card-body" style="display: flex; flex-direction: column; gap: 16px;">
                    <div style="display: flex; gap: 16px; width: 100%;">
                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="representante">Nombre del representante</label>
                            <input type="text" id="representante" name="representante"
                                   class="filter-input-text {{ $errors->has('representante') ? 'input-error' : '' }}"
                                   value="{{ old('representante', $empresa->representante ?? '') }}" required>
                            @error('representante') <span class="config-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="email_representante">Email del representante</label>
                            <input type="email" id="email_representante" name="email_representante"
                                   class="filter-input-text {{ $errors->has('email_representante') ? 'input-error' : '' }}"
                                   value="{{ old('email_representante', $empresa->email_representante ?? '') }}" required>
                            @error('email_representante') <span class="config-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Ubicación ── --}}
            <div class="perfil-card">
                <div class="perfil-card-header">
                    <i class="bi bi-geo-alt"></i> Ubicación
                </div>
                <div class="perfil-card-body" style="display: flex; flex-direction: column; gap: 16px;">

                    <div class="info-item" style="width: 100%;">
                        <label class="info-label" for="direccion">Dirección</label>
                        <input type="text" id="direccion" name="direccion"
                               class="filter-input-text"
                               placeholder="Av. Siempreviva 742"
                               value="{{ old('direccion', $empresa->direccion ?? '') }}">
                    </div>

                    <div style="display: flex; gap: 16px; width: 100%;">
                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="id_provincia">Provincia</label>
                            <div class="select-wrapper">
                                <select id="id_provincia" name="id_provincia" class="filter-select">
                                    <option value="">Seleccionar...</option>
                                    @foreach($provincias as $p)
                                        <option value="{{ $p->id_provincia }}"
                                            {{ (int) old('id_provincia', $empresa->id_provincia ?? 0) === $p->id_provincia ? 'selected' : '' }}>
                                            {{ $p->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="id_localidad">Localidad</label>
                            <div class="select-wrapper">
                                <select id="id_localidad" name="id_localidad" class="filter-select">
                                    <option value="">Seleccionar...</option>
                                    @foreach($localidades as $l)
                                        <option value="{{ $l->id_localidad }}"
                                            {{ (int) old('id_localidad', $empresa->id_localidad ?? 0) === $l->id_localidad ? 'selected' : '' }}>
                                            {{ $l->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Contacto ── --}}
            <div class="perfil-card">
                <div class="perfil-card-header">
                    <i class="bi bi-envelope"></i> Contacto
                </div>
                <div class="perfil-card-body" style="display: flex; flex-direction: column; gap: 16px;">

                    <div style="display: flex; gap: 16px; width: 100%;">
                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="email_contacto">Correo electrónico</label>
                            <input type="email" id="email_contacto" name="email_contacto"
                                   class="filter-input-text {{ $errors->has('email_contacto') ? 'input-error' : '' }}"
                                   value="{{ old('email_contacto', $usuario->email ?? '') }}" required>
                            @error('email_contacto') <span class="config-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="telefono">Teléfono de contacto</label>
                            <input type="text" id="telefono" name="telefono"
                                   class="filter-input-text {{ $errors->has('telefono') ? 'input-error' : '' }}"
                                   placeholder="+54 9 11 0000 0000"
                                   value="{{ old('telefono', $empresa->telefono ?? '') }}" required>
                            @error('telefono') <span class="config-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Redes Sociales ── --}}
            <div class="perfil-card">
                <div class="perfil-card-header">
                    <i class="bi bi-share"></i> Redes Sociales
                </div>
                <div class="perfil-card-body" style="display: flex; flex-direction: column; gap: 16px;">

                    <div style="display: flex; gap: 16px; width: 100%;">
                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="linkedin">LinkedIn</label>
                            <input type="url" id="linkedin" name="linkedin"
                                   class="filter-input-text"
                                   placeholder="https://linkedin.com/company/empresa"
                                   value="{{ old('linkedin', $empresa->linkedin ?? '') }}">
                        </div>

                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="facebook">Facebook</label>
                            <input type="url" id="facebook" name="facebook"
                                   class="filter-input-text"
                                   placeholder="https://facebook.com/empresa"
                                   value="{{ old('facebook', $empresa->facebook ?? '') }}">
                        </div>

                        <div class="info-item" style="flex: 1;">
                            <label class="info-label" for="instagram">Instagram</label>
                            <input type="url" id="instagram" name="instagram"
                                   class="filter-input-text"
                                   placeholder="https://instagram.com/empresa"
                                   value="{{ old('instagram', $empresa->instagram ?? '') }}">
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Acciones ── --}}
            <div class="perfil-card">
                <div class="perfil-card-body" style="display: flex; flex-direction: row; justify-content: flex-end; gap: 12px;">
                    <a href="{{ route('empresa.perfil') }}" class="btn-outline">
                        Cancelar
                    </a>
                    <button type="submit" class="btn-apply-filters">
                        <i class="bi bi-check-lg"></i> Guardar cambios
                    </button>
                </div>
            </div>

        </form>

    </div>

</div>

<style>
.avatar-overlay {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: rgba(0,0,0,0.55);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity .15s ease;
    color: #fff;
    font-size: 1.1rem;
}
.perfil-avatar:hover .avatar-overlay {
    opacity: 1;
}
</style>

<script>
function previewBanner(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('bannerPreview');
            preview.style.background = `url('${e.target.result}') center/cover no-repeat`;
            preview.innerHTML = '';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

const bannerTrigger = document.getElementById('banner-trigger');
const bannerOverlay = document.getElementById('banner-overlay');
if (bannerTrigger && bannerOverlay) {
    bannerTrigger.addEventListener('mouseenter', () => bannerOverlay.style.opacity = '1');
    bannerTrigger.addEventListener('mouseleave', () => bannerOverlay.style.opacity = '0');
    bannerTrigger.addEventListener('click', () => document.getElementById('banner').click());
}

function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const avatar = document.getElementById('logoPreview');
            avatar.style.backgroundImage = `url('${e.target.result}')`;
            avatar.style.backgroundSize = 'cover';
            avatar.style.backgroundPosition = 'center';
            document.getElementById('logoInitial').textContent = '';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

document.getElementById('id_provincia').addEventListener('change', function () {
    const idProvincia = this.value;
    const selectLocalidad = document.getElementById('id_localidad');

    if (!idProvincia) {
        selectLocalidad.innerHTML = '<option value="">Seleccionar...</option>';
        return;
    }

    selectLocalidad.innerHTML = '<option value="">Cargando...</option>';

    fetch(`/localidades/${idProvincia}`)
        .then(res => res.json())
        .then(data => {
            selectLocalidad.innerHTML = '<option value="">Seleccionar...</option>';
            data.forEach(loc => {
                const opt = document.createElement('option');
                opt.value = loc.id_localidad;
                opt.textContent = loc.nombre;
                selectLocalidad.appendChild(opt);
            });
        })
        .catch(() => {
            selectLocalidad.innerHTML = '<option value="">Error al cargar</option>';
        });
});
</script>

@endsection
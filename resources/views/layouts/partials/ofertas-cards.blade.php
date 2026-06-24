{{-- Tarjetas --}}
<div id="cards-list">
@forelse ($ofertas as $oferta)

@php
$id = data_get($oferta, 'id', data_get($oferta, 'id_oferta', $loop->index));
$titulo = data_get($oferta, 'titulo', '');
$empresa = data_get($oferta, 'empresa.nombre_empresa', data_get($oferta, 'empresa.nombre', data_get($oferta, 'empresa', '')));
$modalidad = data_get($oferta, 'modalidad', '');
$salario = data_get($oferta, 'salario', '');
if (!$salario && data_get($oferta, 'salario_min')) {
$salario = '$' . number_format(data_get($oferta, 'salario_min'), 0, ',', '.')
. (data_get($oferta, 'salario_max') ? ' - $' . number_format(data_get($oferta, 'salario_max'), 0, ',', '.') : '');
}
$tipo = data_get($oferta, 'tipo', data_get($oferta, 'tipo_oferta', ''));
$experiencia = data_get($oferta, 'experiencia_requerida', '');
$area = data_get($oferta, 'area', '');
$carrera = data_get($oferta, 'carrera.nombre', '');
$provincia = data_get($oferta, 'provincia.nombre', '');
$localidad = data_get($oferta, 'localidad.nombre', '');
$ubicacion = collect([$localidad, $provincia])->filter()->implode(', ');
$esNueva = data_get($oferta, 'es_nueva', false);
$descripcion = data_get($oferta, 'descripcion', '');
$fechaTxt = data_get($oferta, 'fecha_texto',
    $oferta->fecha_publicacion
        ? \Carbon\Carbon::parse($oferta->fecha_publicacion)->diffForHumans()
        : ''
);
$logoLetras = strtoupper(substr($empresa ?: '?', 0, 2));
@endphp

<article
    class="job-card"
    data-id="{{ $id }}"
    data-salario="{{ data_get($oferta, 'salario_min', 0) }}"
    data-fecha="{{ $oferta->fecha_publicacion ? \Carbon\Carbon::parse($oferta->fecha_publicacion)->timestamp : 0 }}" style="margin-bottom:1rem;">

    <div class="job-card-top">
        <div class="company-logo" aria-hidden="true">{{ $logoLetras }}</div>
        <div class="job-info">
            <h3 class="job-title">{{ $titulo }}</h3>
            <p class="job-meta">
                <span>{{ $empresa }}</span> &bull; <span>{{ $modalidad }}</span>
            </p>
        </div>
    </div>

    <div class="job-badges">
        @if ($salario)
            <span class="badge badge-salary">{{ $salario }}</span>
        @endif

        @if ($tipo)
            <span class="badge badge-outline">{{ $tipo }}</span>
        @endif

        @if ($modalidad)
            <span class="badge badge-modalidad">{{ $modalidad }}</span>
        @endif

        @if ($experiencia)
            <span class="badge badge-exp">{{ $experiencia }}</span>
        @endif

        @if ($area)
            <span class="badge badge-area">{{ $area }}</span>
        @endif

        @if ($carrera)
            <span class="badge badge-carrera">{{ $carrera }}</span>
        @endif

        @if ($ubicacion)
            <span class="badge badge-location">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                    <circle cx="12" cy="9" r="2.5"/>
                </svg>
                {{ $ubicacion }}
            </span>
        @endif

        @if ($esNueva)
            <span class="badge badge-new">Nuevo</span>
        @endif
    </div>

    @if ($descripcion)
        <p class="job-desc">{{ $descripcion }}</p>
    @endif

    <div class="job-footer">
        <span class="job-date">{{ $fechaTxt }}</span>
        <a href="{{ route('ofertas.detalle', $id) }}" class="btn-ver">Ver oferta</a>
    </div>

</article>

@empty
<div style="padding: 2rem; text-align: center; color: var(--muted);">
    <p>No se encontraron ofertas con los filtros seleccionados.</p>
</div>
@endforelse
</div>

{{-- Paginación --}}
@if ($ofertas instanceof \Illuminate\Pagination\LengthAwarePaginator && $ofertas->hasPages())
<nav class="pagination" id="pagination" aria-label="Páginas de resultados">

    @if ($ofertas->onFirstPage())
    <button class="pg-btn" id="pg-prev" aria-label="Página anterior" disabled>
        <i class="bi bi-chevron-left"></i>
    </button>
    @else
    <a href="{{ $ofertas->previousPageUrl() }}" class="pg-btn" id="pg-prev" aria-label="Página anterior">
        <i class="bi bi-chevron-left"></i>
    </a>
    @endif

    @foreach ($ofertas->getUrlRange(1, $ofertas->lastPage()) as $page => $url)
    @if ($page == $ofertas->currentPage())
    <button class="pg-btn active" aria-current="page">{{ $page }}</button>
    @else
    <a href="{{ $url }}" class="pg-btn">{{ $page }}</a>
    @endif
    @endforeach

    @if ($ofertas->hasMorePages())
    <a href="{{ $ofertas->nextPageUrl() }}" class="pg-btn" id="pg-next" aria-label="Página siguiente">
        <i class="bi bi-chevron-right"></i>
    </a>
    @else
    <button class="pg-btn" id="pg-next" aria-label="Página siguiente" disabled>
        <i class="bi bi-chevron-right"></i>
    </button>
    @endif

</nav>
@endif
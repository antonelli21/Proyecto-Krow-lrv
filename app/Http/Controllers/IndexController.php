<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Oferta;
use App\Models\Empresa;
use App\Models\Estudiante;
use App\Models\Postulacion;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function inicio(Request $request)
    {
        $query = Oferta::with(['empresa', 'provincia', 'localidad', 'carreras', 'habilidades'])->where('estado', 'activa');

        // Filtro de búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('titulo', 'like', "%{$buscar}%")
                    ->orWhere('descripcion', 'like', "%{$buscar}%")
                    ->orWhereHas('empresa', function ($qEmp) use ($buscar) {
                        $qEmp->where('nombre_empresa', 'like', "%{$buscar}%");
                    });
            });
        }

        // Filtro por empresa (desde Base de Empresas)
if ($request->filled('empresa_id')) {
    $query->where('id_empresa', $request->empresa_id);
}

        // Provincia
        if ($request->filled('provincia')) {
            $query->whereHas('provincia', function ($q) use ($request) {
                $q->where('nombre', $request->provincia);
            });
        }

        // Localidad (si está filtrado por localidad)
        if ($request->filled('localidad')) {
            $query->whereHas('localidad', function ($q) use ($request) {
                $q->where('nombre', $request->localidad);
            });
        }

        // Categoría (área)
        if ($request->filled('categoria')) {
            $query->where('area', $request->categoria);
        }

        // Tipo de contrato
        if ($request->filled('contrato')) {
            $query->whereIn('tipo_oferta', $request->contrato);
        }

        if ($request->filled('carrera')) {
    $query->whereHas('carreras', function ($q) use ($request) {
        $q->whereIn('carrera.id_carrera', $request->carrera);
    });
}

        // Modalidad
        if ($request->filled('modalidad')) {
            $query->whereIn('modalidad', $request->modalidad);
        }

// Tecnologías (múltiples)
if ($request->filled('tecnologias')) {
    $techs = $request->tecnologias;
    $query->whereHas('habilidades', function ($q) use ($techs) {
        $q->whereIn('nombre', $techs);
    });
}

        // Fecha de publicación
        if ($request->filled('fecha') && $request->fecha !== 'total') {
            if ($request->fecha === 'hoy') {
                $query->whereDate('fecha_publicacion', \Carbon\Carbon::today());
            } elseif ($request->fecha === 'ultima-semana') {
                $query->where('fecha_publicacion', '>=', \Carbon\Carbon::now()->subWeek());
            } elseif ($request->fecha === 'ultimo-mes') {
                $query->where('fecha_publicacion', '>=', \Carbon\Carbon::now()->subMonth());
            }
        }
        $orden = $request->get('orden', 'recientes');
        if ($orden === 'salario-asc') {
            $query->orderBy('salario_min', 'asc');
        } elseif ($orden === 'salario-desc') {
            $query->orderBy('salario_min', 'desc');
        } else {
            $query->orderBy('fecha_publicacion', 'desc');
        }

        $ofertas = $query->paginate(6)->withQueryString();

        
        // Datos para el right panel
        $panelData = [];
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->rol === 'estudiante' && $user->estudiante) {
                $estudiante = $user->estudiante->load('habilidades');
    
    // Calcular completitud del perfil
    $campos = [
        'foto_perfil'        => 10,
        'cv'                 => 20,
        'telefono'           => 10,
        'descripcion'        => 15,
        'linkedin'           => 10,
        'modalidad_deseada'  => 10,
        'portfolio'          => 10,
    ];
    $completitud = 0;
    foreach ($campos as $campo => $peso) {
        if (!empty($estudiante->$campo)) $completitud += $peso;
    }
    if ($estudiante->habilidades->count() > 0) $completitud += 15;
    
    $panelData['completitud'] = $completitud;
    $panelData['sin_cv'] = empty($estudiante->cv);
                $estudiante = $user->estudiante;
                $panelData['postulaciones'] = Postulacion::where('id_estudiante', $estudiante->id_estudiante)->count();

$panelData['en_revision'] = Postulacion::where('id_estudiante', $estudiante->id_estudiante)
    ->whereIn('estado', ['Preseleccionado', 'En Contacto'])
    ->count();

$panelData['contactado'] = Postulacion::where('id_estudiante', $estudiante->id_estudiante)
    ->where('estado', 'En Contacto')
    ->count();
                $panelData['ultimas_ofertas'] = Oferta::with('empresa')->where('estado', 'activa')->orderBy('fecha_publicacion', 'desc')->take(3)->get();
                
            } elseif ($user->rol === 'empresa' && $user->empresa) {
                $empresa = $user->empresa;
                $ofertasIds = $empresa->ofertas()->pluck('id_oferta');
                $panelData['ofertas_activas'] = $empresa->ofertas()->where('estado', 'activa')->count();
                $panelData['postulantes_recibidos'] = Postulacion::whereIn('id_oferta', $ofertasIds)->count();
                $panelData['ultimos_postulantes'] = Postulacion::with('estudiante.user', 'oferta')->whereIn('id_oferta', $ofertasIds)->orderBy('fecha_postulacion', 'desc')->take(3)->get();
            } elseif ($user->rol === 'admin') {
                $panelData['total_usuarios'] = \App\Models\User::count();
                $panelData['empresas_activas'] = Empresa::where('estado', 'aprobada')->count();
                $panelData['ofertas_publicadas'] = Oferta::where('estado', 'activa')->count();
                $panelData['empresas_pendientes'] = Empresa::where('estado', 'pendiente')->count();
                $panelData['nuevos_registros'] = \App\Models\User::whereDate('fecha_creacion', \Carbon\Carbon::today())->count();
            }
        }

        $provinciasFiltro = \App\Models\Provincia::with('localidades')->orderBy('nombre')->get();
        $categoriasFiltro = Oferta::select('area')->distinct()->pluck('area')->filter();
        $modalidadesFiltro = Oferta::select('modalidad')->distinct()->pluck('modalidad')->filter();
        $contratosFiltro = Oferta::select('tipo_oferta')->distinct()->pluck('tipo_oferta')->filter();
        $carrerasFiltro = \App\Models\Carrera::orderBy('nombre')->get();

        $localidadesMap = [];
        foreach ($provinciasFiltro as $prov) {
            $localidadesMap[$prov->nombre] = $prov->localidades->pluck('nombre')->toArray();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'html'  => view('layouts.partials.ofertas-cards', compact('ofertas'))->render(),
                'total' => $ofertas instanceof \Illuminate\Pagination\LengthAwarePaginator ? $ofertas->total() : $ofertas->count(),
            ]);
        }

        return view('index', compact('ofertas', 'panelData', 'provinciasFiltro', 'categoriasFiltro', 'localidadesMap', 'modalidadesFiltro', 'contratosFiltro', 'carrerasFiltro'));
    }

    public function empresas(Request $request)
    {
        $query = Empresa::with('ofertas')->where('estado', 'aprobada');

        // Filtros (opcionales para el futuro)
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre_empresa', 'like', "%{$buscar}%")
                    ->orWhere('rubro', 'like', "%{$buscar}%");
            });
        }

        $empresas = $query->orderBy('nombre_empresa', 'asc')->get();

        return view('empresas', compact('empresas'));
    }

    public function contacto(Request $request)
    {
        $request->validate([
            'nombre'  => 'required|string|min:2|max:100',
            'email'   => 'required|email|max:150',
            'asunto'  => 'required|string|min:3|max:200',
            'mensaje' => 'required|string|min:20',
        ]);

        $idUsuario = \App\Models\User::where('email', $request->email)->value('id');

        \DB::table('ticket_soporte')->insert([
            'id_usuario'       => $idUsuario,
            'nombre_remitente' => $request->nombre,
            'email_remitente'  => $request->email,
            'asunto'           => $request->asunto,
            'descripcion'      => $request->mensaje,
            'estado'           => 'Abierto',
            'fecha_creacion'   => now(),
        ]);

        // ── Notificar a todos los admins ──────────────────────
        $ahora = now();
        \App\Models\User::where('rol', 'admin')->each(function ($admin) use ($request, $ahora) {
            \DB::table('notificaciones')->insert([
                'id_usuario'  => $admin->id,
                'titulo'      => 'Nuevo reporte de soporte',
                'mensaje'     => "{$request->nombre} abrió un ticket: \"{$request->asunto}\".",
                'url'         => route('admin.reportes'),
                'tipo'        => 'danger',
                'leida'       => false,
                'created_at'  => $ahora,
                'updated_at'  => $ahora,
            ]);
        });

        return redirect()->back()->with('contacto_ok', true);
    }
    public function perfilEmpresa($id)
    {
        $empresa = Empresa::with(['localidad', 'provincia'])
            ->findOrFail($id);

        $esAdmin = auth()->check() && auth()->user()->rol === 'admin';

        if ($empresa->estado !== 'aprobada' && !$esAdmin) {
            abort(404);
        }

        $ofertas = \App\Models\Oferta::where('id_empresa', $empresa->id_empresa)
            ->where('estado', 'Activa')
            ->get();

        return view('empresa.perfil-publico', compact('empresa', 'ofertas'));
    }
}

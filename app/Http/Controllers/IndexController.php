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
        $query = Oferta::with(['empresa', 'provincia', 'localidad', 'carrera', 'habilidades'])->where('estado', 'activa');

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

        $ofertas = $query->orderBy('fecha_publicacion', 'desc')->paginate(6)->withQueryString();

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
                $panelData['en_revision'] = Postulacion::where('id_estudiante', $estudiante->id_estudiante)->where('estado', 'en_revision')->count();
                $panelData['contactado'] = Postulacion::where('id_estudiante', $estudiante->id_estudiante)->where('estado', 'contacto')->count();
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
                $panelData['ofertas_pendientes'] = Oferta::where('estado', 'pendiente')->count();
                $panelData['nuevos_registros'] = \App\Models\User::whereDate('fecha_creacion', \Carbon\Carbon::today())->count();
            }
        }

        $provinciasFiltro = \App\Models\Provincia::with('localidades')->orderBy('nombre')->get();
        $categoriasFiltro = Oferta::select('area')->distinct()->pluck('area')->filter();
        $modalidadesFiltro = Oferta::select('modalidad')->distinct()->pluck('modalidad')->filter();
        $contratosFiltro = Oferta::select('tipo_oferta')->distinct()->pluck('tipo_oferta')->filter();

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

        return view('index', compact('ofertas', 'panelData', 'provinciasFiltro', 'categoriasFiltro', 'localidadesMap', 'modalidadesFiltro', 'contratosFiltro'));
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
            'nombre'  => 'required|min:2',
            'email'   => 'required|email',
            'asunto'  => 'required|min:3',
            'mensaje' => 'required|min:20',
        ]);

        // Parche SSL temporal para entorno local
        config([
            'mail.mailers.smtp.stream' => [
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ],
        ]);

        try {
            Mail::raw("Mensaje de: {$request->nombre} <{$request->email}>\n\nAsunto: {$request->asunto}\n\nMensaje:\n{$request->mensaje}", function ($message) use ($request) {
                $message->to(env('MAIL_FROM_ADDRESS', 'soporte@krow.com'))
                    ->replyTo($request->email, $request->nombre)
                    ->subject("Contacto KROW: {$request->asunto}");
            });
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'No se pudo enviar el correo. Intente más tarde.'], 500);
            }
            return back()->withErrors(['email' => 'No se pudo enviar el correo: ' . $e->getMessage()]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Mensaje enviado correctamente.']);
        }

        return back()->with('contacto_ok', true);
    }

    public function perfilEmpresa($id)
{
    $empresa = Empresa::with(['localidad', 'provincia', 'ofertas'])
        ->where('estado', 'aprobada')
        ->findOrFail($id);

    return view('empresa-perfil-publico', compact('empresa'));
}
}

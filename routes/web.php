<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificacionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\TicketSoporteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ConfiguracionController;

/* ════════════════════════════════════════
   RUTAS PÚBLICAS
   Accesibles por cualquier visitante sin necesidad de autenticación.
════════════════════════════════════════ */


Route::get('/', [IndexController::class, 'inicio'])->name('inicio');

Route::get('/ayuda', function () {
    return view('ayuda');
})->name('ayuda');

Route::get('/empresas', [IndexController::class, 'empresas'])->name('empresas');
Route::get('/empresas/{id}', [IndexController::class, 'perfilEmpresa'])->name('empresas.perfil');

// Detalle de oferta — accesible por todos (invitado, estudiante, empresa, admin)
Route::get('/ofertas/{id_oferta}', [OfertaController::class, 'detalle'])->name('ofertas.detalle');

/* ════════════════════════════════════════
   AUTH — RUTAS DE INVITADOS (GUEST)
   Solo accesibles si el usuario NO está logueado.
   El middleware 'guest' redirige a usuarios autenticados a su panel.
════════════════════════════════════════ */
Route::middleware('guest')->group(function () {

    // Mostrar el formulario de login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

    // Procesar el formulario de login (POST)
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.post');

    // Pantalla de registro pendiente (empresas)
    Route::get('/registro/pendiente', function () {
        return view('auth.registro-pendiente');
    })->name('registro.pendiente');

    // Mostrar el formulario de registro (con tabs candidato/empresa)
    Route::get('/registro', [RegisterController::class, 'showRegistrationForm'])->name('register');

    // Procesar el registro de un estudiante (POST)
    Route::post('/registro/estudiante', [RegisterController::class, 'registerEstudiante'])->name('register.estudiante');

    // Procesar el registro de una empresa (POST)
    Route::post('/registro/empresa', [RegisterController::class, 'registerEmpresa'])->name('register.empresa');

    // ════ RECUPERAR CONTRASEÑA ════
    Route::get('/password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
});

/* ════════════════════════════════════════
   VERIFICACIÓN DE EMAIL
   Rutas para el flujo de verificación de email con código de 6 dígitos.
   Accesibles sin estar logueado (el usuario aún no verificó su email).
════════════════════════════════════════ */

Route::get('/verificar-email', [VerificacionController::class, 'mostrar'])->name('verificacion.mostrar');
Route::post('/verificar-email', [VerificacionController::class, 'verificar'])->name('verificacion.verificar');
Route::post('/verificar-email/reenviar', [VerificacionController::class, 'reenviar'])->name('verificacion.reenviar');

/* ════════════════════════════════════════
   LOGOUT
════════════════════════════════════════ */
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/* ════════════════════════════════════════
   ESTUDIANTE
   Rutas protegidas para usuarios con rol 'estudiante'.
════════════════════════════════════════ */
Route::prefix('estudiante')
    ->name('estudiante.')
    ->middleware(['auth', 'verified', 'role:estudiante', 'alumno.activo']) // <-- ¡OJO! Acordate de meter acá tu middleware de bloqueo
    ->group(function () {
        Route::get('/home',                             function () {     return view('estudiante.home-estudiante');})->name('home');
        Route::get('/empresas',                         function () {return view('empresas');})->name('empresas');
        Route::get('/perfil',                           [EstudianteController::class, 'verPerfil'])->name('perfil');
        Route::get('/mensajes',                         function () {return view('mensajes');})->name('mensajes');
        Route::get('/oferta/{id}',                      function ($id) {return view('estudiante.oferta-detalle', compact('id'));})->name('oferta');
        Route::get('/lista',                            [EstudianteController::class, 'lista'])->name('lista');
        Route::get('/perfil/editar',                    [EstudianteController::class, 'editarPerfil'])->name('perfil.editar');
        Route::put('/perfil/update',                    [EstudianteController::class, 'updatePerfil'])->name('perfil.update');
        Route::get('/ofertas',                          [EstudianteController::class, 'obtenerOfertas'])->name('ofertas');
        Route::post('/ofertas/{id_oferta}/postular',    [OfertaController::class, 'postular'])->name('ofertas.postular');
        Route::get('/oferta/{id_oferta}/preview',       [OfertaController::class, 'preview'])->name('ofertas.preview');
        Route::get('/empresa/{empresa}',                [EmpresaController::class, 'verPerfilPublico'])->name('empresa.perfil');
        Route::delete('/postulacion/{id}', [EstudianteController::class, 'cancelarPostulacion']);
    });

Route::prefix('empresa')
    ->name('empresa.')
    ->middleware(['auth', 'verified', 'role:empresa', 'empresa.activa'])
    ->group(function () {
        Route::get('/home',                         [EmpresaController::class, 'home'])->name('home');
        Route::get('/perfil',                       [EmpresaController::class, 'verPerfil'])->name('perfil');
        Route::get('/mensajes',                     function () {return view('mensajes');})->name('mensajes');
        Route::get('/oferta/{id}/postulantes',      [EmpresaController::class, 'verPostulantes'])->name('ofertas.postulantes');
        Route::post('/crear-oferta',                [EmpresaController::class, 'storeOferta'])->name('ofertas.store');
        Route::get('/crear-oferta',                 [EmpresaController::class, 'crearOferta'])->name('crear-oferta');
        Route::put('/postulacion/{id}/estado',      [EmpresaController::class, 'actualizarEstadoPostulante'])->name('actualizar-estado');
        Route::get('/perfil/editar',                [EmpresaController::class, 'editarPerfil'])->name('perfil.editar');
        Route::put('/perfil/update',                [EmpresaController::class, 'updatePerfil'])->name('perfil.update');
        Route::get('/estudiante/{id}',              [EmpresaController::class, 'verPerfilEstudiante'])->name('estudiante.perfil'); // ← 
        Route::patch('/oferta/{id}/estado',         [EmpresaController::class, 'cambiarEstadoOferta'])->name('ofertas.estado');
        Route::delete('/oferta/{id}',               [EmpresaController::class, 'eliminarOferta'])->name('ofertas.destroy');
       Route::get('/oferta/{id_oferta}/preview',    [OfertaController::class, 'preview'])->name('ofertas.preview');
       
    });
/* ════════════════════════════════════════
   ADMIN
   Rutas protegidas para usuarios con rol 'admin'.
════════════════════════════════════════ */
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        Route::get('/home',           [AdminController::class, 'home'])->name('home');
        Route::get('/empresas',       [AdminController::class, 'listarEmpresas'])->name('empresas');
        Route::get('/estudiantes',    [AdminController::class, 'listarEstudiantes'])->name('estudiantes');
        Route::get('/ofertas',        [AdminController::class, 'listarOfertas'])->name('ofertas');
        Route::get('/mensajes',       fn() => view('mensajes'))->name('mensajes');

        // Estado individual
        Route::post('/estudiantes/{id}/estado', [AdminController::class, 'cambiarEstadoEstudiante'])->name('estudiantes.estado');
        Route::post('/empresas/{id}/estado',    [AdminController::class, 'cambiarEstadoEmpresa'])->name('empresas.estado');
        Route::post('/ofertas/{id}/estado',     [AdminController::class, 'cambiarEstadoOferta'])->name('ofertas.estado');

        // Eliminación individual
        Route::delete('/estudiantes/{id}', [AdminController::class, 'eliminarEstudiante'])->name('estudiantes.destroy');
        Route::delete('/empresas/{id}',    [AdminController::class, 'eliminarEmpresa'])->name('empresas.destroy');
        Route::delete('/ofertas/{id}',     [AdminController::class, 'eliminarOferta'])->name('ofertas.destroy');

        // Bulk estudiantes
        Route::post('/estudiantes/bulk-estado',   [AdminController::class, 'bulkEstadoEstudiantes'])->name('estudiantes.bulk-estado');
        Route::post('/estudiantes/bulk-destroy',  [AdminController::class, 'bulkDestroyEstudiantes'])->name('estudiantes.bulk-destroy');

        // Bulk empresas
        Route::post('/empresas/bulk-estado',      [AdminController::class, 'bulkEstadoEmpresas'])->name('empresas.bulk-estado');
        Route::post('/empresas/bulk-destroy',     [AdminController::class, 'bulkDestroyEmpresas'])->name('empresas.bulk-destroy');

        // Bulk ofertas
        Route::post('/ofertas/bulk-estado',       [AdminController::class, 'bulkEstadoOfertas'])->name('ofertas.bulk-estado');
        Route::post('/ofertas/bulk-destroy',      [AdminController::class, 'bulkDestroyOfertas'])->name('ofertas.bulk-destroy');
    });

/* ════════════════════════════════════════
   COMPARTIDAS (LOGUEADOS)
   Accesibles por cualquier usuario autenticado con email verificado.
════════════════════════════════════════ */




Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mensajes',       function () {
        return view('mensajes');
    })->name('mensajes');
    Route::get('/notificaciones', function () {
        return view('notificaciones');
    })->name('notificaciones');

    Route::get('/configuracion',             [ConfiguracionController::class, 'index'])->name('configuracion');
    Route::put('/configuracion/password',    [ConfiguracionController::class, 'cambiarPassword'])->name('configuracion.password');
    Route::post('/configuracion/logout-all', [ConfiguracionController::class, 'logoutAll'])->name('configuracion.logout-all');


    Route::get('/localidades/{id_provincia}', function ($id_provincia) {
        return \App\Models\Localidad::where('id_provincia', $id_provincia)->orderBy('nombre')->get();
    })->name('localidades.por-provincia');
});

/* ════════════════════════════════════════
   FORMULARIO DE CONTACTO (público)
════════════════════════════════════════ */
Route::post('/ayuda/contacto', [IndexController::class, 'contacto'])->name('ayuda.contacto');

/* ════════════════════════════════════════
   API RESOURCES
   Rutas de API RESTful para los recursos del sistema.
   Usadas por el frontend con fetch/AJAX.
════════════════════════════════════════ */
Route::prefix('api')
    ->middleware(['auth', 'verified'])
    ->group(function () {

        // ── Chats ──
        Route::get('/chats/buscar-o-crear', [ChatController::class, 'buscarOCrear'])
            ->name('chats.buscar-o-crear');

        Route::apiResource('chats', ChatController::class)
            ->only(['index', 'show', 'store', 'destroy']);

        // ── Mensajes ──
        Route::get('/chats/{id_chat}/mensajes', [MensajeController::class, 'getMensajesByChat'])
            ->name('chats.mensajes');
        Route::post('/mensajes', [MensajeController::class, 'store'])
            ->name('mensajes.store'); 

        // ── Recursos públicos que sí pueden necesitar auth ──
        Route::apiResource('tickets', TicketSoporteController::class)
            ->only(['index', 'show', 'store', 'update', 'destroy']);

        // ── Localidades ──
        Route::get('/provincias/{id}/localidades', function ($id) {
            return \App\Models\Localidad::where('id_provincia', $id)
                ->orderBy('nombre')
                ->get(['id_localidad', 'nombre']);
        });

        Route::apiResource('estudiantes', EstudianteController::class)
            ->only(['index', 'show', 'store', 'update', 'destroy']);
    });
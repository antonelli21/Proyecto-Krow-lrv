<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\TicketSoporteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* ════════════════════════════════════════
   RUTAS PÚBLICAS
════════════════════════════════════════ */
Route::get('/', function () {
    return view('index');
})->name('inicio');

Route::get('/ayuda', function () {
    return view('ayuda');
})->name('ayuda');

Route::get('/empresas', function () {
    return view('empresas');
})->name('empresas');

/* ════════════════════════════════════════
   AUTH
════════════════════════════════════════ */
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/registro', function () {
    return view('auth.registro');
})->name('register');

Route::post('/logout', function () {
    //auth()->logout();
    return redirect()->route('inicio');
})->name('logout');

/* ════════════════════════════════════════
   ESTUDIANTE
════════════════════════════════════════ */
    Route::prefix('estudiante')->name('estudiante.')->group(function () {
    Route::get('/home',        function () { return view('estudiante.home-estudiante'); })->name('home');
    Route::get('/empresas',    function () { return view('estudiante.empresas-lista');  })->name('empresas');
    Route::get('/perfil',      function () { return view('estudiante.perfil-estudiante'); })->name('perfil');
    Route::get('/mensajes',    function () { return view('estudiante.mensajes-estudiante'); })->name('mensajes');
    Route::get('/oferta/{id}', function ($id) { return view('estudiante.oferta-detalle', compact('id')); })->name('oferta');
});

/* ════════════════════════════════════════
   EMPRESA
════════════════════════════════════════ */
Route::prefix('empresa')->name('empresa.')->group(function () {
    Route::get('/home',        function () { return view('empresa.home-empresa');       })->name('home');
    Route::get('/perfil',      function () { return view('empresa.perfil-empresa');     })->name('perfil');
    Route::get('/mensajes',    function () { return view('empresa.mensajes-empresa');   })->name('mensajes');
    Route::get('/postulantes', function () { return view('empresa.postulantes-empresa'); })->name('postulantes');
});

/* ════════════════════════════════════════
   ADMIN
════════════════════════════════════════ */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/home',       function () { return view('admin.admin-empresas-ofertas'); })->name('home');
    Route::get('/empresas',   function () { return view('admin.admin-empresas-ofertas'); })->name('empresas');
    Route::get('/estudiantes',function () { return view('admin.admin-estudiantes');      })->name('estudiantes');
});

/* ════════════════════════════════════════
   COMPARTIDAS (logueados)
════════════════════════════════════════ */
Route::get('/mensajes',      function () { return view('mensajes');      })->name('mensajes');
Route::get('/notificaciones',function () { return view('notificaciones'); })->name('notificaciones');
Route::get('/configuracion', function () { return view('configuracion');  })->name('configuracion');



Route::post('/ayuda/contacto', function(Request $request) {
    $request->validate([
        'nombre'  => 'required|min:2',
        'email'   => 'required|email',
        'asunto'  => 'required|min:3',
        'mensaje' => 'required|min:20',
    ]);
    // aquí enviás el mail o guardás en BD
    return back()->with('contacto_ok', true);
})->name('ayuda.contacto');

Route::prefix('api')->group(function () {
    Route::apiResource('estudiantes', EstudianteController::class)->only(['index','show','store','update','destroy']);
    Route::apiResource('empresas', EmpresaController::class)->only(['index','show','store','update','destroy']);
    Route::apiResource('ofertas', OfertaController::class)->only(['index','show','store','update','destroy']);
    Route::apiResource('chats', ChatController::class)->only(['index','show','store','destroy']);
    Route::apiResource('mensajes', MensajeController::class)->only(['index','show','store','update','destroy']);
    Route::apiResource('tickets', TicketSoporteController::class)->only(['index','show','store','update','destroy']);
});
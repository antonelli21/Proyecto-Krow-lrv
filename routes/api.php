<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MensajeController;

// Tus rutas de la API para el JavaScript:
Route::apiResource('chats', ChatController::class);
Route::apiResource('mensajes', MensajeController::class);
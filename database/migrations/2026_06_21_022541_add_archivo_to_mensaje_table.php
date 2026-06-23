<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mensaje', function (Blueprint $table) {
            // Agregamos los campos como nullable porque no todos los mensajes tienen archivos
            $table->string('ruta_archivo')->nullable()->after('contenido');
            $table->string('nombre_archivo')->nullable()->after('ruta_archivo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mensaje', function (Blueprint $table) {
            $table->dropColumn(['ruta_archivo', 'nombre_archivo']);
        });
    }
};
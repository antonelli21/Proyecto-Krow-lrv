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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();

            // Usuario que recibe la notificación
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');

            // Contenido
            $table->string('titulo');
            $table->text('mensaje');

            // Página a la que redirige al hacer clic
            $table->string('url')->nullable();

            // info, success, warning, danger, message
            $table->string('tipo')->default('info');

            // Si fue leída o no
            $table->boolean('leida')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();

            // Usuario que recibe la notificación
            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');

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

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
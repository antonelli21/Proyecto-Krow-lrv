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
        Schema::create('mensajes', function (Blueprint $table) {
            $table->id('id_mensaje');
            // Une el mensaje a la sala de chat correspondiente
            $table->foreignId('id_conversacion')->constrained('conversaciones', 'id_conversacion')->onDelete('cascade');
            // Quién escribió este mensaje concreto (Usuario)
            $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');
            $table->text('contenido');
            $table->boolean('leido')->default(false); // Para saber si el otro lo vio
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensajes');
    }
};

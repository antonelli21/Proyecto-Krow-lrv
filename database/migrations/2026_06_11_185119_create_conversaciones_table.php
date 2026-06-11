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
        schema::create('conversaciones', function (Blueprint $table) {
            $table->id('id_conversacion');
        // El usuario que inicia el chat (ej: el estudiante que postula)
            $table->foreignId('id_remitente')->constrained('users')->onDelete('cascade');
        // El usuario que recibe el chat (ej: la empresa)
            $table->foreignId('id_destinatario')->constrained('users')->onDelete('cascade');
            $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversaciones');
    }
};

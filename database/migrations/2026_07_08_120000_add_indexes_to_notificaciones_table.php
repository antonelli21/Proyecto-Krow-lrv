<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notificaciones', function (Blueprint $table) {
            // Acelera contarNoLeidas() / scopeNoLeidas():
            // WHERE id_usuario = ? AND leida = false
            $table->index(['id_usuario', 'leida'], 'notificaciones_usuario_leida_index');

            // Acelera obtenerRecientes() / obtenerHistorial():
            // WHERE id_usuario = ? ORDER BY created_at DESC
            $table->index(['id_usuario', 'created_at'], 'notificaciones_usuario_created_index');
        });
    }

    public function down(): void
    {
        Schema::table('notificaciones', function (Blueprint $table) {
            $table->dropIndex('notificaciones_usuario_leida_index');
            $table->dropIndex('notificaciones_usuario_created_index');
        });
    }
};
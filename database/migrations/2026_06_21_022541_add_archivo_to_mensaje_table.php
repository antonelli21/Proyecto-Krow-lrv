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
            $table->string('archivo')->nullable()->after('contenido');
            $table->string('archivo_nombre')->nullable()->after('archivo');
        });
    }

    public function down(): void
    {
        Schema::table('mensaje', function (Blueprint $table) {
            $table->dropColumn(['archivo', 'archivo_nombre']);
        });
    }
};

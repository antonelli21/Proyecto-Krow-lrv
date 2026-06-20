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
        Schema::table('oferta', function (Blueprint $table) {
            // Usamos unsignedInteger porque se acopla al tipo de dato de tu PK
            $table->unsignedInteger('id_carrera')->nullable()->after('id_empresa');
            
            // CORREGIDO: Apunta a tu columna id_carrera y a tu tabla singular carrera
            $table->foreign('id_carrera')->references('id_carrera')->on('carrera')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
        */
    public function down(): void
    {
        Schema::table('oferta', function (Blueprint $table) {
            $table->dropForeign(['id_carrera']);
            $table->dropColumn('id_carrera');
        });
    }
    };

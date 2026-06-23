<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Verificar si la tabla existe
        if (Schema::hasTable('empresa')) {
            // Verificar si la columna ya existe
            if (!Schema::hasColumn('empresa', 'banner')) {
                Schema::table('empresa', function (Blueprint $table) {
                    $table->string('banner')->nullable()->after('logo');
                });
                
                // Mensaje de confirmación
                echo "✅ Columna 'banner' agregada a la tabla 'empresa'\n";
            } else {
                echo "ℹ️ La columna 'banner' ya existe en la tabla 'empresa'\n";
            }
        } else {
            echo "❌ La tabla 'empresa' no existe\n";
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('empresa') && Schema::hasColumn('empresa', 'banner')) {
            Schema::table('empresa', function (Blueprint $table) {
                $table->dropColumn('banner');
            });
        }
    }
};

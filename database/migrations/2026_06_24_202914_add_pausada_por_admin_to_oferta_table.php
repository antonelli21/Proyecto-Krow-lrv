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
            $table->boolean('pausada_por_admin')->default(false)->after('estado');
            $table->text('motivo_pausa_admin')->nullable()->after('pausada_por_admin');
        });
    }

    public function down(): void
    {
        Schema::table('oferta', function (Blueprint $table) {
            $table->dropColumn(['pausada_por_admin', 'motivo_pausa_admin']);
        });
    }
};

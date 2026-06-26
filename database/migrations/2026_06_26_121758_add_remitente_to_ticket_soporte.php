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
    Schema::table('ticket_soporte', function (Blueprint $table) {
        $table->string('nombre_remitente', 100)->nullable()->after('id_usuario');
        $table->string('email_remitente', 150)->nullable()->after('nombre_remitente');
    });
}

public function down(): void
{
    Schema::table('ticket_soporte', function (Blueprint $table) {
        $table->dropColumn(['nombre_remitente', 'email_remitente']);
    });
}
};

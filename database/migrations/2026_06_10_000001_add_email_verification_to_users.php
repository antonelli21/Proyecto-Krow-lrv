<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Código de verificación de 6 dígitos (nullable porque se borra después de verificar)
            $table->string('email_verification_code', 6)->nullable()->after('remember_token');

            // Fecha y hora de expiración del código de verificación
            $table->timestamp('email_verification_expires')->nullable()->after('email_verification_code');
        });
    }


    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email_verification_code', 'email_verification_expires']);
        });
    }
};

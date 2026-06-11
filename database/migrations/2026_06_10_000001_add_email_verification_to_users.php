<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración que agrega los campos necesarios para la verificación
 * de email a la tabla 'users':
 * - email_verification_code: código de 6 dígitos enviado por email
 * - email_verification_expires: fecha/hora en que el código expira
 */
return new class extends Migration
{
    /**
     * Ejecutar la migración.
     * Agrega columnas de verificación de email a la tabla users.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Código de verificación de 6 dígitos (nullable porque se borra después de verificar)
            $table->string('email_verification_code', 6)->nullable()->after('remember_token');

            // Fecha y hora de expiración del código de verificación
            $table->timestamp('email_verification_expires')->nullable()->after('email_verification_code');
        });
    }

    /**
     * Revertir la migración.
     * Elimina las columnas de verificación de la tabla users.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email_verification_code', 'email_verification_expires']);
        });
    }
};

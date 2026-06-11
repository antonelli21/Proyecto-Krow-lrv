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
        Schema::table('estudiante', function (Blueprint $table) {
            $table->string('legajo', 20)->nullable()->change();
        });

        Schema::table('empresa', function (Blueprint $table) {
            $table->string('rubro', 100)->nullable()->change();
            $table->string('representante', 100)->nullable()->change();
            $table->string('email_representante', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estudiante', function (Blueprint $table) {
            $table->string('legajo', 20)->nullable(false)->change();
        });

        Schema::table('empresa', function (Blueprint $table) {
            $table->string('rubro', 100)->nullable(false)->change();
            $table->string('representante', 100)->nullable(false)->change();
            $table->string('email_representante', 100)->nullable(false)->change();
        });
    }
};

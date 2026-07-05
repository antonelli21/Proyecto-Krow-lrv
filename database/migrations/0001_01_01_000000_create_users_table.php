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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('rol', ['admin', 'estudiante', 'empresa']);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->rememberToken();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('provincia', function (Blueprint $table) {
            $table->increments('id_provincia');
            $table->string('nombre', 100)->unique();
        });

        Schema::create('localidad', function (Blueprint $table) {
            $table->increments('id_localidad');
            $table->unsignedInteger('id_provincia');
            $table->string('nombre', 100);
            $table->foreign('id_provincia')->references('id_provincia')->on('provincia');
        });

        Schema::create('carrera', function (Blueprint $table) {
            $table->increments('id_carrera');
            $table->string('nombre', 100)->unique();
        });

        Schema::create('habilidad', function (Blueprint $table) {
            $table->increments('id_habilidad');
            $table->string('nombre', 100)->unique();
        });

        Schema::create('estudiante', function (Blueprint $table) {
            $table->increments('id_estudiante');
            $table->unsignedBigInteger('id_usuario')->unique();
            $table->string('nombre', 50);
            $table->string('apellido', 50);
            $table->unsignedInteger('dni')->unique();
            $table->string('legajo', 20)->unique();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('telefono', 15)->nullable();
            $table->unsignedInteger('id_carrera');
            $table->text('descripcion')->nullable();
            $table->enum('modalidad_deseada', ['Full-Time', 'Part-Time', 'Hibrido', 'Remoto'])->nullable();
            $table->string('disponibilidad_horaria', 100)->nullable();
            $table->string('foto_perfil', 255)->nullable();
            $table->string('cv', 255)->nullable();
            $table->string('portfolio', 255)->nullable();
            $table->string('linkedin', 255)->nullable();
            $table->string('github', 255)->nullable();
            $table->unsignedInteger('id_localidad')->nullable();
            $table->unsignedInteger('id_provincia')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->foreign('id_usuario')->references('id')->on('users');
            $table->foreign('id_carrera')->references('id_carrera')->on('carrera');
            $table->foreign('id_localidad')->references('id_localidad')->on('localidad');
            $table->foreign('id_provincia')->references('id_provincia')->on('provincia');
        });

        Schema::create('empresa', function (Blueprint $table) {
            $table->increments('id_empresa');
            $table->unsignedBigInteger('id_usuario')->unique();
            $table->string('nombre_empresa', 100);
            $table->string('razon_social', 150);
            $table->unsignedBigInteger('cuit')->unique();
            $table->string('rubro', 100);
            $table->string('direccion', 200)->nullable();
            $table->string('telefono', 20);
            $table->string('email_contacto', 100);
            $table->string('sitio_web', 255)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('representante', 100);
            $table->string('email_representante', 100);
            $table->enum('tamano_empresa', ['Microempresa', 'Pequena', 'Mediana', 'Grande'])->nullable();
            $table->string('linkedin', 255)->nullable();
            $table->string('instagram', 255)->nullable();
            $table->string('facebook', 255)->nullable();
            $table->unsignedInteger('id_localidad')->nullable();
            $table->unsignedInteger('id_provincia')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->foreign('id_usuario')->references('id')->on('users');
            $table->foreign('id_localidad')->references('id_localidad')->on('localidad');
            $table->foreign('id_provincia')->references('id_provincia')->on('provincia');
        });

        Schema::create('oferta', function (Blueprint $table) {
            $table->increments('id_oferta');
            $table->unsignedInteger('id_empresa');
            $table->string('titulo', 100);
            $table->text('descripcion');
            $table->text('requisitos')->nullable();
            $table->string('area', 50)->nullable();
            $table->enum('experiencia_requerida', ['Sin Experiencia', 'Junior', 'Semi Senior', 'Senior']);
            $table->enum('tipo_oferta', ['Pasantia', 'Practica Profesional', 'Part-Time', 'Full-Time']);
            $table->enum('modalidad', ['Presencial', 'Remoto', 'Hibrido']);
            $table->unsignedInteger('salario_min')->nullable();
            $table->unsignedInteger('salario_max')->nullable();
            $table->unsignedInteger('id_localidad')->nullable();
            $table->unsignedInteger('id_provincia')->nullable();
            $table->timestamp('fecha_publicacion')->useCurrent();
            $table->date('fecha_cierre')->nullable();
            $table->enum('estado', ['Activa', 'Pausada', 'Cerrada'])->default('Activa');
            $table->foreign('id_empresa')->references('id_empresa')->on('empresa');
            $table->foreign('id_localidad')->references('id_localidad')->on('localidad');
            
            $table->foreign('id_provincia')->references('id_provincia')->on('provincia');
        });

        Schema::create('oferta_carrera', function (Blueprint $table) {
            $table->unsignedInteger('id_oferta');
            $table->unsignedInteger('id_carrera');
            $table->primary(['id_oferta', 'id_carrera']);
            $table->foreign('id_oferta')->references('id_oferta')->on('oferta');
            $table->foreign('id_carrera')->references('id_carrera')->on('carrera');
        });

        Schema::create('estudiante_habilidad', function (Blueprint $table) {
            $table->unsignedInteger('id_estudiante');
            $table->unsignedInteger('id_habilidad');
            $table->primary(['id_estudiante', 'id_habilidad']);
            $table->foreign('id_estudiante')->references('id_estudiante')->on('estudiante');
            $table->foreign('id_habilidad')->references('id_habilidad')->on('habilidad');
        });

        Schema::create('oferta_habilidad', function (Blueprint $table) {
            $table->unsignedInteger('id_oferta');
            $table->unsignedInteger('id_habilidad');
            $table->primary(['id_oferta', 'id_habilidad']);
            $table->foreign('id_oferta')->references('id_oferta')->on('oferta');
            $table->foreign('id_habilidad')->references('id_habilidad')->on('habilidad');
        });

        Schema::create('postulacion', function (Blueprint $table) {
            $table->increments('id_postulacion');
            $table->unsignedInteger('id_estudiante');
            $table->unsignedInteger('id_oferta');
            $table->timestamp('fecha_postulacion')->useCurrent();
            $table->enum('estado', ['Postulado', 'En Revision', 'Preseleccionado', 'En Contacto', 'Rechazado'])->default('Postulado');
            $table->unique(['id_estudiante', 'id_oferta']);
            $table->foreign('id_estudiante')->references('id_estudiante')->on('estudiante');
            $table->foreign('id_oferta')->references('id_oferta')->on('oferta');
        });

        Schema::create('chat', function (Blueprint $table) {
            $table->increments('id_chat');
            $table->unsignedBigInteger('id_usuario_1');
            $table->unsignedBigInteger('id_usuario_2');
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->foreign('id_usuario_1')->references('id')->on('users');
            $table->foreign('id_usuario_2')->references('id')->on('users');
        });

        Schema::create('mensaje', function (Blueprint $table) {
            $table->increments('id_mensaje');
            $table->unsignedInteger('id_chat');
            $table->unsignedBigInteger('id_remitente');
            $table->text('contenido');
            $table->timestamp('fecha_envio')->useCurrent();
            $table->boolean('leido')->default(false);
            $table->foreign('id_chat')->references('id_chat')->on('chat');
            $table->foreign('id_remitente')->references('id')->on('users');
        });

        Schema::create('ticket_soporte', function (Blueprint $table) {
            $table->increments('id_ticket');
            $table->unsignedBigInteger('id_usuario');
            $table->string('asunto', 100);
            $table->text('descripcion');
            $table->enum('estado', ['Abierto', 'En Proceso', 'Resuelto'])->default('Abierto');
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->foreign('id_usuario')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_soporte');
        Schema::dropIfExists('mensaje');
        Schema::dropIfExists('chat');
        Schema::dropIfExists('postulacion');
        Schema::dropIfExists('oferta_habilidad');
        Schema::dropIfExists('estudiante_habilidad');
        Schema::dropIfExists('oferta_carrera');
        Schema::dropIfExists('oferta');
        Schema::dropIfExists('empresa');
        Schema::dropIfExists('estudiante');
        Schema::dropIfExists('habilidad');
        Schema::dropIfExists('carrera');
        Schema::dropIfExists('localidad');
        Schema::dropIfExists('provincia');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};


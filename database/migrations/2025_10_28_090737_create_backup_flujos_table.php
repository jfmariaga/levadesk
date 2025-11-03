<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     */
    public function up(): void
    {
        Schema::create('backup_flujos', function (Blueprint $table) {
            $table->id();

            // Agente principal (el que se va de vacaciones)
            $table->foreignId('agente_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Flujo o subcategoría-grupo donde el agente participa (opcional)
            $table->foreignId('flujo_id')
                ->nullable()
                ->constrained('sociedad_subcategoria_grupo')
                ->onDelete('cascade');

            // Aplicación (opcional)
            $table->foreignId('aplicacion_id')
                ->nullable()
                ->constrained('aplicaciones')
                ->onDelete('cascade');

            // Agente backup (el que recibe los tickets durante las vacaciones)
            $table->foreignId('backup_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_flujos');
    }
};

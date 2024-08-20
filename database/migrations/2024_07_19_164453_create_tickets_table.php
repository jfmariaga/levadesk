<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade'); // Usuario que crea el ticket
            $table->foreignId('asignado_a')->nullable()->constrained('users')->onDelete('cascade'); // Usuario asignado (agente TI)
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade'); // Grupo al que pertenece el agente TI
            $table->foreignId('sociedad_id')->constrained('sociedades')->onDelete('cascade'); // Sociedad
            $table->foreignId('tipo_solicitud_id')->constrained('tipo_solicitudes')->onDelete('cascade'); // Tipo de solicitud
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade'); // Categoría
            $table->foreignId('subcategoria_id')->constrained('subcategorias')->onDelete('cascade'); // Subcategoría
            $table->foreignId('estado_id')->constrained('estados')->onDelete('cascade'); // Estado
            $table->foreignId('ans_id')->nullable()->constrained('a_n_s')->onDelete('cascade'); // ANS
            $table->string('nomenclatura')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}

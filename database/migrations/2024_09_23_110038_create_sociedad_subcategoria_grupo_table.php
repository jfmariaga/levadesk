<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSociedadSubcategoriaGrupoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sociedad_subcategoria_grupo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sociedad_id')->constrained('sociedades')->onDelete('cascade');
            $table->foreignId('subcategoria_id')->constrained('subcategorias')->onDelete('cascade');
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade');
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
        Schema::dropIfExists('sociedad_subcategoria_grupo');
    }
}

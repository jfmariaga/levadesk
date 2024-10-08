<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupervisorSupervisorIdToSociedadSubcategoriaGrupoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sociedad_subcategoria_grupo', function (Blueprint $table) {
            // Agrega la columna supervisor_id sin la clave foránea
            $table->foreignId('supervisor_id')->nullable()->after('grupo_id');
        });

        // Después de corregir los datos, ejecuta una nueva migración para agregar la clave foránea:
        Schema::table('sociedad_subcategoria_grupo', function (Blueprint $table) {
            $table->foreign('supervisor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sociedad_subcategoria_grupo', function (Blueprint $table) {
            //
        });
    }
}

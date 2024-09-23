<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveGrupoIdFromSubcategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subcategorias', function (Blueprint $table) {
                        // Primero eliminamos la clave foránea de 'grupo_id'
                        $table->dropForeign(['grupo_id']);

                        // Luego eliminamos la columna 'grupo_id'
                        $table->dropColumn('grupo_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subcategorias', function (Blueprint $table) {
            $table->unsignedBigInteger('grupo_id')->nullable();

            // Restaurar la clave foránea de 'grupo_id'
            $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('set null');
        });
    }
}

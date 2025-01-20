<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupervisorId2ToSociedadSubcategoriaGrupoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sociedad_subcategoria_grupo', function (Blueprint $table) {
            $table->unsignedBigInteger('supervisor_id_2')->nullable()->after('supervisor_id');
            $table->foreign('supervisor_id_2')->references('id')->on('users');
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

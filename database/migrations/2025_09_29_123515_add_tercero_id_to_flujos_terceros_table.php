<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTerceroIdToFlujosTercerosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flujos_terceros', function (Blueprint $table) {
            $table->unsignedBigInteger('tercero_id')->nullable()->after('aplicacion_id');
            $table->foreign('tercero_id')->references('id')->on('terceros')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('flujos_terceros', function (Blueprint $table) {
            $table->dropForeign(['tercero_id']);
            $table->dropColumn('tercero_id');
        });
    }
}

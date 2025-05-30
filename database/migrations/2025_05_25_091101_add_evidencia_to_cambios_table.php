<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEvidenciaToCambiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cambios', function (Blueprint $table) {
            $table->boolean('evidencia')->nullable()->after('check_aprobado_ti');
            $table->boolean('doc_tecnico')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cambios', function (Blueprint $table) {
            //
        });
    }
}

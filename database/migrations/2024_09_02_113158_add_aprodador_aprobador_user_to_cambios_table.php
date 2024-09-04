<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAprodadorAprobadorUserToCambiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cambios', function (Blueprint $table) {
            $table->unsignedBigInteger('aprobador_user_id')->nullable()->after('aprobador_ti_id');
            $table->unsignedBigInteger('aprobador_final_ti_id')->nullable()->after('aprobador_user_id');

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
            $table->dropColumn('aprobador_user_id');
            $table->dropColumn('aprobador_final_ti_id');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateANSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('a_n_s', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('solicitud_id')->unsigned();
            $table->string('nivel');
            $table->string('h_atencion');
            $table->integer('t_asignacion_segundos');
            $table->integer('t_resolucion_segundos');
            $table->integer('t_aceptacion_segundos');
            $table->timestamps();

            $table->foreign('solicitud_id')->references('id')->on('tipo_solicitudes')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->unique(['solicitud_id', 'nivel']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('a_n_s');
    }
}

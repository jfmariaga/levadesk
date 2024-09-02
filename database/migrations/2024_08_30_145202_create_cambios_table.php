<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCambiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cambios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id')->unique();
            $table->unsignedBigInteger('aprobador_funcional_id');
            $table->unsignedBigInteger('aprobador_ti_id')->nullable();
            $table->enum('estado', ['pendiente', 'aprobado_funcional', 'rechazado_funcional', 'aprobado_ti', 'rechazado_ti', 'cerrado']);
            $table->text('comentarios_funcional')->nullable();
            $table->text('comentarios_ti')->nullable();
            $table->timestamps();

            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('aprobador_funcional_id')->references('id')->on('users');
            $table->foreign('aprobador_ti_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cambios');
    }
}

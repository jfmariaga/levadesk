<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlujosTercerosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flujos_terceros', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aplicacion_id'); // relación con tabla aplicaciones
            $table->unsignedBigInteger('usuario_id')->nullable(); // usuario al que se asigna (ej. "sistema")
            $table->json('destinatarios')->nullable(); // correos a los que se notifica
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('aplicacion_id')->references('id')->on('aplicaciones')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flujos_terceros');
    }
}

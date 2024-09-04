<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAplicacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aplicaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sociedad_id')->constrained('sociedades')->onDelete('cascade');
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade');
            $table->string('nombre');
            $table->tinyInteger('estado');
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
        Schema::dropIfExists('aplicaciones');
    }
}

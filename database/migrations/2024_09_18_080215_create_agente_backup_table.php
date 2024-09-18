<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgenteBackupTable extends Migration
{
    public function up()
    {
        Schema::create('agente_backup', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agente_id');  // El agente principal
            $table->unsignedBigInteger('backup_id');  // El agente de respaldo
            $table->foreign('agente_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('backup_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('agente_backup');
    }
}

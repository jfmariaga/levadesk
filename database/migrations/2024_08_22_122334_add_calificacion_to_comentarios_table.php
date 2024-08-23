<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCalificacionToComentariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comentarios', function (Blueprint $table) {
            $table->integer('calificacion')->nullable()->after('comentario'); // Calificación entre 1 y 5
            $table->text('comentario_calificacion')->nullable()->after('calificacion'); // Comentario adicional
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comentarios', function (Blueprint $table) {
            $table->dropColumn('calificacion');
            $table->dropColumn('comentario_calificacion');
        });
    }
}

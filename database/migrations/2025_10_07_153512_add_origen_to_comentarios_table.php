<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrigenToComentariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('comentarios', function (Blueprint $table) {
            if (!Schema::hasColumn('comentarios', 'origen')) {
                $table->string('origen')->nullable()->after('tipo')
                    ->comment('Origen del comentario (por ejemplo, nombre del tercero que respondió)');
            }
        });
    }

    public function down(): void
    {
        Schema::table('comentarios', function (Blueprint $table) {
            if (Schema::hasColumn('comentarios', 'origen')) {
                $table->dropColumn('origen');
            }
        });
    }
}

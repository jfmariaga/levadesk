<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTerceroIdToTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'tercero_id')) {
                $table->foreignId('tercero_id')
                    ->nullable()
                    ->constrained('terceros')
                    ->nullOnDelete()
                    ->after('asignado_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'tercero_id')) {
                $table->dropForeign(['tercero_id']);
                $table->dropColumn('tercero_id');
            }
        });
    }
}

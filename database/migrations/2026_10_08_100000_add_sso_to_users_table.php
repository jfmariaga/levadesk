<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSsoToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('azure_oid', 64)->nullable()->unique()->after('email');
            $table->string('sso_tenant', 20)->nullable()->after('azure_oid');
            $table->timestamp('last_login_at')->nullable()->after('sso_tenant');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['azure_oid']);
            $table->dropColumn(['azure_oid', 'sso_tenant', 'last_login_at']);
        });
    }
}

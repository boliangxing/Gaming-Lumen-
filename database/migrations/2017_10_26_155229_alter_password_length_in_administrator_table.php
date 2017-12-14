<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPasswordLengthInAdministratorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('system')->table('administrator', function (Blueprint $table) {
            $table->dropColumn(['admin_password']);
        });

        Schema::connection('system')->table('administrator', function (Blueprint $table) {
            $table->char('password', 60)->after('admin_name')->comment('密码');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('administrator', function (Blueprint $table) {
            //
        });
    }
}

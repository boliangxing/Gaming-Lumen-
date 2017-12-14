<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUserLoginHistoriesFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_login_histories', function (Blueprint $table) {
            $table->string('ua', 200)->comment('客户端ua')->change();

            $table->unsignedInteger('city_id')->default(0)->comment('城市ID')
                ->after('ua');

            $table->dropColumn(['updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}

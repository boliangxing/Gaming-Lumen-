<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLoginHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_login_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid')->comment('用户ID');
            $table->tinyInteger('status')->default(1)->comment('-1  - 失败， 1 - 成功');
            $table->ipAddress('ip')->comment('客户端ip');
            $table->unsignedTinyInteger('client_type')->default(1)->comment('1 - web, 2 - 手机端, 3 - 微信');
            $table->string('ua', 100)->comment('客户端ua');
            $table->string('address', 100)->comment('登陆地');
            $table->timestamps();

            $table->index(['uid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_login_histories');
    }
}

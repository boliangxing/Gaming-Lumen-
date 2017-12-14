<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid')->comment('用户ID');
            $table->uuid('client_id')->comment('客户端唯一ID');
            $table->unsignedTinyInteger('source')->default(1)->comment('登录来源: 1 - 浏览器');
            $table->ipAddress('ip')->comment('生成时的IP地址');
            $table->unsignedTinyInteger('remember_me')->default(0)->comment('是否记住密码： 0 - 否， 1 - 是');
            $table->unsignedTinyInteger('trusted_client')->default(1)->comment('是否是可信任客户端');
            $table->unsignedTinyInteger('cleared')->default(0)->comment('是否已退出登录');
            $table->string('ua', 200)->comment('user agent');
            $table->dateTime('expired_at')->comment('token 过期时间');
            $table->timestamps();

            $table->index(['uid', 'client_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_clients');
    }
}

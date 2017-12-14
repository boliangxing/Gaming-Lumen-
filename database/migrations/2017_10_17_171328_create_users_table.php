<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nickname', 15)->comment('昵称');
            $table->string('avatar', 100)->default('')->comment('头像');
            $table->unsignedTinyInteger('gender')->default(0)->comment('性别： 0 - 未知， 1 - 男， 2 - 女');
            $table->tinyInteger('status')->default(1)->comment('账号状态: -1 - 删除， -2 - 禁止登录， 1 - 正常， 2 - 禁止评论');
            $table->unsignedTinyInteger('level')->default(1)->comment('等级');
            $table->unsignedInteger('credits')->default(0)->comment('积分');
            $table->unsignedInteger('consumed_credits')->default(0)->comment('已消费积分');
            $table->unsignedInteger('cai')->default(0)->comment('菜币');
            $table->unsignedInteger('consumed_cai')->default(0)->comment('已消费菜币');
            $table->string('bio', 100)->default('')->comment('个性签名');
            $table->dateTime('registered_at');

            $table->index('nickname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

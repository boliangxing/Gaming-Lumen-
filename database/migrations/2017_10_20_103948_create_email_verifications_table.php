<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_verifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 60)->comment('邮箱');
            $table->char('code', 6)->comment('验证码');
            $table->unsignedTinyInteger('usage')->comment('用途： 1 - 注册， 2 - 绑定');
            $table->unsignedTinyInteger('status')->comment('状态： 1 - 未验证， 2 - 已验证');
            $table->dateTime('expired_at')->comment('过期时间');
            $table->timestamps();

            $table->index(['email', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_verifications');
    }
}

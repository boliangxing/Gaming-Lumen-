<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_credentials', function (Blueprint $table) {
            $table->integer('id')->unsigned()->comment('用户ID');
            $table->string('email', 50)->default('')->comment('邮箱');
            $table->string('country_code', 4)->default('86')->comment('国家码');
            $table->string('mobile', 11)->default('')->comment('手机号');
            $table->char('password', 60)->comment('密码');
            $table->integer('error_tries')->unsigned()->default(0)->comment('错误次数');
            $table->dateTime('tired_at')->comment('上次错误登录时间');

            $table->index('email');
            $table->index(['country_code', 'mobile']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_credentials');
    }
}

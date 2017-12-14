<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSocialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_socials', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->unsigned()->comment('用户ID');
            $table->string('open_id', 60)->comment('第三方ID');
            $table->tinyInteger('social_type')->unsigned()->comment('1 - QQ， 2 - steam， 3 - 微博， 4 - 微信');
            $table->string('access_token', 100);
            $table->string('refresh_token', 100)->default('');
            $table->dateTime('expired_at');
            $table->timestamps();

            $table->index('uid');
            $table->index(['social_type', 'open_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_socials');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAchievementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('user')->create('user_achievement', function (Blueprint $table) {
            //$table->increments('id');
            $table->integer('uid')->unsigned()->comment('用户ID');
            $table->tinyInteger('type')->comment('成就类型');
            $table->tinyInteger('achievement_id')->comment('成就ID');
            $table->unsignedInteger('now')->default(0)->comment('用户成就进度');
            //$table->tinyInteger('level')->default(1)->comment('用户成就等级');
            $table->boolean('is_complete')->default(false)->comment('成就是否完成');
            $table->boolean('is_taken')->default(false)->comment('任务奖励是否已经领取');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('user')->dropIfExists('user_achievement');
    }
}

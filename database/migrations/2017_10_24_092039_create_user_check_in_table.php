<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCheckInTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $thisMonth = date('Ym');
        Schema::connection('user')->create(sprintf('user_check_in_%s', $thisMonth), function (Blueprint $table) {
            $table->integer('uid')->unsigned()->comment('用户ID');
            //$table->smallInteger('all_count')->unsigned()->comment('总签到')->default(0);
            $table->smallInteger('this_month_count')->unsigned()->comment('本月签到')->default(0);
            $table->smallInteger('continuous_count')->unsigned()->comment('连续签到')->default(0);
            $table->integer('rank')->unsigned()->comment('今日第几签到');
            $table->tinyInteger('level')->unsigned()->comment('签到奖励的等级')->default(1);
            $table->dateTime('last_check_in')->comment('上次签到时间');
            $table->string('reward_state')->comment('奖励领取情况');
            //$table->string('this_month')->comment('记录的是哪个月');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $thisMonth = date('Ym');
        Schema::connection('user')->dropIfExists(sprintf('user_check_in_%s', $thisMonth));
    }
}

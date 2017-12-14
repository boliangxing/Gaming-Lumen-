<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGuessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('user')->create('user_guess', function (Blueprint $table) {
            $table->unsignedInteger('uid')->comment('用户ID');
            $table->unsignedMediumInteger('guess_times')->default(0)->comment('预测总次数');
            $table->unsignedMediumInteger('guess_win_times')->default(0)->comment('预测赢的次数');
            $table->unsignedInteger('cai_cost_all')->default(0)->comment('菜币预测总值');
            $table->integer('cai_income_day')->default(0)->comment('菜币日收益');
            $table->integer('cai_income_week')->default(0)->comment('菜币周收益');
            $table->integer('cai_income_month')->default(0)->comment('菜币月收益');
            $table->integer('cai_income_all')->default(0)->comment('菜币总收益');
            $table->unsignedDecimal('card_cost_all', 10, 2)->default(0)->comment('皮肤卡预测总值');
            $table->decimal('card_income_day', 10, 2)->default(0)->comment('皮肤卡日收益');
            $table->decimal('card_income_week', 10, 2)->default(0)->comment('皮肤卡周收益');
            $table->decimal('card_income_month', 10, 2)->default(0)->comment('皮肤卡月收益');
            $table->decimal('card_income_all', 10, 2)->default(0)->comment('皮肤卡总收益');
            $table->dateTime('last_update')->comment('最后一次更新时间');

            $table->unique('uid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('user')->dropIfExists('user_guess');
    }
}

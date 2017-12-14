<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuessCardLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('guess')->create('guess_card_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid')->comment('用户ID');
            $table->unsignedInteger('guess_id')->comment('竞猜ID');
            $table->unsignedInteger('option')->comment('选项');
            $table->unsignedInteger('num')->comment('皮肤卡数');
            $table->text('cards')->comment('皮肤卡列表');
            $table->text('rewards')->comment('奖励的皮肤卡列表');
            $table->unsignedInteger('rewards_c')->comment('奖励等值菜币');
            $table->unsignedTinyInteger('status')->comment('状态');
            $table->dateTime('created_at')->comment('竞猜时间');


            $table->index(['uid']);
            $table->index(['guess_id']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('guess')->dropIfExists('guess_card_logs');
    }
}

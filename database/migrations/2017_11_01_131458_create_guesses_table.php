<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('guess')->create('guesses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('game_id')->comment('游戏ID');
            $table->unsignedInteger('match_id')->comment('赛事ID');
            $table->unsignedInteger('battle_id')->comment('对战ID');
            $table->unsignedTinyInteger('type')->comment('竞猜类型: 1 - 输赢, 2 - 10杀, 3 - 胜者，4 - 一血, 5 - 大小分');
            $table->unsignedTinyInteger('round')->comment('第几局');
            $table->unsignedInteger('team_x')->comment('让分队');
            $table->string('score', 4)->comment('让分数');
            $table->unsignedTinyInteger('status')->comment('状态');
            $table->unsignedInteger('option1')->comment('选项一');
            $table->unsignedInteger('option2')->comment('选项二');
            $table->unsignedInteger('result')->default(0)->comment('赢的选项');
            $table->unsignedTinyInteger('option1_approved')->default(0)->comment('选项一支持率');
            $table->unsignedTinyInteger('option2_approved')->default(0)->comment('选项二支持率');
            $table->string('option1_odds', 10)->default('0.95')->comment('选项一的赔率');
            $table->string('option2_odds', 10)->default('0.95')->comment('选项二的赔率');
            $table->unsignedTinyInteger('cancel_option')->default(0)->comment('取消原因');
            $table->dateTime('started_at')->comment('开始时间');
            $table->dateTime('ended_at')->comment('结束时间');
            $table->dateTime('cached_at')->comment('缓存时间');
            $table->unsignedInteger('operator')->comment('操作人员');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('修改时间');

            $table->index(['game_id']);
            $table->index(['battle_id']);
            $table->index(['started_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('guess')->dropIfExists('guesses');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuessCaiLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('guess')->create('guess_cai_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid')->comment('用户ID');
            $table->unsignedInteger('guess_id')->comment('竞猜ID');
            $table->unsignedInteger('option')->comment('选项');
            $table->unsignedInteger('num')->comment('菜币数');
            $table->integer('rewards')->default(0)->comment('奖励数');
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
        Schema::connection('guess')->dropIfExists('guess_cai_logs');
    }
}

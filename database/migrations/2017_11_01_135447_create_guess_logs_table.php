<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuessLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('guess')->create('guess_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid')->comment('用户ID');
            $table->unsignedInteger('guess_id')->comment('竞猜ID');
            $table->unsignedTinyInteger('type')->comment('竞猜类型： 1 - 菜币， 2 - 皮肤卡');
            $table->unsignedTinyInteger('behavior')->comment('操作类型 1 - 预测， 2 - 取消预测');
            $table->string('desc', 100)->default('')->comment('行为描述');
            $table->dateTime('created_at')->comment('操作时间');

            $table->index(['uid']);
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
        Schema::connection('guess')->dropIfExists('guess_logs');
    }
}

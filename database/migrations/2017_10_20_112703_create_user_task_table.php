<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('user')->create('user_task', function (Blueprint $table) {
            //$table->increments('id');
            $table->integer('uid')->unsigned()->comment('用户ID');
            $table->tinyInteger('task_id')->comment('任务ID');
            $table->integer('now')->unsigned()->default(0)->comment('用户任务进度');
            $table->boolean('is_complete')->default(false)->comment('任务是否完成');
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
        Schema::connection('user')->dropIfExists('user_task');
    }
}

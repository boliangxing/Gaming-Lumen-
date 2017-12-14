<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('message_type')->comment('消息类型');
            $table->unsignedTinyInteger('from_type')->comment('消息来源： 1 - 系统通知， 2 - 用户， 3 - 订阅');
            $table->text('content')->comment('消息体');
            $table->tinyInteger('status')->comment('消息状态: 1 - 正常, -1 删除');
            $table->dateTime('created_at')->comment('发送时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}

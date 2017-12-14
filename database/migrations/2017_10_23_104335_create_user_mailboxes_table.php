<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMailboxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_mailboxes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('uid')->comment('收件人');
            $table->unsignedInteger('from_uid')->comment('发件人');
            $table->unsignedTinyInteger('from_type')->comment('消息来源： 1 - 系统通知， 2 - 用户， 3 - 订阅');
            $table->tinyInteger('status')->comment('状态: -1 - 已删除， 0 - 未读， 1 - 已读');
            $table->unsignedBigInteger('message_id')->comment('消息ID');
            $table->dateTime('updated_at')->comment('操作时间');

            $table->index(['uid', 'from_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_mailboxes');
    }
}

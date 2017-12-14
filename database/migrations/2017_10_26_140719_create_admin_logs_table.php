<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('system')->create('admin_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid')->comment('管理员ID');
            $table->string('uri')->comment('请求地址');
            $table->text('params')->comment('请求参数');
            $table->ipAddress('ip')->comment('ip地址');
            $table->string('ua', 200)->comment('客户端ua');
            $table->dateTime('created_at')->comment('操作时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('system')->dropIfExists('admin_logs');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('system')->create('system', function (Blueprint $table) {
            $table->string('keyname',50)->comment('配置项键名');
            $table->text('data')->comment('配置项键值');
            $table->unique('keyname');
        });

        Schema::connection('system')->create('administrator', function (Blueprint $table) {
            $table->increments('id')->comment('自增id');
            $table->string('admin_name',50)->comment('登录名');
            $table->string('admin_password',50)->comment('登录密码');
            $table->string('email',50)->comment('邮箱');
            $table->integer('role_id')->comment('角色id');
            $table->string('nickname',50)->comment('显示昵称');
            $table->string('realname',50)->comment('真实姓名');
            $table->string('phone',50)->comment('电话');
            $table->tinyInteger('status')->comment('状态1：正常0：禁用');
            $table->dateTime('last_login_time')->comment('最后登录时间');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('更新时间');
            $table->unique('admin_name');
        });

        Schema::connection('system')->create('role', function (Blueprint $table) {
            $table->increments('id')->comment('自增id');
            $table->string('role_name',50)->comment('角色名');
            $table->text('has_permission')->comment('拥有的权限');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('system')->dropIfExists('system');
        Schema::connection('system')->dropIfExists('administrator');
        Schema::connection('system')->dropIfExists('role');
    }
}

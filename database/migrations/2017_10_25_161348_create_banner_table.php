<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('system')->create('banner', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 30)->comment('banner标题');
            $table->string('location', 20)->comment('banner标识（所在位置）');
            $table->dateTime('begin_time')->comment('开始时间');
            $table->dateTime('expire_time')->comment('过期时间');
            $table->string('img_uri')->comment('banner图uri');
            $table->string('url')->comment('跳转地址');
            $table->tinyInteger('index')->comment('如果是轮播,顺序');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('system')->dropIfExists('banner');
    }
}

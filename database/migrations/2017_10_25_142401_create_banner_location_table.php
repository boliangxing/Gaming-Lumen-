<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('system')->create('banner_location', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 20)->comment('banner位名称');
            $table->string('location', 20)->comment('banner位标识（所在位置）')->unique();
            $table->tinyInteger('type')->comment('类型 轮播图 等');
            //$table->dateTime('update_time')->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('system')->dropIfExists('banner_location');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopDebrisLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('shop')->create('shop_debris_logs', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('uid')->comment('用户ID');
            $table->string('product_id', 50)->comment('商品id');
            $table->unsignedInteger('debris_count')->default(0)->comment('碎片数量');
            $table->dateTime('created_at');
            $table->index('uid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_debris_logs');
    }
}

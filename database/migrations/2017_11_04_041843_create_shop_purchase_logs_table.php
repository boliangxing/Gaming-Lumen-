<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopPurchaseLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('shop')->create('shop_purchase_logs', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('uid')->comment('用户ID');
            $table->string('product_id', 50)->comment('商品id');
            $table->unsignedInteger('product_count')->default(0)->comment('商品数量');
            $table->unsignedInteger('product_cprice')->default(0)->comment('订单金额');
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
        Schema::dropIfExists('shop_purchase_logs');
    }
}

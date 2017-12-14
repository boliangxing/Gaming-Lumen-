<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('shop')->create('shop_product', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('product_id')->comment('商品ID');
            $table->string('product_name', 50)->comment('商品名称');
            $table->unsignedInteger('cprice')->default(0)->comment('菜币');
            $table->unsignedInteger('category')->default(1)->comment('商品种类:1 皮肤卡虚拟道具 2实物类');
            $table->string('product_pic', 100)->default('')->comment('商品图像');
            $table->dateTime('updated_at');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_product');
    }
}

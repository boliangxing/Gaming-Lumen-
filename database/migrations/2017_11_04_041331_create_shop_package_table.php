<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::connection('shop')->create('shop_package', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('uid')->comment('用户ID');
            $table->string('product_id', 50)->comment('商品ID');
            $table->unsignedInteger('product_count')->default(0)->comment('商品数量');
            $table->dateTime('updated_at');
            $table->index('product_id');
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
        Schema::dropIfExists('shop_package');
    }
}

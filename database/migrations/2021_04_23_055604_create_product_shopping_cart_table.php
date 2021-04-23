<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductShoppingCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_shopping_cart', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("shopping_cart_id")->unsigned();
            $table->integer("product_id")->unsigned();
            $table->integer("quantity");
            $table->timestamps();

            $table->foreign("shopping_cart_id")->references("id")->on("shopping_carts")->onUpdate('cascade')->onDelete('cascade');
            $table->index("shopping_cart_id", "product_shopping_cart_shopping_cart_id_foreign");

            $table->foreign("product_id")->references("id")->on("products")->onUpdate('cascade')->onDelete('cascade');
            $table->index("product_id", "product_shopping_cart_product_id_foreign");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_shopping_cart');
    }
}

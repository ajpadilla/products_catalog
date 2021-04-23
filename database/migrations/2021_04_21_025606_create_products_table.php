<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments("id");
            $table->string("sku")->unique();
            $table->string("image_url");
            $table->string("name");
            $table->string("description");
            $table->integer("stock");
            $table->decimal("price", 8, 2);
            $table->integer("category_id")->unsigned();
            $table->timestamps();

            $table->foreign("category_id")->references("id")->on("categories")->onUpdate('cascade')->onDelete('cascade');
            $table->index("category_id", "products_category_id_foreign");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}

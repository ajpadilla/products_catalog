<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_carts', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("user_id")->unsigned();
            $table->double('total_pay', 8, 2)->nullable()->default(0);
            $table->integer('total_items')->nullable()->default(0);
            $table->string('references')->nullable()->default('');
            $table->boolean("pay")->default(false);
            $table->timestamps();

            $table->foreign("user_id")->references("id")->on("users")->onUpdate('cascade')->onDelete('cascade');
            $table->index("user_id", "shopping_carts_user_id_foreign");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shopping_carts');
    }
}

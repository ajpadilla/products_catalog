<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name")->unique();
            $table->enum('status', ['active', 'inactive'])->default("active");
            $table->integer("parent_id")->unsigned()->nullable();
            $table->timestamps();

            $table->foreign("parent_id")->references("id")->on("categories")->onUpdate('cascade')->onDelete('cascade');
            $table->index("parent_id", "categories_parent_id_foreign");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}

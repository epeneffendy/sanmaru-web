<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->index('products_slug_index');
            $table->unsignedBigInteger('weight');
            $table->string('merk')->nullable();
            $table->unsignedBigInteger('stock');
            $table->decimal('price', 16, 2);
            $table->enum('status',['unpublished', 'published']);
            $table->bigInteger('type_id');
            $table->string('type_name');
            $table->bigInteger('category_id');
            $table->string('category_name');
            $table->string('image_path')->nullable();
            $table->softdeletes();
            $table->timestamps();
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

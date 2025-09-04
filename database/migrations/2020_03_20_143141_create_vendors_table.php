<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('vendors')) {
            return;
        }

        Schema::create('vendors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index('vendors_name_index');
            $table->text('address');
            $table->string('city');
            $table->string('phone');
            $table->string('pic');
            $table->string('nota_number');
            $table->date('nota_date');
            $table->softDeletes();
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
        Schema::dropIfExists('vendors');
    }
}

<?php

use App\Enums\ProductTypeEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeProductTypesAndCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_types', function (Blueprint $table) {
            $table->enum('type', [
                ProductTypeEnum::SERAGAM,
                ProductTypeEnum::KANTIN,
            ])->default(ProductTypeEnum::SERAGAM)->after('slug');
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->enum('type', [
                ProductTypeEnum::SERAGAM,
                ProductTypeEnum::KANTIN,
            ])->default(ProductTypeEnum::SERAGAM)->after('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_types', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}

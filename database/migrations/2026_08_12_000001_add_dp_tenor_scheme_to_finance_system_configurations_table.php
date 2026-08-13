<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDpTenorSchemeToFinanceSystemConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('finance_system_configurations', function (Blueprint $table) {
            $table->text('dp_tenor_scheme')->nullable()->after('max_absolute_installment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('finance_system_configurations', function (Blueprint $table) {
            $table->dropColumn('dp_tenor_scheme');
        });
    }
}

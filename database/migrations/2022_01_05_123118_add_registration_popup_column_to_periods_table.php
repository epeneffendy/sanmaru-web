<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegistrationPopupColumnToPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('periods', function (Blueprint $table) {
            $table->boolean('show_registration_popup')->default(false);
            $table->text('popup_content')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('periods', function (Blueprint $table) {
            $table->dropColumn('show_registration_popup');
            $table->dropColumn('popup_content');
        });
    }
}

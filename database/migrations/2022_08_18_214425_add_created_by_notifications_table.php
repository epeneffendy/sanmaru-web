<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreatedByNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->boolean('send_email')->default(false)->comment('Flagging if notification is need send to email user');
            $table->dateTime('sended_email')->nullable()->comment('Date notification sended to email');
            $table->unsignedBigInteger('created_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['send_email', 'sended_email', 'created_by']);
        });
    }
}

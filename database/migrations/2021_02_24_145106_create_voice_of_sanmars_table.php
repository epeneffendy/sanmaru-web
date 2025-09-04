<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoiceOfSanmarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voice_of_sanmars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->index('voice_of_sanmars_title_index');
            $table->string('content_url')->index('voice_of_sanmars_content_url_index');
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
        Schema::dropIfExists('voice_of_sanmars');
    }
}

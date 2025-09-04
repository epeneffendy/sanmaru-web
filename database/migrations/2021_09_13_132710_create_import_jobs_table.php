<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->string('params')->nullable();
            $table->string('path')->nullable();
            $table->enum('status', ['not_started', 'processing', 'finished','failed'])->default('not_started');
            $table->boolean('show')->default(true);
            $table->integer('total_success')->default(0);
            $table->integer('total_errors')->default(0);
            $table->longText('success')->nullable();
            $table->longText('errors')->nullable();
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
        Schema::dropIfExists('import_jobs');
    }
}

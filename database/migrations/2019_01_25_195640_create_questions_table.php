<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('text', 1200);
            $table->text('code', 2000)->nullable();
            $table->string('illustration')->nullable();
            $table->unsignedInteger('quiz_id')->index();
            $table->boolean('is_multiple')->default(false);
            $table->unsignedInteger('positive_score')->default(4);
            $table->unsignedInteger('negative_score')->default(1);
            $table->string('answer_keys')->nullable();
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
        Schema::dropIfExists('questions');
    }
}

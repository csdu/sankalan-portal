<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswerChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_choices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->index();
            $table->string('text');
            $table->string('illustration')->nullable();
            $table->unsignedInteger('question_id')->index();
            $table->unique(['key', 'question_id']);
            $table->timestamps();

            $table->foreign('question_id')->references('id')->on('questions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('answer_choices', function(Blueprint $table) {
            $table->dropForeign(['question_id']);
        });

        Schema::dropIfExists('answer_choices');
    }
}

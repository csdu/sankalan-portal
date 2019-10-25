<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_responses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('question_id')->index();
            $table->string('response_keys')->nullable();
            $table->unsignedInteger('quiz_response_id')->index();
            $table->timestamps();

            $table->foreign('question_id')->references('id')->on('questions');
            $table->foreign('quiz_response_id')->references('id')->on('quiz_responses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('question_responses', function (Blueprint $table) {
            $table->dropForeign(['question_id']);
            $table->dropForeign(['quiz_participation_id']);
        });

        Schema::dropIfExists('question_responses');
    }
}

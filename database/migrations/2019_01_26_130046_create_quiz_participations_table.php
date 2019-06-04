<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizParticipationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_participations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('quiz_id')->index();
            $table->unsignedInteger('team_id')->index();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->integer('score')->index()->nullable();
            $table->timestamps();

            $table->foreign('quiz_id')->references('id')->on('quizzes');
            $table->foreign('team_id')->references('id')->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quiz_participations', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropForeign(['quiz_id']);
        });

        Schema::dropIfExists('quiz_participations');
    }
}

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
        Schema::dropIfExists('quiz_participations');
    }
}

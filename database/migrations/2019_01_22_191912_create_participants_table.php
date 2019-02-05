<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_participations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('event_id')->index();
            $table->unsignedInteger('team_id')->index();
            $table->unique(['event_id', 'team_id']);
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events');
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
        Schema::table('event_participations', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropForeign(['team_id']);
        });
        Schema::dropIfExists('event_participations');
    }
}

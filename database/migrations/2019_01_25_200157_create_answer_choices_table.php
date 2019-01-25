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
            $table->boolean('is_illustration')->default(false);
            $table->boolean('is_code')->default(false);
            $table->string('illustration')->nullable();
            $table->unsignedInteger('question_id')->index();
            $table->unique(['key', 'question_id']);
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
        Schema::dropIfExists('answer_choices');
    }
}

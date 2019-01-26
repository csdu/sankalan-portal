<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizParticipation extends Model
{
    protected $guarded = [];

    protected $dates = ['started_at'];
}

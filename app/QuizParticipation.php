<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizParticipation extends Model
{
    protected $guarded = [];

    protected $dates = ['started_at', 'finished_at'];

    public function responses()
    {
        return $this->hasMany(QuizResponse::class);
    }
}

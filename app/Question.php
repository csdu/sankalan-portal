<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = [];

    protected $hidden = ['answer_keys'];

    public function choices()
    {
        return $this->hasMany(AnswerChoice::class);
    }
}

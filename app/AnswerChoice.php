<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnswerChoice extends Model
{
    protected $guarded = [];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function getIllustrationAttribute($illustration)
    {
        if (!$illustration) {
            return $illustration;
        }

        return asset("/images$illustration");
    }
}

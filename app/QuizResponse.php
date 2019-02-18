<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Question;

class QuizResponse extends Model
{
    protected $guarded = [];

    public function question() {
        return $this->belongsTo(Question::class);
    }
    
    public function getScoreAttribute() {

        if($this->question->correct_answer_keys->contains($this->response_keys)) {
            return $this->question->positive_score;
        }
        
        return -$this->question->negative_score;
    }

    public function getResponseKeysAttribute($value)
    {
        return strtolower(trim(
            preg_replace(
                '~[^a-zA-Z0-9]+~' , 
                '', 
                preg_replace('~( |_|\-)+~', ' ', $value)
            )
        ));
    }

    public function isChosen(AnswerChoice $choice) {
        return $this->response_keys == $choice->key;
    }
}

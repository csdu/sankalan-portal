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

        $diff = $this->question->correct_answer_keys->diff($this->response_keys);
        
        if($diff->count() === 0) {
            return $this->question->positive_score;
        }
        
        return -$this->question->negative_score;
    }

    public function getResponseKeysAttribute($response_keys) {
        return collect(explode(':', $response_keys));
    }
}

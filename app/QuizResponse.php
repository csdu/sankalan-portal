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

        $diff = $this->question->answer_keys->diff($this->response_key);
        
        if($diff->count() === 0) {
            return $this->question->positive_score;
        }
        
        return -$this->question->negative_score;
    }

    public function getResponseKeyAttribute($response_key) {
        return collect(explode(':', $response_key));
    }
}

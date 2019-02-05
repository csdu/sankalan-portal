<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = [];

    protected $hidden = ['answer_keys'];

    protected $casts = [
        'positive_score' => 'integer',
        'negative_score' => 'integer'
    ];

    public function choices()
    {
        return $this->hasMany(AnswerChoice::class);
    }

    public function setAnswerKeysAttribute($value) {
        if(!$value) {
            $this->attributes['answer_keys'] = '';
        } else if(is_string($value)) {
            $this->attributes['answer_keys']  = $value;
        } else {
            $this->attributes['answer_keys'] = implode(':', $value);
        }
    }

    public function getAnswerKeysAttribute($keys)
    {
        return collect(explode(':', $keys));
    } 
}

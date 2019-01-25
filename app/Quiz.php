<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $guarded = [];

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }
}

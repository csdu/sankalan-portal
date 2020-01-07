<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionAttachment extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($team) {
            $team->update(['uid' => env("ID_PREFIX", "SNKLN") ."-T". str_pad("$team->id", 3, "0", STR_PAD_LEFT)]);
        });
    }

    public function members() 
    {
        return $this->belongsToMany(User::class);
    }
}

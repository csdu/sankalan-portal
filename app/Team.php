<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Event;

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

    public function events()
    {
        return $this->belongsToMany(Event::class, 'participants');
    }

    public function participate($event) {
        $this->events()->attach($event->id);
        return $this;
    }
}

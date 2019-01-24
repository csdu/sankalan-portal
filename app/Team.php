<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Event;

class Team extends Model
{
    protected $guarded = [];

    public function getUidAttribute() {
        return env("ID_PREFIX", "SNKLN") . "-T" . str_pad("$this->id", 3, "0", STR_PAD_LEFT);
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
        if($event->isAnyParticipating($this->members)) {
            return false;
        }
        $this->events()->syncWithoutDetaching($event->id);
        return true;
    }

    public function withdrawParticipation($event)
    {
        $this->events()->detach($event->id);
        return true;
    }
}

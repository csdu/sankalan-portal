<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Event;

class Team extends Model
{
    protected $guarded = [];

    protected $appends = ['uid'];

    public function getUidAttribute() {
        return env("ID_PREFIX", "SNKLN") . "-T" . str_pad("$this->id", 3, "0", STR_PAD_LEFT);
    }

    public function members() 
    {
        return $this->belongsToMany(User::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_participations');
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

    public function endQuiz(Quiz $quiz, $responses)
    {
        $responses = $quiz->recordResponses($this, $responses);
        $quiz->participationByTeam($this)->update(['finished_at' => now()]);
        return $responses;
    }

    public function beginQuiz(Quiz $quiz)
    {
        $participation = $quiz->participationByTeam($this);
        if(!$participation->started_at) {
            return $participation->update(['started_at' => now()]);
        }
        return false;
    }

}

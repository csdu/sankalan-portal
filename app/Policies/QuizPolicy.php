<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Quiz;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuizPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the quiz.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Quiz  $quiz
     * @return mixed
     */
    public function view(User $user, Quiz $quiz)
    {
        if(!$quiz->isActive) {
            return false;
        }

        $team = $quiz->event->participatingTeamByUser($user);

        if(!$team) {
            return false;
        }
        
        $participation = $quiz->participations()->where('team_id', $team->id)->first();

        if(!$participation) {
            return false;
        }

        if($participation->finished_at) {
            return false;
        }

        return true;
    }

    public function create_response(User $user, Quiz $quiz) {
        if(!$this->view($user, $quiz)) {
            return false;
        }
        
        $team = $quiz->event->participatingTeamByUser($user);
        $participation = $quiz->participations()->where('team_id', $team->id)->first();
        
        if(!$participation->started_at) {
            return false;
        }

        return true;
    } 

}

<?php 

namespace App\Checks;

use Auth;
use App\Exceptions\TeamNotAllowedForQuizException;
use App\Exceptions\TeamNotParticipatingInEventException;
use App\Exceptions\QuizNotActiveException;

class TeamCanTakeQuiz {

    protected $quiz;
    protected $team;

    public function __construct($team, $quiz)
    {
        $this->quiz = $quiz;
        $this->team = $team;
    }
    
    public static function check($team, $quiz) {
        return (new static($team, $quiz))->perform();
    }

    public function perform() {
        return $this->isTeamParticipating() && 
            $this->isQuizActive() && 
            $this->isTeamAllowedForQuiz() &&
            $this->teamHasNotSubmittedResponse();
    }

    private function isTeamParticipating() {
        if($this->team) {
            return true;
        }

        flash('You are not participating in this event!')->error();
        return false;
    }

    private function isQuizActive()
    {
        if ($this->quiz->isActive) {
            return true;
        }

        flash('Quiz is not yet active!')->error();
        return false;
    }

    private function isTeamAllowedForQuiz()
    {
        if ($this->quiz->isTeamAllowed($this->team)) {
            return true;
        }

        flash('You are not allowed for this Quiz! Please contact help desk.')->error();
        return false;
    }

    private function teamHasNotSubmittedResponse()
    {
        if(!$this->quiz->hasTeamResponded($this->team)) {
            return true;
        }

        flash('You already have submitted the response for this quiz.')->warning();
        return false;
    }
}
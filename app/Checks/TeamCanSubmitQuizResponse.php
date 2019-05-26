<?php

namespace App\Checks;

class TeamCanSubmitQuizResponse
{
    protected $quiz;
    protected $team;

    public function __construct($team, $quiz)
    {
        $this->quiz = $quiz;
        $this->team = $team;
    }

    public static function check($team, $quiz)
    {
        return (new static($team, $quiz))->perform();
    }

    public function perform()
    {
        return TeamCanTakeQuiz::check($this->team, $this->quiz) &&
        $this->checkUserHasStartedQuiz() &&
        $this->checkUserIsNotSubmittingAgain();
    }

    public function checkUserHasStartedQuiz()
    {
        if ($this->quiz->participationByTeam($this->team)->started_at) {
            return true;
        }

        flash('You tried something fishy, you are disqualified!')->error();
        return false;
    }

    public function checkUserIsNotSubmittingAgain()
    {
        if ( ! $this->quiz->participationByTeam($this->team)->finished_at) {
            return true;
        }

        flash('You tried something fishy, you are disqualified!')->error();
        return false;
    }
}

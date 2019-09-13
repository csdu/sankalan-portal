<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Http\Requests\TakeQuizRequest;

class QuizController extends Controller
{
    public function show(TakeQuizRequest $request, Quiz $quiz)
    {
        $team = $request->validated()['team'];

        $quiz->loadMissing([
            'questions.choices',
            'participations' => function ($query) use ($team) {
                $query->where('quiz_participations.team_id', $team->id);
            },
        ]);

        $team->beginQuiz($quiz);

        return view('quizzes.show', compact('quiz'));
    }

    public function instructions(Quiz $quiz)
    {
        $quiz->load(['event']);

        return view('quizzes.instructions', compact('quiz'));
    }
}

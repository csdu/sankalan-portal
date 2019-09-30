<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizVerificationController extends Controller
{
    public function showVerificationForm(Quiz $quiz)
    {
        return view('quizzes.verify')->withQuiz($quiz);
    }

    public function verify(Request $request, Quiz $quiz)
    {
        if($request->verification_token == $quiz->token) {
            session()->put('quiz_token', $request->verification_token);
            return redirect(route('quizzes.show', $quiz));
        }

        return redirect(route('quizzes.verify', $quiz));
    }
}

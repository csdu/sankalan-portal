<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuizVerificationController extends Controller
{
    public function showVerificationForm(Quiz $quiz)
    {
        return view('quizzes.verify')->withQuiz($quiz);
    }

    public function verify(Request $request, Quiz $quiz)
    {
        if($request->verification_token == "12345") {
            $request->session()->put('quiz_token', $request->verification_token);
            return redirect(route('quizzes.show', $quiz));
        }

        return redirect()->back();
    }
}

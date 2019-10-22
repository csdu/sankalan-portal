<?php

namespace App\Exceptions;

use Exception;

class QuizNotVerifiedException extends Exception
{
    public function render($request)
    {
        if(!$request->expectsJson()) {
            flash($this->getMessage())->error();
            
            return redirect()->route('quizzes.verify', $request->quiz);
        }

        return response()->json([
            'status' => 'error',
            'message' => $this->getMessage(),
            'verification_url' => route('quizzes.verify', $request->quiz)
        ], 401);
    }
}

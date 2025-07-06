<?php

namespace App\Exceptions;

use Exception;

class QuizVerificationException extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => $this->getMessage()
            ], 403);
        }

        flash($this->getMessage())->warning();

        return redirect()->back();
    }
}

<?php

namespace App\Http\Middleware;

use Closure;

class VerifyQuiz
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        if ($request->session()->has('quiz_token')) {
            if ($request->session()->get('quiz_token') == '12345') {
                return $next($request);
            } 
        }

        return redirect(route('quizzes.verify', $request->quiz));
    }
}

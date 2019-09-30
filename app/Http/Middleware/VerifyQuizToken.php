<?php

namespace App\Http\Middleware;

use Closure;

class VerifyQuizToken
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
        if (session()->has('quiz_token')) {
            if (session()->get('quiz_token') == $request->quiz->token) {
                return $next($request);
            } 
        }

        return redirect(route('quizzes.verify', $request->quiz));
    }
}

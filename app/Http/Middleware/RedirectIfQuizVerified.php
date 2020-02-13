<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class RedirectIfQuizVerified
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
        if (Session::has('quiz_token') && $request->quiz->verify(Session::get('quiz_token'))) {
            return redirect()->route('quizzes.take', $request->quiz);
        }

        return $next($request);
    }
}

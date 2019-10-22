<?php

namespace App\Http\Middleware;

use App\Exceptions\QuizNotVerifiedException;
use App\Exceptions\QuizVerificationException;
use Closure;
use Illuminate\Support\Facades\Session;

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
        $teamId = auth()->user()->teams->pluck('id')->intersect($request->quiz->event->teams->pluck('id'))->first();

        if (! $teamId) {
            throw new QuizVerificationException('you have not participated in the event.');
        }

        if (! $request->quiz->isActive) {
            throw new QuizVerificationException('quiz is not live yet.');
        }

        $participation = $request->quiz->participations()->where('team_id', $teamId)->first();
        
        if ($participation && $participation->finished_at) {
            throw new QuizVerificationException('you have already taken this quiz.');
        }

        if (! Session::has('quiz_token') || ! $request->quiz->verify(Session::get('quiz_token'))) {
            throw new QuizNotVerifiedException('you have not verified quiz token.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\EventParticipation;
use Closure;

class CheckForParticipantDisqualified
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
        $event = $request->route('quiz')->event;
        $team = $request->route('quiz')->event->participatingTeamByUser($request->user());
        if ((bool) EventParticipation::where([
            'event_id' => $event->id,
            'team_id' => $team->id,
        ])->first()->disqualified) {
            flash('You have been disqualified!')->warning();

            return redirect()->back();
        }

        return $next($request);
    }
}

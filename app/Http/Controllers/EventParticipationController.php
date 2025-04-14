<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EventParticipationController extends Controller
{
    public function store(Event $event)
    {
        request()->validate([
            'team_id' => [
                'nullable',
                'integer',
                Rule::exists('team_user', 'team_id')->where(
                    fn ($query) => $query->where('user_id', auth()->id())
                ),
            ],
        ]);

        $user = Auth::user();

        $teamId = request('team_id');

        $team = $teamId ? Team::findOrFail($teamId)
            : ($user->individualTeam ?? $user->createTeam($user->name));

        if (! $team->participate($event)) {
            flash('We do not allow same person to participate in same event twice, not even as a different team')->error();

            return redirect()->back();
        }

        flash("We have registered your team '{$team->uid}' for '{$event->title}' event!")->success();

        return redirect()->back();
    }

    public function destroy(Event $event)
    {
        $team = $event->participatingTeamByUser(Auth::user());

        if (! $team) {
            flash('You are not participating in this event!')->error();

            return redirect()->back();
        }

        $team->withdrawParticipation($event);

        flash('We have withdrawn your Participation')->success();

        return redirect()->back();
    }
}

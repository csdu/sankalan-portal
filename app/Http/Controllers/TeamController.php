<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTeamRequest;
use App\Models\User;
use Auth;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Auth::user()->teams()->with('members')->get();
        $users = User::where('id', '<>', Auth::id())->get();

        return view('teams.index', compact('teams', 'users'));
    }

    public function store(CreateTeamRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        $member = User::whereEmail($data['member_email'] ?? null)->first();

        if (!$this->canCreateTeam($user, $member)) {
            flash('You already have this Team!')->warning();

            return redirect()->back();
        }

        $team = $user->createTeam(request('name'), $member);
        flash('Your team was created! TeamId: ' . $team->uid)->success();

        return redirect()->back();
    }

    protected function canCreateTeam($user, $member = null)
    {
        if (!$member) {
            return !$user->individualTeam()->exists();
        }

        // Does user already have any team containing the same member?
        return !$user->teams()
            ->whereHas(
                'members',
                fn ($query) => $query->where('team_user.user_id', $member->id)
            )->exists();
    }
}

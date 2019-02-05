<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Team;
use Auth;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Auth::user()->teams()->with('members')->get();
        $users = User::where('id', '<>', Auth::id())->get();
        return view('teams.index', compact('teams', 'users'));
    }

    public function store()
    {
        $data = request()->validate([
            'name' => ['required', 'string', 'min:3', 'max:190'],
            'member_email' => ['nullable', 'email', 'exists:users,email'],
        ]);

        $user = Auth::user();
        $member = request()->has('member_email') ? User::whereEmail(request('member_email'))->first() : null;

        if($this->canCreateTeam($user, $member)) {
            $team = $user->createTeam($data['name'], $member);
            flash('Your team was created! TeamId: '. $team->uid )->success();
        } else {
            flash('You already have this Team!')->warning();
        }
        
        return redirect()->back();

    }


    protected function canCreateTeam($user, $member = null) {
        if(!$member) {
            return !$user->individualTeam()->exists();
        }

        // Does user already have any team containing the same member?
        return !$user->teams()
            ->whereHas('members', function($query) use($member) {
                return $query->where('team_user.user_id', $member->id);
            })->exists();
    }
}

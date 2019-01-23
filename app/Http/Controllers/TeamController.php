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
            'member_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $user = Auth::user();

        if($this->canCreateTeam($user)) {
            $team = $user->createTeam($data['name'], $data['member_id'] ?? null);
        }
        
        return redirect()->back();

    }


    protected function canCreateTeam($user) {
        if(!request('member_id')) {
            return $user->team_id === null;
        }

        // Does user already have any team containing the same member?
        return !$user->teams()->whereHas('members', function($query) {
            return $query->where('team_user.user_id', request('member_id'));
        })->exists();
    }
}

<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Validator;

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
        $user = auth()->user();

        Validator::make(request()->all(), [
            'name' => ['required', 'string', 'min:3', 'max:190'],
            'member_email' => ['nullable', 'email', 'exists:users,email'],
        ])->after(function ($validator) use ($user) {
            if (request('member_email') === $user->email) {
                $validator->errors()->add('member_email', 'You cannot team up with yourself!');
            }
        })->validate();

        $member = request()->has('member_email') ? User::whereEmail(request('member_email'))->first() : null;

        if ( ! $this->canCreateTeam($user, $member)) {
            flash('You already have this Team!')->warning();
            return redirect()->back();
        }

        tap($user->createTeam(request('name'), $member), function ($team) {
            flash('Your team was created! TeamId: ' . $team->uid)->success();
        });

        return redirect()->back();
    }

    protected function canCreateTeam($user, $member = null)
    {
        if ( ! $member) {
            return ! $user->individualTeam()->exists();
        }

        // Does user already have any team containing the same member?
        return ! $user->teams()
            ->whereHas('members', function ($query) use ($member) {
                return $query->where('team_user.user_id', $member->id);
            })->exists();
    }
}

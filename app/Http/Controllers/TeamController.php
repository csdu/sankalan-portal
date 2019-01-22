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
            'members' => ['required', 'array', 'min:1', 'max:2'],
            'members.*' => ['required', 'integer', 'exists:users,id'],
        ]);

        array_push($data['members'], Auth::id());

        $team = Team::create(['name' => $data['name']]);
        $team->members()->sync($data['members']);

        return redirect()->back();
    }
}

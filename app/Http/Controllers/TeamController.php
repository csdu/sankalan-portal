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
            'member_id' => ['sometimes', 'integer', 'exists:users,id'],
        ]);

        Auth::user()->createTeam($data['name'], $data['member_id'] ?? null);

        return redirect()->back();
    }
}

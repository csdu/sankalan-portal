<?php

namespace App\Http\Controllers\Admin;

use App\Models\Team;
use App\Http\Controllers\Controller;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::withCount(['events'])->with(['members'])->paginate(config('app.pagination.perPage'));

        return view('admin.teams.index', compact('teams'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Team;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::withCount(['events'])->with(['members'])->paginate(config('app.pagination.perPage'));
        return view('admin.teams.index', compact('teams'));
    }
}

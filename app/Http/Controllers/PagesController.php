<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class PagesController extends Controller
{
    public function index()
    {
        return view('welcome', [
            'timeLeft' => max(0, Carbon::now()->diffInSeconds(Carbon::parse(env('SANKALAN_START_TIME')), false)),
        ]);
    }

    public function help()
    {
        return view('help');
    }
}

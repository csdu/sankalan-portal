<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class PagesController extends Controller
{
    public function index()
    {
        return view('welcome', [
            'timeLeft' => now()->diffInSeconds(Carbon::parse(env('SANKALAN_START_TIME')), true),
        ]);
    }

    public function help()
    {
        return view('help');
    }
}

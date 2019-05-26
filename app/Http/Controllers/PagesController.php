<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class PagesController extends Controller
{
    public function index()
    {
        return view('welcome', [
            'timeLeft' => now()->diffInSeconds(Carbon::parse(config('app.sankalan_start_time')), false),
        ]);
    }

    public function help()
    {
        return view('help');
    }
}

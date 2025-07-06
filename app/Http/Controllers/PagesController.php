<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Date;

class PagesController extends Controller
{
    public function index()
    {
        return view('welcome', [
            'timeLeft' => max(0, Date::now()->diffInSeconds(Date::parse(env('SANKALAN_START_TIME')), false)),
        ]);
    }

    public function help()
    {
        return view('help');
    }
}

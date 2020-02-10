<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
    public function index()
    {
        return view('welcome', [
            'timeLeft' => now()->diffInSeconds(config('app.sankalan_start_time'), true),
        ]);
    }

    public function help()
    {
        return view('help');
    }
}

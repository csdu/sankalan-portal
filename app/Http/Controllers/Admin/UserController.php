<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('is_admin', false)->paginate(config('app.pagination.perPage'));
        return view('admin.users.index', compact('users'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('is_admin', false)->paginate(config('app.pagination.perPage'));

        return view('admin.users.index', compact('users'));
    }
}

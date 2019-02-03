<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('is_admin', false)->paginate(15);
        return view('admin.users.index', compact('users'));
    }
}

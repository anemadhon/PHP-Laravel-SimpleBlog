<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereKeyNot(auth()->id())->whenSearch('name')
                    ->withCount('articles')->paginate(5)->withQueryString();

        return view('users.index', compact('users'));
    }
}

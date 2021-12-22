<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '<>', auth()->id())->withCount('articles')->paginate(5, ['id', 'name', 'username', 'email', 'articles_count']);

        return view('users.index', compact('users'));
    }
}

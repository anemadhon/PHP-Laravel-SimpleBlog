<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereKeyNot(auth()->id())->when(request('keyword'), function ($query)
        {
            $query->where('name', 'like', '%'.request('keyword').'%');
        })->withCount('articles')->paginate(5, ['id', 'name', 'username', 'email', 'articles_count'])->withQueryString();

        return view('users.index', compact('users'));
    }
}

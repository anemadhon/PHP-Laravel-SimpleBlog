<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $articles = auth()->user()->articles()->latest();

        return view('dashboard', [
            'last_article' => $articles->get(['id', 'slug', 'user_id']),
            'articles_count' => $articles->count()
        ]);
    }
}

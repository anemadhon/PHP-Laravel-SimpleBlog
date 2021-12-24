<?php

namespace App\Http\Controllers\Main;

use App\Models\Article;
use App\Http\Controllers\Controller;
use App\Services\ArticleService;

class ArticleController extends Controller
{
    public function index()
    {
        return view('main.articles', [
            'articles' => (new ArticleService())->list(auth()->user())
        ]);
    }

    public function show(Article $article)
    {
        return view('main.articles-single', [
            'article' => $article->load(['category', 'tags', 'images', 'user'])
        ]);
    }
}

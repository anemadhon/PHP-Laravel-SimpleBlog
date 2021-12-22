<?php

namespace App\Http\Controllers\Main;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    public function index()
    {
        return view('main.articles', [
            'articles' => Article::when(request('keyword'), function($query)
            {
                $query->where('title', 'like', '%'.request('keyword').'%');
            })->with(['category', 'tags', 'images', 'user'])->latest()->paginate(2)->withQueryString()
        ]);
    }

    public function show(Article $article)
    {
        return view('main.articles-single', [
            'article' => $article->load(['category', 'tags', 'images', 'user'])
        ]);
    }
}

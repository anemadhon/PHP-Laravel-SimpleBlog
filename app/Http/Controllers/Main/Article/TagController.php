<?php

namespace App\Http\Controllers\Main\Article;

use App\Models\Tag;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    public function __invoke(Tag $tag)
    {
        return view('main.articles-tags', [
            'tag' => $tag->load(['articles' => function($query) {
                $query->when(request('keyword'), function($query)
                {
                    $query->where('title', 'like', '%'.request('keyword').'%');
                })->latest();
            }, 'articles.images', 'articles.tags', 'articles.user', 'articles.category'])
        ]);
    }
}

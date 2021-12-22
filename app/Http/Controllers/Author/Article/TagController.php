<?php

namespace App\Http\Controllers\Author\Article;

use App\Models\Tag;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    public function __invoke(Tag $tag)
    {
        return view('articles.tags', [
            'tag' => $tag->load(['articles' => function($query) {
                $query->when(!auth()->user()->is_admin, function () use ($query)
                {
                    $query->where('user_id', auth()->id());
                })->when(request('keyword'), function($query)
                {
                    $query->where('title', 'like', '%'.request('keyword').'%');
                })->latest();
            }, 'articles.images', 'articles.tags', 'articles.user', 'articles.category'])
        ]);
    }
}

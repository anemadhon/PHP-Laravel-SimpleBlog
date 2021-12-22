<?php

namespace App\Http\Controllers\Author\Article;

use App\Models\Category;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function __invoke(Category $category)
    {
        return view('articles.categories', [
            'category' => $category->load(['articles' => function($query) {
                $query->when(!auth()->user()->is_admin, function($query)
                {
                    $query->where('user_id', auth()->id());
                })->when(request('keyword'), function($query)
                {
                    $query->where('title', 'like', '%'.request('keyword').'%');
                })->latest();
            }, 'articles.images', 'articles.tags', 'articles.user'])
        ]);
    }
}

<?php

namespace App\Http\Controllers\Main\Article;

use App\Models\Category;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function __invoke(Category $category)
    {
        return view('main.articles-categories', [
            'category' => $category->load(['articles' => function($query) {
                $query->whenSearch('title')->latest();
            }, 'articles.images', 'articles.tags', 'articles.user'])
        ]);
    }
}

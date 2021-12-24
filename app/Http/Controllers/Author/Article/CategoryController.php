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
                $query->whenNotAdmin()->whenSearch('title')->latest();
            }, 'articles.images', 'articles.tags', 'articles.user'])
        ]);
    }
}

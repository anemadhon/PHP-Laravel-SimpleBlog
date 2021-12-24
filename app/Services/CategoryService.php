<?php

namespace App\Services;

class CategoryService
{
    public function delete(object $category)
    {
        $category->articles->each(function($article)
        {
            $article->tags()->detach();
            if ($article->images()->count() > 0) {
                $article->images()->delete();
            }
            $article->delete();
        });

        $category->delete();
    }
}

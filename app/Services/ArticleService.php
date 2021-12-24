<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Facades\File;

class ArticleService
{
    public function list(?object $user)
    {
        if ((!is_null($user) && $user->is_admin) || is_null($user)) {
            return Article::whenSearch('title')->with(['category', 'tags', 'images', 'user'])
                            ->latest()->paginate(8)->withQueryString();
        }

        return $user->articles()->whenSearch('title')->with(['category', 'tags', 'images'])
                ->latest()->paginate(8)->withQueryString();
    }

    public function gallery(array $file, object $article, string $flag = '')
    {
        if ($flag === 'edit') {
            File::deleteDirectory(storage_path('app\public\articles\images\\').$article->slug);
            
            $article->images()->delete();
        }

        foreach ($file as $path) {
            $article->images()->create([
                'path' => $path->storeAs('articles', "images/{$article->slug}/{$path->getClientOriginalName()}", 'public')
            ]);
        }
    }

    public function delete(object $article)
    {
        $article->tags()->update(['is_deleted' => 1]);

        $article->images()->each(function($image)
        {
            $image->delete();
        });

        $article->delete();
    }

    public function restore(object $article)
    {
        $article->deletedTags()->update(['is_deleted' => 0]);

        $article->images()->each(function($image)
        {
            $image->restore();
        });

        $article->restore();
    }
}

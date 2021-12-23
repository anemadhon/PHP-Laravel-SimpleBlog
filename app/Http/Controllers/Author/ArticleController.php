<?php

namespace App\Http\Controllers\Author;

use App\Models\Tag;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ArticleRequest;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->is_admin) {
            $articles = Article::when(request('keyword'), function($query)
            {
                $query->where('title', 'like', '%'.request('keyword').'%');
            })->with(['category', 'tags', 'images'])->latest()->paginate(8)->withQueryString();
        }

        if (!auth()->user()->is_admin) {
            $articles = auth()->user()->articles()->when(request('keyword'), function($query)
            {
                $query->where('title', 'like', '%'.request('keyword').'%');
            })->with(['category', 'tags', 'images'])->latest()->paginate(8)->withQueryString();
        }

        return view('articles.index', [
            'articles' => $articles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('articles.form', [
            'state' => 'New',
            'categories' => Category::all(),
            'tags' => Tag::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        $article = auth()->user()->articles()->create($request->safe()->except(['tags']));

        if ($request->hasFile('path')) {
            foreach ($request->file('path') as $path) {
                $article_path = $path->storeAs('articles', "images/{$article->slug}/{$path->getClientOriginalName()}", 'public');
                $article->images()->create([
                    'path' => $article_path
                ]);
            }
        }

        foreach ($request->safe()->only(['tags']) as $value) {
            $article->tags()->attach($value);
        }

        return redirect()->route('author.articles.index')->with('success', 'Article saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return view('articles.show', [
            'article' => $article->load(['category', 'tags', 'images', 'user'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        $this->authorize('is-yours', $article);

        return view('articles.form', [
            'state' => 'Update',
            'categories' => Category::all(),
            'tags' => Tag::all(),
            'article' => $article->load(['tags', 'images'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, Article $article)
    {
        $this->authorize('is-yours', $article);

        $article->update($request->safe()->except(['tags']));

        if ($request->hasFile('path')) {
            if ($request->image_flag === 'edit') {
                File::deleteDirectory(storage_path('app\public\articles\images\\').$article->slug);
                $article->images()->delete();
                foreach ($request->file('path') as $path) {
                    $article_path = $path->storeAs('articles', "images/{$article->slug}/{$path->getClientOriginalName()}", 'public');
                    $article->images()->create([
                        'path' => $article_path
                    ]);
                }
            }
            
            if ($request->image_flag === 'add') {
                foreach ($request->file('path') as $path) {
                    $article_path = $path->storeAs('articles', "images/{$article->slug}/{$path->getClientOriginalName()}", 'public');
                    $article->images()->create([
                        'path' => $article_path
                    ]);
                }
            }
        }

        if (($article->tags->count() + count($request->tags)) <= 10) {
            $article->tags()->detach();
            foreach ($request->safe()->only(['tags']) as $value) {
                $article->tags()->attach($value);
            }
        }

        return redirect()->route('author.articles.index')->with('success', 'Article updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $this->authorize('is-yours', $article);

        $article->tags()->update(['is_deleted' => 1]);

        $article->images()->each(function ($image)
        {
            $image->delete();
        });

        $article->delete();
        
        return redirect()->back()->with('success', 'Article deleted. <span>Undo? click <a href="'.route('author.articles.restore', ['id' => $article->id]).'" class="underline font-semibold">here</a></span>');
    }

    public function restore(int $id)
    {
        $article = Article::withTrashed()->find($id);
        
        $this->authorize('is-yours', $article);

        if ($article && $article->trashed()) {
            $article->deletedTags()->update(['is_deleted' => 0]);

            $article->images()->each(function($image)
            {
                $image->restore();
            });

            $article->restore();
        }

        return redirect()->back()->with('success', 'Article restored.');
    }
}

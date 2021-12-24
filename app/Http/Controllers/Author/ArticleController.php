<?php

namespace App\Http\Controllers\Author;

use App\Models\Tag;
use App\Models\Article;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Services\ArticleService;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('articles.index', [
            'articles' => (new ArticleService())->list(auth()->user())
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
            (new ArticleService())->gallery($request->file('path'), $article);
        }

        $article->tags()->sync($request->safe()->only(['tags'])['tags']);

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
            (new ArticleService())->gallery($request->file('path'), $article, $request->image_flag);
        }

        if (($article->tags->count() + count($request->tags)) <= 10) {
            $article->tags()->sync($request->safe()->only(['tags'])['tags']);
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

        (new ArticleService())->delete($article);
        
        return redirect()->back()->with('success', 'Article deleted. <span>Undo? click <a href="'.route('author.articles.restore', ['id' => $article->id]).'" class="underline font-semibold">here</a></span>');
    }

    public function restore(int $id)
    {
        $article = Article::withTrashed()->find($id);
        
        $this->authorize('is-yours', $article);

        if ($article && $article->trashed()) {
            (new ArticleService())->restore($article);
        }

        return redirect()->back()->with('success', 'Article restored.');
    }
}

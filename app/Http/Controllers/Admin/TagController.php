<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Services\TagService;
use App\Http\Requests\TagRequest;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tags.index', [
            'tags' => Tag::when(request('keyword'), function($query)
            {
                $query->where('name', 'like', '%'.request('keyword').'%');
            })->paginate(6)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tags.form', [
            'state' => 'New'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagRequest $request)
    {
        Tag::create([
            'name' => (new TagService())->format($request->validated()['name'])
        ]);

        return redirect()->route('author.tags.index')->with('success', 'Tag saved.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        return view('tags.form', [
            'state' => 'Update',
            'tag' => $tag
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(TagRequest $request, Tag $tag)
    {
        $tag->update([
            'name' => (new TagService())->format($request->validated()['name'])
        ]);

        return redirect()->route('author.tags.index')->with('success', 'Tag updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        (new TagService())->delete($tag);

        return redirect()->back()->with('success', 'Tag deleted.');
    }
}

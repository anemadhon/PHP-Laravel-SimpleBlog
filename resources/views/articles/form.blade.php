<x-app-layout>
    <x-slot name="header">
        {{ __($state === 'New' ? "$state Article" : 'Update Article') }}
    </x-slot>

    <div class="p-4 bg-white rounded-lg shadow-md">

        <form action="{{ isset($article) ? route('author.articles.update', ['article' => $article]) : route('author.articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @isset($article)
                @method('put')
            @endisset
            <div class="mt-4">
                <x-label for="title" :value="__('Title')"/>
                <x-input type="text"
                         id="title"
                         name="title"
                         class="block w-full"
                         value="{{ old('title', isset($article) ? $article->title : '') }}"
                         required/>
                @error('title')
                    <span class="text-xs text-red-600">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            
            <div class="mt-4">
                <x-label for="content" :value="__('Content')"/>
                <textarea 
                        name="content" 
                        id="content" 
                        cols="30" 
                        rows="3" 
                        class="block w-full
                        mt-1 
                        border-gray-300 
                        rounded-md shadow-sm 
                        focus:border-primary-300 
                        focus:ring focus:ring-primary-200 
                        focus:ring-opacity-50 
                        focus-within:text-primary-600"
                        required>{{ old('content', isset($article) ? $article->content : '') }}</textarea>
                @error('content')
                    <span class="text-xs text-red-600">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="mt-4">
                <span class="block text-sm text-gray-700">
                    {{ __('Category') }}
                </span>
                <div>
                    @forelse ($categories as $category)
                        <x-label class="inline-flex items-center text-gray-600">
                            <x-slot name="value">
                                <input type="radio"
                                    id="category"
                                    name="category_id"
                                    class="mt-1"
                                    value="{{ old('category_id', $category->id) }}"
                                    required
                                    {{ (isset($article) && $article->category_id === $category->id) || old('category_id') ? 'checked' : '' }}>
                                <span class="ml-2 mt-1 mr-1 text-gray-600">{{ $category->name }}</span>
                            </x-slot>
                        </x-label>
                    @empty
                        <x-label class="inline-flex items-center text-gray-600">
                            <x-slot name="value">
                                <x-input 
                                    type="radio"
                                    id="category"
                                    name="category_id"
                                    class="mt-1"
                                    value=""
                                    required/>
                            </x-slot>
                        </x-label>
                    @endforelse
                </div>
                @error('category_id')
                    <span class="text-xs text-red-600">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="mt-4">
                <span class="block text-sm text-gray-700">
                    {{ __('Tags') }}
                </span>
                <div>
                    @forelse ($tags as $idx => $tag)
                        @isset($article)
                            @php
                                $empty = []
                            @endphp
                            @foreach ($article->tags as $article_tag)
                                @if ($tag->id === $article_tag->tag->tag_id)
                                    <x-label class="inline-flex items-center text-gray-600">
                                        <x-slot name="value">
                                                <x-input 
                                                    type="checkbox"
                                                    id="tag"
                                                    name="tags[]"
                                                    value="{{ old('tags['.$idx.']', $tag->id) }}"
                                                    checked="checked"/>
                                            <span class="ml-2 mt-1 text-gray-600 mr-2">{{ $tag->name }}</span>
                                        </x-slot>
                                    </x-label>
                                    @php
                                        $empty[] = $tag->id
                                    @endphp
                                    @break
                                @endif
                            @endforeach
                            @if (!in_array($tag->id, $empty))
                                <x-label class="inline-flex items-center text-gray-600">
                                    <x-slot name="value">
                                        <x-input
                                            type="checkbox"
                                            id="tag"
                                            name="tags[]"
                                            value="{{ old('tags['.$idx.']', $tag->id) }}"/>
                                        <span class="ml-2 mt-1 text-gray-600 mr-2">{{ $tag->name }}</span>
                                    </x-slot>
                                </x-label>
                            @endif
                        @endisset
                        @if (!isset($article))
                            <x-label class="inline-flex items-center text-gray-600">
                                <x-slot name="value">
                                    <input 
                                        type="checkbox"
                                        id="tag"
                                        name="tags[]"
                                        class="mt-1 
                                        border-gray-300 
                                        rounded-md 
                                        shadow-sm 
                                        focus:border-primary-300 
                                        focus:ring focus:ring-primary-200 
                                        focus:ring-opacity-50 
                                        focus-within:text-primary-600"
                                        value="{{ old("tags[{$idx}]", $tag->id) }}"
                                        {{ is_array(old('tags')) && in_array($tag->id, old('tags')) ? 'checked ' : '' }}/>
                                    <span class="ml-2 mt-1 text-gray-600 mr-2">{{ $tag->name }}</span>
                                </x-slot>
                            </x-label>
                        @endif
                    @empty
                        <x-label class="inline-flex items-center text-gray-600">
                            <x-slot name="value">
                                <x-input 
                                    type="checkbox"
                                    id="tag"
                                    name="tags[]"
                                    value=""
                                    required/>
                            </x-slot>
                        </x-label>
                    @endforelse
                </div>
                @if ((isset($article) && $article->tags->count() < 10) || $state === 'New')
                    <span class="text-xs text-gray-600">
                        You can possible to choose multiple tags {{ isset($article) ? "({$article->tags->count()}/10)" : '(0/10)' }}
                    </span>
                @endif
                @if (isset($article) && $article->tags->count() >= 10)
                    <span class="text-xs text-gray-600">
                        {{ __("You've reached the maximum of the tagged articles (10/10)") }}
                    </span>
                @endif
                @error('tags.*')
                    <span class="text-xs text-red-600">
                        {{ " - $message" }}
                    </span>
                @enderror
                @error('tags')
                    <span class="text-xs text-red-600">
                        {{ " - $message" }}
                    </span>
                @enderror
            </div>

            <div class="mt-4">
                <x-label for="path" :value="__('Image')"/>
                @if ($state === 'Update' && isset($article))
                    <x-label class="inline-flex items-center text-gray-600">
                        <x-slot name="value">
                            <input type="radio"
                                id="imageflag"
                                name="image_flag"
                                class="mt-1 ml-2"
                                value="edit"
                                checked>
                            <span class="ml-2 text-gray-600">Replace</span>
                        </x-slot>
                    </x-label>
                    @if ($article->images->count() < 4)
                        <x-label class="inline-flex items-center text-gray-600">
                            <x-slot name="value">
                                <input type="radio"
                                    id="imageflag"
                                    name="image_flag"
                                    class="mt-1 ml-1"
                                    value="add">
                                <span class="ml-2 text-gray-600">Add</span>
                            </x-slot>
                        </x-label>
                    @endif
                @endif
                <input type="file"
                         id="path"
                         name="path[]"
                         class="mt-1 block
                         w-full
                         text-base
                         font-normal
                         text-gray-700
                         bg-white bg-clip-padding
                         border border-solid border-gray-300
                         rounded
                         transition
                         ease-in-out
                         shadow-sm
                         focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                         value="{{ old('path', '') }}"
                         multiple/>
                @if ((isset($article) && $article->images->count() < 4) || $state === 'New')
                    <span class="text-xs text-gray-600">
                        You can possible to select multiple images {{ isset($article) ? "({$article->images->count()}/4)" : '(0/4)' }}
                    </span>
                @endif
                @if (isset($article) && $article->images->count() >= 4)
                    <span class="text-xs text-gray-600">
                        {{ __("You've reached the maximum of the attached file (4/4)") }}
                    </span>
                @endif
                @error('path')
                    <span class="text-xs text-red-600">
                        {{ " - $message" }}
                    </span>
                @enderror
            </div>

            @if ($state === 'Update' && isset($article) && $article->images->count() > 0)
                <div class="mt-4 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                    @foreach ($article->images as $image)
                        <div class="group relative">
                            <div class="w-full h-48 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:aspect-none">
                                <img src="{{ asset("storage/{$image->path}") }}" alt="{{ "article-images-{$image->id}" }}" class="w-full h-48 object-center object-fill">
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            
            <div class="mt-4 flex justify-end">
                <button type="submit"
                    class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                >
                    <span>{{ __($state === 'New' ? 'Save' : 'Update') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-2 -mr-1" fill="none" viewBox="0 0 22 22" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                </button>
            </div>
        </form>

    </div>
</x-app-layout>

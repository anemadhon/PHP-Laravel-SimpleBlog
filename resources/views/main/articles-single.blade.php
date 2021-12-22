<x-main-layout>
    <x-slot name="header">
        <p class="flex items-center">
            <a href="{{ route('main.articles') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2 -mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </a>
            <span class="ml-2 mr-2">|</span>
            <span>{{ __('Single Article') }}</span>
        </p>
    </x-slot>

    <div class="p-4 bg-white rounded-lg shadow-md">
        <div class="flex flex-wrap w-full mb-10">
            <div class="lg:w-1/2 w-full mb-6 lg:mb-0">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-2 text-gray-700">{{ ucwords($article->title) }}</h1>
                <div class="h-1 w-20 bg-indigo-500 rounded"></div>
            </div>
            <p class="lg:w-1/2 w-full leading-relaxed text-gray-600">{{ ucwords($article->content) }}</p>
            <p class="lg:w-1/2 w-full leading-relaxed text-gray-600 text-xs"><span class="mt-3 text-xs text-indigo-400">{{ __('Category: ') }}</span><a class="mt-3 text-xs text-indigo-400" href="{{ route('main.articles.category', ['category' => $article->category]) }}">{{ ucwords($article->category->name) }}</a></p>
            <p class="lg:w-1/2 w-full leading-relaxed text-indigo-400 text-xs">{{ 'Created By: @'.$article->user->username }} {{ "({$article->created_at->diffForHumans()})" }}</p>
            <div class="mt-1 lg:w-1/2 w-full leading-relaxed text-gray-600 text-xs">
                @foreach ($article->tags as $tag)
                    <a class="mt-3 text-xs font-semibold text-indigo-400" href="{{ route('main.articles.tag', ['tag' => $tag]) }}">{{ "#{$tag->name}" }}</a>
                @endforeach
            </div>
        </div>
        <div class="flex flex-wrap -m-4 justify-center">
            @forelse ($article->images as $image)
                <div class="xl:w-1/4 md:w-1/2 p-4">
                    <div class="bg-gray-800 bg-opacity-40 p-3 rounded-lg">
                        <img class="h-40 rounded w-full object-fill object-center mb-6" src="{{ asset("storage/{$image->path}") }}" alt="{{ "article-images-{$image->id}" }}">
                        <div class="h-2 w-2 bg-indigo-500 rounded-full -mt-2"></div>
                    </div>
                </div>
            @empty
                {{ __('No Gallery Found') }}
            @endforelse
        </div>
    </div>

</x-main-layout>

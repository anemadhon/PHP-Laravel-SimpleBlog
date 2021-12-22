<x-main-layout>
    <x-slot name="header">
        <p class="flex items-center">
            <a href="{{ route('main.articles') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2 -mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </a>
            <span class="ml-2 mr-2">|</span>
            <span>{{ __('List of Articles - Tag:') }}<span class="underline">{{ __($tag->name) }}</span></span>
        </p>
    </x-slot>
    
    <div class="p-4 bg-white rounded-lg shadow-xs">

        <div class="flex justify-center mb-6 md:justify-end">
            <div
                class="relative w-full max-w-xl focus-within:text-purple-500 shadow-md"
            >
                <div class="absolute inset-y-0 flex items-center pl-2">
                    <svg
                        class="w-4 h-4"
                        aria-hidden="true"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                        fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd"
                        ></path>
                    </svg>
                </div>
                <form action="{{ route('main.articles.tag', ['tag' => $tag]) }}" method="get">
                    <input
                        class="w-full pl-8 pr-2 text-sm text-gray-700 placeholder-gray-600 bg-gray-50 border-0 rounded-md focus:placeholder-gray-500 focus:bg-white focus:border-purple-300 focus:outline-none focus:shadow-outline-purple form-input"
                        type="search"
                        name="keyword"
                        value="{{ request('keyword') }}"
                        placeholder="Search Articles and Hit Enter"
                        aria-label="Search"
                    />
                </form>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4 flex justify-center">
            @forelse ($tag->articles as $article)
                <div class="max-w-md py-4 px-8 bg-white shadow-lg rounded-lg my-12">
                    <div class="flex justify-center md:justify-end -mt-16">
                        <img class="w-20 h-20 object-cover rounded-full border-2 border-indigo-500" src="{{ $article->images->count() === 0 ? 'https://ui-avatars.com/api/?name='.urlencode($article->title).'&color=FFFFFF&background=808080' : asset("storage/{$article->images->last()->path}") }}">
                    </div>
                    <div>
                        <h2 class="text-gray-800 text-3xl font-semibold">{{ ucwords($article->title) }}</h2>
                        <p class="mt-2 text-gray-600">{{ ucwords($article->limit_content) }} <span class="text-indigo-400 underline"><a href="{{ route('main.articles.single', ['article' => $article]) }}">{{ __('more') }}</a></span> </p>
                    </div>
                    <div class="flex justify-end mt-4">
                        <p class="text-xl font-medium text-indigo-500">{{ '@'.$article->user->username }}</p>
                        <p class="mt-3 ml-1 text-xs text-gray-600">{{ $article->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="mt-4 -mx-4">
                        <span class="mt-3 text-xs text-indigo-400">{{ __('Category: ') }}</span><a class="mt-3 text-xs text-indigo-400" href="{{ route('main.articles.category', ['category' => $article->category]) }}">{{ ucwords($article->category->name) }}</a>
                    </div>
                    <div class="mt-2 -mx-4">
                        @foreach ($article->tags as $tag)
                            <a class="mt-3 text-xs font-semibold text-indigo-400" href="{{ route('main.articles.tag', ['tag' => $tag]) }}">{{ "#{$tag->name}" }}</a>
                        @endforeach
                    </div>
                </div>
            @empty
                {{ __('No Articles Found') }}
            @endforelse
        </div>
    </div>
</x-main-layout>

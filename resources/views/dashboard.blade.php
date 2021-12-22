<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <div class="p-4 mb-3 bg-white rounded-lg shadow-xs">
        {{ __('Welcome') }} {{ ucwords(auth()->user()->name) }}
    </div>
    
    @if ($articles_count > 0)
        <div class="p-4 mb-3 bg-white rounded-lg shadow-xs">
            {{ __("You've reach : {$articles_count} {$title}. Manage your latest article ") }} <a href="{{ route('author.articles.edit', ['article' => $last_article[0]['slug']]) }}" class="underline">here</a>
        </div>
    @endif
</x-app-layout>

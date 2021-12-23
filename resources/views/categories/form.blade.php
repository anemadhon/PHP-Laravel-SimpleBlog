<x-app-layout>
    <x-slot name="header">
        {{ __($state === 'New' ? "$state Category" : 'Update Category') }}
    </x-slot>

    <div class="p-4 bg-white rounded-lg shadow-md">

        <form action="{{ isset($category) ? route('author.categories.update', ['category' => $category]) : route('author.categories.store') }}" method="POST">
            @csrf
            @isset($category)
                @method('put')
            @endisset
            <div class="mt-4">
                <x-label for="name" :value="__('Name')"/>
                <x-input type="text"
                         id="name"
                         name="name"
                         class="block w-full"
                         value="{{ old('name', $category->name ?? '') }}"
                         required/>
                @error('name')
                    <span class="text-xs text-red-600">
                        {{ $message }}
                    </span>
                @enderror
            </div>

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

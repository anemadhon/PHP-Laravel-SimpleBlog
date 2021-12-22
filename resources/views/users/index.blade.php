<x-app-layout>
    <x-slot name="header">
        {{ __('List of Users') }}
    </x-slot>

    <div class="p-4 bg-white rounded-lg shadow-xs">

        <div class="flex justify-center mb-6 md:justify-end">
            <div
                class="relative w-full max-w-xl focus-within:text-purple-500 rounded-md border"
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
                <form action="{{ route('users.index') }}" method="get">
                    <input
                        class="w-full pl-8 pr-2 text-sm text-gray-700 placeholder-gray-600 bg-gray-50 border-0 rounded-md focus:placeholder-gray-500 focus:bg-white focus:border-purple-300 focus:outline-none focus:shadow-outline-purple form-input"
                        type="search"
                        name="keyword"
                        value="{{ request('keyword') }}"
                        placeholder="Search User and Hit Enter"
                        aria-label="Search"
                    />
                </form>
            </div>
        </div>

        <div class="overflow-hidden mb-8 w-full rounded-lg border shadow-xs">
            <div class="overflow-x-auto w-full">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                    <tr class="text-xs text-center font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b">
                        <th class="px-4 py-3">No.</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Articles</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                    @forelse ($users as $user)
                        <tr class="text-gray-700">
                            <td class="px-4 py-3 text-sm text-center">
                                {{ $users->firstItem() + $loop->index }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $user->name }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $user->email }}
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                {{ $user->articles_count }}
                            </td>
                        </tr>
                    @empty
                        <tr class="text-gray-700">
                            <td class="px-4 py-3 text-sm text-center" colspan="4">
                                {{ __('No Users Found') }}
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 border-t sm:grid-cols-9">
                {{ $users->links() }}
            </div>
        </div>

    </div>
</x-app-layout>

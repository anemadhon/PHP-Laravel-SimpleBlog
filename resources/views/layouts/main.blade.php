<!DOCTYPE html>
<html x-data="data" lang="en">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="{{ asset('js/init-alpine.js') }}"></script>
</head>
<body>
<div
    class="flex h-screen bg-gray-50"
>
    <div class="flex flex-col flex-1 w-full">
        <header class="z-10 py-4 bg-white shadow-md">
            <div class="container flex justify-between items-center mx-auto h-full text-purple-600 md:justify-end">
                @if (Route::has('login'))
                    <div class="px-10 sm:block sm:px-8">
                        <a href="{{ route('login') }}" class="font-medium text-sm">Login</a>
                        <a href="{{ route('me') }}" class="ml-2 font-medium text-sm">Me</a>
                    </div>
                @endif
            </div>
        </header>        
        <main class="h-full overflow-y-auto">
            <div class="container px-6 mx-auto grid">
                <h2 class="my-6 text-2xl font-semibold text-gray-700">
                    {{ $header }}
                </h2>

                {{ $slot }}
            </div>
        </main>
    </div>
</div>
</body>
</html>

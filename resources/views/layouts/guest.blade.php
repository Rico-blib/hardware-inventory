<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> {{ $globalSetting?->app_name ?? config('app.name', 'POS System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-4xl h-[500px] flex shadow-lg rounded-lg overflow-hidden bg-white">
            <!-- Left side: background image/color -->
            <div class="w-1/2 hidden md:block"
                style="
               {{ $globalSetting?->login_background_image
                 ? 'background-image: url(' .
                asset('storage/' . $globalSetting->login_background_image) .
                '); background-size: cover; background-position: center;'
                   : 'background-color: ' . ($globalSetting?->login_background_color ?? '#f3f4f6') . ';' }}
                  ">
            </div>


            <!-- Right side: login form -->
            <div class="w-full md:w-1/2 p-8 flex flex-col justify-center">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>

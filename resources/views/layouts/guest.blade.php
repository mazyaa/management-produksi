<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PT Mitsuba Indonesia') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased min-h-screen bg-gradient-to-tr from-slate-900 via-primary-950 to-slate-900 flex items-center justify-center p-4 relative overflow-hidden">
        <!-- Background decorative elements -->
        <div class="absolute w-[500px] h-[500px] rounded-full bg-primary-600/10 blur-[80px] -top-40 -left-40"></div>
        <div class="absolute w-[400px] h-[400px] rounded-full bg-secondary-500/10 blur-[100px] -bottom-20 -right-20"></div>

        <div class="w-full max-w-md z-10">
            {{ $slot }}
        </div>
    </body>
</html>

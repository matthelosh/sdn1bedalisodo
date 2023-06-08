<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" type="image/png" href="{{ URL::asset('img/logo.png')}}" />
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
        <style>
            ::-webkit-scrollbar {
                width: .5rem;
                background: transparent;
            }
            ::-webkit-scrollbar-track {
                background: #4533ff66;
                border-radius: 1.5rem;
                padding: 0 1px;
            }
            ::-webkit-scrollbar-thumb {
                background: #ffaa33;
                border-radius: 1.5rem;
                box-shadow: 0 1px 3px rgba(0,0,0,0.5);
                width: .5rem;
                margin: 0 1px;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>

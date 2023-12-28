<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" type="image/png" href="{{ URL::asset('img/logo.png')}}" />
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <!-- <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> -->

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js'])
        @inertiaHead
        <style>
            ::-webkit-scrollbar {
                width: .5rem;
                height: .3rem;
                background: transparent;
            }
            ::-webkit-scrollbar-track {
                background: #4533ff66;
                border-radius: 1.5rem;
                padding: 0 1px;
            }
            ::-webkit-scrollbar-thumb {
                background: #5687ff;
                border-radius: 1.5rem;
                box-shadow: 0 1px 3px rgba(0,0,0,0.5);
                width: .5rem;
                height: .3rem;
                margin: 0 1px;
            }
        </style>
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-XWMX756V82"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-XWMX756V82');
        </script>
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>

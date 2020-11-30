<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Colbydude's Repose</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;600;700&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Lato';
            }
        </style>

        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-900 px-4 sm:items-center sm:pt-0 sm:px-6">
            @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                        <a href="{{ route('home') }}" class="text-sm text-gray-700 underline">Home</a> |
                        <a
                            href="{{ route('logout') }}"
                            class="text-sm text-gray-700 underline"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();"
                        >
                            Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            @yield('content')
        </div>
    </body>
</html>

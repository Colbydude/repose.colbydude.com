<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        <title>{{ config('app.name', 'Colbydude\'s Repose') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">

        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10">
            @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 text-secondary sm:block">
                    @auth
                        <a href="{{ route('home') }}" class="text-sm text-secondary underline">Home</a> |
                        <a
                            href="{{ route('logout') }}"
                            class="text-sm text-secondary underline"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();"
                        >
                            Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-secondary underline">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-secondary underline">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            @yield('content')
        </div>
    </body>
</html>

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Scripts -->
    @viteReactRefresh
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Custom Styles -->
    <style>
        /* Background color */
        body {
            background-color: #ADD8E6 !important; /* Light Blue */
            font-family: 'Nunito', sans-serif;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        /* Main container */
        .main-container {
            min-height: 100vh; /* Full height */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .navbar {
            background-color: #ffffff !important; /* White navbar */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Logo Styling */
        .navbar-brand {
            display: flex;
            align-items: center;
            font-size: 1.2rem;
            font-weight: bold;
            position: relative;
        }

        .navbar-brand img {
            position: absolute;
            left: -50px; /* Adjust position as needed */
            width: 40px; /* Adjust size as needed */
            height: auto;
            opacity: 0.7; /* Make it slightly transparent */
        }
        /* Page content */
        .content-wrapper {
            flex-grow: 1;
            padding: 20px;
        }

    </style>
</head>
<body>
<div id="app" class="main-container">
    <nav class="navbar navbar-expand-md navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo"> <!-- Ensure the image exists in public/images -->
                Autilearn
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        @if(Auth::user())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('educator.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('educator.add-student') }}">Add Student</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('learning.index') }}">Let's Learn</a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="content-wrapper">
        @yield('content')
    </main>

    {{--<!-- Footer -->
    <footer class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'Autilearn') }}. All rights reserved.</p>
    </footer>--}}
</div>
</body>
</html>

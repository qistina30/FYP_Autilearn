<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=OpenDyslexic:wght@400;700&display=swap" rel="stylesheet">
    <!-- In the <head> section of your layout file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <!-- Scripts -->
    @viteReactRefresh
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Custom Styles -->
    <style>
        /* Background color */
        body {
            background-color: #ADD8E6 ;
            font-family: 'Nunito', sans-serif;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        /* Make Navbar Fixed */
        .navbar {
            background-color: #ffffff !important; /* White navbar */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000; /* Ensures it stays above other content */
        }

        /* Adjust content to avoid being hidden behind fixed navbar */
        .content-wrapper {
            flex-grow: 1;
            padding: 100px 20px 20px; /* Added top padding to prevent overlap */
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

        /* Base nav-link style */
        .navbar-nav .nav-link {
            position: relative;
            padding: 10px 15px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        /* Underline animation */
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 4px;
            width: 100%;
            height: 2px;
            background-color: #0d6efd; /* Bootstrap primary */
            transform: scaleX(0);
            transform-origin: bottom right;
            transition: transform 0.3s ease;
        }

        /* On hover or active */
        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link.active::after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }

        /* Change text color on hover */
        .navbar-nav .nav-link:hover {
            color: #0d6efd !important;
        }

    </style>

</head>
<body>
<nav class="navbar navbar-expand-md navbar-light">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
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
                @else
                    @auth
                        @if(in_array(auth()->user()->role, ['admin', 'guardian']))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    Dashboard
                                </a>
                            </li>
                        @endif

                        <!-- Activity (admin, educator only) -->
                        @if(in_array(auth()->user()->role, ['admin', 'educator']))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('activity.welcome') ? 'active' : '' }}" href="{{ route('activity.welcome') }}">
                                    Activity
                                </a>
                            </li>
                        @endif

                            @if(in_array(auth()->user()->role, ['admin']))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}" href="{{ route('admin.index') }}">
                                        Manage User
                                    </a>
                                </li>
                            @endif

                        <!-- View Student (admin, educator only) -->
                        @if(in_array(auth()->user()->role, ['admin', 'educator']))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('student.index') ? 'active' : '' }}" href="{{ route('student.index') }}">View Student</a>
                            </li>
                        @endif

                        <!-- View Educator (admin, educator only) -->
                        @if(in_array(auth()->user()->role, ['admin', 'educator']))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('educator.index') ? 'active' : '' }}" href="{{ route('educator.index') }}">View Educator</a>
                            </li>
                        @endif

                        <!-- View Report (admin, educator, guardian) -->
                        @if(in_array(auth()->user()->role, ['admin', 'educator']))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('report.analytics') ? 'active' : '' }}" href="{{ route('report.analytics') }}">View Report</a>
                            </li>
                            @elseif(auth()->user()->role === 'guardian' && auth()->user()->children->isNotEmpty())
                                @php
                                    $firstChild = auth()->user()->children->first();
                                @endphp
                                @if($firstChild)
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('report.show') ? 'active' : '' }}" href="{{ route('report.show', ['id' => $firstChild->id]) }}">
                                            My Child's Report
                                        </a>
                                    </li>
                                @endif
                            @endif

                    @endauth

                    <li class="nav-item dropdown">
                        <a id="logoutDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="logoutDropdown">
                            <!-- Edit Profile -->
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                {{ __('Edit Profile') }}
                            </a>
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

<main class="content-wrapper">
    @yield('content')
</main>
@yield('scripts')
</body>


</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Vasist') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <!-- Styles -->
    <style>
        .sidebar {
            min-height: 100vh;
            box-shadow: 0 0 10px rgba(0,0,0,.1);
            background: #343a40;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.8);
            padding: 1rem;
        }
        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,.1);
        }
        .sidebar .nav-link.active {
            background: rgba(255,255,255,.2);
        }
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        .main-content {
            min-height: 100vh;
            background: #f8f9fa;
        }
        .top-navbar {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="app">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-md navbar-light top-navbar">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Vasist') }}
                </a>

                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Giriş') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Kayıt') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Çıkış') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                @auth
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                    <div class="position-sticky pt-3">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                    <i class="fas fa-home"></i> Ana Sayfa
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('packages*') ? 'active' : '' }}" href="{{ route('packages.index') }}">
                                    <i class="fas fa-box"></i> Paketler
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('customers*') ? 'active' : '' }}" href="#">
                                    <i class="fas fa-users"></i> Müşteriler
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('vehicles*') ? 'active' : '' }}" href="#">
                                    <i class="fas fa-car"></i> Araçlar
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('services*') ? 'active' : '' }}" href="#">
                                    <i class="fas fa-wrench"></i> Servisler
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('reports*') ? 'active' : '' }}" href="#">
                                    <i class="fas fa-chart-bar"></i> Raporlar
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('settings*') ? 'active' : '' }}" href="#">
                                    <i class="fas fa-cog"></i> Ayarlar
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
                @endauth

                <!-- Main Content -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content py-4">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html>

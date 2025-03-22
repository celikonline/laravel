<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Vega Asist</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Styles -->
    <style>
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --text-primary: #212529;
            --text-secondary: #6c757d;
            --border-color: #dee2e6;
            --primary-color: #2563eb;
            --card-bg: #ffffff;
            --navbar-bg: #ffffff;
            --table-header-bg: #f3f4f6;
            --hover-bg: #f3f4f6;
        }

        [data-theme="dark"] {
            --bg-primary: #1a1a1a;
            --bg-secondary: #2d2d2d;
            --text-primary: #ffffff;
            --text-secondary: #9ca3af;
            --border-color: #404040;
            --primary-color: #3b82f6;
            --card-bg: #2d2d2d;
            --navbar-bg: #2d2d2d;
            --table-header-bg: #404040;
            --hover-bg: #404040;
        }

        body {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        /* Navbar Styles */
        .navbar {
            background-color: var(--navbar-bg) !important;
            padding: 1rem 0;
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color) !important;
        }

        .nav-link {
            color: var(--text-primary) !important;
            position: relative;
            padding: 0.5rem 1rem !important;
            margin: 0 0.2rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: var(--hover-bg);
            color: var(--primary-color) !important;
            transform: translateY(-1px);
        }

        .nav-link.active {
            background-color: var(--primary-color);
            color: white !important;
        }

        .nav-link i {
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover i {
            transform: scale(1.1);
        }

        /* Dropdown Styles */
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .dropdown-item {
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: #f3f4f6;
            color: #2563eb;
            transform: translateX(5px);
        }

        .dropdown-item i {
            margin-right: 0.5rem;
            width: 1.25rem;
            text-align: center;
        }

        /* Main Content Styles */
        .main-content {
            min-height: 100vh;
            background: #f8f9fa;
            padding: 2rem 0;
        }

        /* Card Styles */
        .card {
            background-color: var(--card-bg);
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Button Styles */
        .btn {
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        /* Table Styles */
        .table {
            color: var(--text-primary);
            border-radius: 1rem;
            overflow: hidden;
        }

        .table thead th {
            background-color: var(--table-header-bg);
            color: var(--text-primary);
            border-bottom: none;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: var(--border-color);
        }

        /* Badge Styles */
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 500;
        }

        /* Loading Animation */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Footer Styles */
        footer {
            background-color: var(--navbar-bg) !important;
        }

        footer .text-muted {
            color: var(--text-secondary) !important;
        }

        /* Theme Toggle Button */
        .theme-toggle {
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            background-color: var(--hover-bg);
        }

        .theme-toggle i {
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo.svg') }}" alt="Logo" height="40" class="me-2">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav ms-4 me-auto">
                        @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reports.index') }}">
                                <i class="fas fa-chart-bar"></i> Raporlar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('packages.all') }}">
                                <i class="fas fa-box"></i> Tüm Paketler
                            </a>
                        </li>
                        @endauth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('packages.index') }}">
                                <i class="fas fa-list"></i> Paket Listesi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('packages.proposals') }}">
                                <i class="fas fa-file-alt"></i> Teklif Paket Listesi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('packages.create') }}">
                                <i class="fas fa-plus-circle"></i> Yeni Paket
                            </a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt"></i> {{ __('Login') }}
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus"></i> {{ __('Register') }}
                                    </a>
                                </li>
                            @endif
                        @else
                            <!-- Notifications Dropdown -->
                            <li class="nav-item dropdown me-3">
                                <a id="notificationsDropdown" class="nav-link dropdown-toggle position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bell"></i>
                                    <x-notification-badge :count="auth()->user()->notifications()->where('is_read', false)->count()" />
                                </a>

                                <div class="dropdown-menu dropdown-menu-end notifications-dropdown" aria-labelledby="notificationsDropdown" style="width: 300px; max-height: 400px; overflow-y: auto;">
                                    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                                        <h6 class="mb-0">Bildirimler</h6>
                                        @if(auth()->user()->notifications()->where('is_read', false)->exists())
                                            <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none">
                                                    Tümünü Okundu İşaretle
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <div class="notifications-list">
                                        @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                            <div class="dropdown-item {{ $notification->is_read ? '' : 'bg-light' }} border-bottom">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                    <div class="dropdown">
                                                        <button class="btn btn-link btn-sm p-0 text-decoration-none" type="button" data-bs-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            @if(!$notification->is_read)
                                                                <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="dropdown-item">
                                                                        <i class="fas fa-check me-2"></i> Okundu İşaretle
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fas fa-trash me-2"></i> Sil
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h6 class="mb-1">{{ $notification->title }}</h6>
                                                <p class="mb-1 small">{{ Str::limit($notification->message, 100) }}</p>
                                                @if($notification->link)
                                                    <a href="{{ $notification->link }}" class="btn btn-link btn-sm p-0 text-decoration-none">
                                                        Detayları Görüntüle <i class="fas fa-arrow-right ms-1"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        @empty
                                            <div class="dropdown-item text-center text-muted py-3">
                                                <i class="fas fa-bell-slash mb-2 d-block"></i>
                                                Bildiriminiz bulunmamaktadır.
                                            </div>
                                        @endforelse

                                        @if(auth()->user()->notifications()->count() > 5)
                                            <a href="{{ route('notifications.index') }}" class="dropdown-item text-center py-2 text-primary">
                                                Tüm Bildirimleri Görüntüle
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-1"></i>
                                    <span>{{ Auth::user()->name }}</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user-edit"></i> Profil
                                    </a>
                                    <a class="dropdown-item" href="{{ route('audit.index') }}">
                                        <i class="fas fa-history"></i> Sistem Kayıtları
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <h6 class="dropdown-header">Ayarlar</h6>
                                    <a class="dropdown-item" href="{{ route('vehicle-brands.index') }}">
                                        <i class="fas fa-car"></i> Araç Markaları
                                    </a>
                                    <a class="dropdown-item" href="{{ route('vehicle-models.index') }}">
                                        <i class="fas fa-car-side"></i> Araç Modelleri
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> {{ __('Çıkış Yap') }}
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

        <main class="py-4">
            <div class="container">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-white shadow-sm mt-5">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-primary mb-3">VASist</h5>
                    <p class="text-muted">Araç servis asistanınız olarak size en iyi hizmeti sunmak için buradayız.</p>
                    <div class="social-links">
                        <a href="#" class="text-muted me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-muted me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-muted me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-muted"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-primary mb-3">Hızlı Linkler</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('dashboard') }}" class="text-muted text-decoration-none">Dashboard</a></li>
                        <li class="mb-2"><a href="{{ route('packages.all') }}" class="text-muted text-decoration-none">Tüm Paketler</a></li>
                        <li class="mb-2"><a href="{{ route('packages.create') }}" class="text-muted text-decoration-none">Yeni Paket</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="text-primary mb-3">İletişim</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2 text-muted"><i class="fas fa-map-marker-alt me-2"></i> İstanbul, Türkiye</li>
                        <li class="mb-2 text-muted"><i class="fas fa-phone me-2"></i> +90 (xxx) xxx xx xx</li>
                        <li class="mb-2 text-muted"><i class="fas fa-envelope me-2"></i> info@vasist.com</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center text-muted">
                <small>&copy; {{ date('Y') }} VASist. Tüm hakları saklıdır.</small>
            </div>
        </div>
    </footer>

    @stack('scripts')

    <!-- Bootstrap Bundle JS (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Tema değiştirme fonksiyonu
        function toggleTheme() {
            const html = document.documentElement;
            const themeIcon = document.querySelector('.theme-toggle i');
            
            // Mevcut temayı kontrol et
            const isDark = html.getAttribute('data-theme') === 'dark';
            
            // Temayı değiştir
            if (isDark) {
                html.removeAttribute('data-theme');
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
                localStorage.setItem('theme', 'light');
            } else {
                html.setAttribute('data-theme', 'dark');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
                localStorage.setItem('theme', 'dark');
            }
        }

        // Sayfa yüklendiğinde kaydedilmiş temayı uygula
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme');
            const themeIcon = document.querySelector('.theme-toggle i');
            
            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            }
        });
    </script>
</body>
</html>

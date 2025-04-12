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
            --sidebar-width: 250px;
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

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background-color: var(--navbar-bg);
            padding: 1rem;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar.collapsed {
            width: 60px;
        }

        .sidebar.collapsed .sidebar-brand span,
        .sidebar.collapsed .nav-link p,
        .sidebar.collapsed .nav-link .right {
            display: none;
        }

        .sidebar.collapsed .nav-link {
            padding: 0.75rem;
            justify-content: center;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        .sidebar.collapsed .nav-treeview {
            display: none;
        }

        .sidebar-toggle {
            position: absolute;
            right: -12px;
            top: 50%;
            transform: translateY(-50%);
            background-color: var(--navbar-bg);
            border: 1px solid var(--border-color);
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1001;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .sidebar-toggle i {
            transition: transform 0.3s ease;
        }

        .sidebar.collapsed .sidebar-toggle i {
            transform: rotate(180deg);
        }

        .sidebar-header {
            padding: 1rem 0;
            margin-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            color: var(--primary-color);
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .sidebar-brand img {
            height: 40px;
            margin-right: 0.5rem;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--text-primary);
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link .right {
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .nav-link.collapsed .right {
            transform: rotate(-90deg);
        }

        .nav-link:hover {
            background-color: var(--hover-bg);
            color: var(--primary-color);
        }

        .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }

        .nav-link i {
            margin-right: 0.75rem;
            width: 1.25rem;
            text-align: center;
        }

        .nav-treeview {
            padding-left: 0;
            margin-top: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .nav-treeview.show {
            max-height: 500px;
        }

        .nav-treeview .nav-link {
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            font-size: 0.9rem;
            border-radius: 0;
        }

        .nav-treeview .nav-link:hover {
            background-color: var(--hover-bg);
        }

        .nav-treeview .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 5rem 2rem 2rem 2rem;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 60px;
        }

        /* Top Navbar Styles */
        .top-navbar {
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            height: 60px;
            background-color: var(--navbar-bg);
            padding: 0 1rem;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            z-index: 999;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
            margin-left: var(--sidebar-width);
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

        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .top-navbar {
                left: 0;
            }

            footer {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="app">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-toggle" onclick="toggleSidebar()">
                <i class="fas fa-chevron-left"></i>
            </div>
            <div class="sidebar-header">
                <a href="{{ url('/') }}" class="sidebar-brand">
                    <img src="{{ asset('images/logo.svg') }}" alt="Logo">
                    <span>Vega Asist</span>
                </a>
            </div>

            <nav class="mt-3">
                @auth
                <ul class="nav nav-pills nav-sidebar flex-column">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#reportsSubmenu">
                            <i class="fas fa-chart-bar"></i>
                            <p>Raporlar</p>
                            <i class="right fas fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview collapse {{ request()->routeIs('reports.*') ? 'show' : '' }}" id="reportsSubmenu">
                            <li class="nav-item">
                                <a href="{{ route('reports.packages') }}" class="nav-link {{ request()->routeIs('reports.packages') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Paket Raporları</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reports.customers') }}" class="nav-link {{ request()->routeIs('reports.customers') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Müşteri Raporları</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('packages.all') ? 'active' : '' }}" href="{{ route('packages.all') }}">
                            <i class="fas fa-box"></i>
                            <p>Tüm Paketler</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('packages.create') ? 'active' : '' }}" href="{{ route('packages.create') }}">
                            <i class="fas fa-plus-circle"></i>
                            <p>Yeni Paket</p>
                        </a>
                    </li>
                </ul>
                @endauth
            </nav>
        </aside>

        <!-- Top Navbar -->
        <nav class="top-navbar">
            <ul class="navbar-nav ms-auto">
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
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>

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
    </div>

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

        // Menüyü açıp kapatma fonksiyonu
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            const topNavbar = document.querySelector('.top-navbar');
            const footer = document.querySelector('footer');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            if (sidebar.classList.contains('collapsed')) {
                topNavbar.style.left = '60px';
                footer.style.marginLeft = '60px';
                localStorage.setItem('sidebarCollapsed', 'true');
            } else {
                topNavbar.style.left = 'var(--sidebar-width)';
                footer.style.marginLeft = 'var(--sidebar-width)';
                localStorage.setItem('sidebarCollapsed', 'false');
            }
        }

        // Sayfa yüklendiğinde kaydedilmiş temayı ve menü durumunu uygula
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme');
            const themeIcon = document.querySelector('.theme-toggle i');
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            
            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            }

            if (sidebarCollapsed) {
                toggleSidebar();
            }
        });

        // Mobil menü toggle
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.querySelector('.sidebar');
            const menuToggle = document.querySelector('.menu-toggle');
            
            if (menuToggle) {
                menuToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('show');
                });
            }
        });

        // Alt menü toggle fonksiyonu
        document.addEventListener('DOMContentLoaded', () => {
            const submenuLinks = document.querySelectorAll('.nav-link[data-bs-toggle="collapse"]');
            
            submenuLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const target = document.querySelector(link.getAttribute('data-bs-target'));
                    const isCollapsed = link.classList.contains('collapsed');
                    
                    link.classList.toggle('collapsed');
                    target.classList.toggle('show');
                });
            });
        });
    </script>
</body>
</html>

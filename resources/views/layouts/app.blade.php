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
            justify-content: center;
            padding: 1rem;
            text-decoration: none;
        }

        .sidebar-brand img {
            max-height: 40px;
            transition: all 0.3s ease;
        }

        .sidebar-brand .logo {
            display: block;
        }

        .sidebar-brand .logo-mini {
            display: none;
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
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nav-link p {
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nav-link .right {
            margin-left: auto;
            transition: transform 0.3s ease;
            flex-shrink: 0;
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
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 999;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .top-navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .new-package-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            font-weight: 500;
        }

        .new-package-btn:hover {
            background-color: var(--primary-color);
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .new-package-btn i {
            font-size: 1rem;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.25rem;
            padding: 0.5rem;
            cursor: pointer;
            margin-right: 1rem;
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .navbar-nav .nav-item {
            position: relative;
            margin: 0;
            z-index: 1;
        }

        .navbar-nav .nav-link {
            color: var(--text-primary);
            padding: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 0.5rem;
        }

        .navbar-nav .nav-link:hover {
            background-color: var(--hover-bg);
        }

        .navbar-nav .nav-link i {
            font-size: 1.25rem;
        }

        .navbar-nav .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 0.5rem;
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            z-index: 1000;
        }

        .navbar-nav .dropdown-item {
            padding: 0.75rem 1rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .navbar-nav .dropdown-item:hover {
            background-color: var(--hover-bg);
        }

        .navbar-nav .dropdown-item i {
            width: 1.25rem;
            text-align: center;
        }

        .navbar-nav .dropdown-divider {
            border-top: 1px solid var(--border-color);
            margin: 0.5rem 0;
        }

        .navbar-nav .dropdown-header {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .notifications-dropdown {
            width: 300px;
            max-height: 400px;
            overflow-y: auto;
        }

        .notifications-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            transform: translate(50%, -50%);
            background-color: #ef4444;
            color: white;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
            min-width: 1.5rem;
            text-align: center;
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
            .menu-toggle {
                display: block;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 4rem 1rem 1rem 1rem;
            }

            .top-navbar {
                left: 0;
            }

            .new-package-btn span {
                display: none;
            }

            .new-package-btn {
                padding: 0.5rem;
                border-radius: 50%;
            }

            .sidebar-toggle {
                display: none;
            }

            .sidebar-brand .logo {
                display: none;
            }

            .sidebar-brand .logo-mini {
                display: block;
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
                    <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="logo">
                    <img src="{{ asset('images/logo_mini.svg') }}" alt="Logo" class="logo-mini">
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
                </ul>
                @endauth
            </nav>
        </aside>

        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="top-navbar-left">
                <button class="menu-toggle" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="{{ route('packages.create') }}" class="new-package-btn {{ request()->routeIs('packages.create') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle"></i>
                    <span>Yeni Paket</span>
                </a>
            </div>
            <ul class="navbar-nav ms-auto">
                @auth
                   

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
                @endauth
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>
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
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            if (sidebar.classList.contains('collapsed')) {
                topNavbar.style.left = '60px';
                localStorage.setItem('sidebarCollapsed', 'true');
            } else {
                topNavbar.style.left = 'var(--sidebar-width)';
                localStorage.setItem('sidebarCollapsed', 'false');
            }
        }

        // Mobil menü toggle fonksiyonu
        function toggleMobileMenu() {
            const sidebar = document.querySelector('.sidebar');
            const menuToggle = document.querySelector('.menu-toggle i');
            
            sidebar.classList.toggle('show');
            
            if (sidebar.classList.contains('show')) {
                menuToggle.classList.remove('fa-bars');
                menuToggle.classList.add('fa-times');
            } else {
                menuToggle.classList.remove('fa-times');
                menuToggle.classList.add('fa-bars');
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

            // Mobil görünümde menüyü otomatik kapat
            if (window.innerWidth <= 768) {
                const sidebar = document.querySelector('.sidebar');
                sidebar.classList.remove('show');
            }
        });

        // Ekran boyutu değiştiğinde menüyü kontrol et
        window.addEventListener('resize', () => {
            const sidebar = document.querySelector('.sidebar');
            const menuToggle = document.querySelector('.menu-toggle i');
            
            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
                menuToggle.classList.remove('fa-times');
                menuToggle.classList.add('fa-bars');
            }
        });
    </script>
</body>
</html>

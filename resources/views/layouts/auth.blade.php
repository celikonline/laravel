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

        /* Form Styles */
        .form-floating > .form-control {
            border-radius: 10px;
            border: 2px solid #eee;
            padding: 1rem;
            height: calc(3.5rem + 2px);
            line-height: 1.25;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-floating > .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
            background: rgba(255, 255, 255, 0.95);
        }

        .form-floating > label {
            padding: 1rem;
        }

        /* Logo Styles */
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .logo {
            height: 60px;
            transition: all 0.3s ease;
        }

        .logo-mini {
            display: none;
            height: 40px;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .logo {
                display: none;
            }

            .logo-mini {
                display: block;
            }

            .card {
                margin: 1rem;
            }

            .container {
                padding: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="app">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html> 
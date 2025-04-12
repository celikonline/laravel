@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-5">
            <div class="card login-card">
                <div class="card-body p-5">
                    <div class="logo-container">
                        <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="logo">
                        <img src="{{ asset('images/logo_mini.svg') }}" alt="Logo" class="logo-mini">
                    </div>
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-primary">Vega Asist</h2>
                        <p class="text-muted">Hesabınıza giriş yapın</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-floating mb-3">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                placeholder="E-posta">
                            <label for="email">E-posta Adresi</label>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                name="password" required autocomplete="current-password"
                                placeholder="Şifre">
                            <label for="password">Şifre</label>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" 
                                    id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Beni Hatırla
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="text-primary text-decoration-none" href="{{ route('password.request') }}">
                                    Şifremi Unuttum
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 mb-4">
                            <i class="fas fa-sign-in-alt me-2"></i>Giriş Yap
                        </button>

                        @if (Route::has('register'))
                            <div class="text-center">
                                <p class="text-muted mb-0">
                                    Hesabınız yok mu? 
                                    <a href="{{ route('register') }}" class="text-primary text-decoration-none">
                                        Kayıt Ol
                                    </a>
                                </p>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.login-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(20px);
    background: rgba(255, 255, 255, 0.85);
}

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

.btn-primary {
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    background: linear-gradient(45deg, #0d6efd, #0091ff);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
    background: linear-gradient(45deg, #0091ff, #0d6efd);
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.form-check-input:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
}

/* Arka plan stili */
body {
    position: relative;
    min-height: 100vh;
    background-color: #f8f9fa;
    overflow-x: hidden;
}

body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: 
        linear-gradient(
            rgba(0, 0, 0, 0.4),
            rgba(0, 0, 0, 0.4)
        ),
        url('/images/login-bg.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    z-index: -2;
}

body::after {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        45deg,
        rgba(13, 110, 253, 0.2),
        rgba(0, 145, 255, 0.2)
    );
    z-index: -1;
}

/* Animasyonlu parçacıklar */
.particles {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    overflow: hidden;
}

.particle {
    position: absolute;
    width: 5px;
    height: 5px;
    background: rgba(255, 255, 255, 0.5);
    border-radius: 50%;
    animation: float 8s infinite linear;
}

@keyframes float {
    0% {
        transform: translateY(0) translateX(0);
        opacity: 0;
    }
    50% {
        opacity: 0.8;
    }
    100% {
        transform: translateY(-100vh) translateX(100px);
        opacity: 0;
    }
}

/* Responsive ayarlar */
@media (max-width: 768px) {
    .login-card {
        margin: 1rem;
    }
}
</style>
@endsection

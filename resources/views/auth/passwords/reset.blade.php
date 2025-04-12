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
                        <p class="text-muted">Yeni Şifre Belirle</p>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-floating mb-3">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
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
                                name="password" required autocomplete="new-password"
                                placeholder="Yeni Şifre">
                            <label for="password">Yeni Şifre</label>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-floating mb-4">
                            <input id="password-confirm" type="password" class="form-control" 
                                name="password_confirmation" required autocomplete="new-password"
                                placeholder="Şifre Tekrar">
                            <label for="password-confirm">Şifre Tekrar</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 mb-4">
                            <i class="fas fa-key me-2"></i>Şifreyi Sıfırla
                        </button>

                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-primary text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i>Giriş Sayfasına Dön
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

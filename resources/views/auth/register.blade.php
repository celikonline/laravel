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
                        <p class="text-muted">Yeni hesap oluşturun</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-floating mb-3">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                                placeholder="Ad Soyad">
                            <label for="name">Ad Soyad</label>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                name="email" value="{{ old('email') }}" required autocomplete="email"
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
                                placeholder="Şifre">
                            <label for="password">Şifre</label>
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
                            <i class="fas fa-user-plus me-2"></i>Kayıt Ol
                        </button>

                        <div class="text-center">
                            <p class="text-muted mb-0">
                                Zaten hesabınız var mı? 
                                <a href="{{ route('login') }}" class="text-primary text-decoration-none">
                                    Giriş Yap
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

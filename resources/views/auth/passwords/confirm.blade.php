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
                        <p class="text-muted">Şifrenizi Onaylayın</p>
                    </div>

                    <p class="text-center text-muted mb-4">
                        Lütfen devam etmeden önce şifrenizi onaylayın.
                    </p>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="form-floating mb-4">
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

                        <button type="submit" class="btn btn-primary w-100 py-2 mb-4">
                            <i class="fas fa-check me-2"></i>Şifreyi Onayla
                        </button>

                        @if (Route::has('password.request'))
                            <div class="text-center">
                                <a href="{{ route('password.request') }}" class="text-primary text-decoration-none">
                                    <i class="fas fa-question-circle me-1"></i>Şifrenizi mi unuttunuz?
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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
                        <p class="text-muted">E-posta Doğrulama</p>
                    </div>

                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            Yeni bir doğrulama bağlantısı e-posta adresinize gönderildi.
                        </div>
                    @endif

                    <p class="text-center text-muted mb-4">
                        Devam etmeden önce lütfen e-posta adresinize gönderilen doğrulama bağlantısını kontrol edin.
                    </p>

                    <p class="text-center text-muted mb-4">
                        E-posta almadıysanız,
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline text-primary">
                                yeni bir doğrulama bağlantısı talep edin
                            </button>.
                        </form>
                    </p>

                    <div class="text-center">
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="text-primary text-decoration-none">
                            <i class="fas fa-sign-out-alt me-1"></i>Çıkış Yap
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

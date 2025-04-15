@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Ödeme Bilgileri') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('packages.process-payment', $package->id) }}" id="payment-form">
                        @csrf
                        
                        <!-- Gerekli gizli alanlar -->
                        <input type="hidden" name="package_id" value="{{ $package->id }}">
                        <input type="hidden" name="order_id" value="{{ $orderId }}">
                        <input type="hidden" name="amount" value="{{ $amount }}">
                        <input type="hidden" name="currency" value="TL">

                        <!-- Toplam tutar bilgisi -->
                        <div class="mb-4 text-center">
                            <h4>Toplam Tutar: {{ number_format($amount / 100, 2) }} TL</h4>
                        </div>

                        <div class="mb-3">
                            <label for="card_owner" class="form-label">{{ __('Kart Sahibi') }}</label>
                            <input type="text" class="form-control @error('card_owner') is-invalid @enderror" 
                                id="card_owner" name="card_owner" value="" required>
                            @error('card_owner')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="card_number" class="form-label">{{ __('Kart Numarası') }}</label>
                            <input type="text" class="form-control @error('card_number') is-invalid @enderror" 
                                id="card_number" name="card_number" value="" 
                                required maxlength="16" pattern="\d{16}">
                            @error('card_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="expiry_month" class="form-label">{{ __('Son Kullanma Ay') }}</label>
                                <input type="text" class="form-control @error('expiry_month') is-invalid @enderror" 
                                    id="expiry_month" name="expiry_month" value="" 
                                    required maxlength="2" pattern="\d{2}">
                                @error('expiry_month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="expiry_year" class="form-label">{{ __('Son Kullanma Yıl') }}</label>
                                <input type="text" class="form-control @error('expiry_year') is-invalid @enderror" 
                                    id="expiry_year" name="expiry_year" value="" 
                                    required maxlength="2" pattern="\d{2}">
                                @error('expiry_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="cvv" class="form-label">{{ __('CVV') }}</label>
                                <input type="text" class="form-control @error('cvv') is-invalid @enderror" 
                                    id="cvv" name="cvv" value="" 
                                    required maxlength="3">
                                @error('cvv')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" id="submit-button">
                                {{ __('Ödemeyi Tamamla') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    console.log('Payment form initialized');

    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');

    // Form gönderim işlemi
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Form gönderimini durdur

        console.log('Payment form submission started');
        
        // Form verilerini kontrol et
        const cardNumber = document.getElementById('card_number').value;
        const expiryMonth = document.getElementById('expiry_month').value;
        const expiryYear = document.getElementById('expiry_year').value;
        const cvv = document.getElementById('cvv').value;
        const cardOwner = document.getElementById('card_owner').value;

        // Basit validasyon
        if (cardNumber.length !== 16) {
            alert('Lütfen geçerli bir kart numarası giriniz.');
            return;
        }
        if (expiryMonth.length !== 2 || parseInt(expiryMonth) > 12) {
            alert('Lütfen geçerli bir son kullanma ayı giriniz.');
            return;
        }
        if (expiryYear.length !== 2) {
            alert('Lütfen geçerli bir son kullanma yılı giriniz.');
            return;
        }
        if (cvv.length !== 3) {
            alert('Lütfen geçerli bir CVV giriniz.');
            return;
        }
        if (!cardOwner.trim()) {
            alert('Lütfen kart sahibi adını giriniz.');
            return;
        }

        console.log('Form data:', {
            card_owner: cardOwner,
            card_number: cardNumber.replace(/\d(?=\d{4})/g, "*"),
            expiry_month: expiryMonth,
            expiry_year: expiryYear,
            cvv: '***'
        });

        // Submit butonunu devre dışı bırak
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> İşleniyor...';

        // Formu gönder
        form.submit();
    });

    // Kart numarası formatlaması
    document.getElementById('card_number').addEventListener('input', function (e) {
        console.log('Card number input changed');
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 16) {
            value = value.substr(0, 16);
        }
        e.target.value = value;
        console.log('Formatted card number length:', value.length);
    });

    // CVV formatlaması
    document.getElementById('cvv').addEventListener('input', function (e) {
        console.log('CVV input changed');
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 3) {
            value = value.substr(0, 3);
        }
        e.target.value = value;
        console.log('Formatted CVV length:', value.length);
    });

    // Son kullanma ay formatlaması
    document.getElementById('expiry_month').addEventListener('input', function (e) {
        console.log('Expiry month input changed');
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 2) {
            value = value.substr(0, 2);
        }
        if (parseInt(value) > 12) {
            value = '12';
        }
        e.target.value = value;
        console.log('Formatted expiry month:', value);
    });

    // Son kullanma yıl formatlaması
    document.getElementById('expiry_year').addEventListener('input', function (e) {
        console.log('Expiry year input changed');
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 2) {
            value = value.substr(0, 2);
        }
        e.target.value = value;
        console.log('Formatted expiry year:', value);
    });
</script>
@endpush
@endsection 
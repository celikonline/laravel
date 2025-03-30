@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Yükleniyor...</span>
                    </div>
                    <p class="mt-2">Ödeme işlemi başlatılıyor, lütfen bekleyiniz...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="payment-process-form" method="POST" action="{{ route('packages.process-payment', $package_id) }}" class="d-none">
    @csrf
    <input type="hidden" name="package_id" value="{{ $package_id }}">
    <input type="hidden" name="order_id" value="{{ $order_id }}">
    <input type="hidden" name="amount" value="{{ $amount }}">
    <input type="hidden" name="currency" value="{{ $currency }}">
    <input type="hidden" name="card_owner" value="{{ $card_owner }}">
    <input type="hidden" name="card_number" value="{{ $card_number }}">
    <input type="hidden" name="expiry_month" value="{{ $expiry_month }}">
    <input type="hidden" name="expiry_year" value="{{ $expiry_year }}">
    <input type="hidden" name="cvv" value="{{ $cvv }}">
</form>

@push('scripts')
<script>
    console.log('Payment process redirect page loaded');
    // Sayfa yüklendiğinde formu otomatik olarak gönder
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Submitting payment process form...');
        document.getElementById('payment-process-form').submit();
    });
</script>
@endpush
@endsection 
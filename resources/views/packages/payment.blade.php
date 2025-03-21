@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Ödeme İşlemi</h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>Paket Bilgileri</h5>
                        <table class="table">
                            <tr>
                                <th>Sözleşme No:</th>
                                <td>{{ $package->contract_number }}</td>
                            </tr>
                            <tr>
                                <th>Müşteri:</th>
                                <td>{{ $package->customer->name }}</td>
                            </tr>
                            <tr>
                                <th>Araç:</th>
                                <td>{{ $package->plate_number }}</td>
                            </tr>
                            <tr>
                                <th>Paket:</th>
                                <td>{{ $package->servicePackage->name }}</td>
                            </tr>
                            <tr>
                                <th>Tutar:</th>
                                <td>{{ number_format($package->price, 2) }} ₺</td>
                            </tr>
                        </table>
                    </div>

                    <form method="POST" action="{{ route('packages.process-payment', $package->id) }}" id="payment-form">
                        @csrf
                        <div class="mb-4">
                            <h5>Kart Bilgileri</h5>
                            <div class="form-group mb-3">
                                <label>Kart Üzerindeki İsim*</label>
                                <input type="text" class="form-control" name="card_holder_name" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Kart Numarası*</label>
                                <input type="text" class="form-control" name="card_number" required maxlength="19" id="card_number">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Son Kullanma Tarihi*</label>
                                        <input type="text" class="form-control" name="expiry_date" placeholder="MM/YY" required maxlength="5" id="expiry_date">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>CVV*</label>
                                        <input type="text" class="form-control" name="cvv" required maxlength="3" id="cvv">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="save_card" id="save_card">
                            <label class="form-check-label" for="save_card">
                                Kartımı sonraki ödemeler için kaydet
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                            <label class="form-check-label" for="terms">
                                Ön Bilgilendirme Koşulları'nı ve Mesafeli Satış Sözleşmesi'ni okudum ve onaylıyorum
                            </label>
                        </div>

                        <button type="submit" class="btn btn-success w-100">{{ number_format($package->price, 2) }} ₺ Ödeme Yap</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Kredi kartı numarası formatı
    $('#card_number').on('input', function() {
        var value = $(this).val().replace(/\D/g, '');
        var formattedValue = value.replace(/(\d{4})(?=\d)/g, '$1 ');
        $(this).val(formattedValue);
    });

    // Son kullanma tarihi formatı
    $('#expiry_date').on('input', function() {
        var value = $(this).val().replace(/\D/g, '');
        if (value.length > 2) {
            value = value.substring(0, 2) + '/' + value.substring(2);
        }
        $(this).val(value);
    });

    // CVV sadece rakam
    $('#cvv').on('input', function() {
        var value = $(this).val().replace(/\D/g, '');
        $(this).val(value);
    });

    // Form gönderimi
    $('#payment-form').on('submit', function(e) {
        var cardNumber = $('#card_number').val().replace(/\s/g, '');
        var expiryDate = $('#expiry_date').val();
        var cvv = $('#cvv').val();

        if (cardNumber.length !== 16) {
            e.preventDefault();
            alert('Lütfen geçerli bir kart numarası giriniz.');
            return false;
        }

        if (!/^\d{2}\/\d{2}$/.test(expiryDate)) {
            e.preventDefault();
            alert('Lütfen geçerli bir son kullanma tarihi giriniz (MM/YY).');
            return false;
        }

        if (cvv.length !== 3) {
            e.preventDefault();
            alert('Lütfen geçerli bir CVV numarası giriniz.');
            return false;
        }
    });
});
</script>
@endpush 
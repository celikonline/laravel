<!-- Makbuz Önizleme -->
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('packages.receipt.pdf', $package->id) }}" class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf"></i> PDF İndir
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-6">
                    <h5 class="mb-3">Vega Asist</h5>
                    <div>Adres: Mustafa Kemal Mahallesi Cumhuriyet bulvarı No:13/B Sincan / Ankara</div>
                    <div>Tel: +90 507 972 56 88</div>
                    <div>E-posta: info@vegaasist.com.tr</div>
                </div>
                <div class="col-6 text-end">
                    <h5 class="mb-3">MAKBUZ</h5>
                    <div>Tarih: {{ $package->payment_date =='0000-00-00 00:00:00' ? $package->payment_date->format('d/m/Y') : 'Ödeme Bekleniyor' }}</div>
                    <div>Makbuz No: {{ $package->contract_number }}</div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-6">
                    <h6 class="mb-3">Müşteri Bilgileri:</h6>
                    <div>{{ $package->customer_name }}</div>
                    <div>Tel: {{ $package->phone_number ?? '-' }}</div>
                    <div>E-posta: {{ $package->email ?? '-' }}</div>
                </div>
                <div class="col-6">
                    <h6 class="mb-3">Araç Bilgileri:</h6>
                    <div>Plaka: {{ $package->plate_city }} {{ $package->plate_letters }} {{ $package->plate_numbers }}</div>
                    <div>Marka: {{ optional($package->vehicleBrand)->name }}</div>
                    <div>Model: {{ optional($package->vehicleModel)->name }}</div>
                    <div>Model Yılı: {{ $package->model_year }}</div>
                </div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Hizmet Paketi</th>
                            <th>Başlangıç Tarihi</th>
                            <th>Bitiş Tarihi</th>
                            <th>Süre (Gün)</th>
                            <th>Tutar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $package->servicePackage->name }}</td>
                            <td>{{ $package->start_date->format('d/m/Y') }}</td>
                            <td>{{ $package->end_date->format('d/m/Y') }}</td>
                            <td>{{ $package->duration }}</td>
                            <td class="text-end">{{ number_format($package->price, 2) }} ₺</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Toplam Tutar:</strong></td>
                            <td class="text-end"><strong>{{ number_format($package->price, 2) }} ₺</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="row">
                <div class="col-12">
                    <p class="text-muted">Not: Bu bir bilgisayar çıktısıdır, imza gerektirmez.</p>
                </div>
            </div>
        </div>
    </div>

   
</div>

<style>
.card {
    box-shadow: 0 0 10px rgba(0,0,0,.1);
}

.table th {
    background-color: #f8f9fa;
}

.table td, .table th {
    padding: 0.75rem;
}

@media print {
    .btn {
        display: none;
    }
    
    .card {
        box-shadow: none;
        border: none;
    }
}
</style> 
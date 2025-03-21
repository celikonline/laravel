<div class="receipt-preview">
    <div class="text-center mb-4">
        <h4>MAKBUZ</h4>
        <p class="text-muted">Makbuz No: {{ $package->contract_number }}/M</p>
    </div>

    <div class="receipt-section mb-4">
        <div class="row">
            <div class="col-6">
                <p><strong>Müşteri:</strong><br>{{ $package->customer->name }}</p>
            </div>
            <div class="col-6 text-end">
                <p><strong>Tarih:</strong><br>{{ now()->format('d.m.Y') }}</p>
            </div>
        </div>
    </div>

    <div class="receipt-section mb-4">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Açıklama</th>
                    <th class="text-end">Tutar</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        {{ $package->servicePackage->name }}<br>
                        <small class="text-muted">
                            Plaka: {{ $package->plate_city }} {{ $package->plate_letters }} {{ $package->plate_numbers }}<br>
                            Süre: {{ $package->start_date->format('d.m.Y') }} - {{ $package->end_date->format('d.m.Y') }}
                        </small>
                    </td>
                    <td class="text-end">{{ number_format($package->price, 2, ',', '.') }} ₺</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-end">TOPLAM</th>
                    <th class="text-end">{{ number_format($package->price, 2, ',', '.') }} ₺</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="receipt-section">
        <div class="row">
            <div class="col-12">
                <p class="mb-0"><strong>Yalnız:</strong> {{ $package->priceInWords }} Türk Lirası</p>
            </div>
        </div>
    </div>
</div>

<style>
.receipt-preview {
    padding: 20px;
    font-size: 14px;
}

.receipt-section {
    margin-bottom: 20px;
}

.table {
    margin-bottom: 0;
}

.table th {
    background-color: #f8f9fa;
}

.table td, .table th {
    padding: 10px;
}
</style> 
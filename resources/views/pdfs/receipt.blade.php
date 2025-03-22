<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Makbuz</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            padding: 20px;
            font-size: 14px;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .text-muted { color: #6c757d; }
        .mb-4 { margin-bottom: 20px; }
        .mb-3 { margin-bottom: 15px; }
        .mb-0 { margin-bottom: 0; }
        .row {
            clear: both;
            margin-bottom: 20px;
        }
        .col-6 {
            width: 48%;
            float: left;
        }
        .col-6:last-child {
            float: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 10px;
        }
        th {
            background-color: #f8f9fa;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="text-center mb-4">
        <h2>MAKBUZ</h2>
        <p class="text-muted">Makbuz No: {{ $package->contract_number }}/M</p>
    </div>

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
            <h4>Müşteri Bilgileri:</h4>
            <div>{{ $package->customer_name }}</div>
            <div>Tel: {{ $package->customer->phone_number ?? '-' }}</div>
            <div>E-posta: {{ $package->customer->email ?? '-' }}</div>
        </div>
        <div class="col-6">
            <h4>Araç Bilgileri:</h4>
            <div>Plaka: {{ $package->plate_city }} {{ $package->plate_letters }} {{ $package->plate_numbers }}</div>
            <div>Marka: {{ optional($package->vehicleBrand)->name }}</div>
            <div>Model: {{ optional($package->vehicleModel)->name }}</div>
            <div>Model Yılı: {{ $package->model_year }}</div>
        </div>
    </div>

    <table>
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
                <td class="text-end">{{ number_format($package->price, 2, ',', '.') }} ₺</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-end"><strong>Toplam Tutar:</strong></td>
                <td class="text-end"><strong>{{ number_format($package->price, 2, ',', '.') }} ₺</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="row">
        <div class="col-12">
            <p><strong>Yalnız:</strong> {{ $package->priceInWords }} Türk Lirası</p>
        </div>
    </div>

    <div class="footer">
        <p>Not: Bu bir bilgisayar çıktısıdır, imza gerektirmez.</p>
        <p>Sayfa 1/1</p>
    </div>
</body>
</html> 
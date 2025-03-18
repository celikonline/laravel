<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Makbuz #{{ $package->package_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .receipt-details {
            margin-bottom: 30px;
        }
        .receipt-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .receipt-details th, .receipt-details td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .receipt-details th {
            background-color: #f5f5f5;
        }
        .total {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>MAKBUZ</h1>
        <p>Makbuz No: {{ $package->package_number }}</p>
        <p>Tarih: {{ $package->created_at->format('d/m/Y') }}</p>
    </div>

    <div class="receipt-details">
        <h2>Müşteri Bilgileri</h2>
        <table>
            <tr>
                <th>Ad Soyad / Firma</th>
                <td>{{ $package->full_name }}</td>
            </tr>
            @if($package->is_individual)
            <tr>
                <th>TC Kimlik No</th>
                <td>{{ $package->identity_number }}</td>
            </tr>
            @else
            <tr>
                <th>Vergi Dairesi</th>
                <td>{{ $package->tax_office }}</td>
            </tr>
            @endif
            <tr>
                <th>Telefon</th>
                <td>{{ $package->phone }}</td>
            </tr>
            <tr>
                <th>E-posta</th>
                <td>{{ $package->email }}</td>
            </tr>
        </table>

        <h2>Araç Bilgileri</h2>
        <table>
            <tr>
                <th>Plaka</th>
                <td>{{ $package->formatted_plate }}</td>
            </tr>
            <tr>
                <th>Marka</th>
                <td>{{ $package->vehicleBrand->name }}</td>
            </tr>
            <tr>
                <th>Model</th>
                <td>{{ $package->vehicleModel->name }}</td>
            </tr>
            <tr>
                <th>Model Yılı</th>
                <td>{{ $package->vehicle_model_year }}</td>
            </tr>
        </table>

        <h2>Paket Bilgileri</h2>
        <table>
            <tr>
                <th>Paket Tipi</th>
                <td>{{ $package->packageType->name }}</td>
            </tr>
            <tr>
                <th>Başlangıç Tarihi</th>
                <td>{{ $package->start_date->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Bitiş Tarihi</th>
                <td>{{ $package->end_date->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Süre (Gün)</th>
                <td>{{ $package->duration }}</td>
            </tr>
        </table>
    </div>

    <div class="total">
        <table style="width: 300px; margin-left: auto;">
            <tr>
                <th>Tutar</th>
                <td>{{ number_format($package->amount, 2) }} TL</td>
            </tr>
            @if($package->discount)
            <tr>
                <th>İndirim</th>
                <td>{{ number_format($package->discount, 2) }} TL</td>
            </tr>
            @endif
            <tr>
                <th>KDV (%18)</th>
                <td>{{ number_format($package->kdv, 2) }} TL</td>
            </tr>
            <tr>
                <th>Toplam</th>
                <td>{{ number_format($package->net_amount, 2) }} TL</td>
            </tr>
        </table>
    </div>
</body>
</html> 
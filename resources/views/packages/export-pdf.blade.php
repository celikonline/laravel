<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Paket Listesi</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ public_path('fonts/DejaVuSans.ttf') }}') format('truetype');
            font-weight: normal;
        }
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ public_path('fonts/DejaVuSans-Bold.ttf') }}') format('truetype');
            font-weight: bold;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px 0;
            border-bottom: 2px solid #0066cc;
        }
        .logo-container {
            margin-bottom: 10px;
        }
        .logo-container img {
            max-width: 150px;
            height: auto;
        }
        .company-info {
            font-weight: bold;
            font-size: 12px;
            color: #333;
        }
        .report-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
            color: #0066cc;
        }
        .report-date {
            text-align: right;
            font-size: 10px;
            color: #666;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }
        table th {
            background-color: #f5f5f5;
            font-weight: bold;
            color: #333;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" alt="Vega Asist Logo">
        </div>
        <div class="company-info">
            <p>VEGA ASİST</p>
            <p>Adres: {{ config('app.address') }}</p>
            <p>Telefon: {{ config('app.phone') }}</p>
            <p>E-posta: {{ config('app.email') }}</p>
        </div>
    </div>

    <h1 class="report-title">PAKET LİSTESİ</h1>
    <div class="report-date">Oluşturulma Tarihi: {{ $date }}</div>

    <table>
        <thead>
            <tr>
                <th>Sözleşme No</th>
                <th>Müşteri</th>
                <th>Plaka</th>
                <th>Servis Paketi</th>
                <th>Ücret</th>
                <th>Başlangıç Tarihi</th>
                <th>Bitiş Tarihi</th>
                <th>Durum</th>
            </tr>
        </thead>
        <tbody>
            @foreach($packages as $package)
            <tr>
                <td>{{ $package->contract_number }}</td>
                <td>{{ $package->customer->name }}</td>
                <td>{{ $package->plate_city }} {{ $package->plate_letters }} {{ $package->plate_numbers }}</td>
                <td>{{ $package->servicePackage->name }}</td>
                <td>{{ number_format($package->price, 2, ',', '.') }} ₺</td>
                <td>{{ $package->start_date->format('d.m.Y') }}</td>
                <td>{{ $package->end_date->format('d.m.Y') }}</td>
                <td>{{ $package->is_active ? 'Aktif' : 'Pasif' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>VEGA ASSIST</p>
        <p>info@vegaasist.com.tr | https://vegaasist.com.tr</p>
        <p>Bu belge elektronik ortamda oluşturulmuştur.</p>
    </div>
</body>
</html> 
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Paket Listesi</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .date {
            text-align: right;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        .status-active {
            color: #28a745;
        }
        .status-inactive {
            color: #dc3545;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #666;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Paket Listesi</h2>
    </div>
    
    <div class="date">
        Oluşturma Tarihi: {{ $date }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Poliçe No</th>
                <th>Müşteri</th>
                <th>Plaka</th>
                <th>Servis Paketi</th>
                <th>Ücret</th>
                <th>Komisyon</th>
                <th>Komisyon Oranı</th>
                <th>Başlangıç</th>
                <th>Bitiş</th>
                <th>Süre</th>
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
                <td>{{ number_format($package->commission, 2, ',', '.') }} ₺</td>
                <td>%{{ number_format($package->commission_rate, 2, ',', '.') }}</td>
                <td>{{ $package->start_date->format('d.m.Y') }}</td>
                <td>{{ $package->end_date->format('d.m.Y') }}</td>
                <td>{{ $package->duration }}</td>
                <td class="{{ $package->is_active ? 'status-active' : 'status-inactive' }}">
                    {{ $package->is_active ? 'Aktif' : 'Pasif' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ config('app.name') }} - Sayfa {PAGE_NUM} / {PAGE_COUNT}
    </div>
</body>
</html> 
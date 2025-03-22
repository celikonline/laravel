<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ ucfirst($reportType) }} Raporu</h2>
            @if($startDate && $endDate)
                <p>{{ $startDate->format('d.m.Y') }} - {{ $endDate->format('d.m.Y') }}</p>
            @endif
        </div>

        <div class="content">
            @if($reportType == 'gelir')
                <h3>Gelir Özeti</h3>
                <p>Toplam Gelir: {{ number_format($reportData['totalRevenue'], 2, ',', '.') }} ₺</p>
                <p>Toplam Paket: {{ $reportData['revenue']->total() }}</p>

                <h3>Son 10 İşlem</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Poliçe No</th>
                            <th>Müşteri</th>
                            <th>Paket</th>
                            <th>Tutar</th>
                            <th>Tarih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['revenue']->take(10) as $package)
                            <tr>
                                <td>{{ $package->contract_number }}</td>
                                <td>{{ $package->customer->first_name }} {{ $package->customer->last_name }}</td>
                                <td>{{ $package->servicePackage->name }}</td>
                                <td>{{ number_format($package->price, 2, ',', '.') }} ₺</td>
                                <td>{{ $package->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @elseif($reportType == 'paketler')
                <h3>Paket Durumu Dağılımı</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Durum</th>
                            <th>Toplam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['statusDistribution'] as $status)
                            <tr>
                                <td>{{ ucfirst($status->status) }}</td>
                                <td>{{ $status->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h3>En Popüler Paketler</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Paket</th>
                            <th>Toplam Satış</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['packageDistribution']->take(5) as $package)
                            <tr>
                                <td>{{ $package->name }}</td>
                                <td>{{ $package->packages_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @elseif($reportType == 'müşteriler')
                <h3>En Çok Hizmet Alan Müşteriler</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Müşteri</th>
                            <th>Toplam Paket</th>
                            <th>Toplam Harcama</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['topCustomers'] as $customer)
                            <tr>
                                <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                                <td>{{ $customer->packages_count }}</td>
                                <td>{{ number_format($customer->packages_sum_price, 2, ',', '.') }} ₺</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="footer">
            <p>Bu rapor otomatik olarak oluşturulmuştur.</p>
            <p>© {{ date('Y') }} Vasist. Tüm hakları saklıdır.</p>
        </div>
    </div>
</body>
</html> 
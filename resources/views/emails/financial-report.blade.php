<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Finansal Rapor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .chart {
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
        }
        .progress {
            height: 20px;
            background-color: #f5f5f5;
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-bar {
            height: 100%;
            background-color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Finansal Rapor</h1>
            <p>{{ now()->format('d.m.Y') }}</p>
        </div>

        <h2>Aylık Gelir Trendi</h2>
        <table>
            <thead>
                <tr>
                    <th>Ay</th>
                    <th>Gelir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($revenueTrend as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $item->month)->format('F Y') }}</td>
                    <td>{{ number_format($item->total, 2, ',', '.') }} ₺</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h2>Servis Paketi Bazında Gelir Dağılımı</h2>
        <table>
            <thead>
                <tr>
                    <th>Servis Paketi</th>
                    <th>Gelir</th>
                    <th>Yüzde</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = $revenueDistribution->sum('packages_sum_price');
                @endphp
                @foreach($revenueDistribution as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ number_format($item->packages_sum_price, 2, ',', '.') }} ₺</td>
                    <td>
                        @php
                            $percentage = $total > 0 ? ($item->packages_sum_price / $total) * 100 : 0;
                        @endphp
                        {{ number_format($percentage, 2, ',', '.') }}%
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $percentage }}%"></div>
                        </div>
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td><strong>Toplam</strong></td>
                    <td><strong>{{ number_format($total, 2, ',', '.') }} ₺</strong></td>
                    <td><strong>100%</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html> 
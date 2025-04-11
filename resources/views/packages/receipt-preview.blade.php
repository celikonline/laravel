<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: normal;
            src: url('{{ public_path('fonts/DejaVuSans.ttf') }}') format('truetype');
        }
        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: bold;
            src: url('{{ public_path('fonts/DejaVuSans-Bold.ttf') }}') format('truetype');
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 11px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
        }
        .logo-container {
            margin-bottom: 15px;
        }
        .logo-container img {
            max-width: 180px;
            height: auto;
        }
        .company-info {
            font-weight: bold;
            font-size: 12px;
            color: #333;
            margin-top: 10px;
        }
        .company-info p {
            margin: 3px 0;
            line-height: 1.3;
        }
        .receipt-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
            color: #0066cc;
            text-transform: uppercase;
        }
        .section {
            margin-bottom: 10px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #0066cc;
            margin-bottom: 10px;
            border-bottom: 1px solid #0066cc;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .total-section {
            margin-top: 10px;
            text-align: right;
        }
        .total-table {
            width: 300px;
            margin-left: auto;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            color: rgba(0, 102, 204, 0.1);
            pointer-events: none;
            z-index: -1;
        }
        
    </style>
</head>
<body>
    <div class="watermark">MAKBUZ</div>
    
    <div class="container">
        <div class="header">
            <table>
                <tr>
                    <td style="text-align: left;">
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" alt="Vega Asist Logo">
                    </td>
                    <td style="text-align: center;">
                        <div class="receipt-title">MAKBUZ</div>
                        <div class="company-info">
                            <p>Mustafa Kemal Mahallesi Cumhuriyet bulvarı No:13/B Sincan / Ankara</p>
                            <p>Tel: +90 507 972 56 88</p>
                            <p>E-posta: info@vegaasist.com.tr</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="section">
            <table>
                <tr>
                    <th width="30%">Makbuz No</th>
                    <td>{{ $package->contract_number }}/M</td>
                    <th width="30%">Tarih</th>
                    <td>{{ $package->payment_date =='0000-00-00 00:00:00' ? 'Ödeme Bekleniyor' : $package->payment_date->format('d/m/Y') }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h2 class="section-title">Müşteri Bilgileri</h2>
            <table>
                <tr>
                    <th width="30%">Müşteri Adı</th>
                    <td>{{ $package->getCustomerNameAttribute() }}</td>
                </tr>
                <tr>
                    <th>TCN / VKN</th>
                    <td>{{ $package->customer->identity_number }}</td>
                </tr>
                <tr>
                    <th>İl:</th>
                    <td>{{ $package->customer->city->name }}</td>
                </tr>
                <tr>
                    <th>Telefon / E-posta:</th>
                    <td>{{ $package->customer->phone }} / {{ $package->customer->email }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h2 class="section-title">Araç Bilgileri</h2>
            <table>
                <tr>
                    <th width="30%">Plaka</th>
                    <td>{{ $package->plate_city }} {{ $package->plate_letters }} {{ $package->plate_numbers }}</td>
                </tr>
                <tr>
                    <th>Marka</th>
                    <td>{{ optional($package->vehicleBrand)->name }}</td>
                </tr>
                <tr>
                    <th>Model</th>
                    <td>{{ optional($package->vehicleModel)->name }}</td>
                </tr>
                <tr>
                    <th>Model Yılı</th>
                    <td>{{ $package->model_year }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h2 class="section-title">Hizmet Bilgileri</h2>
            <table>
                <tr>
                    <th width="30%">Hizmet Paketi</th>
                    <td>{{ $package->servicePackage->name }}</td>
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

        <div class="total-section">
            <table>
                <tr>
                    <th>Net Tutar</th>
                    <td>{{ number_format($package->price - ($package->price * 0.18), 2, ',', '.') }} TL</td>
                </tr>
                <tr>
                    <th>KDV (%18)</th>
                    <td>{{ number_format($package->price * 0.18, 2, ',', '.') }} TL</td>
                </tr>
                <tr>
                    <th>Toplam Tutar</th>
                    <td>{{ number_format($package->price, 2, ',', '.') }} TL</td>
                </tr>
            </table>
            <div class="price-in-words">
                <strong>Yalnız:</strong> {{ $package->priceInWords }}
            </div>
        </div>

        <div class="footer">
            <p>Not: Bu bir bilgisayar çıktısıdır, imza gerektirmez.</p>
        </div>
    </div>
</body>
</html> 
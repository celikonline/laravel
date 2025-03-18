<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sözleşme #{{ $package->package_number }}</title>
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
        .agreement-details {
            margin-bottom: 30px;
        }
        .agreement-details table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .agreement-details th, .agreement-details td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .agreement-details th {
            background-color: #f5f5f5;
        }
        .terms {
            margin-top: 30px;
        }
        .signatures {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            border-top: 1px solid #000;
            width: 200px;
            text-align: center;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>HİZMET SÖZLEŞMESİ</h1>
        <p>Sözleşme No: {{ $package->package_number }}</p>
        <p>Tarih: {{ $package->created_at->format('d/m/Y') }}</p>
    </div>

    <div class="agreement-details">
        <h2>Taraflar</h2>
        <h3>1. Hizmet Veren (Şirket)</h3>
        <table>
            <tr>
                <th>Şirket Adı</th>
                <td>VegaAsist Yol Yardım Hizmetleri</td>
            </tr>
            <tr>
                <th>Adres</th>
                <td>İstanbul, Türkiye</td>
            </tr>
        </table>

        <h3>2. Hizmet Alan (Müşteri)</h3>
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

        <h2>Hizmet Detayları</h2>
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
            <tr>
                <th>Toplam Tutar</th>
                <td>{{ number_format($package->net_amount, 2) }} TL</td>
            </tr>
        </table>
    </div>

    <div class="terms">
        <h2>Sözleşme Şartları</h2>
        <ol>
            <li>Bu sözleşme, yukarıda belirtilen tarihler arasında geçerlidir.</li>
            <li>Hizmet kapsamı, seçilen paket türüne göre belirlenen yol yardım hizmetlerini içerir.</li>
            <li>Hizmet bedeli peşin olarak tahsil edilir.</li>
            <li>Müşteri, hizmetten faydalanmak için 7/24 çağrı merkezini arayabilir.</li>
            <li>Hizmet, Türkiye sınırları içerisinde geçerlidir.</li>
            <li>Bu sözleşme, tarafların karşılıklı rıza ve onayıyla imzalanmıştır.</li>
        </ol>
    </div>

    <div class="signatures">
        <div class="signature-box">
            <p>Hizmet Veren</p>
            <p>VegaAsist</p>
        </div>
        <div class="signature-box">
            <p>Hizmet Alan</p>
            <p>{{ $package->full_name }}</p>
        </div>
    </div>
</body>
</html> 
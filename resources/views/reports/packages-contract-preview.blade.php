<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Paket Sözleşmeleri</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        .page-break {
            page-break-after: always;
        }
        .contract {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .contract-section {
            margin-bottom: 15px;
        }
        .contract-section h5 {
            color: #333;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .vehicle-details {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>PAKET SÖZLEŞMELERİ</h2>
        <p>Tarih: {{ $date }}</p>
    </div>

    @foreach($packages as $package)
        <div class="contract">
            <div class="contract-section">
                <h5>1. TARAFLAR</h5>
                <p><strong>HİZMET VEREN:</strong> {{ config('app.name') }}</p>
                <p><strong>HİZMET ALAN:</strong> {{ $package->customer->name }}</p>
            </div>

            <div class="contract-section">
                <h5>2. KONU</h5>
                <p>İşbu sözleşme, aşağıda detayları belirtilen araç için {{ $package->servicePackage->name }} hizmetinin sağlanmasına ilişkindir.</p>
                
                <div class="vehicle-details">
                    <h6>Araç Bilgileri:</h6>
                    <p>Plaka: {{ $package->plate_city }} {{ $package->plate_letters }} {{ $package->plate_numbers }}</p>
                </div>
            </div>

            <div class="contract-section">
                <h5>3. HİZMET SÜRESİ VE BEDELİ</h5>
                <p><strong>Başlangıç Tarihi:</strong> {{ $package->start_date->format('d.m.Y') }}</p>
                <p><strong>Bitiş Tarihi:</strong> {{ $package->end_date->format('d.m.Y') }}</p>
                <p><strong>Hizmet Bedeli:</strong> {{ number_format($package->price, 2, ',', '.') }} ₺</p>
            </div>

            <div class="contract-section">
                <h5>4. GENEL HÜKÜMLER</h5>
                <p>4.1. Bu sözleşme yukarıda belirtilen tarihler arasında geçerlidir.</p>
                <p>4.2. Hizmet bedeli peşin olarak tahsil edilmiştir.</p>
                <p>4.3. Bu sözleşme taraflar arasında imzalandığı tarihte yürürlüğe girmiştir.</p>
            </div>

            <div class="footer">
                <p>Sözleşme No: {{ $package->contract_number }}</p>
            </div>
        </div>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html> 
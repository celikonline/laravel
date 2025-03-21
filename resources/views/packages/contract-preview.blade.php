<div class="contract-preview">
    <div class="text-center mb-4">
        <h4>HİZMET SÖZLEŞMESİ</h4>
        <p class="text-muted">Sözleşme No: {{ $package->contract_number }}</p>
    </div>

    <div class="contract-section mb-4">
        <h5>1. TARAFLAR</h5>
        <p><strong>HİZMET VEREN:</strong> {{ config('app.name') }}</p>
        <p><strong>HİZMET ALAN:</strong> {{ $package->customer->name }}</p>
    </div>

    <div class="contract-section mb-4">
        <h5>2. KONU</h5>
        <p>İşbu sözleşme, aşağıda detayları belirtilen araç için {{ $package->servicePackage->name }} hizmetinin sağlanmasına ilişkindir.</p>
        
        <div class="vehicle-details mt-3">
            <h6>Araç Bilgileri:</h6>
            <p>Plaka: {{ $package->plate_city }} {{ $package->plate_letters }} {{ $package->plate_numbers }}</p>
        </div>
    </div>

    <div class="contract-section mb-4">
        <h5>3. HİZMET SÜRESİ VE BEDELİ</h5>
        <p><strong>Başlangıç Tarihi:</strong> {{ $package->start_date->format('d.m.Y') }}</p>
        <p><strong>Bitiş Tarihi:</strong> {{ $package->end_date->format('d.m.Y') }}</p>
        <p><strong>Hizmet Bedeli:</strong> {{ number_format($package->price, 2, ',', '.') }} ₺</p>
    </div>

    <div class="contract-section">
        <h5>4. GENEL HÜKÜMLER</h5>
        <p>4.1. Bu sözleşme yukarıda belirtilen tarihler arasında geçerlidir.</p>
        <p>4.2. Hizmet bedeli peşin olarak tahsil edilir.</p>
        <p>4.3. Bu sözleşme taraflar arasında imzalandığı tarihte yürürlüğe girer.</p>
    </div>
</div>

<style>
.contract-preview {
    padding: 20px;
    font-size: 14px;
}

.contract-section {
    margin-bottom: 20px;
}

.contract-section h5 {
    color: #333;
    margin-bottom: 15px;
    border-bottom: 1px solid #eee;
    padding-bottom: 5px;
}

.vehicle-details {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 4px;
}
</style> 
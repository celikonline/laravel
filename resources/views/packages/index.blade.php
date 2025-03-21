<!-- resources/views/packages/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Paket Listesi</h3>
            <div class="d-flex gap-2">
                <input type="text" class="form-control" placeholder="Paket/Müşteri Ara...">
                <a href="{{ route('packages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Yeni
                </a>
                <button class="btn btn-outline-secondary">CSV</button>
                <button class="btn btn-outline-secondary">PDF</button>
                <button class="btn btn-outline-secondary">Excel</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>İşlemler</th>
                        <th>Durum</th>
                        <th>Poliçe No</th>
                        <th>Müşteri</th>
                        <th>Plaka</th>
                        <th>Servis Paketi</th>
                        <th>Ücret</th>
                        <th>Komisyon</th>
                        <th>Komisyon Oranı</th>
                        <th>Düzenleme</th>
                        <th>Başlangıç</th>
                        <th>Bitiş</th>
                        <th>Süre</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packages as $package)
                    <tr>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('packages.contract.pdf', $package->id) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="Hizmet Sözleşmesi">
                                    <i class="fas fa-file-contract"></i>
                                </a>
                                <a href="{{ route('packages.receipt.pdf', $package->id) }}" 
                                   class="btn btn-sm btn-outline-success" 
                                   title="Makbuz">
                                    <i class="fas fa-receipt"></i>
                                </a>
                            </div>
                        </td>
                        <td>
                            <div class="status-indicator {{ $package->is_active ? 'active' : 'inactive' }}"></div>
                        </td>
                        <td>{{ $package->contract_number }}</td>
                        <td>{{ $package->customer->name }}</td>
                        <td>
                            <div class="plate-container">
                                <span class="plate-text">{{ $package->plate_city }} {{ $package->plate_letters }} {{ $package->plate_numbers }}</span>
                            </div>
                        </td>
                        <td>{{ $package->servicePackage->name }}</td>
                        <td>{{ number_format($package->price, 2) }} ₺</td>
                        <td>{{ number_format($package->commission, 2) }} ₺</td>
                        <td>%{{ number_format($package->commission_rate, 2) }}</td>
                        <td>{{ $package->updated_at->format('Y-m-d') }}</td>
                        <td>{{ $package->start_date->format('Y-m-d') }}</td>
                        <td>{{ $package->end_date->format('Y-m-d') }}</td>
                        <td>{{ $package->duration }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Toplam {{ $packages->total() }} kayıttan {{ $packages->firstItem() }} - {{ $packages->lastItem() }} arası gösteriliyor
                </div>
                {{ $packages->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.status-indicator {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: inline-block;
}

.status-indicator.active {
    background: linear-gradient(45deg, #28a745, #34ce57);
    box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
}

.status-indicator.inactive {
    background: linear-gradient(45deg, #dc3545, #e4606d);
    box-shadow: 0 0 5px rgba(220, 53, 69, 0.5);
}

.table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
}

.table tr:hover {
    background-color: rgba(0,0,0,.03);
}

.card {
    box-shadow: 0 0 10px rgba(0,0,0,.1);
}

.card-header {
    background-color: #fff;
    border-bottom: 1px solid #dee2e6;
    padding: 1rem;
}

.form-control {
    min-width: 200px;
}

/* PDF butonları için ek stiller */
.btn-sm {
    padding: 0.25rem 0.5rem;
}

.btn-outline-primary:hover, 
.btn-outline-success:hover {
    color: #fff;
}

/* Plaka stili için ek CSS */
.plate-container {
    display: flex;
    align-items: center;
    background: url('../images/individual_turkish_plate.png') no-repeat center center;
    background-size: 100% 100%;
    padding: 6px 20px;
    border-radius: 4px;
    width: fit-content;
    gap: 6px;
    min-width: 140px;
    height: 32px;
}

.flag-icon {
    width: 16px;
    height: 12px;
}

.plate-text {
    color: black;
    font-weight: 600;
    font-size: 14px;
    letter-spacing: 1px;
    text-transform: uppercase;
}
</style>
@endsection
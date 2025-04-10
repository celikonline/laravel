@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Tüm Paketler</h5>
                        <div>
                            <a href="{{ route('packages.export-csv') }}" class="btn btn-outline-secondary btn-sm me-2">
                                <i class="fas fa-file-csv"></i> CSV
                            </a>
                            <a href="{{ route('packages.export-excel') }}" class="btn btn-outline-secondary btn-sm me-2">
                                <i class="fas fa-file-excel"></i> Excel
                            </a>
                            <a href="{{ route('packages.export-pdf') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Poliçe No</th>
                                    <th>Müşteri</th>
                                    <th>Plaka</th>
                                    <th>Servis Paketi</th>
                                    <th>Ücret</th>
                                    <th>Başlangıç</th>
                                    <th>Bitiş</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($packages as $package)
                                <tr>
                                    <td>{{ $package->contract_number }}</td>
                                    <td>{{ $package->customer_name }}</td>
                                    <td>
                                        <div class="plate-container">
                                            <div class="plate-box">
                                                <span class="plate-text">{{ $package->plate_city }} {{ $package->plate_letters }} {{ $package->plate_numbers }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $package->servicePackage->name }}</td>
                                    <td>{{ number_format($package->price, 2, ',', '.') }} ₺</td>
                                    <td>{{ $package->start_date->format('d.m.Y') }}</td>
                                    <td>{{ $package->end_date->format('d.m.Y') }}</td>
                                    <td>
                                        @if($package->status == 'active')
                                            <span class="badge bg-success">Aktif</span>
                                        @elseif($package->status == 'pending_payment')
                                            <span class="badge bg-warning">Ödeme Bekliyor</span>
                                        @elseif($package->status == 'expired')
                                            <span class="badge bg-danger">Süresi Dolmuş</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $package->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-sm btn-outline-primary" title="Düzenle">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('packages.contract-preview', $package->id) }}" class="btn btn-sm btn-outline-info" title="Sözleşme" target="_blank">
                                                <i class="fas fa-file-contract"></i>
                                            </a>
                                            <a href="{{ route('packages.receipt-preview', $package->id) }}" class="btn btn-sm btn-outline-secondary" title="Makbuz" target="_blank">
                                                <i class="fas fa-receipt"></i>
                                            </a>
                                            @if($package->status == 'pending_payment')
                                            <a href="{{ route('packages.payment', $package->id) }}" class="btn btn-sm btn-outline-success" title="Ödeme">
                                                <i class="fas fa-credit-card"></i>
                                            </a>
                                        @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">Paket bulunamadı.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $packages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.plate-container {
    display: inline-block;
}

.plate-box {
    background-image: url('/images/plate-bg.png');
    background-size: contain;
    background-repeat: no-repeat;
    padding: 4px 8px;
    border-radius: 4px;
    min-width: 120px;
    text-align: center;
}

.plate-text {
    font-family: 'Arial', sans-serif;
    font-weight: bold;
    color: #000;
    font-size: 14px;
}

.table {
    font-size: 0.9rem;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.8rem;
}

.badge {
    font-weight: 500;
    padding: 0.35em 0.65em;
}

.card {
    border: none;
    border-radius: 0.5rem;
}

.card-header {
    border-bottom: 1px solid rgba(0,0,0,.05);
}

.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,.02);
}

.pagination {
    margin-bottom: 0;
}

.pagination .page-link {
    border: none;
    padding: 0.5rem 0.75rem;
    margin: 0 0.25rem;
    border-radius: 0.25rem;
}

.pagination .page-item.active .page-link {
    background-color: #0d6efd;
    color: white;
}
</style>
@endsection 
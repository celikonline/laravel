@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Tüm Paketler</h2>
        <div class="btn-group">
            <a href="{{ route('packages.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Geri
            </a>
            <a href="{{ route('reports.packages.contract-preview') }}" class="btn btn-outline-info" target="_blank">
                <i class="fas fa-file-contract"></i> Sözleşme Önizleme
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-auto">
            <div class="btn-group" role="group">
                <a href="{{ route('packages.export.excel') }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
                <a href="{{ route('packages.export.pdf') }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <a href="{{ route('packages.export.csv') }}" class="btn btn-info">
                    <i class="fas fa-file-csv"></i> CSV
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
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
                                        <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($package->status == 'pending_payment')
                                            <a href="{{ route('packages.payment', $package->id) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-credit-card"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('packages.contract-preview', $package->id) }}" class="btn btn-sm btn-outline-warning" target="_blank">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <a href="{{ route('packages.receipt-preview', $package->id) }}" class="btn btn-sm btn-outline-danger" target="_blank">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <a href="{{ route('reports.packages.contract-preview') }}" class="btn btn-sm btn-outline-info" target="_blank">
                                            <i class="fas fa-file-contract"></i>
                                        </a>
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

            <div class="d-flex justify-content-end">
                {{ $packages->links() }}
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

.btn-group .btn {
    margin-right: 2px;
}
</style>
@endsection 
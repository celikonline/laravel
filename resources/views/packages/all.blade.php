@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tüm Paketler</h3>
                </div>
                <div class="card-body">
                    <!-- Filtreleme Formu -->
                    <form action="{{ route('packages.all') }}" method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-6 col-lg-2">
                                <div class="form-group">
                                    <label for="search" class="form-label">Arama</label>
                                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Sözleşme No, Müşteri, Plaka">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-2">
                                <div class="form-group">
                                    <label for="status" class="form-label">Durum</label>
                                    <select class="form-select" id="status" name="status" onchange="this.form.submit()">
                                        <option value="">Tümü</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="pending_payment" {{ request('status') == 'pending_payment' ? 'selected' : '' }}>Ödeme Bekliyor</option>
                                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Süresi Dolmuş</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Pasif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-2">
                                <div class="form-group">
                                    <label for="service_package_id" class="form-label">Servis Paketi</label>
                                    <select class="form-select" id="service_package_id" name="service_package_id">
                                        <option value="">Tümü</option>
                                        @foreach($servicePackages as $package)
                                            <option value="{{ $package->id }}" {{ request('service_package_id') == $package->id ? 'selected' : '' }}>
                                                {{ $package->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-2">
                                <div class="form-group">
                                    <label for="start_date" class="form-label">Başlangıç Tarihi</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-2">
                                <div class="form-group">
                                    <label for="end_date" class="form-label">Bitiş Tarihi</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-2">
                                <div class="form-group">
                                    <label class="form-label d-block">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary flex-grow-1">Filtrele</button>
                                        <a href="{{ route('packages.all') }}" class="btn btn-secondary">Temizle</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">
                                        <a href="{{ route('packages.all', array_merge(request()->query(), ['sort' => 'contract_number', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">
                                            Poliçe No
                                            @if(request('sort') == 'contract_number')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="text-nowrap">
                                        <a href="{{ route('packages.all', array_merge(request()->query(), ['sort' => 'customer_name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">
                                            Müşteri
                                            @if(request('sort') == 'customer_name')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="text-nowrap">
                                        <a href="{{ route('packages.all', array_merge(request()->query(), ['sort' => 'plate', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">
                                            Plaka
                                            @if(request('sort') == 'plate')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="text-nowrap">
                                        <a href="{{ route('packages.all', array_merge(request()->query(), ['sort' => 'service_package_id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">
                                            Servis Paketi
                                            @if(request('sort') == 'service_package_id')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="text-nowrap">
                                        <a href="{{ route('packages.all', array_merge(request()->query(), ['sort' => 'price', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">
                                            Ücret
                                            @if(request('sort') == 'price')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="text-nowrap">
                                        <a href="{{ route('packages.all', array_merge(request()->query(), ['sort' => 'start_date', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">
                                            Başlangıç
                                            @if(request('sort') == 'start_date')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="text-nowrap">
                                        <a href="{{ route('packages.all', array_merge(request()->query(), ['sort' => 'end_date', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">
                                            Bitiş
                                            @if(request('sort') == 'end_date')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="text-nowrap">
                                        <a href="{{ route('packages.all', array_merge(request()->query(), ['sort' => 'status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}">
                                            Durum
                                            @if(request('sort') == 'status')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="text-nowrap">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($packages as $package)
                                    <tr>
                                        <td class="text-nowrap">{{ $package->contract_number }}</td>
                                        <td class="text-nowrap">{{ $package->customer->first_name }} {{ $package->customer->last_name }}</td>
                                        <td class="text-nowrap">
                                            <div class="plate-container">
                                                <div class="plate-box">
                                                    <span class="plate-text">{{ $package->plate_city }} {{ $package->plate_letters }} {{ $package->plate_numbers }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-nowrap">{{ $package->servicePackage->name }}</td>
                                        <td class="text-nowrap">{{ number_format($package->price, 2, ',', '.') }} ₺</td>
                                        <td class="text-nowrap">{{ $package->start_date->format('d.m.Y') }}</td>
                                        <td class="text-nowrap">{{ $package->end_date->format('d.m.Y') }}</td>
                                        <td class="text-nowrap">
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
                                        <td class="text-nowrap">
                                            <div class="btn-group" role="group">
                                                @if($package->status != 'active')
                                                    <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                                @if($package->status == 'pending_payment')
                                                    <a href="{{ route('packages.payment', $package->id) }}" class="btn btn-sm btn-success active">
                                                        <i class="fas fa-credit-card"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ route('packages.contract-preview', $package->id) }}" class="btn btn-sm btn-outline-warning" target="_blank">
                                                    <i class="fas fa-file-contract"></i>
                                                </a>
                                                <a href="{{ route('packages.receipt-preview', $package->id) }}" class="btn btn-sm btn-outline-danger" target="_blank">
                                                    <i class="fas fa-receipt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $packages->withQueryString()->links() }}
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

.btn-group .btn {
    margin-right: 2px;
}

@media (max-width: 768px) {
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .btn-group {
        display: flex;
        flex-wrap: nowrap;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
}

@media (max-width: 576px) {
    .col-12 {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem;
    }
    
    .plate-box {
        min-width: 100px;
    }
    
    .plate-text {
        font-size: 12px;
    }
}
</style>
@endsection 
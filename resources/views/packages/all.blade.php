@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <div class="row g-0">
        <div class="col-12">
            <div class="card border-0 rounded-0">
                <div class="card-header">
                    <h3 class="card-title">Tüm Paketler</h3>
                </div>
                <div class="card-body">
                    <!-- Filtreleme Formu -->
                    <form action="{{ route('packages.all') }}" method="GET" class="mb-2 p-2 bg-light border rounded">
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
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>İptal Edildi</option>
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
                                            @elseif($package->status == 'cancelled')
                                                <span class="badge bg-danger">İptal Edildi</span>
                                            @elseif($package->status == 'inactive')
                                                <span class="badge bg-secondary">Pasif</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $package->status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-nowrap">
                                            <div class="btn-group" role="group">
                                                @if($package->status != 'active')
                                                    <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-sm btn-primary" title="Düzenle">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                                @if($package->status == 'pending_payment')
                                                    <a href="{{ route('packages.payment', $package->id) }}" class="btn btn-sm btn-success" title="Ödeme Yap">
                                                        <i class="fas fa-credit-card"></i>
                                                    </a>
                                                    <form action="{{ route('packages.activate', $package->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Bu paketi aktif hale getirmek istediğinizden emin misiniz?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-info" title="Aktife Çek">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('packages.cancel', $package->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Bu paketi iptal etmek istediğinizden emin misiniz?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger" title="İptal Et">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($package->status == 'cancelled')
                                                    <form action="{{ route('packages.activate', $package->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Bu iptal edilmiş paketi aktif hale getirmek istediğinizden emin misiniz?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-info" title="Aktife Çek">
                                                            <i class="fas fa-redo"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($package->status == 'active')
                                                    <form action="{{ route('packages.cancel', $package->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Bu paketi iptal etmek istediğinizden emin misiniz?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger" title="İptal Et">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('packages.contract-preview', $package->id) }}" class="btn btn-sm btn-outline-warning" target="_blank" title="Sözleşme Önizleme">
                                                    <i class="fas fa-file-contract"></i>
                                                </a>
                                                <a href="{{ route('packages.receipt-preview', $package->id) }}" class="btn btn-sm btn-outline-danger" target="_blank" title="Makbuz Önizleme">
                                                    <i class="fas fa-receipt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="card-footer bg-white border-top">
                    <div class="d-flex justify-content-center">
                        {{ $packages->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Full Width Layout */
.main-content {
    padding: 60px 0 0 0 !important;
    margin-left: var(--sidebar-width) !important;
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 0 !important;
        padding: 60px 0 0 0 !important;
    }
    
    .table-responsive {
        height: calc(100vh - 250px);
    }
    
    .card-body {
        padding: 0.5rem;
    }
    
    .form-group label {
        font-size: 0.875rem;
    }
    
    .form-control, .form-select {
        font-size: 0.875rem;
        padding: 0.375rem 0.5rem;
    }
}

.card {
    height: calc(100vh - 60px);
    overflow: hidden;
}

.card-body {
    height: calc(100% - 120px);
    overflow-y: auto;
    padding: 1rem;
}

.card-footer {
    height: 60px;
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
}

.table-responsive {
    height: calc(100vh - 200px);
    overflow-y: auto;
    border: 1px solid var(--border-color);
}

.table thead th {
    position: sticky;
    top: 0;
    background-color: var(--table-header-bg) !important;
    z-index: 10;
    border-bottom: 2px solid var(--border-color);
}

.plate-container {
    display: inline-block;
}

.plate-box {
    background-image: url('{{ asset('images/plate-bg.png') }}');
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    padding: 4px 8px;
    border-radius: 4px;
    min-width: 100px;
    max-width: 120px;
    text-align: center;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
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
    
    .table {
        min-width: 800px;
        white-space: nowrap;
    }
    
    .btn-group {
        display: flex;
        flex-wrap: nowrap;
        min-width: max-content;
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
    
    th, td {
        min-width: max-content;
        padding: 0.5rem !important;
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

/* Buton Stilleri */
.btn-group {
    display: flex;
    gap: 2px;
    flex-wrap: nowrap;
}

.btn-group .btn {
    border-radius: 4px;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

/* Pending Payment Status için özel stil */
.btn-group .btn-info {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: white;
}

.btn-group .btn-info:hover {
    background-color: #138496;
    border-color: #117a8b;
}

/* Cancel button için özel stil */
.btn-group .btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.btn-group .btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
}

/* Tooltip stilleri */
.btn[title]:hover::after {
    content: attr(title);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background-color: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 1000;
}

/* Tablo Stilleri */
.table {
    width: 100%;
    margin-bottom: 0;
}

.table td.text-nowrap,
.table th.text-nowrap {
    white-space: nowrap;
}

.table td:last-child,
.table th:last-child {
    width: 200px;
    min-width: 200px;
}

.table tbody tr:hover {
    background-color: var(--hover-bg);
}

@media (min-width: 1200px) {
    .table {
        table-layout: fixed;
    }
    
    .table td.text-nowrap {
        overflow: hidden;
        text-overflow: ellipsis;
    }
}
</style>
@endsection 
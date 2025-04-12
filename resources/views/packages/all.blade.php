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
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="search">Arama</label>
                                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Sözleşme No, Müşteri, Plaka">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="status">Durum</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">Tümü</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="pending_payment" {{ request('status') == 'pending_payment' ? 'selected' : '' }}>Ödeme Bekliyor</option>
                                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Süresi Dolmuş</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Pasif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="service_package_id">Servis Paketi</label>
                                    <select class="form-control" id="service_package_id" name="service_package_id">
                                        <option value="">Tümü</option>
                                        @foreach($servicePackages as $package)
                                            <option value="{{ $package->id }}" {{ request('service_package_id') == $package->id ? 'selected' : '' }}>
                                                {{ $package->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="start_date">Başlangıç Tarihi</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="end_date">Bitiş Tarihi</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-primary flex-grow-1">Filtrele</button>
                                        <a href="{{ route('packages.all') }}" class="btn btn-secondary ms-2">Temizle</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
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
                                @foreach($packages as $package)
                                    <tr>
                                        <td>{{ $package->contract_number }}</td>
                                        <td>{{ $package->customer->first_name }} {{ $package->customer->last_name }}</td>
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
</style>
@endsection 
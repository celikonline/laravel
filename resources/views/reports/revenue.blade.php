@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gelir Raporu</h2>
        <div class="btn-group">
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Geri
            </a>
            <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                <i class="fas fa-print"></i> Yazdır
            </button>
            <a href="#" class="btn btn-outline-success" onclick="exportToExcel()">
                <i class="fas fa-file-excel"></i> Excel
            </a>
            <a href="#" class="btn btn-outline-danger" onclick="exportToPDF()">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a href="{{ route('reports.revenue', ['send_email' => true] + request()->all()) }}" 
               class="btn btn-outline-info">
                <i class="fas fa-envelope"></i> E-posta Gönder
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filtreler -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.revenue') }}" class="row g-3" id="filterForm">
                <!-- Tarih Filtreleri -->
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Başlangıç Tarihi</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Bitiş Tarihi</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                </div>

                <!-- Durum Filtresi -->
                <div class="col-md-3">
                    <label for="status" class="form-label">Durum</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tümü</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Müşteri Filtresi -->
                <div class="col-md-3">
                    <label for="customer_id" class="form-label">Müşteri</label>
                    <select class="form-select" id="customer_id" name="customer_id">
                        <option value="">Tümü</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->first_name }} {{ $customer->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Servis Paketi Filtresi -->
                <div class="col-md-3">
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

                <!-- Fiyat Aralığı Filtreleri -->
                <div class="col-md-3">
                    <label for="min_price" class="form-label">Min. Tutar</label>
                    <input type="number" class="form-control" id="min_price" name="min_price" 
                           value="{{ request('min_price') }}" placeholder="0">
                </div>
                <div class="col-md-3">
                    <label for="max_price" class="form-label">Max. Tutar</label>
                    <input type="number" class="form-control" id="max_price" name="max_price" 
                           value="{{ request('max_price') }}" placeholder="10000">
                </div>

                <!-- Arama -->
                <div class="col-md-3">
                    <label for="search" class="form-label">Arama</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Poliçe no, müşteri adı, telefon...">
                </div>

                <!-- Butonlar -->
                <div class="col-md-3 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filtrele
                        </button>
                        <a href="{{ route('reports.revenue') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Temizle
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Özet Kartları -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Toplam Gelir</h6>
                    <h2 class="card-title mb-0">{{ number_format($totalRevenue, 2, ',', '.') }} ₺</h2>
                    <div class="mt-2 small text-success">
                        <i class="fas fa-chart-line"></i> Seçili dönem
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Ortalama Paket Tutarı</h6>
                    <h2 class="card-title mb-0">
                        {{ $revenue->count() > 0 ? number_format($totalRevenue / $revenue->count(), 2, ',', '.') : '0,00' }} ₺
                    </h2>
                    <div class="mt-2 small text-primary">
                        <i class="fas fa-calculator"></i> Paket başına
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Toplam Paket</h6>
                    <h2 class="card-title mb-0">{{ $revenue->total() }}</h2>
                    <div class="mt-2 small text-info">
                        <i class="fas fa-box"></i> Aktif paket sayısı
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gelir Detayları Tablosu -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Gelir Detayları</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <a href="{{ route('reports.revenue', array_merge(request()->all(), [
                                    'sort_by' => 'contract_number',
                                    'sort_direction' => ($sortField === 'contract_number' && $sortDirection === 'asc') ? 'desc' : 'asc'
                                ])) }}" class="text-decoration-none text-dark">
                                    Poliçe No
                                    @if($sortField === 'contract_number')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Müşteri</th>
                            <th>Paket</th>
                            <th>
                                <a href="{{ route('reports.revenue', array_merge(request()->all(), [
                                    'sort_by' => 'price',
                                    'sort_direction' => ($sortField === 'price' && $sortDirection === 'asc') ? 'desc' : 'asc'
                                ])) }}" class="text-decoration-none text-dark">
                                    Tutar
                                    @if($sortField === 'price')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('reports.revenue', array_merge(request()->all(), [
                                    'sort_by' => 'created_at',
                                    'sort_direction' => ($sortField === 'created_at' && $sortDirection === 'asc') ? 'desc' : 'asc'
                                ])) }}" class="text-decoration-none text-dark">
                                    Tarih
                                    @if($sortField === 'created_at')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Durum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($revenue as $package)
                            <tr>
                                <td>{{ $package->contract_number }}</td>
                                <td>
                                    {{ $package->customer->first_name }} {{ $package->customer->last_name }}
                                    <br>
                                    <small class="text-muted">
                                        {{ $package->customer->email }}
                                    </small>
                                </td>
                                <td>{{ $package->servicePackage->name }}</td>
                                <td>{{ number_format($package->price, 2, ',', '.') }} ₺</td>
                                <td>{{ $package->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <span class="badge bg-{{ $package->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($package->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $revenue->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Select2 için gerekli kodlar
$(document).ready(function() {
    $('#customer_id, #service_package_id').select2({
        theme: 'bootstrap-5'
    });

    // Tarih filtreleri için validasyon
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');

    startDate.addEventListener('change', function() {
        endDate.min = this.value;
    });

    endDate.addEventListener('change', function() {
        startDate.max = this.value;
    });
});

function exportToExcel() {
    alert('Excel export özelliği yakında eklenecek');
}

function exportToPDF() {
    alert('PDF export özelliği yakında eklenecek');
}
</script>
@endpush

<style>
@media print {
    .btn-group, .pagination, form {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .table {
        border: 1px solid #dee2e6;
    }
}
</style>
@endsection 
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
        </div>
    </div>

    <!-- Filtreler -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.revenue') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Başlangıç Tarihi</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Bitiş Tarihi</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filtrele
                    </button>
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
                            <th>Poliçe No</th>
                            <th>Müşteri</th>
                            <th>Paket</th>
                            <th>Tutar</th>
                            <th>Tarih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($revenue as $package)
                            <tr>
                                <td>{{ $package->contract_number }}</td>
                                <td>{{ $package->customer->first_name }} {{ $package->customer->last_name }}</td>
                                <td>{{ $package->servicePackage->name }}</td>
                                <td>{{ number_format($package->price, 2, ',', '.') }} ₺</td>
                                <td>{{ $package->created_at->format('d.m.Y H:i') }}</td>
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
function exportToExcel() {
    // Excel export işlemi için gerekli kodlar
    alert('Excel export özelliği yakında eklenecek');
}

function exportToPDF() {
    // PDF export işlemi için gerekli kodlar
    alert('PDF export özelliği yakında eklenecek');
}

// Tarih filtreleri için validasyon
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');

    startDate.addEventListener('change', function() {
        endDate.min = this.value;
    });

    endDate.addEventListener('change', function() {
        startDate.max = this.value;
    });
});
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
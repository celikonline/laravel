@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Raporlar</h2>
        <div class="btn-group">
            <a href="{{ route('reports.revenue') }}" class="btn btn-outline-primary">
                <i class="fas fa-chart-line"></i> Gelir Raporu
            </a>
            <a href="{{ route('reports.packages') }}" class="btn btn-outline-primary">
                <i class="fas fa-box"></i> Paket Raporu
            </a>
            <a href="{{ route('reports.customers') }}" class="btn btn-outline-primary">
                <i class="fas fa-users"></i> Müşteri Raporu
            </a>
        </div>
    </div>

    <!-- İstatistik Kartları -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Toplam Müşteri</h6>
                    <h2 class="card-title mb-0">{{ $customerStats['total'] }}</h2>
                    <div class="mt-2 small text-success">
                        <i class="fas fa-user-plus"></i> Bu ay: {{ $customerStats['new_this_month'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Aktif Müşteri</h6>
                    <h2 class="card-title mb-0">{{ $customerStats['active'] }}</h2>
                    <div class="mt-2 small text-primary">
                        <i class="fas fa-percentage"></i> 
                        {{ round(($customerStats['active'] / $customerStats['total']) * 100) }}% aktif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Aktif Paketler</h6>
                    <h2 class="card-title mb-0">
                        {{ $packageStatus->where('status', 'active')->first()->total ?? 0 }}
                    </h2>
                    <div class="mt-2 small text-success">
                        <i class="fas fa-check-circle"></i> Aktif durumda
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Bekleyen Ödemeler</h6>
                    <h2 class="card-title mb-0">
                        {{ $packageStatus->where('status', 'pending_payment')->first()->total ?? 0 }}
                    </h2>
                    <div class="mt-2 small text-warning">
                        <i class="fas fa-clock"></i> Ödeme bekliyor
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Gelir Grafiği -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aylık Gelir</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- En Popüler Paketler -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">En Popüler Paketler</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($popularPackages as $package)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $package->name }}</h6>
                                    <span class="badge bg-primary rounded-pill">{{ $package->packages_count }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Paket Durumu Dağılımı -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Paket Durumu Dağılımı</h5>
                </div>
                <div class="card-body">
                    <canvas id="packageStatusChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Müşteri Trendi -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Müşteri Trendi</h5>
                </div>
                <div class="card-body">
                    <canvas id="customerTrendChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gelir Grafiği
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
            datasets: [{
                label: 'Aylık Gelir (₺)',
                data: {!! json_encode($monthlyRevenue->pluck('total')) !!},
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('tr-TR', {
                                style: 'currency',
                                currency: 'TRY'
                            }).format(value);
                        }
                    }
                }
            }
        }
    });

    // Paket Durumu Grafiği
    const statusCtx = document.getElementById('packageStatusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($packageStatus->pluck('status')) !!},
            datasets: [{
                data: {!! json_encode($packageStatus->pluck('total')) !!},
                backgroundColor: [
                    'rgb(75, 192, 192)',
                    'rgb(255, 205, 86)',
                    'rgb(255, 99, 132)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Müşteri Trendi Grafiği
    const customerCtx = document.getElementById('customerTrendChart').getContext('2d');
    new Chart(customerCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($customerTrend->pluck('month')) !!},
            datasets: [{
                label: 'Yeni Müşteriler',
                data: {!! json_encode($customerTrend->pluck('total')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
@endsection 
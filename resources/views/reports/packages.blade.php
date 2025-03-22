@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Paket Raporu</h2>
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

    <!-- Özet Kartları -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Aktif Paketler</h6>
                    <h2 class="card-title mb-0">
                        {{ $statusDistribution->where('status', 'active')->first()->total ?? 0 }}
                    </h2>
                    <div class="mt-2 small">
                        <i class="fas fa-check-circle"></i> Aktif durumda
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Bekleyen Ödemeler</h6>
                    <h2 class="card-title mb-0">
                        {{ $statusDistribution->where('status', 'pending_payment')->first()->total ?? 0 }}
                    </h2>
                    <div class="mt-2 small">
                        <i class="fas fa-clock"></i> Ödeme bekliyor
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Süresi Dolmuş</h6>
                    <h2 class="card-title mb-0">
                        {{ $statusDistribution->where('status', 'expired')->first()->total ?? 0 }}
                    </h2>
                    <div class="mt-2 small">
                        <i class="fas fa-exclamation-circle"></i> Süresi geçmiş
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Toplam Paket</h6>
                    <h2 class="card-title mb-0">
                        {{ $statusDistribution->sum('total') }}
                    </h2>
                    <div class="mt-2 small">
                        <i class="fas fa-box"></i> Tüm paketler
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
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

        <!-- Aylık Paket Trendi -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aylık Paket Trendi</h5>
                </div>
                <div class="card-body">
                    <canvas id="packageTrendChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Servis Paketi Dağılımı -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Servis Paketi Dağılımı</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Paket Adı</th>
                            <th>Toplam Satış</th>
                            <th>Yüzde</th>
                            <th>Grafik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalPackages = $packageDistribution->sum('packages_count');
                        @endphp
                        @foreach($packageDistribution as $package)
                            <tr>
                                <td>{{ $package->name }}</td>
                                <td>{{ $package->packages_count }}</td>
                                <td>
                                    @php
                                        $percentage = $totalPackages > 0 ? 
                                            round(($package->packages_count / $totalPackages) * 100, 1) : 0;
                                    @endphp
                                    {{ $percentage }}%
                                </td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" role="progressbar" 
                                             style="width: {{ $percentage }}%"
                                             aria-valuenow="{{ $percentage }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Paket Durumu Grafiği
    const statusCtx = document.getElementById('packageStatusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($statusDistribution->pluck('status')->map(function($status) {
                return match($status) {
                    'active' => 'Aktif',
                    'pending_payment' => 'Ödeme Bekliyor',
                    'expired' => 'Süresi Dolmuş',
                    default => ucfirst($status)
                };
            })) !!},
            datasets: [{
                data: {!! json_encode($statusDistribution->pluck('total')) !!},
                backgroundColor: [
                    'rgb(40, 167, 69)',  // success
                    'rgb(255, 193, 7)',  // warning
                    'rgb(220, 53, 69)'   // danger
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Paket Trendi Grafiği
    const trendCtx = document.getElementById('packageTrendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($packageTrend->pluck('month')) !!},
            datasets: [{
                label: 'Yeni Paketler',
                data: {!! json_encode($packageTrend->pluck('total')) !!},
                borderColor: 'rgb(54, 162, 235)',
                tension: 0.1,
                fill: true,
                backgroundColor: 'rgba(54, 162, 235, 0.1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
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
    .btn-group {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .progress {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
}
</style>
@endsection 
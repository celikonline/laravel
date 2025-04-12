@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Finansal Rapor</h2>
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
            <a href="{{ route('reports.financial', ['send_email' => true]) }}" 
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

    <div class="row mb-4">
        <!-- Aylık Gelir Trendi -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aylık Gelir Trendi</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueTrendChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Servis Paketi Bazında Gelir Dağılımı -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Servis Paketi Bazında Gelir Dağılımı</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueDistributionChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Gelir Detayları -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Gelir Detayları</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Servis Paketi</th>
                            <th>Toplam Gelir</th>
                            <th>Yüzde</th>
                            <th>Grafik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalRevenue = $revenueDistribution->sum('packages_sum_price');
                        @endphp
                        @foreach($revenueDistribution as $service)
                            <tr>
                                <td>{{ $service->name }}</td>
                                <td>{{ number_format($service->packages_sum_price, 2, ',', '.') }} ₺</td>
                                <td>
                                    @php
                                        $percentage = $totalRevenue > 0 ? 
                                            round(($service->packages_sum_price / $totalRevenue) * 100, 1) : 0;
                                    @endphp
                                    {{ $percentage }}%
                                </td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" 
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
                    <tfoot>
                        <tr>
                            <th>Toplam</th>
                            <th>{{ number_format($totalRevenue, 2, ',', '.') }} ₺</th>
                            <th>100%</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gelir Trendi Grafiği
    const trendCtx = document.getElementById('revenueTrendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($revenueTrend->pluck('month')) !!},
            datasets: [{
                label: 'Aylık Gelir',
                data: {!! json_encode($revenueTrend->pluck('total')) !!},
                borderColor: 'rgb(40, 167, 69)',
                tension: 0.1,
                fill: true,
                backgroundColor: 'rgba(40, 167, 69, 0.1)'
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
                            return value.toLocaleString('tr-TR', {
                                style: 'currency',
                                currency: 'TRY'
                            });
                        }
                    }
                }
            }
        }
    });

    // Gelir Dağılımı Grafiği
    const distributionCtx = document.getElementById('revenueDistributionChart').getContext('2d');
    new Chart(distributionCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($revenueDistribution->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($revenueDistribution->pluck('packages_sum_price')) !!},
                backgroundColor: [
                    'rgb(40, 167, 69)',
                    'rgb(0, 123, 255)',
                    'rgb(255, 193, 7)',
                    'rgb(23, 162, 184)',
                    'rgb(108, 117, 125)'
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
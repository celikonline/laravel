@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Servis Raporu</h2>
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
            <a href="{{ route('reports.services', ['send_email' => true]) }}" 
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
        <!-- Servis Paketi Dağılımı -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Servis Paketi Dağılımı</h5>
                </div>
                <div class="card-body">
                    <canvas id="serviceDistributionChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Servis Kullanım Trendi -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Servis Kullanım Trendi</h5>
                </div>
                <div class="card-body">
                    <canvas id="serviceTrendChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Servis Paketi Detayları -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Servis Paketi Detayları</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Paket Adı</th>
                            <th>Toplam Kullanım</th>
                            <th>Yüzde</th>
                            <th>Grafik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalServices = $serviceDistribution->sum('packages_count');
                        @endphp
                        @foreach($serviceDistribution as $service)
                            <tr>
                                <td>{{ $service->name }}</td>
                                <td>{{ $service->packages_count }}</td>
                                <td>
                                    @php
                                        $percentage = $totalServices > 0 ? 
                                            round(($service->packages_count / $totalServices) * 100, 1) : 0;
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
    // Servis Dağılımı Grafiği
    const distributionCtx = document.getElementById('serviceDistributionChart').getContext('2d');
    new Chart(distributionCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($serviceDistribution->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($serviceDistribution->pluck('packages_count')) !!},
                backgroundColor: [
                    'rgb(54, 162, 235)',
                    'rgb(255, 99, 132)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)'
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

    // Servis Trendi Grafiği
    const trendCtx = document.getElementById('serviceTrendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($serviceTrend->pluck('month')) !!},
            datasets: [{
                label: 'Servis Kullanımı',
                data: {!! json_encode($serviceTrend->pluck('total')) !!},
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
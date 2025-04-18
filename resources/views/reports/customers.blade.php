@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Müşteri Raporu</h2>
        <div class="btn-group">
            <a href="{{ route('reports.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-box"></i> Genel
            </a>
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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Özet Kartları -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">En Yüksek Harcama</h6>
                    <h2 class="card-title mb-0">
                        {{ number_format($topCustomers->max('packages_sum_price'), 2, ',', '.') }} ₺
                    </h2>
                    <div class="mt-2 small">
                        <i class="fas fa-crown"></i> En yüksek müşteri harcaması
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Ortalama Paket/Müşteri</h6>
                    <h2 class="card-title mb-0">
                        {{ number_format($topCustomers->avg('packages_count'), 1) }}
                    </h2>
                    <div class="mt-2 small">
                        <i class="fas fa-calculator"></i> Müşteri başına düşen paket
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Bu Ay Yeni Müşteri</h6>
                    <h2 class="card-title mb-0">
                        {{ $registrationTrend->last()->total ?? 0 }}
                    </h2>
                    <div class="mt-2 small">
                        <i class="fas fa-user-plus"></i> Son 30 gün
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Müşteri Kayıt Trendi -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Müşteri Kayıt Trendi</h5>
                </div>
                <div class="card-body">
                    <canvas id="registrationTrendChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Müşteri Dağılımı -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Müşteri Dağılımı</h5>
                </div>
                <div class="card-body">
                    <canvas id="customerDistributionChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- En Çok Hizmet Alan Müşteriler -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">En Çok Hizmet Alan Müşteriler</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Müşteri</th>
                            <th>Toplam Paket</th>
                            <th>Toplam Harcama</th>
                            <th>Ortalama Harcama</th>
                            <th>Grafik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topCustomers as $customer)
                            <tr>
                                <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                                <td>{{ $customer->packages_count }}</td>
                                <td>{{ number_format($customer->packages_sum_price, 2, ',', '.') }} ₺</td>
                                <td>
                                    @if($customer->packages_count > 0)
                                        {{ number_format($customer->packages_sum_price / $customer->packages_count, 2, ',', '.') }} ₺
                                    @else
                                        0,00 ₺
                                    @endif
                                </td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: {{ $topCustomers->max('packages_sum_price') > 0 ? ($customer->packages_sum_price / $topCustomers->max('packages_sum_price')) * 100 : 0 }}%">
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
    // Müşteri Kayıt Trendi Grafiği
    const trendCtx = document.getElementById('registrationTrendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($registrationTrend->pluck('month')) !!},
            datasets: [{
                label: 'Yeni Müşteriler',
                data: {!! json_encode($registrationTrend->pluck('total')) !!},
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
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Müşteri Dağılımı Grafiği
    const distributionCtx = document.getElementById('customerDistributionChart').getContext('2d');
    new Chart(distributionCtx, {
        type: 'pie',
        data: {
            labels: ['1 Paket', '2-3 Paket', '4+ Paket'],
            datasets: [{
                data: [
                    {{ $topCustomers->where('packages_count', 1)->count() }},
                    {{ $topCustomers->whereBetween('packages_count', [2, 3])->count() }},
                    {{ $topCustomers->where('packages_count', '>=', 4)->count() }}
                ],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(75, 192, 192)'
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
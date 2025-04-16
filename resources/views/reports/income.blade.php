@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gelir Raporu</h2>
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
                    <h6 class="card-subtitle mb-2">Toplam Gelir</h6>
                    <h2 class="card-title mb-0">
                        {{ number_format($totalIncome, 2, ',', '.') }} ₺
                    </h2>
                    <div class="mt-2 small">
                        <i class="fas fa-money-bill-wave"></i> Tüm zamanlar
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Bu Ay Gelir</h6>
                    <h2 class="card-title mb-0">
                        {{ number_format($monthlyIncome, 2, ',', '.') }} ₺
                    </h2>
                    <div class="mt-2 small">
                        <i class="fas fa-calendar-alt"></i> Bu ay
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Ortalama Aylık Gelir</h6>
                    <h2 class="card-title mb-0">
                        {{ number_format($averageMonthlyIncome, 2, ',', '.') }} ₺
                    </h2>
                    <div class="mt-2 small">
                        <i class="fas fa-chart-line"></i> Son 12 ay
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Aylık Gelir Grafiği -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aylık Gelir Trendi</h5>
                </div>
                <div class="card-body">
                    <canvas id="incomeTrendChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Gelir Dağılımı -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Gelir Dağılımı</h5>
                </div>
                <div class="card-body">
                    <canvas id="incomeDistributionChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Gelir Tablosu -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Detaylı Gelir Raporu</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tarih</th>
                            <th>Müşteri</th>
                            <th>Paket</th>
                            <th>Tutar</th>
                            <th>Ödeme Yöntemi</th>
                            <th>Durum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>{{ $payment->created_at->format('d.m.Y') }}</td>
                                <td>{{ $payment->customer->first_name }} {{ $payment->customer->last_name }}</td>
                                <td>{{ $payment->package->name }}</td>
                                <td>{{ number_format($payment->amount, 2, ',', '.') }} ₺</td>
                                <td>{{ $payment->payment_method }}</td>
                                <td>
                                    <span class="badge bg-{{ $payment->status == 'completed' ? 'success' : 'warning' }}">
                                        {{ $payment->status == 'completed' ? 'Tamamlandı' : 'Beklemede' }}
                                    </span>
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
    // Aylık Gelir Trendi Grafiği
    const trendCtx = document.getElementById('incomeTrendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyTrend->pluck('month')) !!},
            datasets: [{
                label: 'Aylık Gelir',
                data: {!! json_encode($monthlyTrend->pluck('amount')) !!},
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
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

    // Gelir Dağılımı Grafiği
    const distributionCtx = document.getElementById('incomeDistributionChart').getContext('2d');
    new Chart(distributionCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($paymentMethods->pluck('method')) !!},
            datasets: [{
                data: {!! json_encode($paymentMethods->pluck('amount')) !!},
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
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
}
</style>
@endsection 
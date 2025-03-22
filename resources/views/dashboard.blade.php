@extends('layouts.app')

@section('content')
<div class="container">
       <!-- İstatistik Kartları -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Toplam Paket</h5>
                    <h2 class="card-text">{{ $totalPackages }}</h2>
                    <i class="fas fa-box-open fa-2x position-absolute end-0 bottom-0 mb-3 me-3 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Aktif Paket</h5>
                    <h2 class="card-text">{{ $activePackages }}</h2>
                    <i class="fas fa-check-circle fa-2x position-absolute end-0 bottom-0 mb-3 me-3 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Bekleyen Ödeme</h5>
                    <h2 class="card-text">{{ $pendingPayments }}</h2>
                    <i class="fas fa-clock fa-2x position-absolute end-0 bottom-0 mb-3 me-3 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Süresi Dolmuş</h5>
                    <h2 class="card-text">{{ $expiredPackages }}</h2>
                    <i class="fas fa-exclamation-circle fa-2x position-absolute end-0 bottom-0 mb-3 me-3 opacity-50"></i>
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

        <!-- Son İşlemler -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Son İşlemler</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($recentPackages as $package)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $package->customer->first_name }} {{ $package->customer->last_name }}</h6>
                                    <small>{{ $package->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">{{ $package->servicePackage->name }}</p>
                                <small>{{ number_format($package->price, 2, ',', '.') }} ₺</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Son Paketler Tablosu -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Son Paketler</h5>
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
                            <th>Durum</th>
                            <th>Tarih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentPackages as $package)
                            <tr>
                                <td>{{ $package->contract_number }}</td>
                                <td>{{ $package->customer->first_name }} {{ $package->customer->last_name }}</td>
                                <td>{{ $package->servicePackage->name }}</td>
                                <td>{{ number_format($package->price, 2, ',', '.') }} ₺</td>
                                <td>
                                    @if($package->status == 'active')
                                        <span class="badge bg-success">Aktif</span>
                                    @elseif($package->status == 'pending_payment')
                                        <span class="badge bg-warning">Ödeme Bekliyor</span>
                                    @elseif($package->status == 'expired')
                                        <span class="badge bg-danger">Süresi Dolmuş</span>
                                    @endif
                                </td>
                                <td>{{ $package->created_at->format('d.m.Y H:i') }}</td>
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
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    const monthlyData = @json($monthlyRevenue);
    const labels = monthlyData.map(item => {
        const [year, month] = item.month.split('-');
        return `${month}/${year}`;
    });
    const data = monthlyData.map(item => item.total);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Aylık Gelir (₺)',
                data: data,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1,
                fill: false
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
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return new Intl.NumberFormat('tr-TR', {
                                style: 'currency',
                                currency: 'TRY'
                            }).format(context.raw);
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush

<style>
.card {
    position: relative;
    overflow: hidden;
}
.card-body {
    z-index: 1;
}
.card i.fa-2x {
    z-index: 0;
}
</style>
@endsection 
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Sistem Kayıtları</h2>
    </div>

    <!-- İstatistikler -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Toplam Kayıt</h6>
                    <h2 class="card-title mb-0">{{ number_format($stats['total_logs']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Bugünkü Kayıt</h6>
                    <h2 class="card-title mb-0">{{ number_format($stats['today_logs']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Tekil Kullanıcı</h6>
                    <h2 class="card-title mb-0">{{ number_format($stats['unique_users']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Tekil IP</h6>
                    <h2 class="card-title mb-0">{{ number_format($stats['unique_ips']) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtreler -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('audit.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Başlangıç Tarihi</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Bitiş Tarihi</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="user_id" class="form-label">Kullanıcı</label>
                    <select class="form-select" id="user_id" name="user_id">
                        <option value="">Tümü</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="module" class="form-label">Modül</label>
                    <select class="form-select" id="module" name="module">
                        <option value="">Tümü</option>
                        @foreach($modules as $module)
                            <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>
                                {{ ucfirst($module) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="action" class="form-label">Eylem</label>
                    <select class="form-select" id="action" name="action">
                        <option value="">Tümü</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ ucfirst($action) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="ip_address" class="form-label">IP Adresi</label>
                    <input type="text" class="form-control" id="ip_address" name="ip_address" 
                           value="{{ request('ip_address') }}">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filtrele
                        </button>
                        <a href="{{ route('audit.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Temizle
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Kayıtlar -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tarih/Saat</th>
                            <th>Kullanıcı</th>
                            <th>Modül</th>
                            <th>Eylem</th>
                            <th>IP Adresi</th>
                            <th>Detaylar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('d.m.Y H:i:s') }}</td>
                                <td>{{ $log->user->name }}</td>
                                <td>{{ ucfirst($log->module) }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $log->action === 'view' ? 'info' : 
                                        ($log->action === 'filter' ? 'warning' : 
                                        ($log->action === 'email' ? 'success' : 'primary')) 
                                    }}">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td>{{ $log->ip_address }}</td>
                                <td>
                                    <a href="{{ route('audit.show', $log) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#user_id').select2({
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
</script>
@endpush
@endsection 
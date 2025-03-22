@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Sistem Kaydı Detayı</h2>
        <a href="{{ route('audit.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Geri
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Temel Bilgiler</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Tarih/Saat:</th>
                            <td>{{ $log->created_at->format('d.m.Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Kullanıcı:</th>
                            <td>{{ $log->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Modül:</th>
                            <td>{{ ucfirst($log->module) }}</td>
                        </tr>
                        <tr>
                            <th>Eylem:</th>
                            <td>
                                <span class="badge bg-{{ 
                                    $log->action === 'view' ? 'info' : 
                                    ($log->action === 'filter' ? 'warning' : 
                                    ($log->action === 'email' ? 'success' : 'primary')) 
                                }}">
                                    {{ ucfirst($log->action) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>IP Adresi:</th>
                            <td>{{ $log->ip_address }}</td>
                        </tr>
                        <tr>
                            <th>Tarayıcı:</th>
                            <td>{{ $log->user_agent }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            @if($log->filters)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Filtreler</h5>
                    </div>
                    <div class="card-body">
                        <pre class="mb-0"><code>{{ json_encode($log->filters, JSON_PRETTY_PRINT) }}</code></pre>
                    </div>
                </div>
            @endif

            @if($log->old_values)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Eski Değerler</h5>
                    </div>
                    <div class="card-body">
                        <pre class="mb-0"><code>{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</code></pre>
                    </div>
                </div>
            @endif

            @if($log->new_values)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Yeni Değerler</h5>
                    </div>
                    <div class="card-body">
                        <pre class="mb-0"><code>{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</code></pre>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 
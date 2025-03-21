<!-- resources/views/packages/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Paket Listesi</h3>
            <div class="d-flex gap-2">
                <input type="text" class="form-control" placeholder="Paket/Müşteri Ara...">
                <a href="{{ route('packages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Yeni
                </a>
                <a href="{{ route('packages.export.csv') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-file-csv"></i> CSV
                </a>
                <a href="{{ route('packages.export.excel') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
                <a href="{{ route('packages.export.pdf') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>İşlemler</th>
                        <th>Durum</th>
                        <th>Poliçe No</th>
                        <th>Müşteri</th>
                        <th>Plaka</th>
                        <th>Servis Paketi</th>
                        <th>Ücret</th>
                        <th>Komisyon</th>
                        <th>Komisyon Oranı</th>
                        <th>Düzenleme</th>
                        <th>Başlangıç</th>
                        <th>Bitiş</th>
                        <th>Süre</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packages as $package)
                    <tr>
                        <td>
                            <div class="d-flex gap-1">
                                <button type="button"
                                   class="btn btn-sm btn-outline-warning btn-contract" 
                                   title="Hizmet Sözleşmesi"
                                   data-contract-id="{{ $package->id }}">
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                                <button type="button"
                                   class="btn btn-sm btn-outline-danger btn-receipt" 
                                   title="Makbuz"
                                   data-receipt-id="{{ $package->id }}">
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            <div class="status-indicator {{ $package->is_active ? 'active' : 'inactive' }}"></div>
                        </td>
                        <td>{{ $package->contract_number }}</td>
                        <td>{{ $package->customer->name }}</td>
                        <td>
                            <div class="plate-container">
                                <span class="plate-text">{{ $package->plate_city }} {{ $package->plate_letters }} {{ $package->plate_numbers }}</span>
                            </div>
                        </td>
                        <td>{{ $package->servicePackage->name }}</td>
                        <td>{{ number_format($package->price, 2) }} ₺</td>
                        <td>{{ number_format($package->commission, 2) }} ₺</td>
                        <td>%{{ number_format($package->commission_rate, 2) }}</td>
                        <td>{{ $package->updated_at->format('Y-m-d') }}</td>
                        <td>{{ $package->start_date->format('Y-m-d') }}</td>
                        <td>{{ $package->end_date->format('Y-m-d') }}</td>
                        <td>{{ $package->duration }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Toplam {{ $packages->total() }} kayıttan {{ $packages->firstItem() }} - {{ $packages->lastItem() }} arası gösteriliyor
                </div>
                {{ $packages->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Hizmet Sözleşmesi Modal -->
<div class="modal fade" id="contractModal" tabindex="-1" aria-labelledby="contractModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contractModalLabel">Hizmet Sözleşmesi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body" id="contractModalBody">
                <!-- Sözleşme içeriği AJAX ile yüklenecek -->
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Yükleniyor...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                <a href="" class="btn btn-warning" id="downloadContractBtn">
                    <i class="fas fa-file-pdf"></i> PDF İndir
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Makbuz Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">Makbuz</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body" id="receiptModalBody">
                <!-- Makbuz içeriği AJAX ile yüklenecek -->
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Yükleniyor...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                <a href="" class="btn btn-danger" id="downloadReceiptBtn">
                    <i class="fas fa-file-pdf"></i> PDF İndir
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.status-indicator {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: inline-block;
}

.status-indicator.active {
    background: linear-gradient(45deg, #28a745, #34ce57);
    box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
}

.status-indicator.inactive {
    background: linear-gradient(45deg, #dc3545, #e4606d);
    box-shadow: 0 0 5px rgba(220, 53, 69, 0.5);
}

.table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
}

.table tr:hover {
    background-color: rgba(0,0,0,.03);
}

.card {
    box-shadow: 0 0 10px rgba(0,0,0,.1);
}

.card-header {
    background-color: #fff;
    border-bottom: 1px solid #dee2e6;
    padding: 1rem;
}

.form-control {
    min-width: 200px;
}

/* PDF butonları için ek stiller */
.btn-sm {
    padding: 0.25rem 0.5rem;
}

.btn-outline-warning:hover, 
.btn-outline-danger:hover {
    color: #fff;
}

/* Plaka stili için ek CSS */
.plate-container {
    display: flex;
    align-items: center;
    background: url('../images/individual_turkish_plate.png') no-repeat center center;
    background-size: 100% 100%;
    padding: 6px 20px;
    border-radius: 4px;
    width: fit-content;
    gap: 6px;
    min-width: 140px;
    height: 32px;
}

.flag-icon {
    width: 16px;
    height: 12px;
}

.plate-text {
    color: black;
    font-weight: 600;
    font-size: 14px;
    letter-spacing: 1px;
    text-transform: uppercase;
}

/* Modal styles */
.modal-lg {
    max-width: 800px;
}

.modal-body {
    max-height: 70vh;
    overflow-y: auto;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    // Contract modal handling
    $('.btn-contract').click(function() {
        var contractId = $(this).data('contract-id');
        var modal = $('#contractModal');
        
        // Show loading spinner
        modal.find('.modal-body').html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Yükleniyor...</span></div></div>');
        
        // Update download button href
        $('#downloadContractBtn').attr('href', '/packages/' + contractId + '/contract/pdf');
        
        // Show modal
        modal.modal('show');
        
        // Load contract preview
        $.get('/packages/' + contractId + '/contract-preview', function(response) {
            modal.find('.modal-body').html(response);
        }).fail(function() {
            modal.find('.modal-body').html('<div class="alert alert-danger">Sözleşme yüklenirken bir hata oluştu.</div>');
        });
    });

    // Receipt modal handling
    $('.btn-receipt').click(function() {
        var receiptId = $(this).data('receipt-id');
        var modal = $('#receiptModal');
        
        // Show loading spinner
        modal.find('.modal-body').html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Yükleniyor...</span></div></div>');
        
        // Update download button href
        $('#downloadReceiptBtn').attr('href', '/packages/' + receiptId + '/receipt/pdf');
        
        // Show modal
        modal.modal('show');
        
        // Load receipt preview
        $.get('/packages/' + receiptId + '/receipt-preview', function(response) {
            modal.find('.modal-body').html(response);
        }).fail(function() {
            modal.find('.modal-body').html('<div class="alert alert-danger">Makbuz yüklenirken bir hata oluştu.</div>');
        });
    });
});
</script>
@endpush
@endsection
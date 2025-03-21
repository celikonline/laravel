@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Paketler</h4>
                    <a href="{{ route('packages.create') }}" class="btn btn-primary">Yeni Paket Ekle</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Sözleşme No</th>
                                    <th>Durumu</th>
                                    <th>Müşteri</th>
                                    <th>Araç</th>
                                    <th>Servis Paketi</th>
                                    <th>Ücret</th>
                                    <th>Komisyon</th>
                                    <th>Komisyon Oranı</th>
                                    <th>Ödeme zamanı</th>
                                    <th>Başlangıç</th>
                                    <th>Bitiş</th>
                                    <th>Süre</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($packages as $package)
                                <tr>
                                    <td>{{ $package->id }}</td>
                                    <td>{{ $package->contract_number }}</td>
                                    <td>
                                        <span class="badge {{ $package->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $package->status === 'active' ? 'Aktif' : 'Pasif' }}
                                        </span>
                                    </td>
                                    <td>{{ $package->first_name }}</td>
                                    <td>{{ $package->Plate }}</td>
                                    <td>{{ $package->package_name }}</td>
                                    <td>{{ number_format($package->price, 2) }} ₺</td>
                                    <td>{{ number_format($package->commission, 2) }} ₺</td>
                                    <td>{{ $package->commission_rate }}%</td>
                                    <td>{{ $package->payment_date }}</td>
                                    <td>{{ $package->start_date }}</td>
                                    <td>{{ $package->end_date }}</td>
                                    <td>{{ $package->duration }} gün</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('packages.show', $package->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('packages.destroy', $package->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bu paketi silmek istediğinizden emin misiniz?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-3">
                        {{ $packages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush 
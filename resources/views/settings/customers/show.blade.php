@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Müşteri Detayı</h5>
                    <div>
                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Düzenle
                        </a>
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Geri
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Ad</h6>
                            <p>{{ $customer->first_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Soyad</h6>
                            <p>{{ $customer->last_name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>E-posta</h6>
                            <p>{{ $customer->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Telefon</h6>
                            <p>{{ $customer->phone }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Durum</h6>
                            <p>
                                @if($customer->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Pasif</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Araç Sayısı</h6>
                            <p>{{ $customer->vehicles_count ?? 0 }}</p>
                        </div>
                    </div>

                    @if($customer->vehicles_count > 0)
                        <div class="mt-4">
                            <h5>Araçları</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Plaka</th>
                                            <th>Marka</th>
                                            <th>Model</th>
                                            <th>Yıl</th>
                                            <th>Durum</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($customer->vehicles as $vehicle)
                                            <tr>
                                                <td>{{ $vehicle->plate_number }}</td>
                                                <td>{{ $vehicle->brand->name }}</td>
                                                <td>{{ $vehicle->model->name }}</td>
                                                <td>{{ $vehicle->model_year }}</td>
                                                <td>
                                                    @if($vehicle->is_active)
                                                        <span class="badge bg-success">Aktif</span>
                                                    @else
                                                        <span class="badge bg-danger">Pasif</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
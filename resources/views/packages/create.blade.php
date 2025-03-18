// resources/views/packages/create.blade.php
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Paket Oluştur</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('packages.store') }}">
                        @csrf
                        
                        <!-- Paket Seçimi -->
                        <div class="card mb-4">
                            <div class="card-header">1. Paket Seçimi</div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Servis Paketi</label>
                                    <select name="package_type_id" class="form-control @error('package_type_id') is-invalid @enderror">
                                        <option value="">Seçiniz</option>
                                        @foreach($packageTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('package_type_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Müşteri Bilgileri -->
                        <div class="card mb-4">
                            <div class="card-header">2. Müşteri Bilgileri</div>
                            <div class="card-body">
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="is_individual" name="is_individual" value="1" checked>
                                    <label class="form-check-label">Bireysel Müşteri</label>
                                </div>

                                <div id="individual-form">
                                    <div class="form-group">
                                        <label>Ad</label>
                                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror">
                                        @error('first_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Soyad</label>
                                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror">
                                        @error('last_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>TC Kimlik No</label>
                                        <input type="text" name="identity_number" class="form-control @error('identity_number') is-invalid @enderror">
                                        @error('identity_number')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div id="corporate-form" style="display: none;">
                                    <div class="form-group">
                                        <label>Şirket Adı</label>
                                        <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror">
                                        @error('company_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Vergi No</label>
                                        <input type="text" name="tax_number" class="form-control @error('tax_number') is-invalid @enderror">
                                        @error('tax_number')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Telefon</label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror">
                                    @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>E-posta</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror">
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Araç Bilgileri -->
                        <div class="card mb-4">
                            <div class="card-header">3. Araç Bilgileri</div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Plaka Tipi</label>
                                    <select name="plate_type" class="form-control @error('plate_type') is-invalid @enderror">
                                        <option value="">Seçiniz</option>
                                        @foreach($plateTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('plate_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Plaka</label>
                                    <input type="text" name="plate" class="form-control @error('plate') is-invalid @enderror">
                                    @error('plate')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Marka</label>
                                    <select name="vehicle_brand" class="form-control @error('vehicle_brand') is-invalid @enderror">
                                        <option value="">Seçiniz</option>
                                        @foreach($vehicleBrands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('vehicle_brand')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Model</label>
                                    <select name="vehicle_model" class="form-control @error('vehicle_model') is-invalid @enderror">
                                        <option value="">Seçiniz</option>
                                    </select>
                                    @error('vehicle_model')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Model Yılı</label>
                                    <input type="number" name="vehicle_model_year" class="form-control @error('vehicle_model_year') is-invalid @enderror">
                                    @error('vehicle_model_year')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Kaydet ve Ödeme Sayfasına Geç</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Bireysel/Kurumsal form değişimi
    $('#is_individual').change(function() {
        if($(this).is(':checked')) {
            $('#individual-form').show();
            $('#corporate-form').hide();
        } else {
            $('#individual-form').hide();
            $('#corporate-form').show();
        }
    });

    // Marka seçimine göre model listesini güncelle
    $('select[name="vehicle_brand"]').change(function() {
        var brandId = $(this).val();
        if(brandId) {
            $.get('/api/vehicle-models/' + brandId, function(data) {
                var modelSelect = $('select[name="vehicle_model"]');
                modelSelect.empty();
                modelSelect.append('<option value="">Seçiniz</option>');
                $.each(data, function(key, value) {
                    modelSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            });
        }
    });
});
</script>
@endpush
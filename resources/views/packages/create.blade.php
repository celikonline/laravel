// resources/views/packages/create.blade.php
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Paket Seçimi</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('packages.store') }}">
                        @csrf
                        <!-- 1. Paket Seçimi -->
                        <div class="mb-4">
                            <h5>1. Paket Seçimi</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Servis Paketi*</label>
                                        <select class="form-select" name="service_package_id" required>
                                            <option value="">Servis Paketi Seçiniz</option>
                                            @foreach($servicePackages as $package)
                                                <option value="{{ $package->id }}" data-duration="{{ $package->duration_days }}">{{ $package->name }} - {{ $package->price }} TL</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Paket Başlangıç Tarihi*</label>
                                        <input type="date" class="form-control" name="start_date" required value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Paket Bitiş Tarihi*</label>
                                        <input type="date" class="form-control" name="end_date" readonly required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Poliçe Süresi(Gün)*</label>
                                        <input type="number" class="form-control" name="policy_duration" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Müşteri Bilgileri -->
                        <div class="mb-4">
                            <h5>2. Müşteri Bilgileri</h5>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="customer_type" id="bireysel" value="individual" checked>
                                        <label class="form-check-label" for="bireysel">Bireysel Müşteri</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="customer_type" id="kurumsal" value="corporate">
                                        <label class="form-check-label" for="kurumsal">Kurumsal Müşteri</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>TC Kimlik No*</label>
                                        <input type="text" class="form-control" name="identity_number" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Adı*</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>İl*</label>
                                        <select class="form-select" name="city_id" required>
                                            <option value="">İl Seçiniz</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>İlçe*</label>
                                        <select class="form-select" name="district_id" required>
                                            <option value="">İlçe Seçiniz</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Telefon*</label>
                                        <input type="tel" class="form-control" name="phone" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Araç Bilgileri -->
                        <div class="mb-4">
                            <h5>3. Araç Bilgileri</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Araç Plaka Tipi*</label>
                                        <select class="form-select" name="plate_type" required>
                                            <option value="">Plaka Tipi Seçiniz</option>
                                            <option value="normal">Normal</option>
                                            <option value="special">Özel</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Araç Plakası*</label>
                                        <input type="text" class="form-control" name="plate_number" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Araç Markası*</label>
                                        <select class="form-select" name="brand_id" required>
                                            <option value="">Araç Markası Seçiniz</option>
                                            @foreach($vehicleBrands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Araç Modeli*</label>
                                        <select class="form-select" name="model_id" required>
                                            <option value="">Araç Modeli Seçiniz</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Araç Model Yılı*</label>
                                        <select class="form-select" name="model_year" required>
                                            <option value="">Araç Model Yılı Seçiniz</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Kaydet ve ödeme sayfasına geç -->
                        <div class="mb-4">
                            <h5>4. Kaydet ve ödeme sayfasına geç</h5>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    KVKK Metni'ni okudum ve onaylıyorum
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="agreement" id="agreement" required>
                                <label class="form-check-label" for="agreement">
                                    İstisnaları Satış Sözleşmesi'ni okudum ve onaylıyorum
                                </label>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Kaydet ve Ödeme Sayfasına Geç</button>
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
    // Bugünün tarihini başlangıç tarihine set et
    var today = new Date().toISOString().split('T')[0];
    $('input[name="start_date"]').val(today);

    // Servis paketi seçildiğinde süreyi ve bitiş tarihini ayarla
    $('select[name="service_package_id"]').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var durationDays = selectedOption.data('duration');
        var startDate = new Date($('input[name="start_date"]').val());
        
        if(durationDays && startDate) {
            // Poliçe süresini set et
            $('input[name="policy_duration"]').val(durationDays);
            
            // Bitiş tarihini hesapla
            var endDate = new Date(startDate);
            endDate.setDate(endDate.getDate() + durationDays);
            var formattedEndDate = endDate.toISOString().split('T')[0];
            $('input[name="end_date"]').val(formattedEndDate);
        }
    });

    // Başlangıç tarihi değiştiğinde bitiş tarihini güncelle
    $('input[name="start_date"]').on('change', function() {
        var selectedOption = $('select[name="service_package_id"]').find('option:selected');
        var durationDays = selectedOption.data('duration');
        var startDate = new Date($(this).val());
        
        if(durationDays && startDate) {
            // Bitiş tarihini hesapla
            var endDate = new Date(startDate);
            endDate.setDate(endDate.getDate() + durationDays);
            var formattedEndDate = endDate.toISOString().split('T')[0];
            $('input[name="end_date"]').val(formattedEndDate);
        }
    });

    // İl seçildiğinde ilçeleri getir
    $('select[name="city_id"]').on('change', function() {
        var cityId = $(this).val();
        if(cityId) {
            $.get('/packages/districts/' + cityId, function(data) {
                var districtSelect = $('select[name="district_id"]');
                districtSelect.empty();
                districtSelect.append('<option value="">İlçe Seçiniz</option>');
                $.each(data, function(key, value) {
                    districtSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            });
        }
    });

    // Marka seçildiğinde modelleri getir
    $('select[name="brand_id"]').on('change', function() {
        var brandId = $(this).val();
        if(brandId) {
            $.get('/packages/vehicle-models/' + brandId, function(data) {
                var modelSelect = $('select[name="model_id"]');
                modelSelect.empty();
                modelSelect.append('<option value="">Araç Modeli Seçiniz</option>');
                $.each(data, function(key, value) {
                    modelSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            });
        }
    });
});
</script>
@endpush
@endsection
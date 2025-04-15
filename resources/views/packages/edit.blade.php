@extends('layouts.app')

@section('content')
<style>
.bg-custom-gray {
    background-color: #f0f0f0 !important;
}
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Paket Düzenleme</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('packages.update', $package->id) }}" id="packageEditForm">
                        @csrf
                        @method('PUT')
                        
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <!-- 1. Paket Seçimi -->
                        <div class="mb-4">
                            <h5>1. Paket Seçimi</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Servis Paketi*</label>
                                        <select class="form-select" name="service_package_id" required>
                                            <option value="">Servis Paketi Seçiniz</option>
                                            @foreach($servicePackages as $servicePackage)
                                                <option value="{{ $servicePackage->id }}" 
                                                    data-duration="{{ $servicePackage->duration_days }}"
                                                    {{ $package->service_package_id == $servicePackage->id ? 'selected' : '' }}>
                                                    {{ $servicePackage->name }} - {{ $servicePackage->price }} TL
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Paket Başlangıç Tarihi*</label>
                                        <input type="date" class="form-control" name="start_date" required 
                                            value="{{ $package->start_date->format('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Paket Bitiş Tarihi*</label>
                                        <input type="date" class="form-control bg-custom-gray" name="end_date" readonly required
                                            value="{{ $package->end_date->format('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Poliçe Süresi(Gün)*</label>
                                        <input type="number" class="form-control bg-custom-gray" name="policy_duration" readonly
                                            value="{{ $package->duration }}">
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
                                        <input class="form-check-input" type="radio" name="customer_type" id="bireysel" 
                                            value="individual" {{ $package->customer->type === 'individual' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="bireysel">Bireysel Müşteri</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="customer_type" id="kurumsal" 
                                            value="corporate" {{ $package->customer->type === 'corporate' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="kurumsal">Kurumsal Müşteri</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label id="identity_label">{{ $package->customer->type === 'corporate' ? 'Vergi Kimlik No*' : 'TC Kimlik No*' }}</label>
                                        <input type="text" class="form-control" name="identity_number" required
                                            value="{{ $package->customer->identity_number }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3" id="name_row">
                                <div class="col-md-{{ $package->customer->type === 'corporate' ? '12' : '6' }}">
                                    <div class="form-group">
                                        <label id="first_name_label">{{ $package->customer->type === 'corporate' ? 'Şirket Adı*' : 'Adı*' }}</label>
                                        <input type="text" class="form-control" name="first_name" required
                                            value="{{ $package->customer->first_name }}">
                                    </div>
                                </div>
                                @if($package->customer->type === 'individual')
                                <div class="col-md-6" id="last_name_container">
                                    <div class="form-group">
                                        <label>Soyadı*</label>
                                        <input type="text" class="form-control" name="last_name" required
                                            value="{{ $package->customer->last_name }}">
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>İl*</label>
                                        <select class="form-select" name="city_id" required>
                                            <option value="">İl Seçiniz</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}" 
                                                    {{ $package->customer->city_id == $city->id ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>İlçe*</label>
                                        <select class="form-select" name="district_id" required>
                                            <option value="">İlçe Seçiniz</option>
                                            @foreach($districts as $district)
                                                <option value="{{ $district->id }}" 
                                                    {{ $package->customer->district_id == $district->id ? 'selected' : '' }}>
                                                    {{ $district->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Telefon*</label>
                                        <input type="tel" class="form-control" name="phone" required
                                            value="{{ $package->customer->phone_number }}">
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
                                            @foreach($plateTypes as $plateType)
                                                <option value="{{ $plateType->id }}" 
                                                    {{ $package->plate_type == $plateType->id ? 'selected' : '' }}>
                                                    {{ $plateType->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Araç Plakası*</label>
                                        <div class="d-flex gap-2">
                                            <input type="text" class="form-control" name="plate_city" placeholder="İl" maxlength="2" 
                                                style="width: 70px;" required value="{{ $package->plate_city }}">
                                            <input type="text" class="form-control" name="plate_letters" placeholder="Harf" maxlength="3" 
                                                style="width: 90px;" required value="{{ $package->plate_letters }}">
                                            <input type="text" class="form-control" name="plate_numbers" placeholder="Sayı" maxlength="4" 
                                                style="width: 90px;" required value="{{ $package->plate_numbers }}">
                                            <input type="hidden" name="plate_number" value="{{ $package->plate_number }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Araç Markası*</label>
                                        <select class="form-select" name="brand_id" required>
                                            <option value="">Araç Markası Seçiniz</option>
                                            @foreach($vehicleBrands as $brand)
                                                <option value="{{ $brand->id }}" 
                                                    {{ $package->brand_id == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
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
                                            @foreach($vehicleModels as $model)
                                                <option value="{{ $model->id }}" 
                                                    {{ $package->model_id == $model->id ? 'selected' : '' }}>
                                                    {{ $model->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Araç Model Yılı*</label>
                                        <select class="form-select" name="model_year" required>
                                            <option value="">Araç Model Yılı Seçiniz</option>
                                            @php
                                                $currentYear = date('Y');
                                                for($year = $currentYear; $year >= $currentYear - 30; $year--) {
                                                    $selected = $package->model_year == $year ? 'selected' : '';
                                                    echo "<option value='{$year}' {$selected}>{$year}</option>";
                                                }
                                            @endphp
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Kaydet -->
                        <div class="mb-4">
                            <button type="submit" class="btn btn-primary w-100">Değişiklikleri Kaydet</button>
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
    // Müşteri tipi değiştiğinde form alanlarını güncelle
    $('input[name="customer_type"]').on('change', function() {
        var customerType = $(this).val();
        
        if(customerType === 'corporate') {
            // Kurumsal müşteri seçildiğinde
            $('#identity_label').text('Vergi Kimlik No*');
            $('#first_name_label').text('Şirket Adı*');
            $('#last_name_container').hide();
            $('input[name="last_name"]').prop('required', false);
            $('.col-md-6:first', '#name_row').removeClass('col-md-6').addClass('col-md-12');
        } else {
            // Bireysel müşteri seçildiğinde
            $('#identity_label').text('TC Kimlik No*');
            $('#first_name_label').text('Adı*');
            $('#last_name_container').show();
            $('input[name="last_name"]').prop('required', true);
            $('.col-md-12', '#name_row').removeClass('col-md-12').addClass('col-md-6');
        }
    });

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
            endDate.setDate(endDate.getDate() + durationDays-1);
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

    // Plaka formatını düzenle
    function formatPlate() {
        var city = $('input[name="plate_city"]').val().toUpperCase();
        var letters = $('input[name="plate_letters"]').val().toUpperCase();
        var numbers = $('input[name="plate_numbers"]').val();
        
        // Sadece sayıları kabul et
        city = city.replace(/[^0-9]/g, '');
        // Sadece harfleri kabul et
        letters = letters.replace(/[^A-Z]/g, '');
        // Sadece sayıları kabul et
        numbers = numbers.replace(/[^0-9]/g, '');

        $('input[name="plate_city"]').val(city);
        $('input[name="plate_letters"]').val(letters);
        $('input[name="plate_numbers"]').val(numbers);

        // Birleştirilmiş plaka
        var fullPlate = city + ' ' + letters + ' ' + numbers;
        $('input[name="plate_number"]').val(fullPlate.trim());
    }

    // Her input değiştiğinde formatı güncelle
    $('input[name="plate_city"], input[name="plate_letters"], input[name="plate_numbers"]').on('input', formatPlate);

    // Form gönderilmeden önce son bir kez format kontrolü yap
    $('form').on('submit', function(e) {
        e.preventDefault(); // Önce form gönderimini durdur
        
        formatPlate();
        
        var city = $('input[name="plate_city"]').val();
        var letters = $('input[name="plate_letters"]').val();
        var numbers = $('input[name="plate_numbers"]').val();

        if (city.length < 2 || letters.length < 1 || numbers.length < 1) {
            alert('Lütfen geçerli bir plaka numarası giriniz.');
            return false;
        }

        // Tüm validasyonlar başarılı ise formu gönder
        console.log('Form gönderiliyor...');
        this.submit();
    });

    // Hata mesajlarını göster
    @if($errors->any())
        var errorMessages = [];
        @foreach($errors->all() as $error)
            errorMessages.push("{{ $error }}");
        @endforeach
        alert('Hata(lar):\n' + errorMessages.join('\n'));
    @endif

    // Başarı mesajını göster
    @if(session('success'))
        alert("{{ session('success') }}");
    @endif

    // Hata mesajını göster
    @if(session('error'))
        alert("{{ session('error') }}");
    @endif
});
</script>
@endpush
@endsection 
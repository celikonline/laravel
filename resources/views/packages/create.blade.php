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
                    <h4>Paket Seçimi</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('packages.store') }}" id="packageCreateForm">
                        @csrf

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
                                            @foreach($servicePackages as $package)
                                                <option value="{{ $package->id }}" 
                                                    data-duration="{{ $package->duration_days }}"
                                                    data-description="{{ $package->description }}">
                                                    {{ $package->name }} - {{ $package->price }} TL
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
                                        <input type="date" class="form-control" name="start_date" required value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Paket Bitiş Tarihi*</label>
                                        <input type="date" class="form-control bg-custom-gray" name="end_date" readonly required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Poliçe Süresi(Gün)*</label>
                                        <input type="number" class="form-control bg-custom-gray" name="policy_duration" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="package-description alert alert-info d-none">
                                        <!-- Paket açıklaması buraya gelecek -->
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label id="identity_label">TC Kimlik No*</label>
                                        <input type="text" class="form-control" name="identity_number" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3" id="name_row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label id="first_name_label">Adı*</label>
                                        <input type="text" class="form-control" name="first_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6" id="last_name_container">
                                    <div class="form-group">
                                        <label>Soyadı*</label>
                                        <input type="text" class="form-control" name="last_name" required>
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
                                        <input type="tel" class="form-control" name="phone_number" required>
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
                                                <option value="{{ $plateType->id }}">{{ $plateType->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Araç Plakası*</label>
                                        <div class="d-flex gap-2">
                                            <input type="text" class="form-control" name="plate_city" placeholder="İl" maxlength="2" style="width: 70px;" required>
                                            <input type="text" class="form-control" name="plate_letters" placeholder="Harf" maxlength="3" style="width: 90px;" required>
                                            <input type="text" class="form-control" name="plate_numbers" placeholder="Sayı" maxlength="4" style="width: 90px;" required>
                                            <input type="hidden" name="plate_number">
                                        </div>
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
                                            @php
                                                $currentYear = date('Y');
                                                for($year = $currentYear; $year >= $currentYear - 30; $year--) {
                                                    echo "<option value='{$year}'>{$year}</option>";
                                                }
                                            @endphp
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
    // TC Kimlik No Doğrulama Fonksiyonu
    function validateTCKN(value) {
        value = value.toString();
        if (value.length !== 11) return false;
        if (value[0] === '0') return false;
        
        let oddSum = 0;
        let evenSum = 0;
        let total = 0;
        
        for (let i = 0; i < 9; i++) {
            if (i % 2 === 0) {
                oddSum += parseInt(value[i]);
            } else {
                evenSum += parseInt(value[i]);
            }
            total += parseInt(value[i]);
        }
        
        let digit10 = (oddSum * 7 - evenSum) % 10;
        let digit11 = (total + digit10) % 10;
        
        return (digit10 === parseInt(value[9]) && digit11 === parseInt(value[10]));
    }

    // Vergi Kimlik No Doğrulama Fonksiyonu
    function validateVKN(value) {
        value = value.toString();
        if (value.length !== 10) return false;
        
        // VKN must be numeric
        if (!/^\d+$/.test(value)) return false;
        
        return true;
    }

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
            
            // VKN için input event listener
            $('input[name="identity_number"]').off('input').on('input', function() {
                let value = $(this).val().replace(/[^0-9]/g, '');
                $(this).val(value);
                
                if (value.length > 0) {
                    if (!validateVKN(value)) {
                        $(this).addClass('is-invalid');
                        if (!$(this).next('.invalid-feedback').length) {
                            $(this).after('<div class="invalid-feedback">Geçersiz Vergi Kimlik Numarası</div>');
                        }
                    } else {
                        $(this).removeClass('is-invalid').addClass('is-valid');
                        $(this).next('.invalid-feedback').remove();
                    }
                }
            });
        } else {
            // Bireysel müşteri seçildiğinde
            $('#identity_label').text('TC Kimlik No*');
            $('#first_name_label').text('Adı*');
            $('#last_name_container').show();
            $('input[name="last_name"]').prop('required', true);
            $('.col-md-12', '#name_row').removeClass('col-md-12').addClass('col-md-6');
            
            // TCKN için input event listener
            $('input[name="identity_number"]').off('input').on('input', function() {
                let value = $(this).val().replace(/[^0-9]/g, '');
                $(this).val(value);
                
                if (value.length > 0) {
                    if (!validateTCKN(value)) {
                        $(this).addClass('is-invalid');
                        if (!$(this).next('.invalid-feedback').length) {
                            $(this).after('<div class="invalid-feedback">Geçersiz TC Kimlik Numarası</div>');
                        }
                    } else {
                        $(this).removeClass('is-invalid').addClass('is-valid');
                        $(this).next('.invalid-feedback').remove();
                    }
                }
            });
        }
    });

    // Servis paketi seçildiğinde
    $('select[name="service_package_id"]').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var description = selectedOption.data('description');
        var duration = selectedOption.data('duration');
        var packageDescription = $('.package-description');
        
        // Açıklama varsa göster, yoksa gizle
        if(description) {
            packageDescription.html(description).removeClass('d-none');
        } else {
            packageDescription.addClass('d-none');
        }

        // Süre varsa bitiş tarihini hesapla
        if(duration) {
            var startDate = $('input[name="start_date"]').val();
            if(startDate) {
                var endDate = new Date(startDate);
                endDate.setDate(endDate.getDate() + parseInt(duration));
                
                // Bitiş tarihini formata çevir (YYYY-MM-DD)
                var formattedEndDate = endDate.toISOString().split('T')[0];
                
                $('input[name="end_date"]').val(formattedEndDate);
                $('input[name="policy_duration"]').val(duration);
            }
        } else {
            $('input[name="end_date"]').val('');
            $('input[name="policy_duration"]').val('');
        }
    });

    // Başlangıç tarihi değiştiğinde
    $('input[name="start_date"]').on('change', function() {
        var selectedOption = $('select[name="service_package_id"]').find('option:selected');
        var duration = selectedOption.data('duration');
        
        if(duration) {
            var startDate = $(this).val();
            var endDate = new Date(startDate);
            endDate.setDate(endDate.getDate() + parseInt(duration));
            
            // Bitiş tarihini formata çevir (YYYY-MM-DD)
            var formattedEndDate = endDate.toISOString().split('T')[0];
            
            $('input[name="end_date"]').val(formattedEndDate);
            $('input[name="policy_duration"]').val(duration);
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
        
        // Kimlik/VKN kontrolü
        var customerType = $('input[name="customer_type"]:checked').val();
        var identityNumber = $('input[name="identity_number"]').val();
        var isIdentityValid = customerType === 'corporate' ? validateVKN(identityNumber) : validateTCKN(identityNumber);
        
        if (!isIdentityValid) {
            alert(customerType === 'corporate' ? 'Lütfen geçerli bir Vergi Kimlik Numarası giriniz.' : 'Lütfen geçerli bir TC Kimlik Numarası giriniz.');
            return false;
        }

        // Plaka kontrolü
        formatPlate();
        
        var city = $('input[name="plate_city"]').val();
        var letters = $('input[name="plate_letters"]').val();
        var numbers = $('input[name="plate_numbers"]').val();

        if (city.length < 2 || letters.length < 1 || numbers.length < 1) {
            alert('Lütfen geçerli bir plaka numarası giriniz.');
            return false;
        }

        // Sözleşme kontrolü
        if (!$('#terms').is(':checked')) {
            alert('Lütfen KVKK Metnini okuyup onaylayınız.');
            return false;
        }

        if (!$('#agreement').is(':checked')) {
            alert('Lütfen İstisnaları Satış Sözleşmesini okuyup onaylayınız.');
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
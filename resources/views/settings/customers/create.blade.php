@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Yeni Müşteri</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('customers.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">Ad</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Soyad</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="identity_number" class="form-label">TC Kimlik No</label>
                                    <input type="text" class="form-control @error('identity_number') is-invalid @enderror" id="identity_number" name="identity_number" value="{{ old('identity_number') }}">
                                    @error('identity_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tax_number" class="form-label">Vergi No</label>
                                    <input type="text" class="form-control @error('tax_number') is-invalid @enderror" id="tax_number" name="tax_number" value="{{ old('tax_number') }}">
                                    @error('tax_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-posta</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">Telefon</label>
                                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                                    @error('phone_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Adres</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="city_id" class="form-label">İl</label>
                                    <select class="form-control @error('city_id') is-invalid @enderror" id="city_id" name="city_id" required>
                                        <option value="">Seçiniz</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('city_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="district_id" class="form-label">İlçe</label>
                                    <select class="form-control @error('district_id') is-invalid @enderror" id="district_id" name="district_id" required>
                                        <option value="">Önce il seçiniz</option>
                                    </select>
                                    @error('district_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="postal_code" class="form-label">Posta Kodu</label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
                                    @error('postal_code')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_type" class="form-label">Müşteri Tipi</label>
                                    <select class="form-control @error('customer_type') is-invalid @enderror" id="customer_type" name="customer_type">
                                        <option value="">Seçiniz</option>
                                        <option value="individual" {{ old('customer_type') == 'individual' ? 'selected' : '' }}>Bireysel</option>
                                        <option value="corporate" {{ old('customer_type') == 'corporate' ? 'selected' : '' }}>Kurumsal</option>
                                    </select>
                                    @error('customer_type')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row" id="corporateFields" style="display: none;">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_name" class="form-label">Firma Adı</label>
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name') }}">
                                    @error('company_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tax_office" class="form-label">Vergi Dairesi</label>
                                    <input type="text" class="form-control @error('tax_office') is-invalid @enderror" id="tax_office" name="tax_office" value="{{ old('tax_office') }}">
                                    @error('tax_office')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notlar</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Aktif</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Geri
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const customerType = document.getElementById('customer_type');
        const corporateFields = document.getElementById('corporateFields');
        const citySelect = document.getElementById('city_id');
        const districtSelect = document.getElementById('district_id');

        // Müşteri tipi değiştiğinde kurumsal alanları göster/gizle
        function toggleCorporateFields() {
            if (customerType.value === 'corporate') {
                corporateFields.style.display = 'flex';
            } else {
                corporateFields.style.display = 'none';
            }
        }

        // İl değiştiğinde ilçeleri getir
        function loadDistricts() {
            const cityId = citySelect.value;
            districtSelect.innerHTML = '<option value="">İlçe seçiniz</option>';
            
            if (cityId) {
                fetch(`/api/districts/city/${cityId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(result => {
                        if (result.success && Array.isArray(result.data)) {
                            result.data.forEach(district => {
                                const option = document.createElement('option');
                                option.value = district.id;
                                option.textContent = district.name;
                                if (district.id == '{{ old('district_id') }}') {
                                    option.selected = true;
                                }
                                districtSelect.appendChild(option);
                            });
                        } else {
                            throw new Error(result.message || 'Invalid response format');
                        }
                    })
                    .catch(error => {
                        console.error('İlçeler yüklenirken hata oluştu:', error);
                        districtSelect.innerHTML = '<option value="">İlçeler yüklenemedi</option>';
                    });
            }
        }

        customerType.addEventListener('change', toggleCorporateFields);
        citySelect.addEventListener('change', loadDistricts);
        
        // Sayfa yüklendiğinde
        toggleCorporateFields();
        if (citySelect.value) {
            loadDistricts();
        }
    });
</script>
@endpush
@endsection 
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_package_id' => 'required|exists:service_packages,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'customer_type' => 'required|in:individual,corporate',
            'identity_number' => 'required|string',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'plate_type' => 'required|in:normal,special',
            'plate_number' => 'required|string|unique:vehicles,plate_number',
            'brand_id' => 'required|exists:vehicle_brands,id',
            'model_id' => 'required|exists:vehicle_models,id',
            'model_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'terms' => 'required|accepted',
            'agreement' => 'required|accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'service_package_id.required' => 'Lütfen bir servis paketi seçin.',
            'start_date.required' => 'Başlangıç tarihi gereklidir.',
            'end_date.required' => 'Bitiş tarihi gereklidir.',
            'end_date.after' => 'Bitiş tarihi başlangıç tarihinden sonra olmalıdır.',
            'customer_type.required' => 'Müşteri tipi gereklidir.',
            'identity_number.required' => 'TC Kimlik/Vergi No gereklidir.',
            'name.required' => 'Ad Soyad/Firma Adı gereklidir.',
            'phone.required' => 'Telefon numarası gereklidir.',
            'city_id.required' => 'Lütfen il seçin.',
            'district_id.required' => 'Lütfen ilçe seçin.',
            'plate_type.required' => 'Plaka tipi gereklidir.',
            'plate_number.required' => 'Plaka numarası gereklidir.',
            'plate_number.unique' => 'Bu plaka numarası zaten kayıtlı.',
            'brand_id.required' => 'Lütfen araç markası seçin.',
            'model_id.required' => 'Lütfen araç modeli seçin.',
            'model_year.required' => 'Model yılı gereklidir.',
            'terms.required' => 'Lütfen kullanım koşullarını kabul edin.',
            'agreement.required' => 'Lütfen sözleşmeyi kabul edin.',
        ];
    }
} 
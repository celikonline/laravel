<?php 

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleModelRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'vehicle_brand_id' => 'integer|exists:vehicle_brands,id',
            'name' => 'string|max:255',
            'code' => 'string|max:50|unique:vehicle_models,code,' . $this->route('vehicle_model'),
            'is_active' => 'boolean',
            'is_deleted' => 'boolean'
        ];
    }
} 
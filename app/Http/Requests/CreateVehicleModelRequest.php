<?php 
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVehicleModelRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'vehicle_brand_id' => 'required|integer|exists:vehicle_brands,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:vehicle_models,code',
            'is_active' => 'boolean',
            'is_deleted' => 'boolean'
        ];
    }
} 
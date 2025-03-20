<?php 
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVehicleBrandRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:vehicle_brands,code',
            'is_active' => 'boolean',
            'is_deleted' => 'boolean'
        ];
    }
} 
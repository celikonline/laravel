<?php 
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVehicleModelYearRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'vehicle_model_id' => 'required|integer|exists:vehicle_models,id',
            'year' => [
                'required',
                'integer',
                'min:1900',
                'max:' . (date('Y') + 1),
                'unique:vehicle_model_years,year,NULL,id,vehicle_model_id,' . $this->vehicle_model_id
            ],
            'is_active' => 'boolean',
            'is_deleted' => 'boolean'
        ];
    }
} 
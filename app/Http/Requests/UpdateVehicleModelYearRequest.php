namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleModelYearRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'vehicle_model_id' => 'integer|exists:vehicle_models,id',
            'year' => [
                'integer',
                'min:1900',
                'max:' . (date('Y') + 1),
                'unique:vehicle_model_years,year,' . $this->route('vehicle_model_year') . ',id,vehicle_model_id,' . ($this->vehicle_model_id ?? $this->route('vehicle_model_year')->vehicle_model_id)
            ],
            'is_active' => 'boolean',
            'is_deleted' => 'boolean'
        ];
    }
} 
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleBrandRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'string|max:255',
            'code' => 'string|max:50|unique:vehicle_brands,code,' . $this->route('vehicle_brand'),
            'is_active' => 'boolean',
            'is_deleted' => 'boolean'
        ];
    }
} 
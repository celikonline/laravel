namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePackageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'policy_date' => 'nullable|date',
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'identity_number' => 'nullable|string|max:20',
            'tax_office' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'vehicle_brand' => 'required|integer|exists:vehicle_brands,id',
            'vehicle_model' => 'required|integer|exists:vehicle_models,id',
            'vehicle_model_year' => 'required|integer',
            'plate_city' => 'nullable|string|max:50',
            'plate_letters' => 'nullable|string|max:10',
            'plate_numbers' => 'nullable|string|max:10',
            'city_id' => 'nullable|integer|exists:cities,id',
            'district_id' => 'nullable|integer|exists:districts,id',
            'company_name' => 'nullable|string|max:200',
            'plate_type' => 'required|integer|exists:plate_types,id',
            'is_individual' => 'required|boolean',
            'plate' => 'nullable|string',
            'package_type_id' => 'required|integer|exists:package_types,id',
            'email' => 'nullable|email'
        ];
    }
} 
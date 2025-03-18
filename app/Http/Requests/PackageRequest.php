// app/Http/Requests/PackageRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'package_type_id' => 'required|exists:package_types,id',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_individual' => 'required|boolean',
            'first_name' => 'required_if:is_individual,true',
            'last_name' => 'required_if:is_individual,true',
            'company_name' => 'required_if:is_individual,false',
            'identity_number' => 'required_if:is_individual,true',
            'tax_number' => 'required_if:is_individual,false',
            'phone' => 'required',
            'email' => 'required|email',
            'plate_type' => 'required|exists:plate_types,id',
            'plate' => 'required',
            'vehicle_brand' => 'required|exists:vehicle_brands,id',
            'vehicle_model' => 'required|exists:vehicle_models,id',
            'vehicle_model_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
        ];
    }
}
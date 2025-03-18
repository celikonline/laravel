namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDistrictRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'city_id' => 'required|integer|exists:cities,id',
            'is_active' => 'boolean'
        ];
    }
} 
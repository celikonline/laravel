namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'string|max:255',
            'code' => 'string|max:10|unique:cities,code,' . $this->route('id'),
            'is_active' => 'boolean'
        ];
    }
} 
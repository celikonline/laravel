namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateServicePackageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'is_deleted' => 'boolean'
        ];
    }
} 
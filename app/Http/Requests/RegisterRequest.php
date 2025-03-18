namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|string|max:50|min:3|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|max:100',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
        ];
    }
} 
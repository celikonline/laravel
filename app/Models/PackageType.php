namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PackageType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'duration_in_days',
        'base_price',
        'is_recurring',
        'recurring_period',
        'discount',
        'is_active'
    ];

    protected $casts = [
        'base_price' => 'float',
        'discount' => 'float',
        'is_recurring' => 'boolean',
        'is_active' => 'boolean',
        'duration_in_days' => 'integer',
        'recurring_period' => 'integer'
    ];

    public function packages()
    {
        return $this->hasMany(Package::class);
    }
} 
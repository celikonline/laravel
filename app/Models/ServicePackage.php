namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_days',
        'is_active',
        'is_deleted'
    ];

    protected $casts = [
        'price' => 'float',
        'duration_days' => 'integer',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean'
    ];

    // Packages that belong to this service package
    public function packages()
    {
        return $this->hasMany(Package::class);
    }
} 
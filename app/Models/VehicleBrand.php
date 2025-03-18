namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleBrand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'is_active',
        'is_deleted'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_deleted' => 'boolean'
    ];

    // Vehicle models belonging to this brand
    public function vehicleModels()
    {
        return $this->hasMany(VehicleModel::class);
    }

    // Packages using this brand
    public function packages()
    {
        return $this->hasMany(Package::class);
    }
} 
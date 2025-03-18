namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_brand_id',
        'name',
        'code',
        'is_active',
        'is_deleted'
    ];

    protected $casts = [
        'vehicle_brand_id' => 'integer',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean'
    ];

    // Brand this model belongs to
    public function vehicleBrand()
    {
        return $this->belongsTo(VehicleBrand::class);
    }

    // Packages using this model
    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    // Vehicle model years for this model
    public function modelYears()
    {
        return $this->hasMany(VehicleModelYear::class);
    }
} 
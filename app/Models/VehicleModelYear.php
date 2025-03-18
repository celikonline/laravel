namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModelYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_model_id',
        'year',
        'is_active',
        'is_deleted'
    ];

    protected $casts = [
        'vehicle_model_id' => 'integer',
        'year' => 'integer',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean'
    ];

    // Model this year belongs to
    public function vehicleModel()
    {
        return $this->belongsTo(VehicleModel::class);
    }

    // Packages using this model year
    public function packages()
    {
        return $this->hasMany(Package::class);
    }
} 
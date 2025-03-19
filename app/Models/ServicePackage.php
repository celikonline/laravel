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
        'duration',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration' => 'integer',
        'is_active' => 'boolean',
    ];

    // Packages that belong to this service package
    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_package_services')
            ->withPivot('quantity')
            ->withTimestamps();
    }
} 
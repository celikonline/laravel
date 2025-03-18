namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_number',
        'amount',
        'discount',
        'status',
        'start_date',
        'end_date',
        'policy_date',
        'first_name',
        'last_name',
        'identity_number',
        'tax_office',
        'phone',
        'vehicle_brand',
        'vehicle_model',
        'vehicle_model_year',
        'plate_city',
        'plate_letters',
        'plate_numbers',
        'city_id',
        'district_id',
        'company_name',
        'plate_type',
        'is_individual',
        'plate',
        'package_type_id',
        'is_active',
        'is_deleted',
        'credit_card_number',
        'credit_cart_owner',
        'three_d_collection',
        'email',
        'net_amount',
        'kdv'
    ];

    protected $casts = [
        'amount' => 'float',
        'discount' => 'float',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'policy_date' => 'datetime',
        'is_individual' => 'boolean',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'net_amount' => 'float',
        'kdv' => 'float'
    ];

    protected $appends = [
        'full_name',
        'formatted_plate',
        'duration'
    ];

    public function packageType()
    {
        return $this->belongsTo(PackageType::class);
    }

    public function vehicleBrand()
    {
        return $this->belongsTo(VehicleBrand::class, 'vehicle_brand');
    }

    public function vehicleModel()
    {
        return $this->belongsTo(VehicleModel::class, 'vehicle_model');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function getFullNameAttribute()
    {
        if ($this->is_individual) {
            return $this->first_name . ' ' . $this->last_name;
        }
        return $this->company_name;
    }

    public function getFormattedPlateAttribute()
    {
        return sprintf('%s %s %s', 
            $this->plate_city ?? '', 
            $this->plate_letters ?? '', 
            $this->plate_numbers ?? ''
        );
    }

    public function getDurationAttribute()
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }
        return $this->start_date->diffInDays($this->end_date);
    }
} 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'service_package_id',
        'contract_number',
        'start_date',
        'end_date',
        'price',
        'commission',
        'commission_rate',
        'status',
        'payment_date',
        'duration',
        'is_active',
        // Araç bilgileri
        'plate_number',
        'plate_city',
        'plate_letters',
        'plate_numbers',
        'plate_type',
        'brand_id',
        'model_id',
        'model_year',
        'phone_number',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'payment_date' => 'datetime',
        'price' => 'decimal:2',
        'commission' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'duration' => 'integer',
        'is_active' => 'boolean',
        'model_year' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function servicePackage()
    {
        return $this->belongsTo(ServicePackage::class);
    }

    public function vehicleBrand()
    {
        return $this->belongsTo(VehicleBrand::class, 'brand_id');
    }

    public function vehicleModel()
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }

    public function plateType()
    {
        return $this->belongsTo(PlateType::class, 'plate_type');
    }

    public function getCustomerNameAttribute()
    {
        if (!$this->customer) {
            return '-';
        }

        if ($this->customer->customer_type === 'corporate') {
            return $this->customer->first_name; // Şirket adı first_name alanında
        }

        return $this->customer->first_name . ' ' . $this->customer->last_name;
    }
} 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'vehicle_id',
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
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function servicePackage()
    {
        return $this->belongsTo(ServicePackage::class);
    }
} 
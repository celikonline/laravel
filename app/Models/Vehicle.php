<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'plate_number',
        'plate_city',
        'plate_letters',
        'plate_numbers',
        'plate_type',
        'brand_id',
        'model_id',
        'model_year',
        'is_active',
    ];

    protected $casts = [
        'model_year' => 'integer',
        'is_active' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function brand()
    {
        return $this->belongsTo(VehicleBrand::class);
    }

    public function model()
    {
        return $this->belongsTo(VehicleModel::class);
    }

    public function plateType()
    {
        return $this->belongsTo(PlateType::class, 'plate_type');
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }
} 
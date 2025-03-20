<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModelYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_id',
        'year',
        'is_active'
    ];

    protected $casts = [
        'year' => 'integer',
        'is_active' => 'boolean'
    ];

    // Model this year belongs to
    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }

    // Packages using this model year
    public function packages()
    {
        return $this->hasMany(Package::class);
    }
} 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function servicePackages()
    {
        return $this->belongsToMany(ServicePackage::class, 'service_package_services')
            ->withPivot('quantity')
            ->withTimestamps();
    }
} 
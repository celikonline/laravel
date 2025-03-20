<?php

namespace App\Repositories;

use App\Models\PackageType;

class PackageTypeRepository extends BaseRepository
{
    public function __construct(PackageType $model)
    {
        parent::__construct($model);
    }

    public function all()
    {
        return $this->model
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }
} 
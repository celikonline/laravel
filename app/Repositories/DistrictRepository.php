<?php

namespace App\Repositories;

use App\Models\District;

class DistrictRepository extends BaseRepository
{
    public function __construct(District $model)
    {
        parent::__construct($model);
    }

    public function getByCityId(int $cityId)
    {
        return $this->model->where('city_id', $cityId)->get();
    }

    public function getActiveByCityId(int $cityId)
    {
        return $this->model
            ->where('city_id', $cityId)
            ->where('is_active', true)
            ->get();
    }
} 
<?php

namespace App\Repositories;

use App\Models\City;

class CityRepository extends BaseRepository
{
    public function __construct(City $model)
    {
        parent::__construct($model);
    }

    public function getActive()
    {
        return $this->model->where('is_active', true)->get();
    }

    public function findByCode(string $code)
    {
        return $this->model->where('code', $code)->firstOrFail();
    }
} 
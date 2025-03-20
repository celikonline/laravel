<?php

namespace App\Repositories;

use App\Models\Service;

class ServiceRepository extends BaseRepository
{
    public function __construct(Service $model)
    {
        parent::__construct($model);
    }

    public function getAllPaginated(int $perPage = 10)
    {
        return $this->model->paginate($perPage);
    }

    public function findByName(string $name)
    {
        return $this->model->where('name', $name)->first();
    }

    public function getActiveServices()
    {
        return $this->model->where('is_active', true)->get();
    }
} 
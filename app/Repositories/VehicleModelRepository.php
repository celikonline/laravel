<?php

namespace App\Repositories;

use App\Models\VehicleModel;

class VehicleModelRepository extends BaseRepository
{
    public function __construct(VehicleModel $model)
    {
        parent::__construct($model);
    }

    public function getAllActive()
    {
        return $this->model
            ->with('vehicleBrand')
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->orderBy('name')
            ->get();
    }

    public function findById(int $id)
    {
        return $this->model
            ->with('vehicleBrand')
            ->where('id', $id)
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->firstOrFail();
    }

    public function findByBrandId(int $brandId)
    {
        return $this->model
            ->where('vehicle_brand_id', $brandId)
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->orderBy('name')
            ->get();
    }

    public function findByCode(string $code)
    {
        return $this->model
            ->with('vehicleBrand')
            ->where('code', $code)
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->firstOrFail();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $model = $this->findById($id);
        $model->update($data);
        return $model;
    }

    public function delete(int $id)
    {
        $model = $this->findById($id);
        $model->update(['is_deleted' => true]);
        return true;
    }
} 
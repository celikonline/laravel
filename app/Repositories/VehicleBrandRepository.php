<?php 
namespace App\Repositories;

use App\Models\VehicleBrand;

class VehicleBrandRepository extends BaseRepository
{
    public function __construct(VehicleBrand $model)
    {
        parent::__construct($model);
    }

    public function getAllActive()
    {
        return $this->model
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->orderBy('name')
            ->get();
    }

    public function findById(int $id)
    {
        return $this->model
            ->where('id', $id)
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->firstOrFail();
    }

    public function findByCode(string $code)
    {
        return $this->model
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
        $brand = $this->findById($id);
        $brand->update($data);
        return $brand;
    }

    public function delete(int $id)
    {
        $brand = $this->findById($id);
        $brand->update(['is_deleted' => true]);
        return true;
    }
} 
<?php 

namespace App\Repositories;

use App\Models\VehicleModelYear;

class VehicleModelYearRepository extends BaseRepository
{
    public function __construct(VehicleModelYear $model)
    {
        parent::__construct($model);
    }

    public function getAllActive()
    {
        return $this->model
            ->with(['vehicleModel.vehicleBrand'])
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->orderBy('year', 'desc')
            ->get();
    }

    public function findById(int $id)
    {
        return $this->model
            ->with(['vehicleModel.vehicleBrand'])
            ->where('id', $id)
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->firstOrFail();
    }

    public function findByModelId(int $modelId)
    {
        return $this->model
            ->where('vehicle_model_id', $modelId)
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->orderBy('year', 'desc')
            ->get();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $modelYear = $this->findById($id);
        $modelYear->update($data);
        return $modelYear;
    }

    public function delete(int $id)
    {
        $modelYear = $this->findById($id);
        $modelYear->update(['is_deleted' => true]);
        return true;
    }
} 
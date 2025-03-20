<?php

namespace App\Repositories;

use App\Models\ServicePackage;
use Illuminate\Support\Facades\DB;

class ServicePackageRepository extends BaseRepository
{
    public function __construct(ServicePackage $model)
    {
        parent::__construct($model);
    }

    public function getAllPaginated(int $perPage = 10)
    {
        return $this->model->with('services')->paginate($perPage);
    }

    public function findWithServices($id)
    {
        return $this->model->with('services')->find($id);
    }

    public function createWithServices(array $data)
    {
        try {
            DB::beginTransaction();

            $servicePackage = $this->create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'duration' => $data['duration'],
                'is_active' => $data['is_active'] ?? true,
            ]);

            if (isset($data['services'])) {
                $services = collect($data['services'])->mapWithKeys(function ($service) {
                    return [$service['id'] => ['quantity' => $service['quantity']]];
                })->toArray();

                $servicePackage->services()->attach($services);
            }

            DB::commit();
            return $servicePackage;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateWithServices($id, array $data)
    {
        try {
            DB::beginTransaction();

            $servicePackage = $this->update($id, [
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'duration' => $data['duration'],
                'is_active' => $data['is_active'] ?? true,
            ]);

            if (isset($data['services'])) {
                $services = collect($data['services'])->mapWithKeys(function ($service) {
                    return [$service['id'] => ['quantity' => $service['quantity']]];
                })->toArray();

                $servicePackage->services()->sync($services);
            }

            DB::commit();
            return $servicePackage;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteWithServices($id)
    {
        try {
            DB::beginTransaction();

            $servicePackage = $this->find($id);
            $servicePackage->services()->detach();
            $result = $this->delete($id);

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getActivePackages()
    {
        return $this->model->where('is_active', true)
            ->with('services')
            ->get();
    }
} 
<?php

namespace App\Services;

use App\Models\ServicePackage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ServicePackageService
{
    public function __construct(protected ServicePackage $servicePackage)
    {
    }

    public function getAll(): Collection
    {
        return $this->servicePackage->where('is_active', true)->get();
    }

    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->servicePackage->with('services')->paginate($perPage);
    }

    public function findById(int $id): ?ServicePackage
    {
        return $this->servicePackage->with('services')->find($id);
    }

    public function create(array $data): ServicePackage
    {
        try {
            DB::beginTransaction();

            $servicePackage = $this->servicePackage->create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'duration' => $data['duration'],
                'is_active' => $data['is_active'] ?? true,
            ]);

            $services = collect($data['services'])->mapWithKeys(function ($service) {
                return [$service['id'] => ['quantity' => $service['quantity']]];
            })->toArray();

            $servicePackage->services()->attach($services);

            DB::commit();
            return $servicePackage;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            DB::beginTransaction();

            $servicePackage = $this->findById($id);
            
            if (!$servicePackage) {
                return false;
            }

            $servicePackage->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? $servicePackage->description,
                'price' => $data['price'],
                'duration' => $data['duration'],
                'is_active' => $data['is_active'] ?? $servicePackage->is_active,
            ]);

            if (isset($data['services'])) {
                $services = collect($data['services'])->mapWithKeys(function ($service) {
                    return [$service['id'] => ['quantity' => $service['quantity']]];
                })->toArray();

                $servicePackage->services()->sync($services);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {
            DB::beginTransaction();

            $servicePackage = $this->findById($id);
            
            if (!$servicePackage) {
                return false;
            }

            $servicePackage->services()->detach();
            $result = $servicePackage->delete();

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
} 
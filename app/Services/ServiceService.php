<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ServiceService
{
    public function __construct(protected Service $service)
    {
    }

    public function getAll(): Collection
    {
        return $this->service->where('is_active', true)->get();
    }

    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->service->paginate($perPage);
    }

    public function findById(int $id): ?Service
    {
        return $this->service->find($id);
    }

    public function create(array $data): Service
    {
        return $this->service->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $service = $this->findById($id);
        
        if (!$service) {
            return false;
        }

        return $service->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? $service->description,
            'is_active' => $data['is_active'] ?? $service->is_active,
        ]);
    }

    public function delete(int $id): bool
    {
        $service = $this->findById($id);
        
        if (!$service) {
            return false;
        }

        return $service->delete();
    }
} 
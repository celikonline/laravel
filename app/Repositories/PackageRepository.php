// app/Repositories/PackageRepository.php
namespace App\Repositories;

use App\Models\Package;

class PackageRepository extends BaseRepository
{
    public function __construct(Package $model)
    {
        parent::__construct($model);
    }

    public function getAllPaginated(int $pageNumber, int $pageSize)
    {
        $query = $this->model
            ->with(['packageType', 'vehicleBrand', 'vehicleModel', 'city', 'district'])
            ->orderBy('created_at', 'desc');

        $total = $query->count();
        $items = $query->skip(($pageNumber - 1) * $pageSize)
                      ->take($pageSize)
                      ->get();

        return [
            'data' => $items,
            'pageNumber' => $pageNumber,
            'pageSize' => $pageSize,
            'totalCount' => $total,
            'totalPages' => ceil($total / $pageSize),
            'hasPreviousPage' => $pageNumber > 1,
            'hasNextPage' => $pageNumber < ceil($total / $pageSize)
        ];
    }

    public function findByCustomerId(int $customerId)
    {
        return $this->model
            ->where('customer_id', $customerId)
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->get();
    }

    public function findByVehicleId(int $vehicleId)
    {
        return $this->model
            ->where('vehicle_id', $vehicleId)
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->get();
    }

    public function findWithRelations(int $id)
    {
        return $this->model
            ->with(['packageType', 'vehicleBrand', 'vehicleModel', 'city', 'district'])
            ->where('id', $id)
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->firstOrFail();
    }
}
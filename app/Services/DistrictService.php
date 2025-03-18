namespace App\Services;

use App\Repositories\DistrictRepository;
use Exception;

class DistrictService
{
    protected $districtRepository;

    public function __construct(DistrictRepository $districtRepository)
    {
        $this->districtRepository = $districtRepository;
    }

    public function getAllDistricts()
    {
        return $this->districtRepository->all();
    }

    public function getDistrictById(int $id)
    {
        return $this->districtRepository->find($id);
    }

    public function createDistrict(array $data)
    {
        return $this->districtRepository->create($data);
    }

    public function updateDistrict(int $id, array $data)
    {
        return $this->districtRepository->update($id, $data);
    }

    public function deleteDistrict(int $id)
    {
        return $this->districtRepository->delete($id);
    }

    public function getDistrictsByCityId(int $cityId)
    {
        return $this->districtRepository->getByCityId($cityId);
    }

    public function getActiveDistrictsByCityId(int $cityId)
    {
        return $this->districtRepository->getActiveByCityId($cityId);
    }
} 
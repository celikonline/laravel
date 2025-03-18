namespace App\Services;

use App\Repositories\CityRepository;
use Exception;

class CityService
{
    protected $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function getAllCities()
    {
        return $this->cityRepository->all();
    }

    public function getActiveCities()
    {
        return $this->cityRepository->getActive();
    }

    public function getCityById(int $id)
    {
        return $this->cityRepository->find($id);
    }

    public function getCityByCode(string $code)
    {
        return $this->cityRepository->findByCode($code);
    }

    public function createCity(array $data)
    {
        return $this->cityRepository->create($data);
    }

    public function updateCity(int $id, array $data)
    {
        return $this->cityRepository->update($id, $data);
    }

    public function deleteCity(int $id)
    {
        return $this->cityRepository->delete($id);
    }
} 
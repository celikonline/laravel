// app/Services/PackageService.php
namespace App\Services;

use App\Repositories\PackageRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class PackageService
{
    protected $packageRepository;

    public function __construct(PackageRepository $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    public function getAllPackages(int $pageNumber, int $pageSize)
    {
        return $this->packageRepository->getAllPaginated($pageNumber, $pageSize);
    }

    public function createPackage(array $data)
    {
        try {
            DB::beginTransaction();

            // Paket numarası oluştur
            $data['package_number'] = 'PKG-' . date('Ymd') . '-' . rand(1000, 9999);
            
            // KDV hesapla
            $data['kdv'] = $data['amount'] * 0.18;
            $data['net_amount'] = $data['amount'] + $data['kdv'];

            // Plaka formatını düzenle
            if (isset($data['plateCity']) && isset($data['plateLetters']) && isset($data['plateNumbers'])) {
                $data['plate'] = sprintf('%s %s %s', 
                    $data['plateCity'], 
                    $data['plateLetters'], 
                    $data['plateNumbers']
                );
            }

            $package = $this->packageRepository->create($data);

            if (isset($data['services'])) {
                $package->services()->attach($data['services']);
            }

            DB::commit();
            return $package;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getPackagesByCustomerId(int $customerId)
    {
        return $this->packageRepository->findByCustomerId($customerId);
    }

    public function getPackagesByVehicleId(int $vehicleId)
    {
        return $this->packageRepository->findByVehicleId($vehicleId);
    }
}
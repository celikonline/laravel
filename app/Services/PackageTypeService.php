namespace App\Services;

use App\Repositories\PackageTypeRepository;

class PackageTypeService
{
    protected $packageTypeRepository;

    public function __construct(PackageTypeRepository $packageTypeRepository)
    {
        $this->packageTypeRepository = $packageTypeRepository;
    }

    public function getAllPackageTypes()
    {
        return $this->packageTypeRepository->all();
    }
} 
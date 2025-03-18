namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PackageService;
use App\Http\Requests\CreatePackageRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PackagesController extends Controller
{
    protected $packageService;

    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    public function index(Request $request): JsonResponse
    {
        $pageNumber = $request->input('pageNumber', 1);
        $pageSize = $request->input('pageSize', 10);
        
        $packages = $this->packageService->getAllPackages($pageNumber, $pageSize);
        return response()->json($packages);
    }

    public function store(CreatePackageRequest $request): JsonResponse
    {
        $package = $this->packageService->createPackage($request->validated());
        return response()->json($package, 201);
    }

    public function getByCustomerId(int $customerId): JsonResponse
    {
        $packages = $this->packageService->getPackagesByCustomerId($customerId);
        return response()->json($packages);
    }

    public function getByVehicleId(int $vehicleId): JsonResponse
    {
        $packages = $this->packageService->getPackagesByVehicleId($vehicleId);
        return response()->json($packages);
    }
} 
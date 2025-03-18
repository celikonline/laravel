namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PackageTypeService;
use Illuminate\Http\JsonResponse;

class PackageTypesController extends Controller
{
    protected $packageTypeService;

    public function __construct(PackageTypeService $packageTypeService)
    {
        $this->packageTypeService = $packageTypeService;
    }

    public function index(): JsonResponse
    {
        $packageTypes = $this->packageTypeService->getAllPackageTypes();
        return response()->json($packageTypes);
    }
} 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateServicePackageRequest;
use App\Http\Requests\UpdateServicePackageRequest;
use App\Repositories\ServicePackageRepository;
use Illuminate\Http\JsonResponse;

class ServicePackageController extends Controller
{
    protected $servicePackageRepository;

    public function __construct(ServicePackageRepository $servicePackageRepository)
    {
        $this->servicePackageRepository = $servicePackageRepository;
    }

    public function index(): JsonResponse
    {
        $servicePackages = $this->servicePackageRepository->getAllActive();
        return response()->json($servicePackages);
    }

    public function store(CreateServicePackageRequest $request): JsonResponse
    {
        $servicePackage = $this->servicePackageRepository->create($request->validated());
        return response()->json($servicePackage, 201);
    }

    public function show(int $id): JsonResponse
    {
        $servicePackage = $this->servicePackageRepository->findById($id);
        return response()->json($servicePackage);
    }

    public function update(UpdateServicePackageRequest $request, int $id): JsonResponse
    {
        $servicePackage = $this->servicePackageRepository->update($id, $request->validated());
        return response()->json($servicePackage);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->servicePackageRepository->delete($id);
        return response()->json(null, 204);
    }
} 
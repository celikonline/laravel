namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateVehicleBrandRequest;
use App\Http\Requests\UpdateVehicleBrandRequest;
use App\Repositories\VehicleBrandRepository;
use Illuminate\Http\JsonResponse;

class VehicleBrandController extends Controller
{
    protected $vehicleBrandRepository;

    public function __construct(VehicleBrandRepository $vehicleBrandRepository)
    {
        $this->vehicleBrandRepository = $vehicleBrandRepository;
    }

    public function index(): JsonResponse
    {
        $brands = $this->vehicleBrandRepository->getAllActive();
        return response()->json($brands);
    }

    public function store(CreateVehicleBrandRequest $request): JsonResponse
    {
        $brand = $this->vehicleBrandRepository->create($request->validated());
        return response()->json($brand, 201);
    }

    public function show(int $id): JsonResponse
    {
        $brand = $this->vehicleBrandRepository->findById($id);
        return response()->json($brand);
    }

    public function update(UpdateVehicleBrandRequest $request, int $id): JsonResponse
    {
        $brand = $this->vehicleBrandRepository->update($id, $request->validated());
        return response()->json($brand);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->vehicleBrandRepository->delete($id);
        return response()->json(null, 204);
    }

    public function getByCode(string $code): JsonResponse
    {
        $brand = $this->vehicleBrandRepository->findByCode($code);
        return response()->json($brand);
    }
} 
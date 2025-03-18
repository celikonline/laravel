namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateVehicleModelRequest;
use App\Http\Requests\UpdateVehicleModelRequest;
use App\Repositories\VehicleModelRepository;
use Illuminate\Http\JsonResponse;

class VehicleModelController extends Controller
{
    protected $vehicleModelRepository;

    public function __construct(VehicleModelRepository $vehicleModelRepository)
    {
        $this->vehicleModelRepository = $vehicleModelRepository;
    }

    public function index(): JsonResponse
    {
        $models = $this->vehicleModelRepository->getAllActive();
        return response()->json($models);
    }

    public function store(CreateVehicleModelRequest $request): JsonResponse
    {
        $model = $this->vehicleModelRepository->create($request->validated());
        return response()->json($model, 201);
    }

    public function show(int $id): JsonResponse
    {
        $model = $this->vehicleModelRepository->findById($id);
        return response()->json($model);
    }

    public function update(UpdateVehicleModelRequest $request, int $id): JsonResponse
    {
        $model = $this->vehicleModelRepository->update($id, $request->validated());
        return response()->json($model);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->vehicleModelRepository->delete($id);
        return response()->json(null, 204);
    }

    public function getByBrandId(int $brandId): JsonResponse
    {
        $models = $this->vehicleModelRepository->findByBrandId($brandId);
        return response()->json($models);
    }

    public function getByCode(string $code): JsonResponse
    {
        $model = $this->vehicleModelRepository->findByCode($code);
        return response()->json($model);
    }
} 
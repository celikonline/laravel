namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DistrictService;
use App\Http\Requests\CreateDistrictRequest;
use App\Http\Requests\UpdateDistrictRequest;
use Illuminate\Http\JsonResponse;

class DistrictsController extends Controller
{
    protected $districtService;

    public function __construct(DistrictService $districtService)
    {
        $this->districtService = $districtService;
    }

    public function index(): JsonResponse
    {
        $districts = $this->districtService->getAllDistricts();
        return response()->json($districts);
    }

    public function store(CreateDistrictRequest $request): JsonResponse
    {
        $district = $this->districtService->createDistrict($request->validated());
        return response()->json($district);
    }

    public function show(int $id): JsonResponse
    {
        $district = $this->districtService->getDistrictById($id);
        return response()->json($district);
    }

    public function update(int $id, UpdateDistrictRequest $request): JsonResponse
    {
        $district = $this->districtService->updateDistrict($id, $request->validated());
        return response()->json($district);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->districtService->deleteDistrict($id);
        return response()->json(null, 204);
    }

    public function getByCityId(int $cityId): JsonResponse
    {
        $districts = $this->districtService->getDistrictsByCityId($cityId);
        return response()->json($districts);
    }

    public function getActiveByCityId(int $cityId): JsonResponse
    {
        $districts = $this->districtService->getActiveDistrictsByCityId($cityId);
        return response()->json($districts);
    }
} 
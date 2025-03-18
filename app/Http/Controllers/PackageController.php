namespace App\Http\Controllers;

use App\Services\PackageService;
use App\Http\Requests\PackageRequest;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    protected $packageService;

    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
        $this->middleware('auth');
    }

    public function index()
    {
        return view('packages.index');
    }

    public function create()
    {
        return view('packages.create');
    }

    public function store(PackageRequest $request)
    {
        try {
            $package = $this->packageService->createPackage($request->validated());
            return redirect()->route('packages.index')
                ->with('success', 'Paket başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            return back()->with('error', 'Paket oluşturulurken bir hata oluştu.');
        }
    }
}

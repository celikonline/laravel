<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Customer;
use App\Models\District;
use App\Models\Package;
use App\Models\PlateType;
use App\Models\ServicePackage;
use App\Models\Vehicle;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Vasist Asistans API Dokümantasyonu",
 *     description="Asistans hizmeti paket oluşturma ve ödeme API'leri",
 *     @OA\Contact(
 *         email="admin@vegaasist.com.tr"
 *     )
 * )
 */
class PackageController extends Controller
{
    /**
     * @OA\Get(
     *     path="/packages",
     *     summary="Paketleri listeler",
     *     tags={"Paketler"},
     *     @OA\Response(
     *         response=200,
     *         description="Başarılı"
     *     )
     * )
     */
    public function index()
    {
        $packages = Package::with(['customer', 'vehicle', 'servicePackage'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('packages.index', compact('packages'));
    }

    /**
     * @OA\Get(
     *     path="/packages/create",
     *     summary="Paket oluşturma formunu gösterir",
     *     tags={"Paketler"},
     *     @OA\Response(
     *         response=200,
     *         description="Başarılı"
     *     )
     * )
     */
    public function create()
    {
        $servicePackages = ServicePackage::where('is_active', true)->get();
        $cities = City::where('is_active', true)->get();
        $vehicleBrands = VehicleBrand::where('is_active', true)->get();
        $plateTypes = PlateType::where('is_active', true)->get();

        return view('packages.create', compact('servicePackages', 'cities', 'vehicleBrands', 'plateTypes'));
    }

    /**
     * @OA\Post(
     *     path="/packages",
     *     summary="Yeni paket oluşturur",
     *     tags={"Paketler"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"service_package_id","start_date","end_date","customer_type","identity_number","first_name","last_name","phone","city_id","district_id","plate_type","plate_city","plate_letters","plate_numbers","brand_id","model_id","model_year","terms","agreement"},
     *             @OA\Property(property="service_package_id", type="integer", description="Hizmet paketi ID"),
     *             @OA\Property(property="start_date", type="string", format="date", description="Başlangıç tarihi"),
     *             @OA\Property(property="end_date", type="string", format="date", description="Bitiş tarihi"),
     *             @OA\Property(property="customer_type", type="string", description="Müşteri tipi (Bireysel/Kurumsal)"),
     *             @OA\Property(property="identity_number", type="string", description="TC Kimlik No"),
     *             @OA\Property(property="first_name", type="string", description="Ad"),
     *             @OA\Property(property="last_name", type="string", description="Soyad"),
     *             @OA\Property(property="phone", type="string", description="Telefon"),
     *             @OA\Property(property="city_id", type="integer", description="İl ID"),
     *             @OA\Property(property="district_id", type="integer", description="İlçe ID"),
     *             @OA\Property(property="plate_type", type="string", description="Plaka tipi (Normal/Özel)"),
     *             @OA\Property(property="plate_city", type="string", description="Plaka şehir kodu"),
     *             @OA\Property(property="plate_letters", type="string", description="Plaka harfler"),
     *             @OA\Property(property="plate_numbers", type="string", description="Plaka sayılar"),
     *             @OA\Property(property="brand_id", type="integer", description="Araç markası ID"),
     *             @OA\Property(property="model_id", type="integer", description="Araç modeli ID"),
     *             @OA\Property(property="model_year", type="integer", description="Araç model yılı"),
     *             @OA\Property(property="terms", type="boolean", description="Sözleşme onayı"),
     *             @OA\Property(property="agreement", type="boolean", description="Anlaşma onayı")
     *         )
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Başarılı - Ödeme sayfasına yönlendirilir"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasyon hatası"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_package_id' => 'required|exists:service_packages,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'customer_type' => 'required|in:individual,corporate',
            'identity_number' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'plate_type' => 'required|exists:plate_types,id',
            'plate_city' => 'required|digits:2',
            'plate_letters' => 'required|regex:/^[A-Z]{1,3}$/',
            'plate_numbers' => 'required|digits_between:1,4',
            'brand_id' => 'required|exists:vehicle_brands,id',
            'model_id' => 'required|exists:vehicle_models,id',
            'model_year' => 'required|integer',
            'terms' => 'required|accepted',
            'agreement' => 'required|accepted',
        ]);

        try {
            DB::beginTransaction();

            // Plaka numarasını birleştir
            $plateNumber = $request->plate_city . ' ' . $request->plate_letters . ' ' . $request->plate_numbers;

            // Müşteri oluştur
            $customer = Customer::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'identity_number' => $request->identity_number,
                'phone' => $request->phone,
                'type' => $request->customer_type,
                'city_id' => $request->city_id,
                'district_id' => $request->district_id,
            ]);

            // Araç oluştur
            $vehicle = Vehicle::create([
                'customer_id' => $customer->id,
                'plate_number' => $plateNumber,
                'plate_city' => $request->plate_city,
                'plate_letters' => $request->plate_letters,
                'plate_numbers' => $request->plate_numbers,
                'plate_type' => $request->plate_type,
                'brand_id' => $request->brand_id,
                'model_id' => $request->model_id,
                'model_year' => $request->model_year,
            ]);

            // Paket oluştur
            $servicePackage = ServicePackage::findOrFail($request->service_package_id);
            $package = Package::create([
                'customer_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
                'service_package_id' => $servicePackage->id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'price' => $servicePackage->price,
                'status' => 'pending_payment',
                'contract_number' => $this->generateContractNumber(),
                'duration' => $this->calculateDuration($request->start_date, $request->end_date),
            ]);

            DB::commit();

            return redirect()->route('packages.payment', $package->id)
                ->with('success', 'Paket başarıyla oluşturuldu. Lütfen ödeme işlemini tamamlayın.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Paket oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/packages/vehicle-models/{brand_id}",
     *     summary="Araç markasına göre modelleri listeler",
     *     tags={"Araçlar"},
     *     @OA\Parameter(
     *         name="brand_id",
     *         in="path",
     *         required=true,
     *         description="Araç markası ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Başarılı",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function getVehicleModels($brandId)
    {
        $models = VehicleModel::where('brand_id', $brandId)
            ->where('is_active', true)
            ->get();

        return response()->json($models);
    }

    /**
     * @OA\Get(
     *     path="/packages/districts/{city_id}",
     *     summary="İle göre ilçeleri listeler",
     *     tags={"Adres"},
     *     @OA\Parameter(
     *         name="city_id",
     *         in="path",
     *         required=true,
     *         description="İl ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Başarılı",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function getDistricts($cityId)
    {
        $districts = District::where('city_id', $cityId)
            ->where('is_active', true)
            ->get();

        return response()->json($districts);
    }

    /**
     * @OA\Get(
     *     path="/packages/{id}/payment",
     *     summary="Paket ödeme sayfasını gösterir",
     *     tags={"Ödemeler"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Paket ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Başarılı"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Paket bulunamadı"
     *     )
     * )
     */
    public function payment($id)
    {
        $package = Package::with(['customer', 'vehicle', 'servicePackage'])
            ->findOrFail($id);

        return view('packages.payment', compact('package'));
    }

    /**
     * @OA\Post(
     *     path="/packages/{id}/payment",
     *     summary="Ödeme işlemini gerçekleştirir",
     *     tags={"Ödemeler"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Paket ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"card_owner","card_number","expiry","cvv","terms"},
     *             @OA\Property(property="card_owner", type="string", description="Kart sahibi"),
     *             @OA\Property(property="card_number", type="string", description="Kart numarası"),
     *             @OA\Property(property="expiry", type="string", description="Son kullanma tarihi (AA/YY)"),
     *             @OA\Property(property="cvv", type="string", description="CVV"),
     *             @OA\Property(property="terms", type="boolean", description="Sözleşme onayı")
     *         )
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Başarılı - Başarılı ödeme sayfasına yönlendirilir"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasyon hatası"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Paket bulunamadı"
     *     )
     * )
     */
    public function processPayment(Request $request, $id)
    {
        $package = Package::findOrFail($id);

        // Ödeme işlemi burada gerçekleştirilecek
        // Örnek olarak direkt başarılı kabul ediyoruz
        $package->update([
            'status' => 'active',
            'payment_date' => now(),
        ]);

        return redirect()->route('packages.index')
            ->with('success', 'Ödeme başarıyla tamamlandı ve paket aktifleştirildi.');
    }

    private function generateContractNumber()
    {
        $prefix = date('Ymd');
        $lastContract = Package::where('contract_number', 'like', $prefix . '%')
            ->orderBy('contract_number', 'desc')
            ->first();

        if ($lastContract) {
            $lastNumber = intval(substr($lastContract->contract_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $newNumber;
    }

    private function calculateDuration($startDate, $endDate)
    {
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $interval = $start->diff($end);
        return $interval->days;
    }
}

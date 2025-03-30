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
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PackagesExport;
use App\Services\PosnetService;
use Illuminate\Support\Facades\Log;

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
        $packages = Package::with(['customer', 'servicePackage'])
            ->orderBy('updated_at', 'desc')
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
            'phone_number' => 'required|string',
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

            // Müşteri kontrolü - Varsa güncelle, yoksa oluştur
            $customer = Customer::where('identity_number', $request->identity_number)->first();

            if ($customer) {
                // Mevcut müşteriyi güncelle
                $customer->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->customer_type === 'individual' ? $request->last_name : null,
                    'phone_number' => $request->phone_number,
                    'customer_type' => $request->customer_type,
                    'city_id' => $request->city_id,
                    'district_id' => $request->district_id,
                ]);
            } else {
                // Yeni müşteri oluştur
                $customer = Customer::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->customer_type === 'individual' ? $request->last_name : null,
                    'identity_number' => $request->identity_number,
                    'phone_number' => $request->phone_number,
                    'customer_type' => $request->customer_type,
                    'city_id' => $request->city_id,
                    'district_id' => $request->district_id,
                ]);
            }

            // Paket oluştur
            $servicePackage = ServicePackage::findOrFail($request->service_package_id);
            $package = Package::create([
                'customer_id' => $customer->id,
                'service_package_id' => $servicePackage->id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'price' => $servicePackage->price,
                'status' => 'pending_payment',
                'contract_number' => $this->generateContractNumber(),
                'duration' => $this->calculateDuration($request->start_date, $request->end_date),
                // Araç bilgilerini doğrudan pakete ekle
                'plate_number' => $plateNumber,
                'plate_city' => $request->plate_city,
                'plate_letters' => $request->plate_letters,
                'plate_numbers' => $request->plate_numbers,
                'plate_type' => $request->plate_type,
                'brand_id' => $request->brand_id,
                'model_id' => $request->model_id,
                'model_year' => $request->model_year,
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
        $package = Package::with(['customer', 'servicePackage'])
            ->findOrFail($id);

        $orderId = 'PKG_' . $package->id . '_' . time();
        $amount = $package->price;

        return view('payment.form', [
            'package' => $package,
            'orderId' => $orderId,
            'amount' => $amount
        ]);
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
        
        $request->validate([
            'card_owner' => 'required|string',
            'card_number' => 'required|string|size:16',
            'expiry_month' => 'required|string|size:2',
            'expiry_year' => 'required|string|size:2',
            'cvv' => 'required|string|size:3',
        ]);

        try {
            $orderId = substr(hash('sha256', uniqid()),0,20);// 'PKG_' . $package->id . '_' . time();
            $amount = $package->price * 100; // Convert to kuruş
            $currency = 'TL';

            // Get the PosnetService instance
            $posnetService = app(PosnetService::class);

            // Encrypt the data
            $response = $posnetService->encryptData(
                $orderId,
                $amount,
                $currency,
                '00', // installment
                $request->card_owner,
                $request->card_number,
                $request->expiry_year . $request->expiry_month,
                $request->cvv
            );

            // Store payment data in session
            session([
                'payment_package_id' => $package->id,
                'payment_order_id' => $orderId,
                'payment_amount' => $amount
            ]);

            // Get 3D form data
            $formData = [
                'action_url' => config('posnet.threed_url'),
                'inputs' => [
                    'mid' => config('posnet.merchant_id'),
                    'tid' => config('posnet.terminal_id'),
                    'PosnetID' => config('posnet.posnet_id'),
                    'posnetData' => $response->oosRequestDataResponse->data1,
                    'posnetData2' => $response->oosRequestDataResponse->data2,
                    'digest' => $response->oosRequestDataResponse->sign,
                    'vftCode' => "",
                    'merchantReturnURL' => route('payment.result'),
                    'lang' => 'tr',
                    'url' => config('posnet.merchant_url'),
                ]
            ];

            Log::info('Form Data', ['form Data' => $formData]);

            return view('payment.redirect', compact('formData'));

        } catch (\Exception $e) {
            \Log::error('Payment error', ['error' => $e->getMessage()]);
            return back()
                ->with('error', 'Ödeme işlemi başlatılırken bir hata oluştu: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * @OA\Get(
     *     path="/packages/{id}/edit",
     *     summary="Paket düzenleme formunu gösterir",
     *     tags={"Paketler"},
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
    public function edit($id)
    {
        $package = Package::with(['customer', 'servicePackage', 'vehicleBrand', 'vehicleModel', 'plateType'])
            ->findOrFail($id);
            
        $servicePackages = ServicePackage::where('is_active', true)->get();
        $cities = City::where('is_active', true)->get();
        $districts = District::where('city_id', $package->customer->city_id)->where('is_active', true)->get();
        $vehicleBrands = VehicleBrand::where('is_active', true)->get();
        $vehicleModels = VehicleModel::where('brand_id', $package->brand_id)->where('is_active', true)->get();
        $plateTypes = PlateType::where('is_active', true)->get();

        return view('packages.edit', compact(
            'package', 
            'servicePackages', 
            'cities', 
            'districts',
            'vehicleBrands', 
            'vehicleModels',
            'plateTypes'
        ));
    }

    /**
     * @OA\Put(
     *     path="/packages/{id}",
     *     summary="Paketi günceller",
     *     tags={"Paketler"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Paket ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PackageRequest")
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Başarılı - Paket listesine yönlendirilir"
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
    public function update(Request $request, $id)
    {
        \Log::info('Package update started', ['id' => $id, 'request' => $request->all()]);
        
        try {
            $package = Package::findOrFail($id);
            
            $validatedData = $request->validate([
                'service_package_id' => 'required|exists:service_packages,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'customer_type' => 'required|in:individual,corporate',
                'identity_number' => 'required|string',
                'first_name' => 'required|string',
                'last_name' => $request->customer_type === 'individual' ? 'required|string' : 'nullable',
                'phone_number' => 'required|string',
                'city_id' => 'required|exists:cities,id',
                'district_id' => 'required|exists:districts,id',
                'plate_type' => 'required|exists:plate_types,id',
                'plate_city' => 'required|digits:2',
                'plate_letters' => 'required|regex:/^[A-Z]{1,3}$/',
                'plate_numbers' => 'required|digits_between:1,4',
                'brand_id' => 'required|exists:vehicle_brands,id',
                'model_id' => 'required|exists:vehicle_models,id',
                'model_year' => 'required|integer|min:1900|max:'.(date('Y')+1),
            ]);

            \Log::info('Validation passed', ['validatedData' => $validatedData]);

            DB::beginTransaction();

            // Plaka numarasını birleştir
            $plateNumber = $request->plate_city . ' ' . $request->plate_letters . ' ' . $request->plate_numbers;

            // Müşteri kontrolü - Farklı bir identity_number girilmiş mi?
            if ($package->customer->identity_number !== $request->identity_number) {
                // Yeni girilen identity_number'a sahip başka bir müşteri var mı?
                $existingCustomer = Customer::where('identity_number', $request->identity_number)
                    ->where('id', '!=', $package->customer_id)
                    ->first();

                if ($existingCustomer) {
                    // Varolan müşteriyi kullan ve güncelle
                    $existingCustomer->update([
                        'first_name' => $request->first_name,
                        'last_name' => $request->customer_type === 'individual' ? $request->last_name : null,
                        'phone_number' => $request->phone_number,
                        'customer_type' => $request->customer_type,
                        'city_id' => $request->city_id,
                        'district_id' => $request->district_id,
                    ]);
                    
                    // Paketin customer_id'sini güncelle
                    $package->customer_id = $existingCustomer->id;
                    $package->save();
                } else {
                    // Mevcut müşteriyi güncelle
                    $package->customer->update([
                        'first_name' => $request->first_name,
                        'last_name' => $request->customer_type === 'individual' ? $request->last_name : null,
                        'identity_number' => $request->identity_number,
                        'phone_number' => $request->phone_number,
                        'customer_type' => $request->customer_type,
                        'city_id' => $request->city_id,
                        'district_id' => $request->district_id,
                    ]);
                }
            } else {
                // Identity number değişmemiş, normal güncelleme yap
                $package->customer->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->customer_type === 'individual' ? $request->last_name : null,
                    'phone_number' => $request->phone_number,
                    'customer_type' => $request->customer_type,
                    'city_id' => $request->city_id,
                    'district_id' => $request->district_id,
                ]);
            }

            // Paket bilgilerini güncelle
            $servicePackage = ServicePackage::findOrFail($request->service_package_id);
            $package->update([
                'service_package_id' => $servicePackage->id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'price' => $servicePackage->price,
                'duration' => $this->calculateDuration($request->start_date, $request->end_date),
                // Araç bilgilerini güncelle
                'plate_number' => $plateNumber,
                'plate_city' => $request->plate_city,
                'plate_letters' => $request->plate_letters,
                'plate_numbers' => $request->plate_numbers,
                'plate_type' => $request->plate_type,
                'brand_id' => $request->brand_id,
                'model_id' => $request->model_id,
                'model_year' => $request->model_year,
                'phone_number' => $request->phone_number,
                
            ]);

            \Log::info('Package updated', ['package' => $package]);

            DB::commit();

            return redirect()->route('packages.index')
                ->with('success', 'Paket başarıyla güncellendi.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Update error', ['error' => $e->getMessage()]);
            DB::rollback();
            return back()
                ->with('error', 'Paket güncellenirken bir hata oluştu: ' . $e->getMessage())
                ->withInput();
        }
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

    public function generateContractPdf(Package $package)
    {
        // PDF oluşturma işlemleri burada yapılacak
        // Örnek olarak:
        $pdf = PDF::loadView('pdfs.contract', compact('package'));
        return $pdf->download('hizmet-sozlesmesi-' . $package->contract_number . '.pdf');
    }

    public function generateReceiptPdf(Package $package)
    {
        // PDF oluşturma işlemleri burada yapılacak
        // Örnek olarak:
        $pdf = PDF::loadView('pdfs.receipt', compact('package'));
        return $pdf->download('makbuz-' . $package->contract_number . '.pdf');
    }

    public function downloadReceiptPdf(Package $package)
    {
        $pdf = PDF::loadView('pdfs.receipt', compact('package'));
        return $pdf->download('makbuz-' . $package->contract_number . '.pdf');
    }

    // app/Models/Package.php

    public function getFormattedPlateAttribute()
    {
        return $this->plate_city . ' ' . $this->plate_letters . ' ' . $this->plate_numbers;
    }

    public function exportCsv()
    {
        $fileName = 'paketler_' . date('Y-m-d_H-i-s') . '.csv';
        
        $packages = Package::with(['customer', 'servicePackage'])->get();
        
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        
        $columns = [
            'Poliçe No',
            'Müşteri',
            'Plaka',
            'Servis Paketi',
            'Ücret',
            'Komisyon',
            'Komisyon Oranı',
            'Başlangıç',
            'Bitiş',
            'Süre',
            'Durum'
        ];

        $callback = function() use($packages, $columns) {
            $file = fopen('php://output', 'w');
            // UTF-8 BOM for Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $columns);

            foreach ($packages as $package) {
                fputcsv($file, [
                    $package->contract_number,
                    $package->customer->name,
                    $package->plate_city . ' ' . $package->plate_letters . ' ' . $package->plate_numbers,
                    $package->servicePackage->name,
                    number_format($package->price, 2, ',', '.') . ' ₺',
                    number_format($package->commission, 2, ',', '.') . ' ₺',
                    '%' . number_format($package->commission_rate, 2, ',', '.'),
                    $package->start_date->format('d.m.Y'),
                    $package->end_date->format('d.m.Y'),
                    $package->duration,
                    $package->is_active ? 'Aktif' : 'Pasif'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportExcel()
    {
        $packages = Package::with(['customer', 'servicePackage'])->get();
        
        $fileName = 'paketler_' . date('Y-m-d_H-i-s') . '.xls';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];
        
        $columns = [
            'Poliçe No',
            'Müşteri',
            'Plaka',
            'Servis Paketi',
            'Ücret',
            'Komisyon',
            'Komisyon Oranı',
            'Başlangıç',
            'Bitiş',
            'Süre',
            'Durum'
        ];

        $callback = function() use ($packages, $columns) {
            $file = fopen('php://output', 'w');
            // UTF-8 BOM for Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, $columns, "\t");
            
            // Data rows
            foreach ($packages as $package) {
                fputcsv($file, [
                    $package->contract_number,
                    $package->customer->name,
                    $package->plate_city . ' ' . $package->plate_letters . ' ' . $package->plate_numbers,
                    $package->servicePackage->name,
                    number_format($package->price, 2, ',', '.') . ' ₺',
                    number_format($package->commission, 2, ',', '.') . ' ₺',
                    '%' . number_format($package->commission_rate, 2, ',', '.'),
                    $package->start_date->format('d.m.Y'),
                    $package->end_date->format('d.m.Y'),
                    $package->duration,
                    $package->is_active ? 'Aktif' : 'Pasif'
                ], "\t");
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $packages = Package::with(['customer', 'servicePackage'])->get();
        
        $pdf = PDF::loadView('exports.packages-pdf', [
            'packages' => $packages,
            'date' => date('d.m.Y H:i')
        ]);
        
        return $pdf->download('paketler_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function contractPreview(Package $package)
    {
        return view('packages.contract-preview', compact('package'));
    }

    public function receiptPreview(Package $package)
    {
        return view('packages.receipt-preview', compact('package'));
    }

    public function downloadTemplateAgreementPdf()
    {
        $pdf = PDF::loadView('packages.agreement-pdf');
        return $pdf->download('ikame-arac-paketi-sozlesmesi.pdf');
    }

    public function downloadKvkkPdf()
    {
        $pdf = PDF::loadView('packages.kvkk-pdf');
        return $pdf->download('kvkk-aydinlatma-metni.pdf');
    }

    public function proposals()
    {
        $packages = Package::with(['customer', 'servicePackage'])
            ->where('status', 'pending_payment')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        
        return view('packages.proposals', compact('packages'));
    }

    public function allPackages()
    {
        $packages = Package::with(['customer', 'servicePackage'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        
        return view('packages.all', compact('packages'));
    }

    /**
     * Generate service agreement for the package
     */
    public function previewAgreement(Package $package)
    {
        $html = view('packages.agreement-preview', [
            'package' => $package,
            'netAmount' => $package->price / 1.18, // KDV'siz tutar
            'kdv' => $package->price - ($package->price / 1.18), // KDV tutarı
            'formattedPlate' => $package->plate_city . ' ' . $package->plate_letters . ' ' . $package->plate_numbers,
            'maskedIdentityNumber' => $this->maskIdentityNumber($package->customer->identity_number)
        ])->render();

        return $html;
    }

    /**
     * Download service agreement as PDF
     */
    public function downloadAgreementPdf(Package $package)
    {
        $html = $this->previewAgreement($package);
        
        $pdf = PDF::loadHTML($html);
        $pdf->setPaper('A4');
        
        return $pdf->download('hizmet-sozlesmesi-' . $package->contract_number . '.pdf');
    }

    /**
     * Mask identity number for privacy
     */
    private function maskIdentityNumber($identityNumber)
    {
        if (strlen($identityNumber) != 11) {
            return $identityNumber;
        }
        
        return substr($identityNumber, 0, 3) . '******' . substr($identityNumber, -2);
    }
}

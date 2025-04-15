<?php

namespace App\Http\Controllers;

use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VehicleModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cacheKey = 'vehicle_models_index_' . md5(json_encode($request->all()));
        $data = Cache::remember($cacheKey, now()->addHours(24), function () use ($request) {
            $query = VehicleModel::with('brand')
                ->when($request->brand_id, function ($q) use ($request) {
                    return $q->where('brand_id', $request->brand_id);
                })
                ->when($request->name, function ($q) use ($request) {
                    return $q->where('name', 'like', '%' . $request->name . '%');
                })
                ->when($request->is_active !== null, function ($q) use ($request) {
                    return $q->where('is_active', $request->is_active);
                })
                ->orderBy('name');

            return [
                'models' => $query->paginate(10),
                'brands' => VehicleBrand::where('is_active', true)->orderBy('name')->get()
            ];
        });

        return view('settings.vehicle-models.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = VehicleBrand::where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'id');
        return view('settings.vehicle-models.create', compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'brand_id' => 'required|exists:vehicle_brands,id',
            'name' => 'required|string|max:255',
            'status' => 'required|boolean'
        ]);

        VehicleModel::create($request->all());

        return redirect()->route('vehicle-models.index')
            ->with('success', 'Araç modeli başarıyla eklendi.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(VehicleModel $vehicleModel)
    {
        $brands = VehicleBrand::where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'id');
        return view('settings.vehicle-models.edit', compact('vehicleModel', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VehicleModel $vehicleModel)
    {
        $request->validate([
            'brand_id' => 'required|exists:vehicle_brands,id',
            'name' => 'required|string|max:255',
            'status' => 'required|boolean'
        ]);

        $vehicleModel->update($request->all());

        return redirect()->route('vehicle-models.index')
            ->with('success', 'Araç modeli başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(VehicleModel $vehicleModel)
    {
        // Model ile ilişkili kayıtlar varsa silmeyi engelle
        if ($vehicleModel->packages()->exists()) {
            return back()->with('error', 'Bu modele ait paketler bulunduğu için silinemez.');
        }

        $vehicleModel->delete();

        return redirect()->route('vehicle-models.index')
            ->with('success', 'Araç modeli başarıyla silindi.');
    }

    public function getModelsByBrand(VehicleBrand $brand)
    {
        $cacheKey = 'vehicle_models_by_brand_' . $brand->id;
        $models = Cache::remember($cacheKey, now()->addHours(24), function () use ($brand) {
            return $brand->models()
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        });

        return response()->json($models);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Illuminate\Http\Request;

class VehicleModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $models = VehicleModel::with('brand')
            ->orderBy('name')
            ->paginate(10);
        return view('settings.vehicle-models.index', compact('models'));
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

    public function getModelsByBrand($brandId)
    {
        $models = VehicleModel::where('brand_id', $brandId)
            ->where('status', true)
            ->orderBy('name')
            ->get(['id', 'name']);
        
        return response()->json($models);
    }
}

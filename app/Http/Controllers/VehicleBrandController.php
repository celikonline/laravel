<?php

namespace App\Http\Controllers;

use App\Models\VehicleBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VehicleBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cacheKey = 'vehicle_brands_index_' . md5(json_encode($request->all()));
        $data = Cache::remember($cacheKey, now()->addHours(24), function () use ($request) {
            $query = VehicleBrand::query()
                ->when($request->name, function ($q) use ($request) {
                    return $q->where('name', 'like', '%' . $request->name . '%');
                })
                ->when($request->is_active !== null, function ($q) use ($request) {
                    return $q->where('is_active', $request->is_active);
                })
                ->orderBy('name');

            return [
                'brands' => $query->paginate(10)
            ];
        });

        return view('settings.vehicle-brands.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.vehicle-brands.create');
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
            'name' => 'required|string|max:255|unique:vehicle_brands',
            'status' => 'required|boolean'
        ]);

        VehicleBrand::create($request->all());

        return redirect()->route('vehicle-brands.index')
            ->with('success', 'Araç markası başarıyla eklendi.');
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
    public function edit(VehicleBrand $vehicleBrand)
    {
        return view('settings.vehicle-brands.edit', compact('vehicleBrand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VehicleBrand $vehicleBrand)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:vehicle_brands,name,' . $vehicleBrand->id,
            'status' => 'required|boolean'
        ]);

        $vehicleBrand->update($request->all());

        return redirect()->route('vehicle-brands.index')
            ->with('success', 'Araç markası başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(VehicleBrand $vehicleBrand)
    {
        // Marka ile ilişkili modeller varsa silmeyi engelle
        if ($vehicleBrand->models()->exists()) {
            return back()->with('error', 'Bu markaya ait modeller bulunduğu için silinemez.');
        }

        $vehicleBrand->delete();

        return redirect()->route('vehicle-brands.index')
            ->with('success', 'Araç markası başarıyla silindi.');
    }
}

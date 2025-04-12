<?php

namespace App\Http\Controllers;

use App\Models\VehicleBrand;
use Illuminate\Http\Request;

class VehicleBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = VehicleBrand::query();

        // Arama filtresi
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Durum filtresi
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $brands = $query->orderBy('name')->paginate(10);
        
        return view('settings.vehicle-brands.index', compact('brands'));
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

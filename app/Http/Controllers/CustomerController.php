<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        // Müşteri adı/soyadı arama filtresi
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%');
            });
        }

        // Durum filtresi
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $customers = $query->with(['city', 'district'])->orderBy('first_name')->paginate(10);
        
        return view('settings.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = DB::table('cities')->orderBy('name')->get();
        return view('settings.customers.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'identity_number' => 'nullable|string|max:191|unique:customers,identity_number',
            'tax_number' => 'nullable|string|max:191|unique:customers,tax_number',
            'email' => 'nullable|email|max:191|unique:customers,email',
            'phone_number' => 'nullable|string|max:191',
            'address' => 'nullable|string',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'postal_code' => 'nullable|string|max:191',
            'customer_type' => 'required|in:individual,corporate',
            'company_name' => 'required_if:customer_type,corporate|nullable|string|max:191',
            'tax_office' => 'required_if:customer_type,corporate|nullable|string|max:191',
            'notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Müşteri başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return view('settings.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        $cities = DB::table('cities')->orderBy('name')->get();
        $districts = DB::table('districts')
            ->where('city_id', $customer->city_id)
            ->orderBy('name')
            ->get();
            
        return view('settings.customers.edit', compact('customer', 'cities', 'districts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'identity_number' => 'nullable|string|max:191|unique:customers,identity_number,' . $customer->id,
            'tax_number' => 'nullable|string|max:191|unique:customers,tax_number,' . $customer->id,
            'email' => 'nullable|email|max:191|unique:customers,email,' . $customer->id,
            'phone_number' => 'nullable|string|max:191',
            'address' => 'nullable|string',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'postal_code' => 'nullable|string|max:191',
            'customer_type' => 'required|in:individual,corporate',
            'company_name' => 'required_if:customer_type,corporate|nullable|string|max:191',
            'tax_office' => 'required_if:customer_type,corporate|nullable|string|max:191',
            'notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Müşteri başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        // Müşteri ile ilişkili araçlar varsa silmeyi engelle
        if ($customer->vehicles()->exists()) {
            return back()->with('error', 'Bu müşteriye ait araçlar bulunduğu için silinemez.');
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Müşteri başarıyla silindi.');
    }

    public function getDistricts(Request $request)
    {
        $districts = DB::table('districts')
            ->where('city_id', $request->city_id)
            ->orderBy('name')
            ->get();
            
        return response()->json($districts);
    }
} 
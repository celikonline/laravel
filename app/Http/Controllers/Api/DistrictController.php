<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistrictController extends Controller
{
    public function getDistrictsByCity(Request $request, $cityId)
    {
        try {
            $districts = DB::table('districts')
                ->where('city_id', $cityId)
                ->orderBy('name')
                ->get(['id', 'name']);

            return response()->json([
                'success' => true,
                'data' => $districts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'İlçeler yüklenirken bir hata oluştu'
            ], 500);
        }
    }
} 
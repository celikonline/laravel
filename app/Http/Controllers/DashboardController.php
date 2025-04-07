<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Toplam paket sayısı
        $totalPackages = Package::count();
        
        // Aktif paket sayısı
        $activePackages = Package::where('status', 'active')->count();
        
        // Bekleyen ödeme sayısı
        $pendingPayments = Package::where('status', 'pending_payment')->count();
        
        // Süresi dolmuş paket sayısı
        $expiredPackages = Package::where('status', 'expired')->count();
        
        // Son 6 ayın aylık gelir grafiği için veriler
        $monthlyRevenue = Package::where('status', 'active')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->select(
                DB::raw('sum(price) as total'),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        // Son 100 paket
        $recentPackages = Package::with(['customer', 'servicePackage'])
            ->orderBy('created_at', 'desc')
            ->take(100)
            ->get();

        return view('dashboard', compact(
            'totalPackages',
            'activePackages',
            'pendingPayments',
            'expiredPackages',
            'monthlyRevenue',
            'recentPackages'
        ));
    }
} 
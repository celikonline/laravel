<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Customer;
use App\Models\ServicePackage;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Son 12 ayın gelir grafiği
        $monthlyRevenue = Package::where('status', 'active')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->select(
                DB::raw('sum(price) as total'),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Paket durumlarının dağılımı
        $packageStatus = Package::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // En popüler servis paketleri
        $popularPackages = ServicePackage::withCount(['packages' => function($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('packages_count', 'desc')
            ->limit(5)
            ->get();

        // Müşteri istatistikleri
        $customerStats = [
            'total' => Customer::count(),
            'active' => Customer::whereHas('packages', function($query) {
                $query->where('status', 'active');
            })->count(),
            'new_this_month' => Customer::where('created_at', '>=', Carbon::now()->startOfMonth())->count()
        ];

        // Aylık yeni müşteri trendi
        $customerTrend = Customer::where('created_at', '>=', Carbon::now()->subMonths(12))
            ->select(
                DB::raw('count(*) as total'),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('reports.index', compact(
            'monthlyRevenue',
            'packageStatus',
            'popularPackages',
            'customerStats',
            'customerTrend'
        ));
    }

    public function revenue(Request $request)
    {
        $startDate = $request->get('start_date') 
            ? Carbon::parse($request->get('start_date')) 
            : Carbon::now()->startOfMonth();
        $endDate = $request->get('end_date')
            ? Carbon::parse($request->get('end_date'))
            : Carbon::now();

        // Gelir detayları
        $revenue = Package::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'active')
            ->select(
                'id',
                'contract_number',
                'price',
                'created_at',
                'customer_id',
                'service_package_id'
            )
            ->with(['customer:id,first_name,last_name', 'servicePackage:id,name'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Toplam gelir
        $totalRevenue = Package::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'active')
            ->sum('price');

        return view('reports.revenue', compact('revenue', 'totalRevenue', 'startDate', 'endDate'));
    }

    public function packages()
    {
        // Paket durumu dağılımı
        $statusDistribution = Package::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Servis paketi bazında dağılım
        $packageDistribution = ServicePackage::withCount('packages')
            ->orderBy('packages_count', 'desc')
            ->get();

        // Aylık yeni paket trendi
        $packageTrend = Package::where('created_at', '>=', Carbon::now()->subMonths(12))
            ->select(
                DB::raw('count(*) as total'),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('reports.packages', compact(
            'statusDistribution',
            'packageDistribution',
            'packageTrend'
        ));
    }

    public function customers()
    {
        // En çok hizmet alan müşteriler
        $topCustomers = Customer::withCount('packages')
            ->withSum('packages', 'price')
            ->orderBy('packages_count', 'desc')
            ->limit(10)
            ->get();

        // Müşteri kayıt trendi
        $registrationTrend = Customer::where('created_at', '>=', Carbon::now()->subMonths(12))
            ->select(
                DB::raw('count(*) as total'),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('reports.customers', compact('topCustomers', 'registrationTrend'));
    }
} 
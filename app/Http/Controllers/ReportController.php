<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Customer;
use App\Models\ServicePackage;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReportMail;
use PDF;

class ReportController extends Controller
{
    public function index()
    {
        // Audit log kaydı
        AuditLog::log('view', 'dashboard');

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

        // Audit log için filtreleri hazırla
        $filters = array_filter($request->all());

        // Gelir detayları query builder
        $query = Package::query()
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Durum filtresi
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'active');
        }

        // Fiyat aralığı filtresi
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Müşteri filtresi
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Servis paketi filtresi
        if ($request->filled('service_package_id')) {
            $query->where('service_package_id', $request->service_package_id);
        }

        // Arama filtresi
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('contract_number', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('customer', function($q) use ($searchTerm) {
                        $q->where('first_name', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('email', 'LIKE', "%{$searchTerm}%");
                    })
                    ->orWhereHas('servicePackage', function($q) use ($searchTerm) {
                        $q->where('name', 'LIKE', "%{$searchTerm}%");
                    });
            });
        }

        // Sıralama
        $sortField = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $allowedSortFields = ['created_at', 'price', 'contract_number'];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Verileri çek
        $revenue = $query->select(
                'id',
                'contract_number',
                'price',
                'created_at',
                'customer_id',
                'service_package_id',
                'status'
            )
            ->with(['customer:id,first_name,last_name,email', 'servicePackage:id,name'])
            ->paginate(15)
            ->withQueryString();

        // Toplam gelir
        $totalRevenue = $query->sum('price');

        // Filtreler için gerekli veriler
        $customers = Customer::select('id', 'first_name', 'last_name')
            ->orderBy('first_name')
            ->get();

        $servicePackages = ServicePackage::select('id', 'name')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $statuses = Package::distinct()
            ->pluck('status');

        // E-posta gönderme isteği varsa
        if ($request->has('send_email')) {
            $reportData = [
                'revenue' => $revenue,
                'totalRevenue' => $totalRevenue
            ];
            
            Mail::to($request->user()->email)->send(new ReportMail('gelir', $reportData, $startDate, $endDate));

            // E-posta gönderimi için audit log
            AuditLog::log('email', 'revenue', $filters);

            return redirect()->back()->with('success', 'Gelir raporu e-posta olarak gönderildi.');
        }

        // Görüntüleme için audit log
        AuditLog::log(
            empty($filters) ? 'view' : 'filter',
            'revenue',
            $filters
        );

        return view('reports.revenue', compact(
            'revenue', 
            'totalRevenue', 
            'startDate', 
            'endDate',
            'customers',
            'servicePackages',
            'statuses',
            'sortField',
            'sortDirection'
        ));
    }

    public function packages(Request $request)
    {
        // Audit log için filtreleri hazırla
        $filters = array_filter($request->all());

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

        // E-posta gönderme isteği varsa
        if ($request->has('send_email')) {
            $reportData = [
                'statusDistribution' => $statusDistribution,
                'packageDistribution' => $packageDistribution,
                'packageTrend' => $packageTrend
            ];
            
            Mail::to($request->user()->email)->send(new ReportMail('paketler', $reportData));

            // E-posta gönderimi için audit log
            AuditLog::log('email', 'packages', $filters);

            return redirect()->back()->with('success', 'Paket raporu e-posta olarak gönderildi.');
        }

        // Görüntüleme için audit log
        AuditLog::log('view', 'packages', $filters);

        return view('reports.packages', compact(
            'statusDistribution',
            'packageDistribution',
            'packageTrend'
        ));
    }

    public function packagesContractPreview()
    {
        // Get all active packages
        $packages = Package::with(['customer', 'servicePackage'])
            ->where('status', 'active')
            ->get();

        // Generate contract preview HTML
        $html = view('reports.packages-contract-preview', [
            'packages' => $packages,
            'date' => now()->format('d.m.Y')
        ])->render();

        // Generate PDF
        $pdf = PDF::loadHTML($html);
        $pdf->setPaper('A4');
        
        return $pdf->stream('paket-sozlesmeleri-' . now()->format('Y-m-d') . '.pdf');
    }

    public function customers(Request $request)
    {
        // Audit log için filtreleri hazırla
        $filters = array_filter($request->all());

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

        // E-posta gönderme isteği varsa
        if ($request->has('send_email')) {
            $reportData = [
                'topCustomers' => $topCustomers,
                'registrationTrend' => $registrationTrend
            ];
            
            Mail::to($request->user()->email)->send(new ReportMail('müşteriler', $reportData));

            // E-posta gönderimi için audit log
            AuditLog::log('email', 'customers', $filters);

            return redirect()->back()->with('success', 'Müşteri raporu e-posta olarak gönderildi.');
        }

        // Görüntüleme için audit log
        AuditLog::log('view', 'customers', $filters);

        return view('reports.customers', compact('topCustomers', 'registrationTrend'));
    }
} 
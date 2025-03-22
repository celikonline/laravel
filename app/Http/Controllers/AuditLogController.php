<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        // Tarih filtresi
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Kullanıcı filtresi
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Modül filtresi
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        // Eylem filtresi
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // IP adresi filtresi
        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'LIKE', "%{$request->ip_address}%");
        }

        $logs = $query->paginate(20)->withQueryString();

        // İstatistikler
        $stats = [
            'total_logs' => AuditLog::count(),
            'today_logs' => AuditLog::whereDate('created_at', Carbon::today())->count(),
            'unique_users' => AuditLog::distinct('user_id')->count('user_id'),
            'unique_ips' => AuditLog::distinct('ip_address')->count('ip_address')
        ];

        // Filtreler için veriler
        $modules = AuditLog::distinct()->pluck('module');
        $actions = AuditLog::distinct()->pluck('action');
        $users = \App\Models\User::select('id', 'name')->get();

        return view('audit.index', compact(
            'logs',
            'stats',
            'modules',
            'actions',
            'users'
        ));
    }

    public function show(AuditLog $log)
    {
        return view('audit.show', compact('log'));
    }
}

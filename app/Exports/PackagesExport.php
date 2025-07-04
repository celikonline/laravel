<?php

namespace App\Exports;

use App\Models\Package;

class PackagesExport
{
    protected $packages;

    public function __construct($packages)
    {
        $this->packages = $packages;
    }

    public function export()
    {
        $fileName = 'paketler_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Poliçe No',
            'Müşteri',
            'Müşteri Email',
            'Müşteri Telefon',
            'Plaka',
            'Araç Markası',
            'Araç Modeli',
            'Araç Yılı',
            'Servis Paketi',
            'Ücret',
            'Başlangıç Tarihi',
            'Bitiş Tarihi',
            'Durum',
            'Oluşturulma Tarihi',
        ];

        // CSV response headers
        $response = response()->streamDownload(function () use ($headers) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Write headers
            fputcsv($file, $headers, ';');
            
            // Write data
            foreach ($this->packages as $package) {
                $row = [
                    $package->contract_number,
                    $package->customer->first_name . ' ' . $package->customer->last_name,
                    $package->customer->email ?? '',
                    $package->customer->phone_number ?? '',
                    $package->plate_city . ' ' . $package->plate_letters . ' ' . $package->plate_numbers,
                    $package->vehicle_brand ?? '',
                    $package->vehicle_model ?? '',
                    $package->model_year ?? '',
                    $package->servicePackage->name,
                    number_format($package->price, 2, ',', '.') . ' ₺',
                    $package->start_date->format('d.m.Y'),
                    $package->end_date->format('d.m.Y'),
                    $this->getStatusText($package->status),
                    $package->created_at->format('d.m.Y H:i:s'),
                ];
                fputcsv($file, $row, ';');
            }
            
            fclose($file);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);

        return $response;
    }

    private function getStatusText($status)
    {
        switch ($status) {
            case 'active':
                return 'Aktif';
            case 'pending_payment':
                return 'Ödeme Bekliyor';
            case 'expired':
                return 'Süresi Dolmuş';
            case 'cancelled':
                return 'İptal Edildi';
            case 'inactive':
                return 'Pasif';
            default:
                return $status;
        }
    }
} 
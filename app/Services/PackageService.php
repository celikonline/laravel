<?php

namespace App\Services;

use App\Repositories\PackageRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class PackageService
{
    protected $packageRepository;

    public function __construct(PackageRepository $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    public function getAllPaginated(int $pageNumber = 1, int $pageSize = 100)
    {
        return $this->packageRepository->getAllPaginated($pageNumber, $pageSize);
    }

    public function createPackage(array $data)
    {
        try {
            DB::beginTransaction();

            // Paket numarası oluştur
            $data['package_number'] = 'PKG-' . date('YmdHis') .substr(hash('sha256', uniqid()),0,20);
            $data['transaction_id'] ='PKG-' . date('YmdHis') .substr(hash('sha256', uniqid()),0,20);

            
            // KDV hesapla
            $data['kdv'] = $data['amount'] * 0.18;
            $data['net_amount'] = $data['amount'] + $data['kdv'];

            // Plaka formatını düzenle
            if (isset($data['plateCity']) && isset($data['plateLetters']) && isset($data['plateNumbers'])) {
                $data['plate'] = sprintf('%s %s %s', 
                    $data['plateCity'], 
                    $data['plateLetters'], 
                    $data['plateNumbers']
                );
            }

            $package = $this->packageRepository->create($data);

            if (isset($data['services'])) {
                $package->services()->attach($data['services']);
            }

            DB::commit();
            return $package;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function findByCustomerId(int $customerId)
    {
        return $this->packageRepository->findByCustomerId($customerId);
    }

    public function findByVehicleId(int $vehicleId)
    {
        return $this->packageRepository->findByVehicleId($vehicleId);
    }

    public function findWithRelations(int $id)
    {
        return $this->packageRepository->findWithRelations($id);
    }
}
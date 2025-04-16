<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReceiptService;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    protected $receiptService;

    public function __construct(ReceiptService $receiptService)
    {
        $this->receiptService = $receiptService;
    }

    public function index(Request $request): JsonResponse
    {
        $pageNumber = $request->get('page', 1);
        $pageSize = $request->get('per_page', 100);
        
        $receipts = $this->receiptService->getAllPaginated($pageNumber, $pageSize);
        return response()->json($receipts);
    }

    public function show(int $id): JsonResponse
    {
        $receipt = $this->receiptService->findWithRelations($id);
        return response()->json($receipt);
    }

    public function getByPackageId(int $packageId): JsonResponse
    {
        $receipts = $this->receiptService->findByPackageId($packageId);
        return response()->json($receipts);
    }

    public function getByCustomerId(int $customerId): JsonResponse
    {
        $receipts = $this->receiptService->findByCustomerId($customerId);
        return response()->json($receipts);
    }

    public function download(int $id): JsonResponse
    {
        $pdfPath = $this->receiptService->generatePdf($id);
        return response()->json(['pdf_url' => $pdfPath]);
    }

    public function getHtml(int $id): Response
    {
        $html = $this->receiptService->generateReceiptHtml($id);
        return response($html)->header('Content-Type', 'text/html');
    }

    public function getPdf(int $id): StreamedResponse
    {
        $pdf = $this->receiptService->generateReceiptPdf($id);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'receipt.pdf', [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="receipt.pdf"'
        ]);
    }

    public function getAgreementHtml(int $id): Response
    {
        $html = $this->receiptService->generateAgreementHtml($id);
        return response($html)->header('Content-Type', 'text/html');
    }

    public function getAgreementPdf(int $id): StreamedResponse
    {
        $pdf = $this->receiptService->generateAgreementPdf($id);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'agreement.pdf', [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="agreement.pdf"'
        ]);
    }
} 
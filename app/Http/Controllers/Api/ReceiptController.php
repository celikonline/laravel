namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReceiptService;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReceiptController extends Controller
{
    protected $receiptService;

    public function __construct(ReceiptService $receiptService)
    {
        $this->receiptService = $receiptService;
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
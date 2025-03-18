namespace App\Services;

use App\Repositories\PackageRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use Exception;

class ReceiptService
{
    protected $packageRepository;

    public function __construct(PackageRepository $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    public function generateReceiptHtml(int $id): string
    {
        $package = $this->packageRepository->findWithRelations($id);
        if (!$package) {
            throw new Exception('Package not found', 404);
        }

        return View::make('receipts.receipt', [
            'package' => $package
        ])->render();
    }

    public function generateReceiptPdf(int $id): string
    {
        $html = $this->generateReceiptHtml($id);
        return Pdf::loadHTML($html)->output();
    }

    public function generateAgreementHtml(int $id): string
    {
        $package = $this->packageRepository->findWithRelations($id);
        if (!$package) {
            throw new Exception('Package not found', 404);
        }

        return View::make('receipts.agreement', [
            'package' => $package
        ])->render();
    }

    public function generateAgreementPdf(int $id): string
    {
        $html = $this->generateAgreementHtml($id);
        return Pdf::loadHTML($html)->output();
    }
} 
<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Services\PosnetService;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $posnetService;

    public function __construct(PosnetService $posnetService)
    {
        $this->posnetService = $posnetService;
    }

    public function showForm()
    {
        return view('payment.form');
    }

    /**
     * Step 1: Encrypt payment data and get 3D form data
     */
    public function processPayment(Request $request)
    {
        try {
            Log::info('Payment process started', [
                'package_id' => $request->package_id,
                'order_id' => $request->order_id,
                'amount' => $request->amount
            ]);

            // Validate request data
            $request->validate([
                'package_id' => 'required|exists:packages,id',
                'order_id' => 'required|string',
                'amount' => 'required|numeric',
                'currency' => 'required|string',
                'card_owner' => 'required|string',
                'card_number' => 'required|string|size:16',
                'expiry_month' => 'required|string|size:2',
                'expiry_year' => 'required|string|size:2',
                'cvv' => 'required|string|size:3',
            ]);

            // Get package details
            $package = Package::findOrFail($request->package_id);
            Log::info('Package found', ['package' => $package->toArray()]);
            
            // Encrypt data and get 3D form data
            Log::info('Sending data to POSNET for encryption', [
                'order_id' => $request->order_id,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'card_number' => substr($request->card_number, 0, 6) . '******' . substr($request->card_number, -4),
                'expiry' => $request->expiry_month . '/' . $request->expiry_year,
            ]);

            $response = $this->posnetService->encryptData(
                $request->order_id,
                $request->amount,
                $request->currency,
                $request->card_number,
                $request->expiry_month . $request->expiry_year,
                $request->cvv,
                $request->card_owner
            );

            Log::info('POSNET encryption response received', [
                'response' => $response
            ]);

            // Check if response is valid
            if (!is_object($response)) {
                throw new \Exception('Invalid response from POSNET service');
            }

            if (!isset($response->approved) || !isset($response->oosRequestDataResponse)) {
                throw new \Exception('Incomplete response from POSNET service');
            }

            if ($response->approved == 1) {
                // Store payment data in session
                session([
                    'payment_package_id' => $package->id,
                    'payment_order_id' => $request->order_id,
                    'payment_amount' => $request->amount
                ]);
                Log::info('Payment data stored in session');

                // Check if required data exists in response
                if (!isset($response->oosRequestDataResponse->data1) || 
                    !isset($response->oosRequestDataResponse->data2) || 
                    !isset($response->oosRequestDataResponse->sign)) {
                    throw new \Exception('Missing required data in POSNET response');
                }

                // Get 3D form data
                $formData = [
                    'action_url' => config('posnet.threed_url'), // POSNET 3D URL'ini config'den al
                    'inputs' => [
                        'mid' => config('posnet.merchant_id'),
                        'tid' => config('posnet.terminal_id'),
                        'posnetData' => $response->oosRequestDataResponse->data1,
                        'posnetData2' => $response->oosRequestDataResponse->data2,
                        'digest' => $response->oosRequestDataResponse->sign,
                        'vftCode' => "",
                        'merchantReturnURL' => route('payment.result'),
                        'lang' => 'tr',
                        'url' => config('posnet.merchant_url'),
                    ]
                ];

                Log::info('3D form data prepared', ['action_url' => $formData['action_url']]);

               // return view('payment.redirect', compact('formData'));
            }

            Log::error('POSNET encryption failed', [
                'respCode' => $response->respCode ?? null,
                'respText' => $response->respText ?? null
            ]);

            //return redirect()->route('packages.payment', $package->id)
              //  ->with('error', 'Ödeme başlatılamadı: ' . ($response->respText ?? 'Bilinmeyen hata'));

        } catch (\Exception $e) {
            Log::error('Payment process error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            //return redirect()->route('packages.payment', $request->package_id)
              //  ->with('error', 'Ödeme işlemi başlatılırken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Step 3: Handle 3D authentication result
     */
    public function paymentResult(Request $request)
    {
        try {
            Log::info('3D authentication result received', [
                'request_data' => $request->all()
            ]);

            $orderId = $request->Xid;

            $package = Package::where('transaction_id', $orderId)
                ->where('is_active', true)
                ->first();

            if (!$package) {
                throw new \Exception('Paket bulunamadı.');
            }

            $packageId = $package->id;
            $amount = intval($package->price * 100);

            Log::info('Payment data retrieved', [
                'package_id' => $packageId,
                'order_id' => $orderId,
                'amount' => $amount
            ]);

            // Verify required parameters
            if (!$request->has('BankPacket') || !$request->has('MerchantPacket') || !$request->has('Sign')) {
                throw new \Exception('Eksik banka yanıtı.');
            }

            // Verify 3D authentication
            Log::info('Sending data to POSNET for 3D verification');
            $response = $this->posnetService->resolveMerchantData(
                $request->input('BankPacket'),
                $request->input('MerchantPacket'),
                $request->input('Sign'),
                $orderId
            );

            Log::info('POSNET 3D verification response received', [
                'response' => $response
            ]);

            if (!is_object($response)) {
                throw new \Exception('Invalid response from POSNET service');
            }

            if ($response->approved == 1 && isset($response->oosResolveMerchantDataResponse)) {
                // Finalize transaction
                Log::info('Starting transaction finalization');
                $calculatedMac = $this->calculateMac(
                    $orderId, 
                    $amount,
                    'TL',
                    config('posnet.merchant_id')
                );

                $finalResponse = $this->posnetService->finalizeTransaction(
                    $request->input('BankPacket'),
                    $response->calculatedMac
                );

                Log::info('Transaction finalization response received', [
                    'response' => $finalResponse
                ]);

                if (isset($finalResponse->approved) && $finalResponse->approved == 1) {
                    // Update package status
                    $package->update([
                        'status' => 'active',
                        'payment_date' => now(),
                        'transaction_result' => $finalResponse->hostlogkey ?? null
                    ]);

                    Log::info('Package status updated after successful payment', [
                        'package_id' => $package->id,
                        'status' => 'active',
                        'transaction_result' => $finalResponse->hostlogkey ?? null
                    ]);

                    // Store success message in session
                    session()->put('success', 'Ödeme başarıyla tamamlandı ve paket aktifleştirildi.');
                    
                    // Redirect to packages/all with success message
                    return redirect()->route('packages.all')->with('success', 'Ödeme başarıyla tamamlandı ve paket aktifleştirildi.');
                }
            }

            Log::error('Payment verification failed', [
                'respCode' => $response->respCode ?? null,
                'respText' => $response->respText ?? null
            ]);

            session()->put('error', 'Ödeme işlemi başarısız: ' . ($response->respText ?? 'Bilinmeyen hata'));
            return view('payment.result', [
                'status' => 'error',
                'message' => 'Ödeme işlemi başarısız: ' . ($response->respText ?? 'Bilinmeyen hata')
            ]);

        } catch (\Exception $e) {
            Log::error('Payment result processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->put('error', 'Ödeme sonucu işlenirken bir hata oluştu: ' . $e->getMessage());
            return view('payment.result', [
                'status' => 'error',
                'message' => 'Ödeme sonucu işlenirken bir hata oluştu: ' . $e->getMessage()
            ]);
        }
    }

    private function calculateMac($xid, $amount, $currency, $merchantNo)
{

   
    // Hash fonksiyonu
    $hashString = function($originalString) {
        return base64_encode(hash('sha256', $originalString, true));
    };

    // Config'den değerleri al
    $encKey = config('posnet.enc_key'); // "10,10,10,10,10,10,10,10"
    $terminalId = config('posnet.terminal_id'); // "67C31344"

    // Birinci hash'i hesapla
    $firstHash = $hashString($encKey . ";" . $terminalId);

    // MAC değerini hesapla
    $mac = $hashString($xid . ";" . $amount . ";" . $currency . ";" . $merchantNo . ";" . $firstHash);

    // Hesaplama detaylarını logla
    Log::info('MAC Hesaplama Detayları', [
        'input_values' => [
            'xid' => $xid,
            'amount' => $amount,
            'currency' => $currency,
            'merchantNo' => $merchantNo,
            'encKey' => $encKey,
            'terminalId' => $terminalId
        ],
        'calculated_values' => [
            'firstHash' => $firstHash,
            'finalMac' => $mac
        ]
    ]);

    return $mac;
}
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PosnetService
{
    protected $config;

    public function __construct()
    {
        // Debug environment variables
        Log::info('Environment Variables', [
            'POSNET_MERCHANT_ID' => env('POSNET_MERCHANT_ID'),
            'POSNET_TERMINAL_ID' => env('POSNET_TERMINAL_ID'),
            'POSNET_ID' => env('POSNET_ID'),
            'APP_URL' => env('APP_URL'),
            'POSNET_MERCHANT_RETURN_URL' => env('POSNET_MERCHANT_RETURN_URL'),
            'POSNET_XML_SERVICE_URL' => env('POSNET_XML_SERVICE_URL')
        ]);

        // Try to load from config first
        $config = config('posnet');
        Log::info('Config from config() helper', ['config' => $config]);

        // If config is empty, load from environment variables
        if (empty($config)) {
            Log::warning('Config is empty, loading from environment variables');
            $this->config = [
                'merchant_id' => env('POSNET_MERCHANT_ID', '6700972656'),
                'terminal_id' => env('POSNET_TERMINAL_ID', '67C31344'),
                'posnet_id' => env('POSNET_ID', '1010073415007134'),
                'merchant_url' => env('APP_URL', 'http://localhost:8000/payment'),
                'merchant_return_url' => env('POSNET_MERCHANT_RETURN_URL', 'http://localhost:8000/payment/result'),
                'xml_service_url' => env('POSNET_XML_SERVICE_URL', 'https://setmpos.ykb.com/PosnetWebService/XML'),
                'api_url' => env('POSNET_API_URL', 'https://posnet.yapikredi.com.tr/PosnetWebService/XML'),
                'threed_url' => env('POSNET_3D_URL', 'https://setmpos.ykb.com/3DSWebService/YKBPaymentService'),
                'test_mode' => env('POSNET_TEST_MODE', true),
                'timeout' => env('POSNET_TIMEOUT', 90),
                'default_currency' => 'TL'

                
            ];
        } else {
            $this->config = $config;
        }

        
        
        // Debug final config values
        Log::info('Final Config Values', [
            'merchant_id' => $this->config['merchant_id'] ?? 'not set',
            'terminal_id' => $this->config['terminal_id'] ?? 'not set',
            'posnet_id' => $this->config['posnet_id'] ?? 'not set',
            'merchant_url' => $this->config['merchant_url'] ?? 'not set',
            'merchant_return_url' => $this->config['merchant_return_url'] ?? 'not set',
            'xml_service_url' => $this->config['xml_service_url'] ?? 'not set'
        ]);
    }

    // 1. Verileri Şifreleme
    public function encryptData($orderId, $amount, $currency, $installment = '00', $cardHolder = '', $ccNo = '', $expDate = '', $cvc = '')
    {
        try {
            // Log each parameter individually
            Log::info('Order ID', ['value' => $orderId]);
            Log::info('Amount', ['value' => $amount]);
            Log::info('Currency', ['value' => $currency]);
            Log::info('Installment', ['value' => $installment]);
            Log::info('Card Holder', ['value' => $cardHolder]);
            Log::info('Card Number', ['value' => $ccNo]);
            Log::info('Expiry Date', ['value' => $expDate]);
            Log::info('CVC', ['value' => $cvc]);

            // Log configuration values with null checks
            Log::info('Merchant ID', ['value' => $this->config['merchant_id'] ?? 'not set']);
            Log::info('Terminal ID', ['value' => $this->config['terminal_id'] ?? 'not set']);
            Log::info('Posnet ID', ['value' => $this->config['posnet_id'] ?? 'not set']);

            Log::info('All encryptData parameters', [
                'orderId' => $orderId,
                'amount' => $amount,
                'currency' => $currency,
                'installment' => $installment,
                'cardHolder' => $cardHolder,
                'ccNo' => $ccNo,
                'expDate' => $expDate,
                'cvc' => $cvc,
                'merchant_id' => $this->config['merchant_id'],
                'terminal_id' => $this->config['terminal_id'],
                'posnet_id' => $this->config['posnet_id']
            ]);

            // Log all parameters together for reference
            
          
          // Create XML line by line
            $xmlLines = [];
            
            // XML Declaration
            $xmlLines[] = "<?xml version='1.0' encoding='ISO-8859-9'?>";
            Log::info('XML Declaration', ['line' => $xmlLines[0]]);
            
            // Root element
            $xmlLines[] = "<posnetRequest>";
            Log::info('Root element', ['line' => $xmlLines[1]]);
            
            // Merchant and Terminal IDs
            $xmlLines[] = "    <mid>{$this->config['merchant_id']}</mid>";
            $xmlLines[] = "    <tid>{$this->config['terminal_id']}</tid>";
            Log::info('Merchant and Terminal IDs', [
                'mid' => $xmlLines[2],
                'tid' => $xmlLines[3]
            ]);
            
            // OOS Request Data
            $xmlLines[] = "    <oosRequestData>";
            Log::info('OOS Request Data Start', ['line' => $xmlLines[4]]);
            
            // OOS Request Data Fields
            $xmlLines[] = "        <posnetid>{$this->config['posnet_id']}</posnetid>";
            $xmlLines[] = "        <ccno>{$ccNo}</ccno>";
            $xmlLines[] = "        <expDate>{$expDate}</expDate>";
            $xmlLines[] = "        <cvc>{$cvc}</cvc>";
            $xmlLines[] = "        <amount>{$amount}</amount>";
            $xmlLines[] = "        <currencyCode>{$currency}</currencyCode>";
            $xmlLines[] = "        <installment>{$installment}</installment>";
            $xmlLines[] = "        <XID>{$orderId}</XID>";
            $xmlLines[] = "        <cardHolderName>{$cardHolder}</cardHolderName>";
            $xmlLines[] = "        <tranType>Sale</tranType>";
        
            Log::info('OOS Request Data Fields', [
                'fields' => array_slice($xmlLines, 5, 10)
            ]);
            
            // Close OOS Request Data
            $xmlLines[] = "    </oosRequestData>";
            Log::info('OOS Request Data End', ['line' => $xmlLines[15]]);
            
            // Close Root element
            $xmlLines[] = "</posnetRequest>";
            Log::info('Root element End', ['line' => $xmlLines[16]]);
            
            // Combine all lines
            $xmlData = implode("\n", $xmlLines);
            
            Log::info('encryptData xmlData', [
                'xmlData' => $xmlData
            ]);

            return $this->sendRequest($xmlData);


        } catch (\Exception $e) {
            Log::error('encryptData process error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    // 2. Kullanıcıyı Banka Ödeme Sayfasına Yönlendirme
    public function get3DFormData($data1, $data2, $sign)
    {
        return [
            'mid' => $this->config['merchant_id'],
            'posnetID' => $this->config['posnet_id'],
            'posnetData' => $data1,
            'posnetData2' => $data2,
            'digest' => $sign,
            'merchantReturnURL' => $this->config['merchant_return_url'],
            'lang' => 'tr'
        ];
    }

    // 3. Kullanıcı Doğrulama Sonucu Sorgulama
    public function resolveMerchantData($bankData, $merchantData, $sign, $mac)
    {
        $xmlData = "<?xml version='1.0' encoding='ISO-8859-9'?>
        <posnetRequest>
            <mid>{$this->config['merchant_id']}</mid>
            <tid>{$this->config['terminal_id']}</tid>
            <oosResolveMerchantData>
                <bankData>{$bankData}</bankData>
                <merchantData>{$merchantData}</merchantData>
                <sign>{$sign}</sign>
                <mac>{$mac}</mac>
            </oosResolveMerchantData>
        </posnetRequest>";

        return $this->sendRequest($xmlData);
    }

    // 4. Finansallaştırma
    public function finalizeTransaction($bankData, $mac)
    {
        $xmlData = "<?xml version='1.0' encoding='ISO-8859-9'?>
        <posnetRequest>
            <mid>{$this->config['merchant_id']}</mid>
            <tid>{$this->config['terminal_id']}</tid>
            <oosTranData>
                <bankData>{$bankData}</bankData>
                <mac>{$mac}</mac>
            </oosTranData>
        </posnetRequest>";

        return $this->sendRequest($xmlData);
    }

    private function sendRequest($xmlData)
    {

        /*$xmlData = '<?xml version="1.0" encoding="ISO-8859-9"?>
        <posnetRequest>
            <mid>6700972656</mid>
            <tid>67C31344</tid>
            <username/>
            <password/>
            <oosRequestData>
                <posnetid>1010073415007134</posnetid>
                <ccno>4506349089054811</ccno>
                <expDate>3333</expDate>
                <cvc>000</cvc>
                <amount>100</amount>
                <currencyCode>TL</currencyCode>
                <installment>00</installment>
                <XID>4b0e62a5f4a95c759891</XID>
                <cardHolderName>Özgür Çelik</cardHolderName>
                <tranType>Sale</tranType>
            </oosRequestData>
        </posnetRequest>';*/

        // Log the request data
        Log::info('POSNET sendRequest xmldata', [
            'xmldata' => urlencode($xmlData)
        ]);
    
        
        // Convert to GET request with query parameters
        $response = Http::get($this->config['xml_service_url'], [
            'xmldata' => urlencode($xmlData)
        ]);

        // Log the response
        Log::info('POSNET encryption response', [
            'response' => $response
        ]);
    
        return simplexml_load_string($response->body());
    }
}
